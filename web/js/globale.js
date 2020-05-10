$(document).ready(function () {

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



});


/*-----------------------Ajouter une notification-------------------------------------*/


/*
 * Ajouter une notification
 */
function addNotification(message, type) {
    var css_class = '';
    var icon = '';
    if (type === 'error') {
        css_class = 'danger';
        icon = '<i class="fa fa-times-circle" aria-hidden="true"></i>';
    } else if (type === 'notice') {
        css_class = 'warning';
        icon = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';
    } else if (type === 'success') {
        css_class = 'success';
        icon = '<i class="icon fa fa-check" ></i>';
    }
    var notification = '<br>'
            + '<section>'
            + '<div class="container" >'
            + '<div class="row" >'
            + '<div class="col-lg-12" >'
            + '<div class="alert alert-' + css_class + ' alert-dismissable">'
            + icon
            + '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'
            + ' ' + message
            + '</div>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '</section>';
    $("#notifications").html(notification);
    $("html, body").animate({scrollTop: 0}, "slow");
}

/*------------------------------------------------------------------------------*/