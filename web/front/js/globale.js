$(document).ready(function () {
    /*--------delete item from cart -----*/
    $("body").on('click', '.delete-item', function () {
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
                        $("div.cart-info").html(json.panier);
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

    /*---------Conroller la saisie  pour les champs numerique/entier/phone----------*/

    /*--------------------------Empecher copier/coller------------------------------*/

    function controlerLaSaisie() {
        /*
         * la classe phone autorise la saisie des chifres, des espace et le signe + 
         * exemple : +212 522 21 54 98 | 06 67 35 48 67 
         */
        $(".phone").on("keypress", function (e) {
            var char = e.keyCode || e.which;
            if ((char < 48 || char > 57) && char != 43 && char != 32) {
                return false;
            }
        });

        /*
         * la classe numerique autorise la saisie des chifres et des points 
         * exemple : 5.25 | 50.09
         */
        $(".numerique").on("keypress", function (e) {
            var char = e.keyCode || e.which;
            if ((char < 48 || char > 57) && char != 46) {
                return false;
            }
        });


        /*
         * la classe entier autorise la saisie des chifres seulement 
         * exemple : 5 | 152 
         */
        $(".entier").on("keypress", function (e) {
            var char = e.keyCode || e.which;
            if (char < 48 || char > 57) {
                return false;
            }
        });

        /*
         * Empecher copier/coller pour toutes les classes
         */
        $('.phone,.numerique,.entier').bind('paste', function (e) {
            e.preventDefault();
        });
    }

    controlerLaSaisie();

    /*------------------------------------------------------------------------------*/


    /*-----------------------Empecher le click droit-------------------------------------*/

    function cancelRightClick() {

        /*
         * Empecher le click droit pour les images
         */
        $("img").bind("contextmenu", function (e) {
            e.preventDefault();
        });
    }

    cancelRightClick();

    /*------------------------------------------------------------------------------*/

    /*-----------------------Controller les types des fichiers autorisé-------------------------------------*/


    /*
     * Autoriser les fichiers images seulement
     */
    $("input[type=file]").attr('accept', 'image/*');

    /*------------------------------------------------------------------------------*/


    /* ---------- inscription newsLetter ----------------*/


    $("body").on('submit', '#formNewsLetter', function (e) {
        e.preventDefault();
        $this = $(this);
        $btn = $this.find("button[type=submit]");
        $btn.button("loading");
        $.post(
                $this.attr('action'),
                $this.serialize(),
                function (json) {
                    if (json.code === 1) {
                        addNotification(json.msg, 'success');
                    } else if (json.code === 0) {
                        addNotification(json.msg, 'error');
                    } else if (json.code === -1) {
                        addNotification(json.msg, 'notice');
                    }
                    $btn.button("reset");
                },
                'JSON'
                ).fail(function ($xhr) {
            var data = $.parseJSON($xhr.responseText);
            addNotification(data.exception, 'error');
            $btn.button("reset");
        });
    });

    /*---------------------------------------------------*/
});