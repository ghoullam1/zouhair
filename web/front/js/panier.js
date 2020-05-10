$(document).ready(function () {


    $("body").on('click', '#clear-cart', function (e) {
        e.preventDefault();
        $this = $(this);
        $this.button("loading");

        $.post(
                $this.attr('data-url'),
                {},
                function (json) {
                    if (json.code === 1) {
                        addNotification(json.msg, 'success');
                        $("div.cart-details").html(json.panier);
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

    $("body").on("click", ".qtybutton", function () {
        var $this = $(this);
        var oldValue = $this.parent().find("input").val();
        if ($this.hasClass('dec') && oldValue === '1') {
            addNotification("La quantité commandée ne doit pas être inférieure a 1", 'notice');
            return false;
        }
        $data = {};
        $data['reference'] = $this.parent("div.cart-plus-minus").attr('data-reference');
        if ($this.parent("div.cart-plus-minus").attr('data-couleur') !== '')
            $data['couleur'] = $this.parent("div.cart-plus-minus").attr('data-couleur');
        if ($this.parent("div.cart-plus-minus").attr('data-taille') !== '')
            $data['taille'] = $this.parent("div.cart-plus-minus").attr('data-taille');
        $this.button("loading");
        $.post(
                $this.attr('data-url'),
                $data,
                function (json) {
                    if (json.code === 1) {
                        $("div.cart-details").html(json.panier);
                    } else if (json.code === 0) {
                        addNotification(json.msg, 'error');
                    } else if (json.code === -1) {
                        addNotification(json.msg, 'notice');
                    }
                    $this.button("loading");
                },
                'JSON'
                ).fail(function ($xhr) {
            var data = $.parseJSON($xhr.responseText);
            addNotification(data.exception, 'error');
            $this.button("loading");
        });
    });

    $("#cart-qte").find("input").on("keypress", function (e) {
        e.preventDefault();
    });
    $("#cart-qte").find("input").bind('paste', function (e) {
        e.preventDefault();
    });


    /*--------delete item from cart -----*/
    $("body").on('click', '.delete-produit', function () {
        $this = $(this);
        $data = {};
        $data['reference'] = $this.attr("data-reference");
        if ($this.attr("data-couleur") !== '')
            $data['couleur'] = $this.attr("data-couleur");
        if ($this.attr("data-taille") !== '')
            $data['taille'] = $this.attr("data-taille");
        $.post(
                $this.attr('data-url'),
                $data,
                function (json) {
                    if (json.code === 1) {
                        addNotification(json.msg, 'success');
                        $("div.cart-details").html(json.panier);
                    } else if (json.code === 0) {
                        addNotification(json.msg, 'error');
                    } else if (json.code === -1) {
                        addNotification(json.msg, 'notice');
                    }
                },
                'JSON'
                ).fail(function ($xhr) {
            var data = $.parseJSON($xhr.responseText);
            addNotification(data.exception, 'error');
        });
    });



    /*-------- Coupon operation -----*/
    $("body").on('click', '.coupon-link', function () {
        $this = $(this);
        $this.button('loading');
        $.post(
                $this.attr('data-url'),
                {
                    'code': $("#code-coupon").val()
                },
                function (json) {
                    if (json.code === 1) {
                        addNotification(json.msg, 'success');
                        $("div.cart-details").html(json.panier);
                    } else if (json.code === 0) {
                        addNotification(json.msg, 'error');
                    } else if (json.code === -1) {
                        addNotification(json.msg, 'notice');
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

});