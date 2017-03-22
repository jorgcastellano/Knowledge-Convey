jQuery(document).ready(function ($) {

    (function ($) {
        $.each(['show', 'hide'], function (i, ev) {
            var el = $.fn[ev];
            $.fn[ev] = function () {
                this.trigger(ev);
                return el.apply(this, arguments);
            };
        });
    })(jQuery);

    init_calendar();

    init_map();

    function init_map() {
        var maps = new Array();

        $('.yith_wcevti_address_map').each(function (i) {
            $(this).attr('id',  '_map_event_ticket_' + i);
            var latlng = new google.maps.LatLng($(this).data('latitude'), $(this).data('longitude'));
            var options = {
                zoom : 15,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            var map = new google.maps.Map(this, options);

            var marker = new google.maps.Marker(
                {
                    position: latlng,
                    map: map,
                });

            maps[$(this).attr('id')] = map;

        });

        $('.woocommerce-Tabs-panel').each(function () {
            $(this).on('show', function () {
                var display = ($(this).css('display') === 'none' ||
                $(this).css('display') === '') ? 'block' : 'none';
                $(this).css('display', display);
                var latlng = new google.maps.LatLng($(this).find('.yith_wcevti_address_map').data('latitude'), $(this).find('.yith_wcevti_address_map').data('longitude'));
                var map_id = $(this).find('.yith_wcevti_address_map').attr('id');

                if(typeof(map_id) != 'undefined' ) {
                    google.maps.event.trigger(maps[map_id], 'resize');
                    maps[map_id].setCenter(latlng);
                }
            });
        });

    }
    
    function init_calendar() {
        // Post_data must be have our action registered when we call wp_ajax hooks, in this case 'load_calendar_events_action';.
        var post_data =
        {
            action: 'load_calendar_events_action'
        };


        $('.monthly').each(function (i) {
            $(this).attr('id',  'mycalendar' + i);

            if (typeof $(this).monthly === "function") {
                $(this).monthly({
                    mode: 'event',
                    eventList: false,
                    linkCalendarToEventUrl: true,
                    jsonUrl: event_tickets_shortcodes.ajaxurl + '?' + jQuery.param(post_data),
                    dataType: 'json'
                });

                $.ajax({
                    type: "POST",
                    data: post_data,
                    url: event_tickets_shortcodes.ajaxurl
                }).success(function (data) {

                });
            }
        });
    }

});