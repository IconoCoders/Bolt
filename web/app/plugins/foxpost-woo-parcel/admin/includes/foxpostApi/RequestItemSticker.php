<?php

namespace Foxpost\FoxpostApi;

/**
 * Class RequestItemSticker
 *
 * @since 1.1.0
 *
 * @package Foxpost\FoxpostApi
 */
class RequestItemSticker implements RequestItemInterface {
    use ItemObjectTrait;

    /**
     * @var string A rendeléshez tartozó CLFOXID
     */
    protected $clfoxId;
    /**
     * @var string A rendelés egyedi azonosítója
     */
    protected $api_external_id;
}