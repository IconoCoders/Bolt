<?php
/**
 * @author Foxpost-GZ
 */

namespace Foxpost\FoxpostApi;

/**
 * Class MassOperationResponseItem
 */
class MassOperationResponseItem extends ResponseItemAbstract {
    /**
     * @var string
     */
    protected $originalId;
    /**
     * @var array
     */
    protected $validationErrors = [];
    /**
     * @var string
     */
    protected $clfoxId;

    /**
     * @return string
     */
    public function getOriginalId()
    {
        return $this->originalId;
    }

    /**
     * @param string $originalId
     */
    public function setOriginalId($originalId)
    {
        $this->originalId = $originalId;
    }

    /**
     * @return array
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    /**
     * @param array $validationErrors
     */
    public function setValidationErrors($validationErrors)
    {
        $this->validationErrors = $validationErrors;
    }

    /**
     * @return string
     */
    public function getClfoxId()
    {
        return $this->clfoxId;
    }

    /**
     * @param string $clfoxId
     */
    public function setClfoxId($clfoxId)
    {
        $this->clfoxId = $clfoxId;
    }


}
