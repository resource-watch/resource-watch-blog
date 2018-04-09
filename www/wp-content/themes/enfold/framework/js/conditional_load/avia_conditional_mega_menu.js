/**
 * This file holds the main javascript functions which is required for the conditional mega menu
 *
 * @author      Christian "Kriesi" Budschedl
 * @copyright   Copyright ( c ) Christian Budschedl
 * @link        http://kriesi.at
 * @link        http://aviathemes.com
 * @since       Version 1.1
 * @package     AviaFramework
 */
(function($)
{
    "use strict";
    var avia_conditional_logic = {

        recalcTimeout: false,

        // bind the click event to all elements with the class avia_uploader
        input_event: function()
        {
            $(document).on('click', '.menu-item-avia-enableconditionallogic', function()
            {
                var checkbox = $(this),
                    container = checkbox.parents('.menu-item:eq(0)');

                if(checkbox.is(':checked'))
                {
                    container.addClass('avia_conditional_active');
                    container.find('.avia_conditional_logic_field').show();
                }
                else
                {
                    container.removeClass('avia_conditional_active');
                    container.find('.avia_conditional_logic_field').hide();
                }

                //check if anything in the dom needs to be changed to reflect the (de)activation of the mega menu
                avia_conditional_logic.recalc();
            });

            $(document).on('change', '.menu-item-avia-conditional', function()
            {
                var select = $(this),
                    selected = select.find(':selected'),
                    container = select.parents('.menu-item:eq(0)');

                if(selected.hasClass('show_css_field'))
                {
                    container.find('.menu-item-avia-conditionalcss').show();
                }
                else
                {
                    container.find('.menu-item-avia-conditionalcss').hide();
                }

                avia_conditional_logic.recalc();
            });

            $(document).on('change', '.menu-item-avia-conditionalvalue', function()
            {
                var select = $(this),
                    selected = select.find(':selected'),
                    container = select.parents('.menu-item:eq(0)');

                if(selected.hasClass('show_id_field'))
                {
                    container.find('.menu-item-avia-conditionalid').show();
                }
                else
                {
                    container.find('.menu-item-avia-conditionalid').hide();
                }

                avia_conditional_logic.recalc();
            });

            $(document).on('mouseenter', '.menu-item-bar', function()
            {
                avia_conditional_logic.recalc();
            });
        },

        recalcInit: function()
        {
            $(document).on("mouseup", ".menu-item-bar", function(event, ui)
            {
                if(!$(event.target).is('a'))
                {
                    clearTimeout(avia_conditional_logic.recalcTimeout);
                    avia_conditional_logic.recalcTimeout = setTimeout(avia_conditional_logic.recalc, 500);
                }
            });
        },


        recalc : function()
        {
            $('.menu-item').each(function(i)
            {
                var item = $(this),
                    conditionallogic = item.find('.menu-item-avia-enableconditionallogic');

                if(conditionallogic.is(':checked'))
                {
                    item.addClass('avia_conditional_active');
                    item.find('.avia_conditional_logic_field').show();
                }
                else
                {
                    item.removeClass('avia_conditional_active');
                    item.find('.avia_conditional_logic_field').hide();
                }

                var conditionaltype = item.find('.menu-item-avia-conditional','.menu-item-avia-conditionalvalue');
                if(conditionaltype.find('option:selected').hasClass('show_css_field'))
                {
                    item.find('.menu-item-avia-conditionalcss').show();
                }
                else
                {
                    item.find('.menu-item-avia-conditionalcss').hide();
                }

                var conditionalvalue = item.find('.menu-item-avia-conditionalvalue');
                if(conditionalvalue.find('option:selected').hasClass('show_id_field'))
                {
                    item.find('.menu-item-avia-conditionalid').show();
                }
                else
                {
                    item.find('.menu-item-avia-conditionalid').hide();
                }


            });

        }

    };


    $(function()
    {
        avia_conditional_logic.input_event();
        avia_conditional_logic.recalcInit();
        avia_conditional_logic.recalc();
    });


})(jQuery);