/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {

    window.Parsley
            .addValidator('greaterThan', {
                requirementType: 'string',
                validateNumber: function (value, requirement) {
                    return value > document.getElementById(requirement).value;
                }
            });
    $("#appbundle_coupon_montantMin,#appbundle_coupon_montantMax").on('keyup change', function () {
        $("#appbundle_coupon_montantMax").parsley().validate();
    });




    $.timepicker.datetimeRange(
            $("#appbundle_coupon_dateDebut"),
            $("#appbundle_coupon_dateFin"),
            {
                minInterval: (1000 * 60 * 60), // 1 heure
                dateFormat: 'dd/mm/yy',
                timeFormat: 'HH:mm',
                start: {
                    showButtonPanel: true,
                    currentText: "Maintenant",
                    closeText: 'Effacer',
                    onClose: function (dateText, inst) {
                        if ($(window.event.srcElement).hasClass('ui-datepicker-close'))
                        {
                            document.getElementById(this.id).value = '';
                            document.getElementById("appbundle_coupon_dateFin").value = '';
                        }
                    }
                },
                end: {
                    showButtonPanel: true,
                    currentText: "Maintenant",
                    closeText: 'Effacer',
                    onClose: function (dateText, inst) {
                        if ($(window.event.srcElement).hasClass('ui-datepicker-close'))
                        {
                            document.getElementById(this.id).value = '';
                        }
                    }
                }
            }
    );

    $.fn.bootstrapSwitch.defaults.size = 'small';
    $.fn.bootstrapSwitch.defaults.onColor = 'success';
    $('.switch').bootstrapSwitch();
    $(".multi").multiselect({
        buttonText: function (options, select) {
            return options.length + " Clients Séléctionné(s)";
        },
        buttonTitle: function (options, select) {
            var labels = [];
            options.each(function () {
                labels.push($(this).text());
            });
            return labels.join(' - ');
        },
        enableFiltering: true,
        disableIfEmpty: true,
        disabledText: 'Aucun client trouvé'
    });

    $('#appbundle_coupon_freeShipping').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $("#appbundle_coupon_valeur").removeAttr("required");
            $("#appbundle_coupon_valeur").parsley().destroy();
        } else {
            $("#appbundle_coupon_valeur").attr("required", "required");
            $("#appbundle_coupon_valeur").parsley().validate();
        }

    });
    $('#appbundle_coupon_forAll').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $("#clients").hide();
        } else {
            $("#clients").show();
        }
        $('#appbundle_coupon_clients').multiselect('deselectAll', false);
        $('#appbundle_coupon_clients').multiselect('updateButtonText');

    });

    if ($('#appbundle_coupon_freeShipping').is(":checked")) {
        $("#appbundle_coupon_valeur").removeAttr("required");
        $("#appbundle_coupon_valeur").parsley().destroy();
    }

    if ($('#appbundle_coupon_forAll').is(":checked")) {
        $("#clients").hide();
    }



});
