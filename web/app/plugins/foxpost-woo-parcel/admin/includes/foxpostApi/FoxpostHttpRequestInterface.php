<?php
/**
 * @author Foxpost-GZ
 */

namespace Foxpost\FoxpostApi;

/**
 * Interface FoxpostHttpRequestInterface
 * @package Foxpost\FoxpostApi
 */
interface FoxpostHttpRequestInterface {

    const REQUEST_METHOD_POST = 'POST';
    const REQUEST_METHOD_GET = 'GET';
    const REQUEST_METHOD_DELETE = 'DELETE';
    const REQUEST_METHOD_PUT = 'PUT';

    public function setCredentials($credentials);

    public function sendPostRequest($apiUrl, $requestData, $authType);

    public function sendGetRequest($apiUrl, $requestData, $authType);

    public function setExtraOption(array $options);
}