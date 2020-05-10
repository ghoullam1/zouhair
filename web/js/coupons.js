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
                },
//                messages: {
//                    en: 'This value must greater than %s',
//                    fr: 'Cette valeur doit supérieure à %s'
//                }
            });
    $("#appbundle_coupon_montantMin,#appbundle_coupon_montantMax").on('keyup change', function () {
        $("#appbundle_coupon_montantMax").parsley().validate();
    });

    $(".date").datepicker({
        showButtonPanel: true,
        currentText: "Aujourd'hui",
        closeText: 'Effacer',
        dateFormat: "dd/mm/yy",
        onSelect: function () {
            $("#table-coupon").DataTable().ajax.reload();
        },
        onClose: function (dateText, inst) {
            if ($(window.event.srcElement).hasClass('ui-datepicker-close'))
            {
                document.getElementById(this.id).value = '';
                $("#table-coupon").DataTable().ajax.reload();
            }
        }
    });


    $.timepicker.datetimeRange(
            $("#appbundle_coupon_dateDebut"),
            $("#appbundle_coupon_dateFin"),
            {
                minInterval: (1000 * 60 * 60), // 1jour
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

    $('#table-coupon').DataTable({
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        "ajax": {
            "url": $(location).attr('href'),
            "type": "POST",
            "data": function (d) {
                var criteres = new Object();
                $(".filter").each(function () {
                    if ($(this).val() !== "") {
                        criteres[$(this).attr("id")] = $(this).val();
                    }
                });
                d.criteres = criteres;
            }
        },
        "columns": [
            {"data": "code"},
            {"data": "libelle"},
            {"data": "valeur"},
            {"data": "actif"},
            {"data": "freeShipping"},
            {"data": "pourcentage"},
            {"data": "dateDebut"},
            {"data": "dateFin"},
            {"data": "montantMin"},
            {"data": "montantMax"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: 10},
            {className: "text-center text-middle", "targets": [2, 3, 4, 5, 6, 7, 8, 9, 10]}
        ],
        "pageLength": 10,
        "language": {
            "sProcessing": "Traitement en cours...",
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            "sInfoPostFix": "",
            "sLoadingRecords": "Chargement en cours...",
            "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
            "oPaginate": {
                "sFirst": "Premier",
                "sPrevious": "Pr&eacute;c&eacute;dent",
                "sNext": "Suivant",
                "sLast": "Dernier"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            }
        },
    });

    $("input.filter").on("keyup", function () {
        $("#table-coupon").DataTable().ajax.reload();
    });
    $("select.filter").on("change", function () {
        $("#table-coupon").DataTable().ajax.reload();
    });

});
