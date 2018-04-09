/**
 * This file holds the main javascript functions needed for the google maps widget (get coordinates from address, etc.)
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright ( c ) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.1
 * @package 	AviaFramework
 */
(function($)
{
   "use strict";

    /*
     * global var which contains the current widget container. Required for callback function
     */
    var avia_google_maps_widget_container = '';

    $(document).ready(function() {
        $('body').avia_google_maps_options();

        $(document).ajaxSuccess(function(e, xhr, settings){

            var widget_id_base = 'avia_google_maps';

            if(typeof(settings.data) !== 'undefined' && typeof(settings.data.search) !== 'undefined' && settings.data.search('action=save-widget') !== -1 && settings.data.search('id_base=' + widget_id_base) !== -1)
            {
                $('body').avia_google_maps_options();
            }
        });
    });

    $.fn.avia_google_maps_options = function()
    {
        $('.avia-find-coordinates-wrapper,.avia-loading-coordinates').hide();

        $(".avia-coordinates-help-link").on('click', function( event ) {
            event.preventDefault();

            avia_google_maps_widget_container = jQuery(this).parents('.widget-content');
            avia_google_maps_widget_container.find(".avia-coordinates-help-link").hide();
            avia_google_maps_widget_container.find(".avia-coordinates-wrapper").hide();
            avia_google_maps_widget_container.find(".avia-find-coordinates-wrapper").show();
        });

        $(".avia-populate-coordinates").click(function( event ) {
            event.preventDefault();

            avia_google_maps_widget_container = jQuery(this).parents('.widget-content');
            var streetAddress = avia_google_maps_widget_container.find(".avia-map-street-address").val(),
                city = avia_google_maps_widget_container.find(".avia-map-city").val(),
                state = avia_google_maps_widget_container.find(".avia-map-state").val(),
                postcode = avia_google_maps_widget_container.find(".avia-map-postcode").val(),
                country = avia_google_maps_widget_container.find(".avia-map-country").val();

            avia_google_maps_widget_container.find(".avia-loading-coordinates").show();

            var addressGeo = streetAddress + " " + city + " " + state + " " + postcode + " " + country;
            avia_fetch_coordinates(addressGeo);
        });

        function avia_fetch_coordinates(addressGeo)
        {
            var geocoder = new google.maps.Geocoder();

            geocoder.geocode( { 'address': addressGeo}, function(results, status)
            {
                var errormessage = '';

                if (status === google.maps.GeocoderStatus.OK)
                {
                    /*console.log(results);
                     console.log(results[0].geometry.location.lat() );
                     console.log(results[0].geometry.location.lng() );*/
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();

                    avia_print_coordinates(latitude,longitude);
                }
                else if (status === google.maps.GeocoderStatus.ZERO_RESULTS)
                {
                    if (!addressGeo.replace(/\s/g, '').length)
                    {
                        errormessage = AviaMapTranslation.insertaddress;
                    }
                    else
                    {
                        errormessage = AviaMapTranslation.latitude + ' ' + addressGeo + ' ' + AviaMapTranslation.notfound;
                    }
                }
                else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT)
                {
                    errormessage = AviaMapTranslation.toomanyrequests;
                }

                if(errormessage !== '') alert(errormessage);

                avia_google_maps_widget_container.find(".avia-coordinates-help-link").show();
                avia_google_maps_widget_container.find(".avia-find-coordinates-wrapper").fadeOut("fast");
                avia_google_maps_widget_container.find(".avia-loading-coordinates").hide();
                avia_google_maps_widget_container.find(".avia-coordinates-wrapper").show();
            });
        }

        function avia_print_coordinates(latitude,longitude)
        {
            avia_google_maps_widget_container.find(".avia-map-lat").val(latitude);
            avia_google_maps_widget_container.find(".avia-map-lng").val(longitude);
        }
    };
})(jQuery);
