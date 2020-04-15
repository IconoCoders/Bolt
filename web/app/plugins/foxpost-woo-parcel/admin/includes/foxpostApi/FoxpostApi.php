<?php
/**
 * @author Foxpost-GZ
 */

namespace Foxpost\FoxpostApi;

if (!defined('FOXPOSTAPI_ROOT')) {
    define('FOXPOSTAPI_ROOT', __DIR__ . '/');
    require FOXPOSTAPI_ROOT . '/FoxpostApiAutoloader.php';
}

new FoxpostApiAutoloader(FOXPOSTAPI_ROOT);

/**
 * Class FoxpostApi
 * @package Foxpost\FoxpostApi
 */
class FoxpostApi {

    const ACTION_MASS_CREATE_PARCEL = 'mass-create-parcel';
    const ACTION_MASS_CREATE_STICKER = 'mass-create-sticker';

    /**
     * @var string
     */
    protected $apiBaseUrl = 'https://api.csomagvarazslo.hu';
    /**
     * @var string
     */
    protected $apiVersion = 'v1';
    /**
     * @var string
     */
    protected $httpRequestClass = '\Foxpost\FoxpostApi\FoxpostHttpRequest';
    /**
     * @var string
     */
    protected $apiUserName;
    /**
     * @var string
     */
    protected $apiPassword;
    /**
     * @var string
     */
    protected $parcelTrackUrl = 'https://www.foxpost.hu/csomagkovetes/?code=%s';
    /**
     * @var array
     */
    protected $apiActions = array(
        self::ACTION_MASS_CREATE_PARCEL => 'parcel/mass-create-parcel',
        self::ACTION_MASS_CREATE_STICKER => 'parcel/mass-create-sticker',
    );
    /**
     * @var callable
     */
    protected $translationFunction;
    /**
     * @var array
     */
    protected $httpRequestExtraOptions = array();

    /**
     * FoxpostApi constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        foreach ($options as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * @param array $requestItems
     *
     * @return MassOperationResponse
     * @throws \ReflectionException
     */
    public function sendMassCreateParcel(array $requestItems)
    {
        $this->checkRequestItems($requestItems);
        $apiUrl = $this->getApiUrl(self::ACTION_MASS_CREATE_PARCEL);
        $request = $this->getHttpRequestObject();

        $response = $request->sendPostRequest($apiUrl, $this->createRequestDataFromRequestItems($requestItems), 'basicAuth');

        return $this->processResponse($response);
    }

    /**
     * @param array $requestItems
     *
     * @since 1.1.0
     *
     * @return MassOperationResponse
     * @throws \ReflectionException
     */
    public function sendMassCreateSticker(array $requestItems)
    {
        $this->checkRequestItems($requestItems);
        $apiUrl = $this->getApiUrl(self::ACTION_MASS_CREATE_STICKER);
        $request = $this->getHttpRequestObject();

        $response = $request->sendPostRequest($apiUrl, $this->createRequestDataFromRequestItems($requestItems), 'basicAuth');

        return $this->processResponse($response);
    }

    /**
     * @param array[RequestItemInterface] $requestItems
     *
     * @throws \Exception
     */
    protected function checkRequestItems(array $requestItems)
    {
        foreach ($requestItems as $requestItem) {
            if (!$requestItem instanceof \Foxpost\FoxpostApi\RequestItemInterface) {
                throw new \Exception($this->__('The given requestItem does not implement RequestItemInterface!'));
            }
        }
    }

    /**
     * @param string $action
     *
     * @return string
     * @throws \Exception
     */
    protected function getApiUrl($action)
    {
        if (isset($this->apiActions[$action])) {
            return implode('/', array_filter(array(
                rtrim($this->apiBaseUrl, '/'),
                $this->apiVersion,
                $this->apiActions[$action],
            )));
        }
        throw new \Exception($this->__('Invalid api action:') . $action);
    }

    /**
     * @param $requestItems
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function createRequestDataFromRequestItems($requestItems)
    {
        $itemsContainer = array('items' => array());
        /** @var RequestItemInterface $requestItem */
        foreach ($requestItems as $requestItem) {
            $itemsContainer['items'][] = $requestItem->getData();
        }

        return $itemsContainer;
    }

    /**
     * @return FoxpostHttpRequestInterface
     */
    protected function getHttpRequestObject()
    {
        /** @var \Foxpost\FoxpostApi\FoxpostHttpRequestInterface $httpRequest */
        $httpRequest = new $this->httpRequestClass();
        $credentials = array(
            'basicAuth' => array(
                'username' => $this->apiUserName,
                'password' => $this->apiPassword,
            ),
        );
        $httpRequest->setCredentials($credentials);
        $httpRequest->setExtraOption($this->httpRequestExtraOptions);

        return $httpRequest;
    }

    /**
     * @param string $response
     *
     * @return MassOperationResponse
     * @throws \ReflectionException
     */
    protected function processResponse($response)
    {
        $apiResponse = new \Foxpost\FoxpostApi\MassOperationResponse();
        $responseData = json_decode($response, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            $responseString = $response ? var_export($response, true) : '';
            $apiResponse->setData(array(
                'errorMessage' => $this->__('Error during the communnication, invalid JSON response.') . mb_substr($responseString, 0, 500),
                'error' => true,
            ));
        } else {
            if (isset($responseData['status'], $responseData['message'])) {
                $apiResponse->setData(array(
                    'errorMessage' => implode(' : ', array(
                        $responseData['status'],
                        $responseData['message']
                    )),
                    'error' => true,
                ));
            } else {
                $apiResponse->setData(array(
                    'errorMessage' => isset($responseData['errorMessage']) && !empty($responseData['errorMessage']) ? $this->__('Error message from server: ') . $responseData['errorMessage'] : '',
                    'error' => isset($responseData['error']) ? (bool) $responseData['error'] : true,
                    'items' => isset($responseData['items']) ? $responseData['items'] : array(),
                    'attachments' => isset($responseData['attachments']) ? $responseData['attachments'] : array(),
                ));
            }
        }

        return $apiResponse;
    }

    /**
     * I18n function.
     *
     * @param string $string String to translate.
     *
     * @return string
     */
    protected function __($string)
    {
        if (is_callable($this->translationFunction)) {
            return call_user_func($this->translationFunction, $string);
        }

        return $string;
    }

    /**
     * @param string $clfoxId
     *
     * @return string
     */
    public function getParcelTrackUrlByClfoxId($clfoxId)
    {
        return sprintf($this->parcelTrackUrl, urlencode($clfoxId));
    }
}