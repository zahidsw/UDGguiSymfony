/*********************************************************************
***************************** Helper functions ***********************
*********************************************************************/
function createSlider(div) {
	$(div).slider({
		min: 0,
		max: 100,
		value: 50,
		slide: function( event, ui ) {
			$( "#" + ui.handle.parentNode.id + "_display" ).html("("+ui.value+")");
		}
	});
}

function createOnOff(div) {
	$(div).buttonset();
}

function getCharCode(e) {
	e = e || window.event;
    //var charCode = (typeof e.which == "undefined") ? e.keyCode : e.which;
	var charCode = e.keyCode || e.which;
	return charCode;
}

function charCodeToString(c) {
	return String.fromCharCode(c);
}

function isInteger(s) {
	return /^\d+$/.test(s);
}

function isDouble(s) {
	return /^\d+.?\d*$/.test(s);
}

function isEmail(s) {
	return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(s);
}

function isSMS(s) {
	//+41227404656
	return /^[+][0-9]{11}$/.test(s);
}

function isControlCharCode(charCode) {
	if(charCode === 8) return true; //backspace
	if(charCode === 46) return true; //delete
	if(charCode === 37) return true; //left arrow
	if(charCode === 39) return true; //right arrow
	if(charCode === 13) return true; //return
	if(charCode === 9) return true; //tab
	
	return false;
}

/*********************************************************************
***************************** Filter functions ***********************
*********************************************************************/
function filterInteger(e) {
	var c = getCharCode(e);
	return isInteger(charCodeToString(c)) || isControlCharCode(c);
}
function filterDouble(e) {
	var c = getCharCode(e);
	return isDouble(charCodeToString(c)) || isControlCharCode(c);
}
function filterSMS(e) {
	var c = getCharCode(e);
	return isInteger(charCodeToString(c)) || isControlCharCode(c) || c==43; //43=+
}

/*********************************************************************
***************************** Submit functions ***********************
*********************************************************************/
//fieldType: 1(on/off), 2(slider), 3(integer), 4(double), 5(duration), 6(string), 7(email), 8(sms), 9(triplet)
function submitField(fieldName, fieldType) {
	var f = $('#'+fieldName);
	f.removeClass("input_error");
	var paramToSend = '';
	
	switch(fieldType) {
		case 1: // on/off
			paramToSend = $("#" + fieldName + " :radio:checked").val();
			break;
		case 2: //slider
			paramToSend = f.slider("option", "value");
			break;
		case 3: //integer
			if(isInteger(f.val()) == false) {
				f.addClass("input_error");
				f.focus();
				return;
			}
			paramToSend = f.val();
			break;
		case 4: //double
			if(isDouble(f.val()) == false) {
				f.addClass("input_error");
				f.focus();
				return;
			}
			paramToSend = f.val();
			break;
		case 5: //duration
			var vDays = $("#" + fieldName + "_days").val();
			var vHours = $("#" + fieldName + "_hours").val();
			var vMinutes = $("#" + fieldName + "_minutes").val();
			var vSeconds = $("#" + fieldName + "_seconds").val();
			paramToSend = vDays + "," + vHours + "," + vMinutes + "," + vSeconds
			break;
		case 6: //string
			if(f.val() == "") {
				f.addClass("input_error");
				f.focus();
				return;
			}
			paramToSend = encodeParameter(f.val());
			break;
		case 7: //email
			if(isEmail(f.val()) == false) {
				f.addClass("input_error");
				f.focus();
				return;
			}
			paramToSend = encodeParameter(f.val());
			break;
		case 8: //sms
			if(isSMS(f.val()) == false) {
				f.addClass("input_error");
				f.focus();
				return;
			}
			paramToSend = encodeParameter(f.val());
			break;
		case 9: //triplet
			var f1 = $('#'+fieldName+"_triplet_variable");
			var f2 = $('#'+fieldName+"_triplet_value");
			f1.removeClass("input_error");
			f2.removeClass("input_error");
			
			var ok = true;
			
			if(f2.val() == "") {
				f2.addClass("input_error");
				f2.focus();
				ok = false;
			}
			if(f1.val() == "") {
				f1.addClass("input_error");
				f1.focus();
				ok = false;
			}
			
			if(!ok) return;
			
			var vVar = f1.val();
			var vVal = f2.val();
			var vUnit = $('#'+fieldName+"_triplet_unit").val();
			
			paramToSend = encodeParameter(vVar) + "," + encodeParameter(vVal) + "," + encodeParameter(vUnit);
			break;
	}
	
	//execute the action
	var actionID = fieldName.substring(7); //fieldname = action_action.id, so we remove starting "action_"
	executeActionWithParams(actionID, paramToSend);
}

function encodeParameter(param) {
	return encodeURIComponent(param);
}

function executeActionWithParams(actionID, paramsList) {
	//function is defined directly in the twig template
	actionWithParam(actionID, paramsList);
}