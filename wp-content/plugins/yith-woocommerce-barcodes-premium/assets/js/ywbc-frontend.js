(function ($) {

    $(document).ready(function () {
        $('button[name="ywbc-action"]').on('click', function (e) {
            e.preventDefault();

            $('.ywbc-action-results').empty();

            var form = $(this).closest('form[name="yith-barcodes-form"]');
            var barcode_value = form.find('input[name="yith-barcode-value"]').val();

            if (!barcode_value) {
                return;
            }

            var data = {
                'action': 'barcode_actions',
                'type'  : form.find('input[name="yith-type"]').val(),
                'text'  : form.find('input[name="yith-barcode-value"]').val(),
                'value' : $(this).data('action'),
            };

            form.block({
                message   : null,
                overlayCSS: {
                    background: "#fff url(" + ywbc_data.loader + ") no-repeat center",
                    opacity   : .6
                }
            });

            $.post(ywbc_data.ajax_url, data, function (response) {
                if (1 == response.code) {
                    form.append('<div class="ywbc-action-results">' + response.value + '</div>');
                }
                else if (-1 == response.code) {
                    form.append('<div class="ywbc-action-results error">' + response.value + '</div>');
                }
                form.unblock();
            });

        });
    });

})(jQuery);