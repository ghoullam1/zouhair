$(document).ready(function () {


    function checkInfo() {
        if ($("#table-items tbody tr.item").length === 0) {
            addNotification("Veuillez ajouter des produits à votre panier d'achats !", "notice");
            return false;
        }
        if ($("#appbundle_client_nom").val() === '' || $("#appbundle_client_prenom").val() === '' || $("#appbundle_client_email").val() === '' || $("#appbundle_client_gsm").val() === '' || $("#appbundle_client_ville").val() === '' || $("#appbundle_client_adresse").val() === '') {
            addNotification("Veuillez saisir toutes les informations !", "notice");
            return false;
        }
        if ($("#mode").val() === '-1') {
            addNotification("Veuillez sélectionner un mode de livraison !", "notice");
            return false;
        }

        return true;
    }

    checkInfo();

    $("#valider").on('click', function (e) {
        e.preventDefault();
        $this = $(this);
        if (checkInfo() === true) {
            $("#appbundle_client").trigger("submit");
        }
    });

    $("#mode").on('change', function () {
        $this = $(this);
        $.post(
                $this.attr('data-url'),
                {
                    "mode": $this.val()
                },
                function (json) {
                    if (json.code === 1) {
                        $("#table-items tbody").html(json.panier);
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


});