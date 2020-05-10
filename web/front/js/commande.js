$(document).ready(function () {

    $(".rating a").on('click', function () {
        $this = $(this);
        $(".rating a").removeClass("selected-star no-selected-star");
        $(".rating a").each(function (index, element) {
            if (parseInt($this.attr('title')) >= parseInt($(this).attr('title')))
                $(this).addClass('selected-star');
            else
                $(this).addClass('no-selected-star');
        });
        $("#appbundle_commentaire_note").val($this.attr('title'));
    });

});