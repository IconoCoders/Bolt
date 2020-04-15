<?php
/**
 * @author Foxpost-GZ
 */

namespace Foxpost\FoxpostApi;

/**
 * Class RequestItemAbstract
 */
abstract class RequestItemAbstract implements RequestItemInterface {
    use ItemObjectTrait;

    const PARCEL_TYPE_APT = 'apt';
    const PARCEL_TYPE_HD = 'home_delivery';

    /**
     * @return string
     */
    abstract public function getParcelType();
}