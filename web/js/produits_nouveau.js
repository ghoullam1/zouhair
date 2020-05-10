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
    $("#appbundle_produit_images").addClass("row");

    var $container = $('div#appbundle_produit_images');

    function addImage() {
        if (checkSelectedImage() === false) {
            return false;
        }
        indexing();
        index = $container.find("input[type=file]").length;

        var photo = '<div class="col-md-4">'
                + '<div class="form-group">'
                + '<label class="col-sm-3 control-label">Photo ' + (index + 1) + ' </label>'
                + '<div class="col-sm-9">'
                + '    <button class="btn btn-sm btn-primary bind_img"><i class="fa fa-paperclip"></i> Veuillez choisir une photo</button> <button title="Supprimer" class="btn btn-sm btn-danger delete_img"><i class="fa fa-times"></i></button>'
                + '    <input class="img-file" accept="image/*" style="display: none;" required="" type="file" name="appbundle_produit[images][' + index + '][file]" id="appbundle_produit_images_' + index + '_file" />'
                + '</div>'
                + '</div>'
                + '<div class="form-group text-center">'
                + '<img style="max-height: 200px;" class="img-thumbnail show-img" />'
                + '</div>'
                + '</div>';
        $container.append(photo);

    }


    function indexing() {
        $container.find("input[type=file]").each(function (index) {
            $(this).attr("id", "appbundle_produit_images_" + index + "_file");
            $(this).attr("name", "appbundle_produit[images][" + index + "][file]");
        });
        $container.find("label").each(function (index) {
            $(this).text("Photo " + (index + 1));
        });
    }

    $("body").on('click', ".bind_img", function (e) {
        e.preventDefault();
        $input = $(this).closest("div[class=col-md-4]").find("input[type=file]");
        $input.trigger("click");
    });

    $("body").on('click', ".delete_img", function (e) {
        e.preventDefault();
        $(this).closest("div[class=col-md-4]").remove();
        indexing();
    });


    $("#add_image").on('click', function (e) {
        e.preventDefault();
        addImage();
    });

    function checkSelectedImage() {
        var allSelected = true;
        $container.find("input[type=file]").each(function (index) {
            if ($(this).val() === '') {
                addNotification("Vous devez sélectionner un fichier image pour la photo " + (index + 1), "notice");
                allSelected = false;
            }
        });
        return allSelected;
    }

    $("body").on('change', '.img-file', function () {
        var image = $(this)[0].files[0];
        var reg = new RegExp("image/.*");
        if ($(this).val() !== "" && (!reg.test(image.type) || image.size > 2000000)) {
            addNotification("<ul>Vous devez séléctionner un fichier valide : <li>Type : image/*</li><li>Taille MAX : 2 MO</li></ul>", "notice");
            $(this).val("");
        }
        if ($(this).val() === '') {
            $(this).closest("div.col-md-4").find("img.show-img").hide();
            $(this).closest("div.col-md-4").find("img.show-img").attr("src", "");
            $(this).closest("div.col-md-4").find("button.bind_img").html("<i class='fa fa-paperclip'></i> Veuillez choisir une photo");
        } else {
            $(this).closest("div.col-md-4").find("img.show-img").show();
            $(this).closest("div.col-md-4").find("img.show-img").attr("src", URL.createObjectURL(image));
            $(this).closest("div.col-md-4").find("button.bind_img").html("<i class='fa fa-refresh'></i> Modifier la photo");
        }
    });

});