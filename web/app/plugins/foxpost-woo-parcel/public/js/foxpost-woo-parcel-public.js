(function ($) {
    'use strict';
    $(document).ready(function () {

        var FoxpostWooParcel = function ($) {
            var shippingMethodFieldSelector = 'input[name="shipping_method[0]"]';
            var $shippingMethodField = $(shippingMethodFieldSelector);
            var _init = function () {

                if (!$shippingMethodField.length) {
                    return;
                }

                if ($(document.body).hasClass('woocommerce-checkout')) {
                    initAjaxComplete();
                }
            };
            var initAjaxComplete = function () {
                $(document).ajaxComplete(function (event, request, settings) {

                    if ('foxpost_woo_parcel_apt_shipping' === getSelectedShippingMethod($shippingMethodField)) {
                        $('.foxpost_woo_parcel_apt_select_row').show();
                        $('.foxpost_woo_parcel_delivery_message').hide();
                    } else {
                        $('.foxpost_woo_parcel_apt_select_row').hide();
                        $('.foxpost_woo_parcel_delivery_message').show();
                    }
                })
            };
            var getSelectedShippingMethod = function ($shippingMethodField) {
                var selectedShippingMethod;
                if ($shippingMethodField.is(':radio')) {
                    selectedShippingMethod = $(shippingMethodFieldSelector + ':checked').val();
                } else {
                    selectedShippingMethod = $shippingMethodField.val();
                }
                selectedShippingMethod = selectedShippingMethod.replace(/(^.*?):\d+$/, "$1");

                return selectedShippingMethod;
            };

            return {
                init: function () {

                    $("#foxpost_woo_parcel_dialog").dialog(
                        {
                            autoOpen: false,
                            modal: true,
                            draggable: false,
                            height: 800,
                            width: 'auto',
                            maxWidth: 1000,
                            resizable: false,
                            open: function (ev, ui) {
                                $('#foxpost_woo_parcel_dialog_iframe').attr('src', foxpost_woo_parcel_frontend_messages.iframe_src);
                            }
                        }
                    );

                    $(document).on('click', '.map-chooser-button', function (event) {
                        event.preventDefault();
                        $("#foxpost_woo_parcel_dialog").dialog("open");
                    })

                    _init();
                }
            }
        }($).init();

        $('.closesubmodal').on('click', function () {
            $("#googleMapModal").modal("hide");
        });

        $('#terkep-modal').on('hidden.bs.modal', function () {
            $("#googleMapModal").modal("hide");
        });

        window.foxpost_woo_parcel_close_dialog = function (selectedAptId) {
            $('#foxpost_woo_parcel_apt_id').val(selectedAptId);
            $('#foxpost_woo_parcel_dialog').dialog('close');
        }

        $(window).resize(function () {
            $("#foxpost_woo_parcel_dialog").dialog("option", "position", {my: "center", at: "center", of: window});
        });
    });
})(jQuery);
