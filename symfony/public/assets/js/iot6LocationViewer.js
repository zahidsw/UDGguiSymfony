/*********************************************************************
************************ Global **************************************
*********************************************************************/
//initialisation
$(document).ready(function() {
	viewer_canvas_init();
	
	//waiting dialog
	//$("#modal_waiting").css({'background-image': "url:('" + ajax_loading_image_path + "')"}); //set-up background
	 $("#modal_waiting").css({'background': "rgba( 255, 255, 255, .8 ) url('" + ajax_loading_image_path + "') 50% 50% no-repeat"});
				
	$(document).on({
		ajaxStart: function() {
			$("body").addClass("loading");
		},
		ajaxStop: function() { $("body").removeClass("loading");}    
	});
	
	//load list of locations
	loadExistingLocations();
});

/*********************************************************************
***************************** Preload ********************************
*********************************************************************/
function viewer_managePreload() {
	//*************** need to preload some location ?
	//a location can be preloaded
	//3 ways to make the preload :
	//- define a local javascript value called location_preload (i.e. : var location_preload='1')
	//- pass a param in the URL called location_preload
	//- call the function viewer_preload_location with the location_preload content as param
	//Structure of variable = id of location
	//ie: ?location_preload=1
	var preload_as_string = typeof location_preload !== 'undefined' ? location_preload : viewer_getURLParameterValue("location_preload");
	if(preload_as_string === "") return;
	viewer_preload_location(preload_as_string);
};

function viewer_preload_location(preload_as_string) {
	//does the id exists ?
	if($("#s_locations option[value='" + preload_as_string + "']").length <= 0) return;

	//set value in select list
	$("#s_locations").val(preload_as_string);
	//force loading - because event change not triggered by previous line
	viewer_loadCurrentLocationIntoCanvas();
}

/* Reload page with the URL of the current page with all the graph options in the preload param
*/
function viewer_reloadWithParamInURL() {
	var ret = base_page_url;
	
	ret += '?location_preload=';
	
	//location id
	ret += $("#s_locations").val();
	
	document.location.href = ret;
}

function viewer_getURLParameterValue(parameter) {
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++) 
	{
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == parameter) 
		{
			return sParameterName[1];
		}
	}
	return "";
}

/*********************************************************************
*************************  CANVAS ************************************
*********************************************************************/
var _canvas; //global variable which contains the current canvas object (fabricJS)
var _selected_object = null; //current selected object in canvas
var _lockObject = true;
var _tooltip_added_margin = 0; //how much margin from the object to add to the tooltip (higher = upper from the object)

/*Current location as json object. Format :
	{"background_image": image_in_base64,
	objects:[
		{
			###all attributes returned by object.toJSON()
			device_id: 287,
			device_name: 'valve yyy'
		}
	]
	}
*/
var _current_location = {
	background_image: '',
	objects:[]
} ;

//initialize the canvas
function viewer_canvas_init() {
	//create the canvas
	_canvas = this.__canvas = new fabric.Canvas('c');
	fabric.Object.prototype.transparentCorners = false;
	
	//resize the canvas
	_canvas.setWidth($(div_canvas).width());	//width without padding
	
	//attach events to canvas
	_canvas.on('object:selected', function(options) {
		viewer_canvas_object_selected(options);
	});
	_canvas.on('selection:cleared', function(options) {
		viewer_canvas_object_selected(null);
	});
	
	_canvas.on('mouse:over', function(e) {
		//handle mouse over an object
		//e.target.setFill('red');
		//canvas.renderAll();
		viewer_canvas_object_selected(e);
	});
	/*_canvas.on('mouse:out', function(e) {
		//handle mouse out of an object
		//e.target.setFill('green');
		//canvas.renderAll();
	});*/
	_canvas.on('mouse:up', function(e) {
		//handle mouse out of an object
		//e.target.setFill('green');
		//canvas.renderAll();
		if(e.target == null) //is canvas ?
			viewer_canvas_object_selected(null);
	});
	
	//make the tooltip a child of body (otherwise the absolute position will be relative to the container)
	$('#canvas_tooltip').appendTo("body");
	$("#canvas_tooltip").hide(); //hide the tooltip
}

//add an object into the canvas. Take in input the position of the object in the list. Return the Fabric Object representing the param json obj
function viewer_display_object(index) {
	var obj;
	
	//create visually the object depending on the type
	if(_current_location.objects[index].type === "rect") {
		obj = new fabric.Rect(_current_location.objects[index]);
	} else {
		if(_current_location.objects[index].type === "triangle") {
			obj = new fabric.Triangle(_current_location.objects[index]);
		} else {
			obj = new fabric.Circle(_current_location.objects[index]);
		}
	}
	
	if(_lockObject === true) {
		obj.lockMovementX = true;
		obj.lockMovementY = true;
		obj.lockRotation = true;
		obj.lockScalingX = true;
		obj.lockScalingY = true;
		obj.lockUniScaling = true;
		obj.hasBorders = false;
		obj.hasControls = false;
	}
	
	//circle.set('device_id',_current_location.objects[index].device_id);
	_canvas.add(obj);
		
	//need to display image ?
	if(typeof(_current_location.objects[index].display_icon) !== 'undefined' && _current_location.objects[index].display_icon == "1") {
		fabric.Image.fromURL(family_icon_path + _current_location.objects[index].family_icon_name, function(img) {
			img.set({
				left: obj.left,
				top: obj.top,
				originX: "center",
				originY: "center"
			});

			img.perPixelTargetFind = true;
			img.targetFindTolerance = 4;
			img.hasControls = img.hasBorders = false;
			img.evented = false; //img doesn't received event
			
			if(obj.getHeight() < obj.getWidth())
				img.scaleToHeight(obj.getHeight());
			else
				img.scaleToWidth(obj.getWidth());

			img.setAngle(obj.getAngle()); //rotation
				
			img.setLeft(obj.getLeft());
			img.setTop(obj.getTop());
		
			//link object and image
			img.device_id = obj.device_id;
			obj.linked_image = img;
			img.linked_object = obj;
			
			_canvas.add(img);
		});
	};

	return obj;
}

//handle the (de-)selection of an object into the canvas
//if options is null, it's a deselection
function viewer_canvas_object_selected(options) {
	if(options === null) {//deselect
		_selected_object = null
		viewer_hideTooltip();
		return;
	}
	_selected_object = options.target;
	
	if(_canvas.getActiveObject() != _selected_object) {
		_canvas.setActiveObject(_selected_object);
		viewer_showTooltipForSelectedObject();
	}
}

//load the background of the current location
function viewer_canvas_loadBackground() {
	//do we have a background to display
	if(_current_location.background_image === null || _current_location.background_image == '') {
		_canvas.setBackgroundImage(null, _canvas.renderAll.bind(_canvas));
		return;
	}

	var imgInstance = fabric.Image.fromURL(_current_location.background_image, function(oImg) {
		//need to reduce size ? only check width - scale only horizontally / vertically = increase height of canvas
		if(oImg.width > _canvas.width) {
			oImg.scale(_canvas.width / oImg.width);
		}
		
		//set the size of canvas to the size of background
		_canvas.setHeight(oImg.getHeight());
		
		_canvas.setBackgroundImage(oImg, _canvas.renderAll.bind(_canvas));
	});
}

/*********************************************************************
*************************  TOOLTIP ***********************************
*********************************************************************/
//return an object {top,center} with the position of an canvas object into the document
function viewer_canvas_getTopCenterOfObjectInDocument(obj) {
	var parent_offset = $("#c").offset();

	var p = obj.getCenterPoint();
	
	return {
		top: (p.y - obj.getHeight()/2) + parent_offset.top,
		center: p.x + parent_offset.left
	};
}

var callback_beforeShowTooltip = function(){}; //can be overriden

function viewer_showTooltipForSelectedObject(loadContent) {
	loadContent = typeof loadContent !== 'undefined' ? loadContent : true;
	
	//load the value of the selected object into the tooltip
	callback_beforeShowTooltip(); //call callback first
	$("#selected_name").html(_selected_object.device_name);
	
	//place the tooltip
	viewer_setPositionOfTooltip();
		
	//load variables
	if(loadContent)
		viewer_tooltip_load_device_variables();
	
	$("#canvas_tooltip").show();
}

function viewer_setPositionOfTooltip() {
	//get position of canvas object into the window/document
	var pos = viewer_canvas_getTopCenterOfObjectInDocument(_selected_object);
	//get the height of the tooltip
	var tooltip_height = $('#canvas_tooltip').height();
	var tooltip_width =  $('#canvas_tooltip').width();
	//position the tooltip at the correct position
	$("#canvas_tooltip").css({top: pos.top - tooltip_height - _tooltip_added_margin, left: pos.center - (tooltip_width/2)});

}

function viewer_hideTooltip() {
	$("#canvas_tooltip").hide();
}

function viewer_openSelectedObject() {
	var url = object_view_path.replace('xxx',_selected_object.device_id);
	//$("<a target='_blank' href='" + url + "'></a>").click(); 
	window.open(url);
}

function viewer_tooltip_load_device_variables() {
	//show loading indicator
	$("#td_tooltip_variables").html("<img src='" + ajax_loading_image_path + "' />");
	
	var id = _selected_object.device_id;
	
	$.ajax({
		url: "viewer/getDeviceParams?device_id=" + id,
		global: false, //prevent triggering ajaxstart
		dataType: "json"
		})
		.done(function(result) {
			//make sure the object is still selected
			if(_selected_object == null || _selected_object.device_id != id)
				return;
		
			var html = '<div id="device_variable_infos">';
			html += '<span>'+ msg_tooltip_var_id + ':</span> ' + id;
			html += '<br /><span>'+ msg_tooltip_var_variables + ':</span>';
			if(result.id != "") {
				for(var key in result.variables) {
					html += '<br />&nbsp;&nbsp;&nbsp; - ' + key + " : " + result.variables[key];
				}
			}
			html += '<br /><span>'+ msg_tooltip_var_protocol + ':</span> ' + (result.id == '' ? "?" : result.protocol);
			html += '<br /><span>'+ msg_tooltip_var_ipv6 + ':</span> ' + (result.id == '' ? "?" : result.ipv6);
			html += '<br /><span>'+ msg_tooltip_var_timestamp + ':</span> ' + (result.id == '' ? "?" : result.timestamp);
			
			html += "</div>";
			
			$("#td_tooltip_variables").html(html);
			
			//replace the tooltip (size changed)
			viewer_setPositionOfTooltip();
		})
		.fail(function() {
			alert(msg_error_occured);
			$("#td_tooltip_variables").html(msg_error_occured);
	});
}

/*********************************************************************
************************* LOCATIONS MANAGEMENT ***********************
*********************************************************************/
function loadExistingLocations() {
	$.ajax({
			url: "viewer/getLocations",
			dataType: "json"
		})
		.done(function(result) {
			viewer_showLocations(result);
			
			//handle preload
			viewer_managePreload();
		})
		.fail(function() {
			viewer_showLocationsError();
		});
}

function viewer_showLocationsError() {
	var loc_sel = $("#s_locations");
	
	//remove all current options
	loc_sel.empty();
	loc_sel.append('<option selected="selected" value="-1">' + msg_error_retrieve_location + '</option>')
}

function viewer_showLocations(result_JSON) {
	var loc_sel = $("#s_locations");
	
	//remove all current options
	loc_sel.empty();
	
	//*****add all results
	//no element
	if(result_JSON.length <= 0) {
		loc_sel.append('<option selected="selected" value="-1">' + msg_no_existing_location + '</option>')
		return;
	}
	
	loc_sel.append('<option selected="selected" value="-1">' + msg_select_location + '</option>')
	
	//go through each variable
	for(var i=0; i<result_JSON.length; i++) {
		loc_sel.append('<option value="' + result_JSON[i]["id"] + '">' + result_JSON[i]["name"] + '</option>')
	}
}

function viewer_loadCurrentLocationIntoCanvas() {
	//reset the canvas
	_canvas.clear();
	_current_location.background_image = null;
	_current_location.objects = [];
	viewer_canvas_loadBackground(); //reload
	
	//do we have a location to show
	if($("#s_locations").val() == "-1") return;
	
	//load content of location
	$.ajax({
			url: "viewer/getLocationContent?id=" + $("#s_locations").val(),
			dataType: "json"
		})
		.done(function(result) {
			if(result["content"] === "") return;
			_current_location = JSON.parse(result["content"]);
			//reload canvas
			viewer_canvas_loadBackground();
			for(var i=0; i<_current_location.objects.length; i++) {
				viewer_display_object(i);
			}
		})
		.fail(function() {
			alert(msg_error_occured);
		});
}
