/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



$(document).ready(function () {

    $.fn.bootstrapSwitch.defaults.size = 'small';
    $.fn.bootstrapSwitch.defaults.onColor = 'success';
    $('#appbundle_slide_newTab').bootstrapSwitch();

    $("#bind_img").on('click', function (e) {
        e.preventDefault();
        $("#appbundle_slide_image_file").trigger("click");
    });

    $("#appbundle_slide_image_file").on('change', function () {
        var image = $(this)[0].files[0];
        var reg = new RegExp("image/.*");
        if ($(this).val() !== "" && (!reg.test(image.type) || image.size > 2000000)) {
            addNotification("<ul>Vous devez séléctionner un fichier valide : <li>Type : image/*</li><li>Taille MAX : 2 MO</li></ul>", "notice");
            $(this).val("");
        }
        if ($(this).val() === '') {
            $("#bind_img").html("Veuillez choisir une photo <i class='fa fa-paperclip'></i>");
        } else {
            $("#bind_img").html("Choisir une photo <i class='fa fa-paperclip'></i>");
        }
    });

    $("#save-order").on('click', function () {
        $this = $(this);
        $this.button("loading");
        $ul = $('ul.list-slide');
        $li = $ul.children('li');
        var array = {};
        var i = 1;
        $li.each(function () {
            array[i] = $(this).attr('data-id');
            i++;
        });
        ordre = JSON.stringify(array);
        $.post(
                $(location).attr('href'),
                {
                    "ordre": ordre
                },
                function (json) {
                    if (json.code === 0) {
                        addNotification(json.message, 'error');
                    } else if (json.code === 1) {
                        addNotification(json.message, 'success');
                    }
                    $this.button('reset');
                },
                'JSON'
                ).fail(function ($xhr) {
            var data = $.parseJSON($xhr.responseText);
            addNotification(data.exception, 'error');
            $this.button('reset');
        });
    });

    $('.list-slide').sortable({
        placeholder: 'highlight',
        items: ".list-item"
    });

});