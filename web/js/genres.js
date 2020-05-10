$(document).ready(function () {

    if ($('#treeview').length) {
        var updateOutput = function (e)
        {
            var list = e.length ? e : $(e.target),
                    output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        };
        $('#treeview').nestable({
            maxDepth: 1,
            expandBtnHTML: '<button data-action="expand"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>',
            collapseBtnHTML: '<button data-action="collapse"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>'
        });
        $('#treeview').nestable().on('change', updateOutput);
        $('#treeview').nestable('collapseAll');
        updateOutput($('#treeview').data('output', $('#nestable-output')));

    }

    $("#save-order").on('click', function () {
        $this = $(this);
        $this.button("loading");
        $.post(
                $(location).attr('href'),
                {
                    "ordre": $("#nestable-output").val()
                },
                function (json) {
                    if (json.code === 0) {
                        addNotification(json.message, 'error');
                    } else if (json.code === 1) {
                        addNotification(json.message, 'success');
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