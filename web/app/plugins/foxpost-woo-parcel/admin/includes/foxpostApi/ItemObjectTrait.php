<?php
/**
 * @author Foxpost-GZ
 */

namespace Foxpost\FoxpostApi;

/**
 * Trait ItemObjectTrait
 *
 * @since 1.1.0
 *
 * @package Foxpost\FoxpostApi
 */
trait ItemObjectTrait {
    /**
     * Set all property data.
     *
     * @param array $data key->value array
     *
     * @return void
     * @throws \ReflectionException
     */
    public function setData(array $data)
    {
        $properties = $this->getProperties();
        foreach ($data as $key => $value) {
            if (\in_array($key, $properties, true)) {
                $method = 'set' . ucfirst($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                } else {
                    $this->{$key} = $value;
                }
            }
        }
    }

    /**
     * Get all property data.
     *
     * @return array
     * @throws \ReflectionException
     */
    public function getData()
    {
        $data = array();
        foreach ($this->getProperties() as $property) {
            $data[$property] = $this->{$property};
        }

        return $data;
    }

    /**
     * Get all protected properties.
     *
     * @return array
     * @throws \ReflectionException
     */
    public function getProperties()
    {
        $class = new \ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PROTECTED) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }

        return $names;
    }
}