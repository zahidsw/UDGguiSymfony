//initialisation
var dialog_link = null;
var floor_selected_for_link = -1;

$(document).ready(function() {
	//waiting dialog
	//$("#modal_waiting").css({'background-image': "url:('" + ajax_loading_image_path + "')"}); //set-up background
	 $("#modal_waiting").css({'background': "rgba( 255, 255, 255, .8 ) url('" + ajax_loading_image_path + "') 50% 50% no-repeat"});
				
	$(document).on({
		ajaxStart: function() {
			$("body").addClass("loading");
		},
		ajaxStop: function() {
			//$("body").removeClass("loading"); //DO NOTHING, WAIT RELOAD OF WINDOWS
		}    
	});
	
	//dialog to link a new location
	dialog_link = $( "#dialog_link" ).dialog({
		autoOpen: false,
		height: 150,
		width: 350,
		modal: true,
		title: msg_dialog_title,
		buttons: [
			{text: msg_dialog_ok,
			click: list_linkLocation
			},
			{text: msg_dialog_cancel,
			click: function() {
				dialog_link.dialog( "close" );
			}}
		]
	});
});

function list_unlinkLocation(floor_id) {
	if(!confirm(msg_confirmUnlink)) return;
	
	//reset location of floor
	$.get("editor/unlinkLocation?floor_id=" + floor_id)
		.done(function(result) {
			document.location.href = document.location.href;
	})
	.fail(function() {
		alert(msg_error_occured);
		document.location.href = document.location.href;
	});
}

function list_showLinksFor(floor_id) {
	floor_selected_for_link = floor_id;
	dialog_link.dialog( "open" );
}

function list_linkLocation() {
	dialog_link.dialog( "close" );
	
	//has a location?
	if($( "#select_locations" ).val() == null || $( "#select_locations" ).val() == "") return;
	
	//affect to floor
	$.get("editor/affectLocation?floor_id=" + floor_selected_for_link + '&location_id=' + $( "#select_locations" ).val())
		.done(function(result) {
			document.location.href = document.location.href;
	})
	.fail(function() {
		alert(msg_error_occured);
		document.location.href = document.location.href;
	});
}