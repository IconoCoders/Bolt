<?php
/**
 * @author Foxpost-GZ
 */

namespace Foxpost\FoxpostApi;
/**
 * Class FoxpostHttpRequest
 * @package Foxpost\FoxpostApi
 */
class FoxpostHttpRequest implements FoxpostHttpRequestInterface {
    /**
     * @var string
     */
    protected $userAgent;

    /**
     * @var array
     */
    protected $credentials = array();

    /**
     * FoxpostHttpRequest constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (!self::isRemoteFileCallEnabled()) {
            throw new \Exception('To use this Request class please enable allow_url_fopen in the php ini.');
        }

        $this->userAgent = $this->getUserAgent();
    }

    /**
     * @param array $credentials
     */
    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @param string $apiUrl
     * @param array $requestData
     * @param string $authType
     *
     * @return bool|string
     */
    public function sendPostRequest($apiUrl, $requestData, $authType)
    {
        $jsonData = json_encode($requestData);

        $options = array(
            'http' =>
                array(
                    'method' => self::REQUEST_METHOD_POST,
                    'header' => "Content-type: application/json\r\n" .
                                "Accept: application/json\r\n" .
                                "Connection: close\r\n" .
                                "Content-length: " . strlen($jsonData) . "\r\n" .
                                "{$this->userAgent}\r\n",
                    'protocol_version' => 1.1,
                    'content' => $jsonData,
                )
        );
        $options = $this->setAuthenticationToHeader($options, $authType);
        $context = stream_context_create($options);

        $response = file_get_contents($apiUrl, false, $context);
        if (false === $response) {
            $response = isset($http_response_header) ? $http_response_header : false;
        }

        return $response;
    }

    /**
     * @param string $apiUrl
     * @param array $requestData
     * @param string $authType
     *
     * @return bool|string
     */
    public function sendGetRequest($apiUrl, $requestData, $authType)
    {
        $getData = http_build_query($requestData);
        $options = array(
            'http' =>
                array(
                    'method' => self::REQUEST_METHOD_GET,
                    'header' => "Content-type: application/json\r\n" .
                                "Accept: application/json\r\n" .
                                "Connection: close\r\n" .
                                "{$this->userAgent}\r\n",
                    'protocol_version' => 1.1,
                )
        );

        $options = $this->setAuthenticationToHeader($options, $authType);

        $context = stream_context_create($options);

        $response = file_get_contents($apiUrl . '?' . $getData, false, $context);
        if (false === $response) {
            $response = isset($http_response_header) ? $http_response_header : false;
        }

        return $response;
    }

    /**
     * @param array $options
     */
    public function setExtraOption(array $options)
    {
        if (isset($options['userAgent'])) {
            $this->userAgent = $options['userAgent'];
        }
    }

    /**
     * @param array $options
     * @param string $authType
     *
     * @return mixed
     */
    protected function setAuthenticationToHeader($options, $authType = 'basicAuth')
    {
        if ('basicAuth' === $authType) {
            if (isset($this->credentials[$authType]['password'], $this->credentials[$authType]['username'])) {
                $options['http']['header'] .=
                    'Authorization: Basic '
                    . base64_encode(
                        implode(':',
                            [
                                $this->credentials[$authType]['username'],
                                $this->credentials[$authType]['password']
                            ]
                        )
                    ) . "\r\n";
            }
        }

        return $options;
    }

    /**
     * @return bool
     */
    public static function isRemoteFileCallEnabled()
    {
        return (bool) ini_get('allow_url_fopen');
    }

    /**
     * Get user agent string.
     *
     * @return string
     */
    protected function getUserAgent()
    {
        global $woocommerce, $wp_version;

        return sprintf(
            'User-Agent: FoxpostPlugin, '
            . 'WP version:%s, Woocommerce version:%s, FP plugin version:%s, HTTP',
            $wp_version,
            $woocommerce->version,
            FOXPOST_WOO_PARCEL_VERSION
        );
    }
}