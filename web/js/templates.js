$(document).ready(function () {

    tinymce.init({
        language: 'fr_FR',
        selector: '#appbundle_template_contenu',
        height: 250,
        menubar: true,
        setup: function (editor) {
            editor.addButton('mybutton', {
                type: 'menubutton',
                text: 'Informations du formulaire',
                icon: false,
                menu: [{
                        text: 'Nom',
                        onclick: function () {
                            editor.insertContent('{{ name }}');
                        }
                    }, {
                        text: 'Email',
                        onclick: function () {
                            editor.insertContent('{{ email }}');
                        }
                    }, {
                        text: 'Téléphone',
                        onclick: function () {
                            editor.insertContent('{{ phone }}');
                        }
                    }, {
                        text: 'Sujet',
                        onclick: function () {
                            editor.insertContent('{{ subject }}');
                        }
                    }, {
                        text: 'Message',
                        onclick: function () {
                            editor.insertContent('{{ message }}');
                        }
                    }]
            });
            editor.on('blur', function (e) {
                var contenu = editor.getContent();
                $("#appbundle_template_contenu").val(contenu);
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