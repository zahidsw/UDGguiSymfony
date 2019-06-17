$(document).ready(function () {
    $('#div_humidity').hide();
    //------ ajax function called based on check box selected -------
    function ajaxCall($argument) {
        //  alert($argument);
        $.ajax({
            url: "/iotconfig/ajax",
            type: 'POST',
            data: {template_id: $argument},
            dataType: 'json',
            async: true,
            success: function (data, status) {
                //  $("#form").hide();
                //  alert("sadfsdd"+ data.username);
                //   $("#form").show();
                // $(#form).hidde();
                //data.username;
                // $("#pop_securitygroup option:selected").remove();
            },
            error: function (xhr, textStatus, errorThrown) {
                //  alert("fail");
                return false;
                // console.log('request failed');
            }
        });
    }

    //------- end ---------------------

    $("input[name='ingredients[]']").change(function () {
        if ($('#temp').is(":checked")) {
            $('#form').show();
            $('#div_humidity').hide();

            //alert("fff"+data);
        }
        else if ($('#humidity').is(":checked")) {
            $('#form').hide();
            $('#div_humidity').show();

        } else {
            $('#div_humidity').show();
        }

    });
    var input;
    $("tbody p").each(function () {
        var inputId = $(this).attr("id");
        document.getElementById(inputId).style.display = 'none';
    });




})
