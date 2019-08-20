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

    ///////////////////------------------------------

    $(".securityremove").on("click", function (event) {
        var $selectedVal = $('#pop_securitygroup').val();
        if ($('#pop_securitygroup').val() != null) {
            if (confirm('Are you sure you want to delete SecurityGroup?')) {
                $(".hidden").show();
                $.ajax({
                    url: "/groupsecurity/ajax",
                    type: 'POST',
                    data: {template_id: $selectedVal},
                    dataType: 'json',
                    async: true,
                    success: function (data, status) {
                        $("#pop_securitygroup option:selected").remove();
                        $(".hidden").hide();
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log('request failed');
                        $(".hidden").hide();
                    }
                });
            } else {
                $(".hidden").hide();
            }
        } else {
            $(".hidden").hide();
            alert("please select the value first");

        }
    });

    //------- end ---------------------

    ///////////////////------------------------------

    $(".iotslicestatus").click(function () {
        $('.outputtext').css('display', 'none');
        var path = $(this).attr("path");
        var value = $(this).attr("value");
        $(".hidden").show();
        $.ajax({
            url: path,
            type: 'POST',
            data: {slice: value},
            dataType: 'json',
            async: true,
            success: function (data, status) {
                $('.outputtext').slideToggle('slow', function () {
                    $(this).text(data);

                });
                $(".hidden").hide();
            },
            error: function (xhr, textStatus, errorThrown) {
                $('.outputtext').slideToggle('slow', function () {
                });
                $(".hidden").hide();
            }
        });
    });
    ///////////////////------------------------------
    $('.iotslicestatus').each(function () {
        if ($(this).attr("status") == "") {
            $(this).css('display', 'none');
        }
    });
    $(".iotregister").click(function () {
        $('.outputtext').css('display', 'none');
        var path = $(this).attr("path");
        var value = $(this).attr("value");
        var status1 = "iotslice" + value;
        $("#" + status1).css('display', 'block');
        $(this).replaceWith('<img src="/assets/images/interface/icons/updated.png">');
        $(".hidden").show();
        $.ajax({
            url: path,
            type: 'POST',
            data: {slice: value},
            dataType: 'json',
            async: true,
            success: function (data, status) {
                $('.outputtext').slideToggle('slow', function () {
                    $(this).text(data);
                });
                $(".hidden").hide();
            },
            error: function (xhr, textStatus, errorThrown) {
                $('.outputtext').slideToggle('slow', function () {
                });
                $(".hidden").hide();
            }
        });
    });

    //------- end ---------------------
    $(".iotpop").click(function () {
        $('.outputtext').css('display', 'none');
        var path = $(this).attr("path");
        var value = $(this).attr("value");
        $(this).replaceWith('<img src="/assets/images/interface/icons/updated.png">');
        $(".hidden").show();
        $.ajax({
            url: path,
            type: 'POST',
            data: {slice: value},
            dataType: 'json',
            async: true,
            success: function (data, status) {
                $('.outputtext').slideToggle('slow', function () {
                    $(this).text(data);
                });
                $(".hidden").hide();
            },
            error: function (xhr, textStatus, errorThrown) {
                $('.outputtext').slideToggle('slow', function () {
                });
                $(".hidden").hide();
            }
        });
    });
    ///////////----------------------------
    $("input[name='ingredients[]']").change(function () {
        if ($('#temp').is(":checked")) {
            $('#form').show();
            $('#div_humidity').hide();
            }
        else if ($('#humidity').is(":checked")) {
            $('#form').hide();
            $('#div_humidity').show();
        } else {
            $('#form').hide();
            $('#div_humidity').show();
        }

    });

    //------- end ---------------------
    $(".iotslicedelete").click(function () {
        var path = $(this).attr("path");
        var value = $(this).attr("value");
        $(".hidden").show();
        $.ajax({
            url: path,
            type: 'POST',
            data: {slice: value},
            dataType: 'json',
            async: true,
            success: function (data, status) {
                $('.outputtext').slideToggle('slow', function () {
                    $(this).text(data);
                });
                $(".hidden").hide();
            },
            error: function (xhr, textStatus, errorThrown) {
                $('.outputtext').slideToggle('slow', function () {
                 });
                $(".hidden").hide();
            }
        });
    });

    var input;
    $("tbody p").each(function () {
        var inputId = $(this).attr("id");
        document.getElementById(inputId).style.display = 'none';
    });

    $("#remove").click(function () {
        $(".hidden").show();
        //  $(".hidden").hidden();
    });



    $('#iot_configuration_emergencySliceName').on('change', function() {
        if(this.value == 'video') {
            $('.videoslice').show();
        }
        else if(this.value == 'sms') {
            $('.videoslice').hide();
            $('.second-select option[value="4"]').hide();
            $('.second-select option[value="3"]').hide();
            $('.second-select').prop('selectedIndex',2);
            $('#smsslice').show();
        }
        else if(this.value == 'email') {
            $('.videoslice').hide();
            $('.second-select option[value="4"]').hide();
            $('.second-select option[value="3"]').hide();
            $('.second-select').prop('selectedIndex',2);
            $('#smsslice').show();
        }
        else{
            $('.videoslice').hide();
            $('#smsslice').hide();
        }
    })
    $('.videoslice').hide();
    $('#smsslice').hide();
});
