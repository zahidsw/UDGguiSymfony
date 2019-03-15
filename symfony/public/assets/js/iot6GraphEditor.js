/*********************************************************************
************************ Global **************************************
*********************************************************************/
//initialisation
$(document).ready(function() {
	/*
	//event on devices' list
	$(devices).change(function() {
		//if($(devices).val() >= 0) //do nothing if option "ALL" is selected
  		editor_ajax_showVariablesForDevice($(devices).val()); //show variables for the selected device
	});
	
	//force to display "select device please" message
	editor_ajax_showVariablesForDevice($(devices).val()); //show variables for the selected device
	*/
	//check if devices where preloaded
	editor_showVariablesList();
});

function editor_hideShowDiv(div_name, forceToShow) {
	var canHide = forceToShow ? !forceToShow : true;
	
	//need to show or hide ?
	if($("#div_" + div_name).is(":visible") && canHide == true) {
		//change image
		$("#img_" + div_name).attr("src", arrow_closed_path);
		//hide/show div
		$("#div_" + div_name).hide(500);
	} else {
		//change image
		$("#img_" + div_name).attr("src", arrow_open_path);
		//hide/show div
		$("#div_" + div_name).show(500);
	}
}

//initialisation
$(document).ready(function() {
	editor_managePreloadNavigator();
});

/*********************************************************************
***************************** Preload NAVIGATOR **********************
*********************************************************************/
function editor_managePreloadNavigator() {
	//*************** need to preload some value in the navigator?
	//the navigator can be preloaded
	//3 ways to make the preload :
	//- define a local javascript value called graph_preload_navigator (i.e. : var graph_preload_navigator='xxxxxx')
	//- pass a param in the URL called graph_preload_navigator
	//- call the function editor_preload_navigator with the graph_preload content as param
	//Structure of variable = "building_id,floor_id,room_type_id,room_id,category_id,family_id"
	//ie: ?graph_preload_navigator=-1,-1,-1,-1,-1,-1,-1 (-1 = ALL)
	var preload_as_string = typeof graph_preload_navigator !== 'undefined' ? graph_preload_navigator : viewer_getURLParameterValue("graph_preload_navigator");
	editor_preload_navigator(preload_as_string);
}
function editor_preload_navigator(preload_as_string) {
	if(preload_as_string === '') return;
	
	var preload = preload_as_string.split(",");
	
	if(preload.length > 0) {
		//handle building_id
		if(preload[0] !== '') {
			$("#buildings").val(preload[0]);
		}
		//handle floor_id
		if(preload[1] !== '') {
			$("#floors").val(preload[1]);
		}
		//handle room_type_id
		if(preload[2] !== '') {
			$("#roomTypes").val(preload[2]);
		}
		//handle room_id
		if(preload[3] !== '') {
			$("#rooms").val(preload[3]);
		}
		//handle category_id
		if(preload[4] !== '') {
			$("#categories").val(preload[4]);
		}
		//handle family_id
		if(preload[5] !== '') {
			$("#families").val(preload[5]);
		}
		//handle device_id
		if(preload[6] !== '') {
			$("#devices").val(preload[6]);
		}
		
		//force reload of variables by triggering a change on a field (we choose device)
		$("#devices").change();
	}
}

/*********************************************************************
***************************** Variables selection ********************
*********************************************************************/

//integration with the Navigator
function callback_navigator_addVariable() {
	//do we have a variable selected?
	if($("#variables").val() == null || $("#variables").val() < 1) return; //no variable, do nothing
	
	//****** CASE 1: simple existing variable linked to a device
	//do we have a device selected?
	if($("#devices").val() > 0) {
		var device_id = $("#devices").val();
		var device_name = $("#devices option:selected").text();
		var variable_name = $("#variables option:selected").text(); //can have the unit in parenthesis like "enery (kHz)"
		var unit = "";

		//get unit and remove from name if needed
		var pos = variable_name.indexOf("(");
		if(pos > 0) {
			unit = variable_name.substring(pos+1, variable_name.indexOf(")"));
			variable_name = variable_name.substring(0,pos-1);
		}
		//add new variable to the list
		editor_add_variable_to_display(device_id, device_name, variable_name, unit);
		return;
	}
	
	//****** CASE 2: GENERIC variable (linked to criteria)
	var device_id = $("#devices").val();
	var device_name = $("#devices option:selected").text();
	var variable_name = $("#variables option:selected").text(); //can have the unit in parenthesis like "enery (kHz)"
	var unit = "";

	//get unit and remove from name if needed
	var pos = variable_name.indexOf("(");
	if(pos > 0) {
		unit = variable_name.substring(pos+1, variable_name.indexOf(")"));
		variable_name = variable_name.substring(0,pos-1);
	}
	
	var building_id = $("#buildings").val();
	var floor_id = $("#floors").val();
	var room_type_id = $("#roomTypes").val();
	var room_id = $("#rooms").val();
	var category_id = $("#categories").val();
	var family_id = $("#families").val();
	
	//buildName
	device_name = "";
	if(building_id > 0) device_name += "Building='" + $("#buildings option:selected").text() + "' ";
	if(floor_id > 0) device_name += "Floor='" + $("#floors option:selected").text() + "' ";
	if(room_type_id > 0) device_name += "RoomType='" + $("#roomTypes option:selected").text() + "' ";
	if(room_id > 0) device_name += "Room='" + $("#rooms option:selected").text() + "' ";
	if(category_id > 0) device_name += "Category='" + $("#categories option:selected").text() + "' ";
	if(family_id > 0) device_name += "Family='" + $("#families option:selected").text() + "' ";
	
	//add new variable to the list
	editor_add_generic_variable_to_display(device_id, device_name, variable_name, unit, building_id,floor_id,room_type_id, room_id, category_id, family_id);
}


//call an ajax function to get the variables related to a device
/*function editor_ajax_showVariablesForDevice(device_id) {
	//do we have a device selected ?
	if($(devices).val() < 0) {
		$(div_variables).html('<span style="margin:15px;display:block;">' + msg_no_device_selected + '</span>');
		return;
	}
	
	editor_showVariablesLoading();
	editor_hideShowDiv("variables", true); //make sure the variables div is opened
	$.ajax({
			url: "editor/getVariablesForDeviceAction?device_id=" + device_id,
			dataType: "json"
		})
		.done(function(result) {
			editor_showVariablesWithJSON(result);
		})
		.fail(function() {
			editor_showVariablesError();
		});
}

function editor_showVariablesLoading() {
	$(div_variables).html("<img src='" + ajax_loading_image_path + "' />");
}

function editor_showVariablesError() {
	$(div_variables).html(msg_ajax_error);
}

function editor_showVariablesWithJSON(result_JSON) {
	//create HTML fragment (table) to show/select variables
	var var_html = "<table class='graphList'><tr><th></th><th>" + msg_device + "</th><th>" + msg_variable + "</th><th>" + msg_unit + "</th></tr>";
	
	//no element
	if(result_JSON.length <= 0) {
		var_html += "<tr><td colspan='4'>";
		var_html += msg_no_variable;
		var_html += "</td></tr>";
	}
	
	//go through each variable
	for(var i=0; i<result_JSON.length; i++) {
		var_html += "<tr><td>";
		var_html += "<a href='javascript:editor_add_variable_to_display(" + result_JSON[i]["device_id"] + ",\"" + result_JSON[i]["device_name"] + "\",\"" + result_JSON[i]["variable_name"] + "\",\"" + result_JSON[i]["unit"] + "\");'><img src='";
		var_html += add_path;
		var_html += "' /> ";
		var_html += msg_add;
		var_html += "</a></td><td>";
		var_html += result_JSON[i]["device_name"];
		var_html += "</td><td>";
		var_html += result_JSON[i]["variable_name"];
		var_html += "</td><td>";
		var_html += result_JSON[i]["unit"];
		var_html += "</td></tr>";
	}
	
	var_html += "</table>";
	
	$(div_variables).html(var_html);
}*/

/*********************************************************************
***************************** Variables list *************************
*********************************************************************/
function editor_add_variable_to_display(device_id, device_name, variable_name, unit) {
	var random_color = (0x1000000+(Math.random())*0xffffff).toString(16).substr(1,6); //"FF0000";
	//add variable to viewer and refresh graph
	viewer_add_variable_to_display(device_id, device_name, variable_name, unit, random_color, true);
	//refresh list of variables
	editor_showVariablesList();
}
function editor_add_generic_variable_to_display(device_id, device_name, variable_name, unit, building_id,floor_id,room_type_id, room_id, category_id, family_id) {
	var random_color = (0x1000000+(Math.random())*0xffffff).toString(16).substr(1,6); //"FF0000";
	//add variable to viewer and refresh graph
	viewer_add_generic_variable_to_display(device_id, device_name, variable_name, unit, random_color, building_id,floor_id,room_type_id, room_id, category_id, family_id, true);
	//refresh list of variables
	editor_showVariablesList();
}

//function editor_remove_variable_to_display(device_id, variable_name) {
function editor_remove_variable_to_display(index) {
	//remove variable and refresh graph
	viewer_remove_variable_to_display(index);
	//refresh list of variables
	editor_showVariablesList();
}

function editor_showVariablesList() {
	//create the list of variables
	var var_html = "<table class='graphList'><tr><th></th><th>" + msg_device + "</th><th>" + msg_variable + "</th><th>" + msg_unit + "</th><th>" + msg_color + "</th></tr>";
	
	if(graph_elements_list.length == 0) {
		var_html += "<tr><td></td><td colspan='4'>" + msg_no_variable_selected + "</td></tr>";
	}
	
	for(i=0; i < graph_elements_list.length; i++) {
		var_html += "<tr><td>";
		var_html += "<a href='javascript:editor_remove_variable_to_display(" + i + ")'><img src='";
		var_html += remove_path;
		var_html += "' /> ";
		var_html += msg_remove;
		var_html += "</a></td><td>";
		var_html += graph_elements_list[i]["device_name"];
		var_html += "</td><td>";
		var_html += graph_elements_list[i]["variable_name"];
		var_html += "</td><td>";
		var_html += graph_elements_list[i]["unit"];
		var_html += "</td><td>";
		var_html += "<div id=\"var_list_" + i + "\" class=\"color-box\" style=\"width:30px;height:30px;border:solid 1px;background-color:#" + graph_elements_list[i]["color"] + "\"></div>";
		var_html += "</td></tr>";
	}
	
	var_html += "</table>";
	
	//display the list
	$(div_variables_list).html(var_html);
	
	//set the color popup trigger
	$('.color-box').colpick({
		colorScheme:'dark',
		layout:'rgbhex',
		//color:'ff8800',
		onSubmit:function(hsb,hex,rgb,el) {
			$(el).css('background-color', '#'+hex); //change color of div
			$(el).colpickHide(); //hide color picker
			
			//change color of element
			var pos = parseInt($(el).attr('id').replace("var_list_","")); //get position of element
			graph_elements_list[pos]["color"] = hex;
			
			//reload graph
			viewer_ajax_showGraph();
		},
		onShow:function(picker) { //make sure we point to the correct color of the element
			$(this).colpickSetColor(editor_hexc($(this).css('background-color')),true);
		}
	});
}

//convert string like "rgb(255,0,0)" to FF0000
function editor_hexc(colorval) {
    var parts = colorval.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    delete(parts[0]);
    for (var i = 1; i <= 3; ++i) {
        parts[i] = parseInt(parts[i]).toString(16);
        if (parts[i].length == 1) parts[i] = '0' + parts[i];
    }
    //color = '#' + parts.join('');
	color = parts.join('');

    return color;
}