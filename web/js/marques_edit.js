$(document).ready(function () {

    $("#bind_img").on('click', function (e) {
        e.preventDefault();
        $("#appbundle_marque_image_file").trigger("click");
    });

    $("#appbundle_marque_categories").multiselect({
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

    $("#appbundle_marque_image_file").on('change', function () {
        $("#check_image").val("true");
        var image = $(this)[0].files[0];
        var reg = new RegExp("image/.*");
        if ($(this).val() !== "" && (!reg.test(image.type) || image.size > 2000000)) {
            addNotification("<ul>Vous devez séléctionner un fichier valide : <li>Type : image/*</li><li>Taille MAX : 2 MO</li></ul>", "notice");
            $(this).val("");
            return false;
        }
        $("#appbundle_marque_image_file").attr("required", "required");
        if ($(this).val() === '') {
            $("#show_img").hide();
            $("#show_img").attr("src", "");
        } else {
            $("#show_img").attr("src", URL.createObjectURL(image));
             $("#show_img").show();
        }
    });
    $("#appbundle_marque_image_file").removeAttr("required");


});