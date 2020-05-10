$(document).ready(function () {


    $("#bind_img").on('click', function (e) {
        e.preventDefault();
        $("#appbundle_client_image_file").trigger("click");
    });

    $("#appbundle_client_image_file").on('change', function () {
        $("#check_image").val("true");
        var image = $(this)[0].files[0];
        var reg = new RegExp("image/.*");
        if ($(this).val() !== "" && (!reg.test(image.type) || image.size > 2000000)) {
            addNotification("<ul>Vous devez séléctionner un fichier valide : <li>Type : image/*</li><li>Taille MAX : 2 MO</li></ul>", "notice");
            $(this).val("");
        }
        if ($(this).val() === '') {
            $("#deleteImage").hide();
            $("#bind_img").show();
            $("#show_img").attr("src", "/webStore/web/cache/gregwar/f/a/l/l/b/fallback.jpg");
        } else {
            $("#show_img").attr("src", URL.createObjectURL(image));
            $("#show_img").show();
            $("#deleteImage").show();
            $("#bind_img").hide();
        }
    });

    $("#deleteImage").on('click', function (e) {
        e.preventDefault();
        $("#appbundle_client_image_file").val("");
        $("#appbundle_client_image_file").trigger('change');
    });


});