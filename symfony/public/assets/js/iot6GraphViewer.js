/*********************************************************************
***************************** Global ********************************
*********************************************************************/
//the following array contains json objects representing each variable to draw into the graph
//the json has the following structure : {device_id:xx,device_name:xxx,variable_name:'<varName>', unit:'<unit>', color:'FF0000'}	
//we can have GENERIC VARIABLES which are not linked to a device, but to criterias. IN this case the json looks like :
//	{device_id:-1,device_name:'<criterias>',variable_name:'<varName>', unit:'<unit>', color:'FF0000',building_id:xx,floor_id:xx,room_type_id:xx, room_id:xx, category_id:xx, family_id:xx}	
var graph_elements_list = [];
var current_graph = null;

//initialisation
$(document).ready(function() {
	viewer_loadDates();
	viewer_managePreload();
	
	//alpha slider
	$( "#alpha_slider" ).slider({
		value: 0.15,
		min: 0,
		max: 1,
		step:0.05,
		slide: function( event, ui ) {
			if(current_graph != null) {
				current_graph.updateOptions({fillAlpha:$("#alpha_slider").slider( "value" )},false)
			}
		}
	});
});

/*********************************************************************
***************************** Preload ********************************
*********************************************************************/
function viewer_managePreload() {
	//*************** need to preload some graph ?
	//a graph can be preloaded with 1 or n device/variable
	//3 ways to make the preload :
	//- define a local javascript value called graph_preload (i.e. : var graph_preload='xxxxxx')
	//- pass a param in the URL called graph_preload
	//- call the function viewer_preload_graph with the graph_preload content as param
	//Structure of variable = "granularity,dateFrom,dateTo,device_id_1,device_name_1,variable_name_1,unit,color_1,device_id_2,device_name_2,..."
	//granularity,dateFrom and dateTo can be empty. So we use the default param
	//ie: ?graph_preload=5,,1369298803000,193,my device,....   (here from date is empty)
	//granulartiy (0 to 5), dateFrom/dateTo (use date in milliseconds format)
	var preload_as_string = typeof graph_preload !== 'undefined' ? graph_preload : viewer_getURLParameterValue("graph_preload");
	viewer_preload_graph(preload_as_string);
};

/* Reload page with the URL of the current page with all the graph options in the preload param
*/
function viewer_reloadWithParamInURL() {
	var ret = base_page_url;
	
	ret += '?graph_preload=';
	
	//granularity
	ret += $("#granularity").val();
	ret += ",";
	
	//dateFrom
	ret += new Date( $("#from").datepicker( "getDate" ) ).getTime();;
	ret += ",";
	
	//dateTo
	ret += new Date( $("#to").datepicker( "getDate" ) ).getTime();
	
	//variables
	for(var i=0; i<graph_elements_list.length; i++) {
		ret += ",";
		//device_id
		ret += graph_elements_list[i].device_id;
		ret += ",";
		//device_name
		ret += graph_elements_list[i].device_name;
		ret += ",";
		//variable_name
		ret += graph_elements_list[i].variable_name;
		ret += ",";
		//unit
		ret += graph_elements_list[i].unit;
		ret += ",";
		//color
		ret += graph_elements_list[i].color;
	}
	
	document.location.href = ret;
}

function viewer_preload_graph(preload_as_string) {
	if(preload_as_string === '') return;
	
	var preload = preload_as_string.split(",");
	
	if(preload.length > 0) {
		//handle granularity
		if(preload[0] !== '') {
			$("#granularity").val(preload[0]);
		}
		
		//handle dateFrom
		if(preload[1] !== '') {
			$("#from").datetimepicker('setDate',new Date(parseInt(preload[1])));
		}
		
		//handle dateTo
		if(preload[2] !== '') {
			$("#to").datetimepicker('setDate',new Date(parseInt(preload[2])));
		}

		//handle each device/variable
		var i;
		for(i=3; i<preload.length; i++) {
			viewer_add_variable_to_display(preload[i],preload[i+1],preload[i+2],preload[i+3],preload[i+4],false);
			i += 4;
		}
		
		//check minimum regroupement
		viewer_EnableDisableGranularity();
		
		//load the graph
		viewer_ajax_showGraph();
	}
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
***************************** Dates (from/to) ************************
*********************************************************************/
function viewer_loadDates() {
	//install the datetime picker
	$("#from").datetimepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd-mm-yy',
		timeFormat: 'HH:mm:ss',
		stepSecond: 5,
		
		timeOnlyShowDate:true,
		
		onClose: function( selectedDate ) {
			$( "#to" ).datetimepicker( "option", "minDate", selectedDate );
			viewer_EnableDisableGranularity();
		}
	});

	$("#to").datetimepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd-mm-yy',
		timeFormat: 'HH:mm:ss',
		stepSecond: 5,
		onClose: function( selectedDate ) {
			$( "#from" ).datetimepicker( "option", "maxDate", selectedDate );
			viewer_EnableDisableGranularity();
		}
	});
	
	//install the changes event trigger on the dates
	$("#from").change(function() {
		if($("#from").val() == "") //doesn't allow empty values
			$( "#from" ).datetimepicker('setDate', $( "#to" ).datetimepicker('getDate'));
	});
	$("#to").change(function() {
		if($("#to").val() == "") //doesn't allow empty values
			$( "#to" ).datetimepicker('setDate', $( "#from" ).datetimepicker('getDate'));
	});
	
	//set the 2 dates
	var currentDate = new Date();
	$( "#to" ).datetimepicker('setDate', currentDate ); //end date = current date
	//$( "#from" ).datetimepicker('setDate', (new Date((new Date()).getFullYear(),0,1,0,0,0,0))); //start of year
	currentDate.setMonth(currentDate.getMonth()-3);
	$( "#from" ).datetimepicker('setDate', currentDate); //start date = current date - 3 months
	
}

function viewer_EnableDisableGranularity() {
	//enable all granularity options
	$("#granularity > option").each(function() {
		$(this).prop('disabled',false);
	});
	
	//calculate period in days
	var fromMs = new Date( $("#from").datepicker( "getDate" ) ).getTime();;
	var toMs = new Date( $("#to").datepicker( "getDate" ) ).getTime();
	var diffDays = (toMs - fromMs) / 86400000; //day = 1000 ms * 60 seconds * 60 minutes * 24 heures
	
	//period > 1 year, at least regrouped by day
	if(diffDays > 365) {
		//disable no-grouping
		$("#granularity > option:nth-child(1)").prop('disabled',true);
		//disable group by minute
		$("#granularity > option:nth-child(2)").prop('disabled',true);
		//disable group by hour
		$("#granularity > option:nth-child(3)").prop('disabled',true);
		
		//select group by day
		$("#granularity").val(3);
	} else {
		//period > 3 months, at least regrouped by hour
		if(diffDays > 93) {
			//disable no-grouping
			$("#granularity > option:nth-child(1)").prop('disabled',true);
			//disable group by minute
			$("#granularity > option:nth-child(2)").prop('disabled',true);
			
			//select group by hour
			$("#granularity").val(2);
		}
	}
}

/*********************************************************************
***************************** Graphic ********************************
*********************************************************************/
function viewer_add_variable_to_display(device_id, device_name, variable_name, unit, color, loadGraph) {
	//check if variable already exists or not, and if unit is acceptable
	for(i=0; i < graph_elements_list.length; i++) {
		if(graph_elements_list[i]["device_id"] == device_id) {
			if(graph_elements_list[i]["variable_name"] == variable_name) {
				alert(msg_variable_already_exists);
				return;
			}
		}
		if(graph_elements_list[i]["unit"] !== unit) {
			alert(msg_unit_not_compatible);
			return;
		}
	}
	
	var new_element = {
		device_id: device_id,
		device_name: device_name,
		variable_name: variable_name,
		unit: unit,
		color: color
	};
	graph_elements_list.push(new_element);
	
	if(typeof loadGraph !== 'undefined' && loadGraph !== false)
		viewer_ajax_showGraph();
}

function viewer_add_generic_variable_to_display(device_id, device_name, variable_name, unit, color, building_id,floor_id,room_type_id, room_id, category_id, family_id,loadGraph) {
	//check if variable already exists or not, and if unit is acceptable
	for(i=0; i < graph_elements_list.length; i++) {
		if(graph_elements_list[i]["variable_name"] == variable_name) {
			if(graph_elements_list[i]["building_id"] == building_id) {
				if(graph_elements_list[i]["floor_id"] == floor_id) {
					if(graph_elements_list[i]["room_type_id"] == floor_id) {
						if(graph_elements_list[i]["room_id"] == floor_id) {
							if(graph_elements_list[i]["category_id"] == floor_id) {
								if(graph_elements_list[i]["family_id"] == floor_id) {
									alert(msg_variable_already_exists);
									return;
								}
							}
						}
					}
				}
			}
		}
		if(graph_elements_list[i]["unit"] !== unit) {
			alert(msg_unit_not_compatible);
			return;
		}
	}
	
	var new_element = {
		device_id: device_id,
		device_name: device_name,
		variable_name: variable_name,
		unit: unit,
		color: color,
		building_id: building_id,
		floor_id: floor_id,
		room_type_id: room_type_id,
		room_id: room_id,
		category_id: category_id,
		family_id: family_id
	};
	graph_elements_list.push(new_element);
	
	if(typeof loadGraph !== 'undefined' && loadGraph !== false)
		viewer_ajax_showGraph();
}

//function viewer_remove_variable_to_display(device_id, variable_name) {
function viewer_remove_variable_to_display(index) {
	//search variable to remove
	/*for(i=0; i < graph_elements_list.length; i++) {
		if(graph_elements_list[i]["device_id"] == device_id) {
			if(graph_elements_list[i]["variable_name"] == variable_name) {
				graph_elements_list.splice(i,1); //remove element
				viewer_ajax_showGraph(); //force reload array
				return; //quit loop
			}
		}
	}*/
	graph_elements_list.splice(index,1); //remove element
	viewer_ajax_showGraph(); //force reload array
}

function viewer_ajax_showGraph() {
	//check if there is some variable to show
	if(graph_elements_list.length <= 0) {
		$(div_graph).html(msg_no_variable_to_show_in_graph);
		return;
	}

	//build list of device and variable_name and other variables
	var devices_id = ""; //"143,194";
	var variables_name = ""; //"energy,energy";
	for(i=0; i < graph_elements_list.length; i++) {
		if(devices_id !== "") {
			devices_id += ',';
			variables_name += ',';
		}
		devices_id += graph_elements_list[i]['device_id'];
		variables_name += graph_elements_list[i]['variable_name'];
	}
	var from = $("#from").datetimepicker('getDate').toISOString();
	var to =  $("#to").datetimepicker('getDate').toISOString();
	var granularity = $("#granularity").val();
	
	viewer_showGraphLoading();
	//hideShowDiv("variables", true); //make sure the variables div is opened
	$.ajax({
			url: "viewer/getGraphDataForVariables?devices_id=" + devices_id + '&variables_name=' + variables_name + '&from=' + from + '&to=' + to + '&granularity=' + granularity,
			dataType: "json"
		})
		.done(function(result) {
			//do we have values to display ?
			if(result.length <= 0) {
				$(div_graph).html(msg_no_value);
				return;
			}
			//convert all dates from string to date objects
			for(var i=0; i<result.length; i++) {
				result[i][0] = new Date(result[i][0]*1000); //epoch datetime
			}
			viewer_showGraphWithJSON(result);
		})
		.fail(function() {
			viewer_showGraphError();
		});
}

function viewer_showGraphLoading() {
	$(div_graph).html("<img src='" + ajax_loading_image_path + "' />");
}

function viewer_showGraphError() {
	//$(div_variables).html(msg_ajax_error);
}

function viewer_showGraphWithJSON(result_JSON) {
	//create labels + colors for each elements
	var labels = [ msg_date_label]; //by default first one is the x axis = date
	var colors = [];

	for(i=0; i < graph_elements_list.length; i++) {
		labels.push(graph_elements_list[i]["device_name"] + "/" + graph_elements_list[i]["variable_name"]);
		colors.push("#"+graph_elements_list[i]["color"]);
	}

	var options = {
		labels: labels,
		customBars: $("#granularity").val() === "0" ? false : true, //data = [timestamp, min, avg, max]
		fillAlpha: $( "#alpha_slider" ).slider( "value" ),
		colors: colors,
		drawPoints:true,
		showRangeSelector: true,
		labelsDiv: 'div_graph_label',
		ylabel: graph_elements_list[0]["unit"],
		highlightSeriesOpts: {
			strokeWidth: 3,
			strokeBorderWidth: 1,
			highlightCircleSize: 5,
		}
	};

	/*//series 2 - for test only
	var d1 = [];
	var t = new Date().getTime();
	var t2;
	var v;
	var hour = 1000 * 60 * 60;
	for(var i=0; i< 5; i++) {
		t2 = new Date(t+(i*hour));		
		v = i;
		d1.push([t2,v]);
	}*/
	
	//console.log(result_JSON);


	current_graph = new Dygraph(document.getElementById("div_graph"),result_JSON,options);
}