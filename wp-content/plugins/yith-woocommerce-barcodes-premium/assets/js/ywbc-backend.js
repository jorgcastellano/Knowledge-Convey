(function ($) {

    $(document).ready(function () {

        $(document).on('click', '.ywbc-generate', function (e) {
            e.preventDefault();

            var id = $(this).data('id');
            var type = $(this).data('type');
            var container = $(this).closest('.ywbc-barcode-generation');

            var data = {
                'action': 'create_barcode',
                'type'  : type,
                'id'    : id
            };

            container.block({
                message   : null,
                overlayCSS: {
                    background: "#fff url(" + ywbc_data.loader + ") no-repeat center",
                    opacity   : .6
                }
            });

            $.post(ywbc_data.ajax_url, data, function (response) {
                container.replaceWith(response);
                container.unblock();
            });
        });
    });

})(jQuery);