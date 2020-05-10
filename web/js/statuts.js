$(document).ready(function () {

    $.fn.bootstrapSwitch.defaults.size = 'small';
    $.fn.bootstrapSwitch.defaults.onColor = 'success';
    $('.switch').bootstrapSwitch();


    tinymce.init({
        language: 'fr_FR',
        selector: '#appbundle_statutcommande_template',
        height: 250,
        menubar: true,
        setup: function (editor) {
            editor.addButton('mybutton', {
                type: 'menubutton',
                text: 'Commande Informations',
                icon: false,
                menu: [{
                        text: 'Nom Client',
                        onclick: function () {
                            editor.insertContent('{{ commande.client.nom }}');
                        }
                    }, {
                        text: 'Prénom Client',
                        onclick: function () {
                            editor.insertContent('{{ commande.client.prenom }}');
                        }
                    }, {
                        text: 'Référence Commande',
                        onclick: function () {
                            editor.insertContent('{{ commande.reference }}');
                        }
                    }, {
                        text: 'Date Commande',
                        onclick: function () {
                            editor.insertContent('{{ commande.dateCommande|date("d/m/Y - H:i") }}');
                        }
                    }, {
                        text: 'Mode de livraison',
                        onclick: function () {
                            editor.insertContent('{{ commande.modeLivraison.ville.nom ~ " - " ~ commande.modeLivraison.libelle }}');
                        }
                    }, {
                        text: 'Frais de livraison',
                        onclick: function () {
                            editor.insertContent('{{ commande.modeLivraison.fraisLivraison|number_format(2, ".", " ") }} DH');
                        }
                    }, {
                        text: 'Total Commande',
                        onclick: function () {
                            editor.insertContent('{{ commande.total|number_format(2, ".", " ") }} DH');
                        }
                    }, {
                        text: 'Nom Site',
                        onclick: function () {
                            editor.insertContent('Web Store');
                        }
                    }]
            });
            editor.on('blur', function (e) {
                var contenu = editor.getContent();
                $("#appbundle_statutcommande_template").val(contenu);
            });
        },
        plugins: [
            'advlist autolink lists link charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime table contextmenu paste textcolor code'
        ],
        toolbar: 'mybutton | undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | forecolor backcolor ',
        content_css: '//www.tinymce.com/css/codepen.min.css'
    });


});