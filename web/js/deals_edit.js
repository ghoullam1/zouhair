$(document).ready(function () {
    $("#appbundle_deal_image_file").removeAttr("required");
    $("#bind_img").on('click', function (e) {
        e.preventDefault();
        $("#appbundle_deal_image_file").trigger("click");
    });

    $.fn.bootstrapSwitch.defaults.size = 'small';
    $.fn.bootstrapSwitch.defaults.onColor = 'success';
    $('.switch').bootstrapSwitch();

    $("#appbundle_deal_image_file").on('change', function () {
        $("#check_image").val("true");
        var image = $(this)[0].files[0];
        var reg = new RegExp("image/.*");
        if ($(this).val() !== "" && (!reg.test(image.type) || image.size > 2000000)) {
            addNotification("<ul>Vous devez séléctionner un fichier valide : <li>Type : image/*</li><li>Taille MAX : 2 MO</li></ul>", "notice");
            $(this).val("");
        }
        if ($(this).val() === '') {
            $("#show_img").hide();
            $("#deleteImage").hide();
            $("#bind_img").show();
            $("#show_img").attr("src", "");
        } else {
            $("#show_img").attr("src", URL.createObjectURL(image));
            $("#show_img").show();
            $("#deleteImage").show();
            $("#bind_img").hide();
        }
    });

    $("#deleteImage").on('click', function (e) {
        e.preventDefault();
        $("#appbundle_deal_image_file").attr("required", "");
        $("#appbundle_deal_image_file").val("");
        $("#appbundle_deal_image_file").trigger('change');
    });


    $.timepicker.datetimeRange(
            $("#appbundle_deal_dateDebut"),
            $("#appbundle_deal_dateFin"),
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
                            document.getElementById("appbundle_deal_dateFin").value = '';
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


});