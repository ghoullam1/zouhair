/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {


    $(".multi").multiselect({
        buttonText: function (options, select) {
            return options.length + " Séléctionné(s)";
        },
        buttonTitle: function (options, select) {
            var labels = [];
            options.each(function () {
                labels.push($(this).text());
            });
            return labels.join(' - ');
        }
    });

    $('#table-produit').DataTable({
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
            {"data": "reference"},
            {"data": "titre"},
            {"data": "prix"},
            {"data": "stock"},
            {"data": "categories"},
            {"data": "genres"},
            {"data": "isNew"},
            {"data": "enSolde"},
            {"data": "occasion"},
            {"data": "isBestProduct"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [4, 5, 6, 7, 8, 9, 10]},
            {"width": "10%", "targets": [0, 1]},
            {"width": "7%", "targets": 2},
            {"width": "5%", "targets": 3},
            {className: "text-center text-middle", "targets": [2, 3, 6, 7, 8, 9, 10]}
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
        $("#table-produit").DataTable().ajax.reload();
    });
    $("select.filter").on("change", function () {
        $("#table-produit").DataTable().ajax.reload();
    });

});
