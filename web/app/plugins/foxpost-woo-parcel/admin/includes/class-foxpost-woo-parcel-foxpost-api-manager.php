<?php

namespace Foxpost_Woo_Parcel\Admin\Includes;

use Foxpost\FoxpostApi\FoxpostApi;
use Foxpost\FoxpostApi\FoxpostHttpRequest;
use Foxpost\FoxpostApi\FoxpostHttpRequestCurl;
use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel;
use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel_Shipping_Method;

/**
 * Class Foxpost_Woo_Parcel_Foxpost_Api_Manager
 * @package Foxpost_Woo_Parcel\Admin\Includes
 */
class Foxpost_Woo_Parcel_Foxpost_Api_Manager {
    /**
     * @var FoxpostApi
     */
    protected $foxpostApi;

    /**
     * @param array $orderIds
     *
     * @since 1.1.0
     *
     * @return bool
     */
    public function createParcels(array $orderIds)
    {
        $parcelItems = $this->getParcelItemsFromOrderIds($orderIds);

        if (empty($parcelItems)) {
            $message = Foxpost_Woo_Parcel::__('There was not any order to send to API.');
            $message .= ' ' . Foxpost_Woo_Parcel::__('Orders with exisitng CLFOXID are skipped.');
            $message .= ' ' . Foxpost_Woo_Parcel::__('Orders with no Foxpost shipping are skipped.');
            $_SESSION['foxpost_order_export_api_global_error'] = $message;

            return false;
        }

        $result = $this->getFoxpostApi()->sendMassCreateParcel($parcelItems);

        $this->processCreateParcelResult($result);

        return true;
    }

    /**
     * @param array $orderIds
     *
     * @since 1.1.0
     *
     * @return bool
     */
    public function createStickers(array $orderIds)
    {

        $parcelItems = $this->getStickerItemsFromOrderIds($orderIds);

        if (empty($parcelItems)) {
            $message = Foxpost_Woo_Parcel::__('There was not any order to send to API.');
            $message .= ' ' . Foxpost_Woo_Parcel::__('Orders without CLFOXID are skipped.');
            $message .= ' ' . Foxpost_Woo_Parcel::__('Orders with no Foxpost shipping are skipped.');
            $_SESSION['foxpost_order_sticker_api_global_error'] = $message;

            return false;
        }

        $result = $this->getFoxpostApi()->sendMassCreateSticker($parcelItems);

        return $this->processCreateStickerResult($result);
    }

    /**
     * @param array $orderIds
     *
     * @since 1.1.0
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function getParcelItemsFromOrderIds(array $orderIds)
    {
        $parcelItems = array();
        foreach ($orderIds as $orderId) {
            $is_foxpost_parcel = metadata_exists('post', $orderId, 'foxpost_woo_parcel_apt_id');
            if (!$is_foxpost_parcel) {
                continue;
            }

            $hasClfoxId = metadata_exists('post', $orderId, 'foxpost_order_clfox_id');
            if ($hasClfoxId) {
                continue;
            }

            $order_apt_id = get_post_meta($orderId, 'foxpost_woo_parcel_apt_id', true);

            if (preg_match('#hu\d+#', $order_apt_id)) {// APT
                $requestItem = new \Foxpost\FoxpostApi\RequestItemApt();
            } else {//HD
                $requestItem = new \Foxpost\FoxpostApi\RequestItemHomeDelivery();
            }
            $parcelData = Foxpost_Woo_Parcel_Export_Data_Provider::fetch_order_data(
                $orderId, array_combine($requestItem->getProperties(), $requestItem->getProperties())
            );
            $requestItem->setData($parcelData);

            $parcelItems[] = $requestItem;
        }

        return $parcelItems;
    }

    /**
     * @param array $orderIds
     *
     * @since 1.1.0
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function getStickerItemsFromOrderIds(array $orderIds)
    {
        $parcelItems = array();
        foreach ($orderIds as $orderId) {
            $is_foxpost_parcel = metadata_exists('post', $orderId, 'foxpost_woo_parcel_apt_id');
            if (!$is_foxpost_parcel) {
                continue;
            }

            $hasClfoxId = metadata_exists('post', $orderId, 'foxpost_order_clfox_id');
            if (!$hasClfoxId) {
                continue;
            }

            $clfoxId = get_post_meta($orderId, 'foxpost_order_clfox_id', true);

            $requestItem = new \Foxpost\FoxpostApi\RequestItemSticker();

            $parcelData = array(
                'clfoxId' => $clfoxId,
                'api_external_id' => $orderId,
            );
            $requestItem->setData($parcelData);

            $parcelItems[] = $requestItem;
        }

        return $parcelItems;
    }

    /**
     * @param \Foxpost\FoxpostApi\MassOperationResponse $result
     *
     * @since 1.1.0
     */
    protected function processCreateParcelResult($result)
    {
        $successCount = 0;
        /** @var \Foxpost\FoxpostApi\MassOperationResponseItem $item */
        foreach ($result->getItems() as $item) {
            if ($item->hasError()) {
                $errorData = array();
                if ($item->getMessage()) {
                    $errorData['error_message'] = $item->getMessage();
                }
                if ($item->getValidationErrors()) {
                    $errorData['validation_errors'] = $item->getValidationErrors();

                }

                $error = empty($errorData) ? '' : json_encode($errorData, JSON_UNESCAPED_UNICODE);
                update_post_meta($item->getOriginalId(), 'foxpost_order_export_api_error', $error);
            } else {
                ++ $successCount;
                update_post_meta($item->getOriginalId(), 'foxpost_order_exported_api', date('Y-m-d H:i:s'));
                update_post_meta($item->getOriginalId(), 'foxpost_order_clfox_id', $item->getClfoxId());
                delete_post_meta($item->getOriginalId(), 'foxpost_order_export_api_error');
            }
        }
        if ($result->hasError()) {
            $_SESSION['foxpost_order_export_api_global_error'] =
                $result->getErrorMessage();
        } else {
            $_SESSION['foxpost_order_export_api_success_message'] =
                sprintf(Foxpost_Woo_Parcel::__('Successfully sent orders count is: %d'), $successCount);
        }
    }

    /**
     * @param \Foxpost\FoxpostApi\MassOperationResponse $result
     *
     * @since 1.1.0
     */
    protected function processCreateStickerResult($result)
    {
        $successCount = 0;
        /** @var \Foxpost\FoxpostApi\MassOperationResponseItem $item */
        foreach ($result->getItems() as $item) {
            if ($item->hasError()) {
                $errorData = array();
                if ($item->getMessage()) {
                    $errorData['error_message'] = $item->getMessage();
                }
                if ($item->getValidationErrors()) {
                    $errorData['validation_errors'] = $item->getValidationErrors();

                }

                $error = empty($errorData) ? '' : json_encode($errorData, JSON_UNESCAPED_UNICODE);
                update_post_meta($item->getOriginalId(), 'foxpost_order_generate_sticker_error', $error);
            } else {
                ++ $successCount;
                update_post_meta($item->getOriginalId(), 'foxpost_order_generated_sticker', date('Y-m-d H:i:s'));
                delete_post_meta($item->getOriginalId(), 'foxpost_order_generate_sticker_error');
            }
        }
        if ($result->hasError()) {
            $_SESSION['foxpost_order_sticker_api_global_error'] =
                $result->getErrorMessage();
        }

        if ($successCount > 0) {
            $_SESSION['foxpost_order_sticker_api_success_message'] =
                sprintf(Foxpost_Woo_Parcel::__('Successfully generated stickers count is: %d'), $successCount);
        }
        $attachment = $result->getAttachments();

        return reset($attachment);
    }

    /**
     * @since 1.1.0
     *
     * @return \Foxpost\FoxpostApi\FoxpostApi
     */
    public function getFoxpostApi()
    {
        if (null === $this->foxpostApi) {

            $i18nClass = '\Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel';
            $options   = array(
                'apiUserName'             => Foxpost_Woo_Parcel_Shipping_Method::getCsvApiUserName(),
                'apiPassword'             => Foxpost_Woo_Parcel_Shipping_Method::getCsvApiPassword(),
                'translationFunction'     => array($i18nClass, '__'),
                'apiBaseUrl'              => defined('FOXPOST_WOO_PARCEL_API_URL') ? FOXPOST_WOO_PARCEL_API_URL : 'https://api.csomagvarazslo.hu',
                'httpRequestExtraOptions' => array(
                    'curl' => array(
                        CURLOPT_SSL_VERIFYHOST => defined('FOXPOST_CURLOPT_SSL_VERIFYHOST') ? FOXPOST_CURLOPT_SSL_VERIFYHOST : 2,
                        CURLOPT_SSL_VERIFYPEER => defined('FOXPOST_CURLOPT_SSL_VERIFYPEER') ? FOXPOST_CURLOPT_SSL_VERIFYPEER : 0,
                    ),
                ),
            );

            if (FoxpostHttpRequestCurl::isCurlAvailable()) {
                $options['httpRequestClass'] = '\Foxpost\FoxpostApi\FoxpostHttpRequestCurl';
            } elseif (FoxpostHttpRequest::isRemoteFileCallEnabled()) {
                $options['httpRequestClass'] = '\Foxpost\FoxpostApi\FoxpostHttpRequest';
            } else {
                throw new \Exception(Foxpost_Woo_Parcel::__('Nor cUrl or allow_url_fopen not available to make remote API call.'));
            }
            $this->foxpostApi = new \Foxpost\FoxpostApi\FoxpostApi($options);
        }

        return $this->foxpostApi;
    }
}