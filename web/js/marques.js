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
});