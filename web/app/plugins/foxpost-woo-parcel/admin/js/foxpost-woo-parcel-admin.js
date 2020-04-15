(function ($) {
    'use strict';
    jQuery(document).ready(function ($) {
        var $form = $('#posts-filter');
        $form.submit(function (event) {
            var $selects = $('#bulk-action-selector-top, #bulk-action-selector-bottom');

            $selects.each(function (index, elem) {
                if ($('#export_download_frame').length > 0) {
                    $('#export_download_frame').remove();
                }
                if ($(this).val() === 'foxpost_export_to_xls') {
                    var $iframe = $('<iframe id="export_download_frame" width="0" height="0" style="display: none;"></iframe>');
                    $('body').append($iframe);

                    var ids = [];
                    jQuery('#posts-filter').find('input[name="post[]"]:checked').each(function () {
                        ids.push($(this).val());
                    });
                    var params = jQuery.param({'ids': ids});
                    $iframe.attr("src", ajaxurl + (ajaxurl.indexOf('?') === -1 ? '?' : '&') + 'action=foxpost_woo_parcel&method=export_download&' + params);
                    setTimeout(function () {
                        window.location.reload();
                    }, 0);
                    return false;
                }
                if ($(this).val() === 'foxpost_generate_stickers') {

                    var $iframe = $('<iframe id="export_download_frame" width="0" height="0" style="display: none;"></iframe>');
                    $('body').append($iframe);

                    var ids = [];
                    jQuery('#posts-filter').find('input[name="post[]"]:checked').each(function () {
                        ids.push($(this).val());
                    });

                    var params = jQuery.param({'ids': ids});
                    $iframe.attr("src", ajaxurl + (ajaxurl.indexOf('?') === -1 ? '?' : '&') + 'action=foxpost_woo_parcel&method=generate_stickers&' + params);
                    setTimeout(function () {
                        window.location.reload();
                    }, 0);
                    return false;
                }
            });
        });
    });
})(jQuery);
