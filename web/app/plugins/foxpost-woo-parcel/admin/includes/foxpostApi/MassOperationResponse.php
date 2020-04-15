<?php
/**
 * @author Foxpost-GZ
 */

namespace Foxpost\FoxpostApi;

/**
 * Class MassOperationResponse
 */
class MassOperationResponse {
    use ItemObjectTrait;

    /**
     * @var bool
     */
    protected $error = false;
    /**
     * @var string
     */
    protected $errorMessage = '';
    /**
     * @var array
     */
    protected $items = [];
    /**
     * @var array
     */
    protected $attachments = [];

    /**
     * @return bool
     */
    public function hasError()
    {
        return (bool) $this->error;
    }

    /**
     * @param ResponseItemAbstract|array $item
     */
    public function addItem($item)
    {
        if (\is_array($item)) {
            $responseItem = new \Foxpost\FoxpostApi\MassOperationResponseItem();
            $responseItem->setData($item);

            $this->items[] = $responseItem;
        } elseif ($item instanceof \Foxpost\FoxpostApi\ResponseItemAbstract) {
            $this->items[] = $item;
        }
    }

    /**
     * @param array|\Foxpost\FoxpostApi\AttachmentItem $attachment
     *
     * @since 1.1.0
     *
     * @throws \ReflectionException
     */
    public function addAttachment($attachment)
    {
        if (\is_array($attachment)) {
            $responseItem = new \Foxpost\FoxpostApi\AttachmentItem();
            $responseItem->setData($attachment);

            $this->attachments[] = $responseItem;
        } elseif ($attachment instanceof \Foxpost\FoxpostApi\AttachmentItem) {
            $this->addItem($attachment);
            $this->attachments[] = $attachment;
        }
    }

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
                if ('items' === $key) {
                    foreach ((array) $value as $valueItem) {
                        $this->addItem($valueItem);
                    }
                } elseif ('attachments' === $key) {
                    foreach ((array) $value as $valueItem) {
                        $this->addAttachment($valueItem);
                    }
                } else {
                    $this->{$key} = $value;
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return $this->error;
    }

    /**
     * @param bool $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return ResponseItemAbstract[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items)
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    /**
     * @since 1.1.0
     *
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }
}
