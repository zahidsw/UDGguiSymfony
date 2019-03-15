/*********************************************************************
************************ Global **************************************
*********************************************************************/
//initialisation
$(document).ready(function() {
	$(document).on({
		ajaxStop: function() { editor_showDevicesList(); }    
	});
	
	//check if devices where preloaded
	//editor_showDevicesList();
	
	//post-init canvas
	editor_canvas_init();
	
	//move editor buttons to correct place
	$("#extended_locations_actions").appendTo("#optional_extended_menu");
	
	//move tooltip extended content + actions to correct place
	editor_install_tooltip_extended_options();
	
	//manage precreation if needed
	editor_managePrecreation();
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

/*********************************************************************
**************** Pre-create location and affect it********************
*********************************************************************/
function editor_managePrecreation() {
	//*************** need to create a new location?
	//a location can be created and affected to a floor
	//3 ways to make the preload :
	//- define 2 local javascript values called location_precreation_id and location_precreation_name (i.e. : var location_precreation_id2'1')
	//- pass a param in the URL called location_precreation_id and location_precreation_name
	//- call the function editor_precreate_location
	//Structure of variable = id of location
	//ie: ?location_preload=1
	var location_precreation_id_string = typeof location_precreation_id !== 'undefined' ? location_precreation_id : viewer_getURLParameterValue("location_precreation_id");
	var location_precreation_name_string = typeof location_precreation_name !== 'undefined' ? location_precreation_name : viewer_getURLParameterValue("location_precreation_name");
	if(location_precreation_id_string === "") return;
	editor_precreate_location(location_precreation_id_string,location_precreation_name_string);
};

function editor_precreate_location(location_precreation_id_as_string,location_precreation_name_as_string) {
	$.post("editor/saveAsLocation?name=" + location_precreation_name_as_string, {content: JSON.stringify(_current_location)})
	.done(function(result) {
		var new_id = result["new_id"];
		if(new_id == null || new_id == '') return;
		//add in the list
		$("#s_locations").append('<option selected="selected" value="' + new_id + '">' + location_precreation_name_as_string + '</option>');
		
		//affect to floor
		$.get("editor/affectLocation?floor_id=" + location_precreation_id_as_string + '&location_id=' + new_id)
			.done(function(result) {
		})
		.fail(function() {
			alert(msg_error_occured);
		});
	})
	.fail(function() {
		alert(msg_error_occured);
	});
}

/*********************************************************************
*************************  DEVICES ***********************************
*********************************************************************/
function editor_showDevicesList() {
	var _html = '';
	
	$("#devices > option").each(function() {
		if(this.value !== "-1") { //take all devices except first line "ALL"
			//get family_icon_name
			var family_icon_name = $(this).data("family_id") in families_icon ? families_icon[$(this).data("family_id")] : "";
			_html += '<div id="device_id_' + this.value + '" name="' + this.text + '" family_icon_name="' + family_icon_name + '" class="location_device">';
			if(family_icon_name != "") {
				_html += "<img src='" + family_icon_path + family_icon_name + "' style='width:25px;height:25px;vertical-align:middle;' />";
			}
			_html += this.text;
			_html += "</div>";
		}
	});
	
	$('#div_devices_list').html(_html);
	
	//make each device draggable
	//Helper position bug when document scrolled (in 1.10.3) : http://bugs.jqueryui.com/ticket/9315
	$( ".location_device" ).draggable({ revert: true, containment: "document",appendTo: "body", helper: "clone" , scroll: false});
}

/*********************************************************************
*************************  CANVAS ************************************
*********************************************************************/
_lockObject = false; //override viewer setup
_tooltip_added_margin = 50; //override viewer setup
var default_radius = 20;

//initialize the canvas
function editor_canvas_init() {
	//install load image trigger
	document.getElementById('files').addEventListener('change', editor_handleImageSelect, false);

	//attach events to canvas
	_canvas.on('object:moving', function(options) {
		editor_canvas_object_modified(options);
	});
	_canvas.on('object:modified', function(options) {
		editor_canvas_object_modified(options);
	});
	/*_canvas.on('object:rotating', function(options) {
		editor_canvas_object_modified(options);
	});*/
	_canvas.on('object:scaling', function(options) {
		editor_canvas_object_modified(options);
	});
	
	//make the canvas droppable
	$( "#c" ).droppable({
		activeClass: "canvas_dropactive",
		hoverClass: "canvas_drophover",
		drop: function( event, ui ) {
			//calculate relative position in canvas
			var top;
			var left;
			
			//get parent offset
			var parent_offset = $("#c").offset();

			//remove parent position to current object offset
			//top = ui.offset.top - parent_offset.top;
			//left = ui.offset.left - parent_offset.left;
			top = event.pageY - parent_offset.top - default_radius;
			left = event.pageX - parent_offset.left - default_radius;
			
			//console.log(event.screenX + " - " + parrent_offset.left + " = " + left);
			
			//get device info
			var device_id = ui.draggable.attr('id');
			device_id = device_id.substring(10, device_id.length);
			var device_name = ui.draggable.attr('name');
			var device_family_icon_name = ui.draggable.attr('family_icon_name');
			var color = '#FF0000';
			//force display icon ?
			var display_icon = false;
			if(device_family_icon_name != "") {
				display_icon = true;
			}
			
			//create the new object
			editor_add_object(top, left, device_id, device_name, color, device_family_icon_name, display_icon);
		}
	});
		
	//initialize the color component into the tooptip
	//set the color popup trigger
	$('#color-box').colpick({
		colorScheme:'dark',
		layout:'rgbhex',
		//color:'ff8800',
		onSubmit:function(hsb,hex,rgb,el) {
			$(el).css('background-color', '#'+hex); //change color of div
			$(el).colpickHide(); //hide color picker
			
			//change color of element
			editor_setColorOfSelectedObject(hex);
		},
		onShow:function(picker) { //make sure we point to the correct color of the element
			$(this).colpickSetColor(editor_hexc($(this).css('background-color')),true);
		}
	});
}

//add an object to the list of objects and display it
function editor_add_object(top, left, device_id, device_name, color, family_icon_name, display_icon) {
	//***** check if the object already exist
	for(var i=0; i<_current_location.objects.length; i++) {
		if(_current_location.objects[i].device_id == device_id) {
			if(confirm(msg_confirm_object_already_exists) === false) return;
			break;
		}
	}

	//add object to the list
	var new_object = {
		top: top,
		left: left,
		device_id: device_id,
		device_name: device_name,
		family_icon_name: family_icon_name,
		display_icon: display_icon ? 1 : 0,
		fill: color,
		type: "circle",
		radius: default_radius,
		rotatingPointOffset: 20, //default 40
		originX: "center",
		originY: "center",
		opacity: display_icon ? 0 : 1,
	};
	
	_current_location.objects.push(new_object);
	
	//display object
	var obj = viewer_display_object(_current_location.objects.length - 1);
	
	//select new object
	_selected_object = obj;
	_canvas.setActiveObject(obj);
	
	//save new info - for new object, it makes sure we have all information
	editor_saveSelectedObjectAttributes();
}


//handle different modifications on the object (modified, moving, rotating, scaling, ...)
function editor_canvas_object_modified(options) {
	//modify also linked image if exist
	if(options.target.linked_image) {
		options.target.linked_image.setAngle(options.target.getAngle());
		
		if(options.target.getHeight() < options.target.getWidth())
			options.target.linked_image.scaleToHeight(options.target.getHeight());
		else
			options.target.linked_image.scaleToWidth(options.target.getWidth());
			
		options.target.linked_image.setLeft(options.target.getLeft());
		options.target.linked_image.setTop(options.target.getTop());
	}

	//save new info
	editor_saveSelectedObjectAttributes();
	
	//place correctly the tooptip
	viewer_showTooltipForSelectedObject(false);
}

function editor_clearBackgroundImage() {
	_current_location.background_image = null;
	viewer_canvas_loadBackground(); //reload
}

function editor_selectBackgroundImage() {
	$("#files").click();
}

//handle the loading of an image (click on "select file...")
function editor_handleImageSelect(evt) {
	// Check for the various File API support.
	if (!(window.File && window.FileReader && window.FileList && window.Blob)) {
		alert(msg_fileAPI);
		return;
	}
	
	var files = evt.target.files; // FileList object
	//get first file
	var f = files[0];

     // Only process image files.
     if (!f.type.match('image.*')) {
        alert(msg_notImage);
		return;
    }

	var reader = new FileReader();

    // Closure to capture the file information.
    reader.onload = (function(theFile) {
		return function(e) {
			_current_location.background_image = e.target.result;
			
			viewer_canvas_loadBackground();
        };
      })(f);

      // Read in the image file as a data URL.
	reader.readAsDataURL(f);
}

function editor_changeFormOfSelectedObject() {
	var new_form = $("#form_type").val();
	if(new_form === _selected_object.type) return; //already the correct form
	
	var index = editor_getIndexOfSelectedObject();
		
	//delete type to avoid an override
	delete _current_location.objects[index].type;
		
	//create a new object based on the old one
	var obj;
	switch(new_form) {
		case "circle":
			//set a default radius
			_current_location.objects[index].radius = _current_location.objects[index].width / 2;
			obj = new fabric.Circle(_current_location.objects[index]);
			break;
		case "rect":
			obj = new fabric.Rect(_current_location.objects[index]);
			break;
		case "triangle":
			obj = new fabric.Triangle(_current_location.objects[index]);
			break;
	}
	
	//add it
	_canvas.add(obj);
	//relink image if needed
	if(_selected_object.linked_image) {
		obj.linked_image = _selected_object.linked_image;
		_selected_object.linked_image.linked_object = obj;
		//make sure image is in front
		_selected_object.linked_image.bringToFront();
	}
	//remove old object
	_canvas.remove(_selected_object);
	//select the new one
	_selected_object = obj;
	//resave it
	editor_saveSelectedObjectAttributes();
	//reload tooltip
	viewer_showTooltipForSelectedObject();
}

//return the index of the current selected object in the canvas into the current location list of objects
function editor_getIndexOfSelectedObject() {
	for(var i=0; i<_current_location.objects.length; i++) {
		if(_current_location.objects[i].device_id == _selected_object.device_id)
			return i;
	}
	return -1;
}

//save all the current canvas object attributes to the location object
function editor_saveSelectedObjectAttributes() {
	//get current object
	var index = editor_getIndexOfSelectedObject();
	if(index < 0) return;
	
	_current_location.objects[index] = _selected_object.toJSON(["device_id","device_name","family_icon_name","display_icon"]);
}

/*********************************************************************
*************************  TOOLTIP ***********************************
*********************************************************************/

//override default implementation from viewer
callback_beforeShowTooltip = function(){
	//load the value of the selected object into the tooltip
	$("#color-box").css("background-color", _selected_object.fill);
	$("#form_type").val(_selected_object.type);
	$("#opacity_slider").slider( "value", _selected_object.getOpacity() * 100);
	$("#form_display_image").prop("checked", _selected_object.display_icon == "1" ? true : false);
};

function editor_install_tooltip_extended_options() {
	$("#extended_tooltip_action").appendTo("#td_tooltip_actions");
	$("#extended_tooltip_content").appendTo("#td_tooltip_content");
	
	//create the slider
	$( "#opacity_slider" ).slider({
		min: 0,
		max: 100,
		value: 100,
		slide: function( event, ui ) {
			//set opacity of selected object
			_selected_object.setOpacity(ui.value / 100);
			//refresh canvas
			_canvas.renderAll();
			//save attribute of object
			editor_saveSelectedObjectAttributes();
		}
	});
}

function editor_setColorOfSelectedObject(color) {
	//change color of the canvas object
	_selected_object.setFill('#'+color);
	
	//save new info
	editor_saveSelectedObjectAttributes();
	
	//force reload, otherwise color not changed until unselect
	_canvas.renderAll();
}

function editor_removeSelectedObject() {
	//remove element from location
	_current_location.objects.splice(editor_getIndexOfSelectedObject(),1);
	
	//remove from canvas
	_canvas.fxRemove(_selected_object);
	//remove linked image if exist
	if(_selected_object.linked_image)
		_canvas.fxRemove(_selected_object.linked_image);
	
	//unselect all
	viewer_canvas_object_selected(null);
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

function editor_handleDisplayImageOption() {
	//save value
	_selected_object.display_icon = $("#form_display_image").prop("checked") ? "1" : "0";
	//save new info
	editor_saveSelectedObjectAttributes();

	//remove object
	var index = editor_getIndexOfSelectedObject();
	_canvas.fxRemove(_selected_object);
	//remove linked image if exist
	if(_selected_object.linked_image)
		_canvas.fxRemove(_selected_object.linked_image);
		
	//redisplay it
	var obj = viewer_display_object(index);

	//reselect object
	/*_selected_object = obj;
	//reload tooltip
	viewer_showTooltipForSelectedObject();*/
}

/*********************************************************************
************************* LOCATIONS MANAGEMENT ***********************
*********************************************************************/
function editor_renameLocation() {
	if($("#s_locations").val() == "-1") return; //do nothing if not location selected
	
	//ask the new name
	var ret = prompt("New name : ", $("#s_locations option:selected").text());
	if(ret == null) return;
	
	$.ajax({
			url: "editor/renameLocation?id=" + $("#s_locations").val() + "&name=" + ret,
			dataType: "json"
		})
		.done(function(result) {
			//change the label in the list
			$("#s_locations option:selected").text(ret);
		})
		.fail(function() {
			alert(msg_error_occured);
		});
}

function editor_saveLocation() {
	//if no current location, create a new one
	if($("#s_locations").val() == "-1") {
		editor_saveAsLocation();
		return;
	}
		
	$.post("editor/saveLocation?id=" + $("#s_locations").val(), {content: JSON.stringify(_current_location)})
	.fail(function() {
		alert(msg_error_occured);
	});
}

function editor_saveAsLocation() {
	//ask the new name
	var ret = prompt(msg_save_as, msg_copy_of + $("#s_locations option:selected").text());
	if(ret == null) return;

	$.post("editor/saveAsLocation?name=" + ret, {content: JSON.stringify(_current_location)})
	.done(function(result) {
		var new_id = result["new_id"];
		if(new_id == null || new_id == '') return;
		//add in the list
		$("#s_locations").append('<option selected="selected" value="' + new_id + '">' + ret + '</option>');
	})
	.fail(function() {
		alert(msg_error_occured);
	});
}

function editor_deleteLocation() {
	if(confirm(msg_confirm_delete + " '" + $("#s_locations option:selected").text() + "'") === false) return;
	
	$.ajax({
			url: "editor/deleteLocation?id=" + $("#s_locations").val(),
			dataType: "json"
		})
		.done(function(result) {
			//delete the option in the list
			$("#s_locations option:selected").remove();
			
			//clear the selection
			viewer_loadCurrentLocationIntoCanvas();
		})
		.fail(function() {
			alert(msg_error_occured);
		});
}