<?php
/**
 * @author Foxpost-GZ
 */

namespace Foxpost\FoxpostApi;

/**
 * Class FoxpostHttpRequestCurl
 * @package Foxpost\FoxpostApi
 */
class FoxpostHttpRequestCurl implements FoxpostHttpRequestInterface {
    /**
     * @var array
     */
    protected $credentials = array();
    /**
     * @var array
     */
    protected $curlExtraOption = array();
    /**
     * @var string
     */
    protected $userAgent;

    /**
     * FoxpostHttpRequestCurl constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (!self::isCurlAvailable()) {
            throw new \Exception('To use this Request class require CURL extension.');
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
     * @{@inheritdoc}
     */
    public function sendPostRequest($apiUrl, $requestData, $authType)
    {
        $jsonData = json_encode($requestData);
        $portNumber = null;
        if (preg_match('#\:(\d+)#', $apiUrl, $match)) {
            $portNumber = $match[1];
            $apiUrl = str_replace($match[0], '', $apiUrl);
        }

        $curlHandler = curl_init($apiUrl);
        $this->setAuthenticationToHeader($curlHandler, $authType);

        if (null !== $portNumber) {
            curl_setopt($curlHandler, CURLOPT_PORT, $portNumber);
        }
        $options = array(
            CURLOPT_CUSTOMREQUEST => self::REQUEST_METHOD_POST,
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            )
        );

        if (!empty($this->curlExtraOption)) {
            $options = $options + $this->curlExtraOption;
        }
        curl_setopt_array($curlHandler, $options);

        $result = curl_exec($curlHandler);
        curl_close($curlHandler);

        return $result;
    }

    /**
     * @{@inheritdoc}
     */
    public function sendGetRequest($apiUrl, $requestData, $authType)
    {
        $getData = http_build_query($requestData);

        $curlHandler = curl_init($apiUrl . '?' . $getData);

        $this->setAuthenticationToHeader($curlHandler, $authType);

        curl_setopt($curlHandler, CURLOPT_CUSTOMREQUEST, self::REQUEST_METHOD_GET);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandler, CURLOPT_USERAGENT, $this->userAgent);

        $result = curl_exec($curlHandler);
        curl_close($curlHandler);

        return $result;

    }

    /**
     * @param array $options
     */
    public function setExtraOption(array $options)
    {
        if (isset($options['curl']) && is_array($options['curl'])) {
            foreach ($options['curl'] as $key => $value) {
                if (
                in_array($key, array(
                    CURLOPT_SSL_VERIFYHOST,
                    CURLOPT_SSL_VERIFYPEER
                ), true)) {
                    $this->curlExtraOption[$key] = $value;
                }
            }
        }
        if (isset($options['userAgent'])) {
            $this->userAgent = $options['userAgent'];
        }
    }

    /**
     * @param resource $curlHandler
     * @param string $authType
     */
    protected function setAuthenticationToHeader($curlHandler, $authType = 'basicAuth')
    {
        if ('basicAuth' === $authType) {
            if (isset($this->credentials[$authType]['password'], $this->credentials[$authType]['username'])) {
                curl_setopt($curlHandler, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($curlHandler, CURLOPT_USERPWD, implode(':',
                    [
                        $this->credentials[$authType]['username'],
                        $this->credentials[$authType]['password']
                    ]
                ));

            }
        }
    }

    /**
     * @return bool
     */
    public static function isCurlAvailable()
    {
        return function_exists('curl_init');
    }

    /**
     * Get user agent string.
     *
     * @return string
     */
    protected function getUserAgent()
    {
        global $woocommerce, $wp_version;

        $curlValues  = curl_version();
        $curlVersion = isset($curlValues['version']) ? $curlValues['version'] : 'unknown';

        return sprintf(
            'User-Agent: FoxpostPlugin, '
            . 'WP version:%s, Woocommerce version:%s, FP plugin version:%s, CURL(%s)',
            $wp_version,
            $woocommerce->version,
            FOXPOST_WOO_PARCEL_VERSION,
            $curlVersion
        );
    }
}