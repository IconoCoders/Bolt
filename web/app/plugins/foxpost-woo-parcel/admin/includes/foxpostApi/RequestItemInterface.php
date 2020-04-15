<?php
/**
 * @author Foxpost-GZ
 */

namespace Foxpost\FoxpostApi;

/**
 * Interface RequestItemInterface
 */
interface RequestItemInterface {
    /**
     * Set all property data.
     *
     * @param array $data key->value array
     *
     * @return void
     * @throws \ReflectionException
     */
    public function setData(array $data);

    /**
     * Get all property data.
     *
     * @return array
     * @throws \ReflectionException
     */
    public function getData();
}