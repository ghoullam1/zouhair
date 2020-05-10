/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {


    $("#appbundle_produit_genres").multiselect({
        buttonText: function (options, select) {
            if (options.length === 0)
                return 'sélectionner des genres';
            else {
                var labels = [];
                options.each(function () {
                    labels.push($(this).text());
                });
                return labels.join(' , ');
            }
        },
        buttonTitle: function (options, select) {
            var labels = [];
            options.each(function () {
                labels.push($(this).text());
            });
            return labels.join(' - ');
        }
    });
    $("#appbundle_produit_categories").multiselect({
        buttonText: function (options, select) {
            if (options.length === 0)
                return 'sélectionner des catégories';
            else {
                var labels = [];
                options.each(function () {
                    labels.push($(this).text());
                });
                return labels.join(' , ');
            }
        },
        buttonTitle: function (options, select) {
            var labels = [];
            options.each(function () {
                labels.push($(this).text());
            });
            return labels.join(' - ');
        }
    });
    
    $.fn.bootstrapSwitch.defaults.size = 'small';
    $.fn.bootstrapSwitch.defaults.onColor = 'success';
    $('.switch').bootstrapSwitch();
    $("#appbundle_produit_dateEndNew_date,#appbundle_produit_dateEndNew_time").addClass("form-control");

    $("#add_image").on('click', function () {
        $("#imageModal").modal("show");
    });
    $("#add_variation").on('click', function () {
        $("#variationModal").modal("show");
    });

    $("#bind_img").on('click', function (e) {
        e.preventDefault();
        $("#appbundle_image_file").trigger("click");
    });

    $("body").on('change', '#appbundle_image_file', function () {
        var image = $(this)[0].files[0];
        var reg = new RegExp("image/.*");
        if ($(this).val() !== "" && (!reg.test(image.type) || image.size > 2000000)) {
            addNotification("<ul>Vous devez séléctionner un fichier valide : <li>Type : image/*</li><li>Taille MAX : 2 MO</li></ul>", "notice");
            $(this).val("");
        }
        if ($(this).val() === '') {
            $("#show-img").hide();
            $("#show-img").attr("src", "");
            $("#bind_img").html("<i class='fa fa-paperclip'></i> Veuillez choisir une photo");
        } else {
            $("#show-img").show();
            $("#show-img").attr("src", URL.createObjectURL(image));
            $("#bind_img").html("<i class='fa fa-refresh'></i> Modifier la photo");
        }
    });
    $('#imageModal').on('show.bs.modal', function (event) {
        $("#appbundle_image_file").val('');
        $("#show-img").hide();
        $("#show-img").attr("src", "");
        $("#bind_img").html("<i class='fa fa-paperclip'></i> Veuillez choisir une photo");
        $("#appbundle_image").parsley().reset();
        $("#appbundle_image").trigger("reset");
    });
    $('#variationModal').on('show.bs.modal', function (event) {
        $("#appbundle_produitvariation").parsley().reset();
        $("#appbundle_produitvariation").trigger("reset");
    });

});