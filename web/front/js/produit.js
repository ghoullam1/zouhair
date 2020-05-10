$(document).ready(function () {

    $("#taille").on('change', function () {
        if ($("#couleur").length > 0) {
            $this = $(this);
            $.post(
                    $this.attr('data-url'),
                    {
                        "reference": $this.attr('data-reference'),
                        "taille": $this.val()
                    },
                    function (json) {
                        $("#couleur option").remove();
                        $.each(json, function (index, element) {
                            var o = new Option(element.nom, element.id);
                            $("#couleur").append(o);
                        });
                    },
                    'JSON'
                    ).fail(function ($xhr) {
                var data = $.parseJSON($xhr.responseText);
                addNotification(data.exception, 'error');
            });
        }
    });

    $("#couleur").on('change', function () {
        if ($("#taille").length > 0) {
            $this = $(this);
            $.post(
                    $this.attr('data-url'),
                    {
                        "reference": $this.attr('data-reference'),
                        "couleur": $this.val()
                    },
                    function (json) {
                        $("#taille option").remove();
                        $.each(json, function (index, element) {
                            var o = new Option(element.nom, element.id);
                            $("#taille").append(o);
                        });
                    },
                    'JSON'
                    ).fail(function ($xhr) {
                var data = $.parseJSON($xhr.responseText);
                addNotification(data.exception, 'error');
            });
        }
    });


    $("#share").jsSocials(
            {
                shares: [
                    {
                        share: "facebook",
                        label: "facebook"
                    },
                    "twitter",
                    "googleplus",
                    "whatsapp"
                ],
                showLabel: true,
                showCount: false,
                shareIn: "popup"
            }
    );

    $(".jssocials-share-whatsapp").addClass("hidden-lg hidden-md");

    $("#add-panier").on('click', function (e) {
        e.preventDefault();
        $this = $(this);
        $this.button("loading");
        $data = {};
        $data['quantite'] = $("#quantite").val();
        $data['reference'] = $this.attr("data-reference");
        if ($("#couleur").length > 0)
            $data['couleur'] = $("#couleur").val();
        if ($("#taille").length > 0)
            $data['taille'] = $("#taille").val();
        $.post(
                $this.attr('data-url'),
                $data,
                function (json) {
                    if (json.code === 1) {
                        addNotification(json.msg, 'success');
                        $("div.cart-info").html(json.panier);
                    } else if (json.code === 0) {
                        addNotification(json.msg, 'error');
                    } else if (json.code === -1) {
                        addNotification(json.msg, 'notice');
                    }
                    $this.button("reset");
                },
                'JSON'
                ).fail(function ($xhr) {
            var data = $.parseJSON($xhr.responseText);
            $this.button("reset");
            addNotification(data.exception, 'error');
        });
    });

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