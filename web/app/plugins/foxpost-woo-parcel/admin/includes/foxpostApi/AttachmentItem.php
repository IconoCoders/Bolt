<?php

/**
 * @author Foxpost-GZ
 */

namespace Foxpost\FoxpostApi;

/**
 * Class AttachmentItem
 * @package api\components\apiResponse
 */
class AttachmentItem {

    use ItemObjectTrait;

    /**
     * @var string Mime-type
     */
    protected $mimeType;
    /**
     * @var string Content
     */
    protected $content;

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     *
     * @return AttachmentItem
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @param bool $binary
     *
     * @return string
     */
    public function getContent($binary = false)
    {
        if ($binary) {
            return base64_decode($this->content);
        }

        return $this->content;
    }

    /**
     * @param mixed $content
     *
     * @return AttachmentItem
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return array
     */
    public function getAsArray()
    {
        return ['mimeType' => $this->getMimeType(), '' => $this->getContent()];
    }
}