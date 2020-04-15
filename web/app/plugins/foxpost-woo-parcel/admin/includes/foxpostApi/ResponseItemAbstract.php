<?php
/**
 * @author Foxpost-GZ
 */

namespace Foxpost\FoxpostApi;


/**
 * Class ResponseItemAbstract
 */
abstract class ResponseItemAbstract {
    use ItemObjectTrait;

    /**
     * @var bool
     */
    protected $error = false;
    /**
     * @var string
     */
    protected $message = '';

    /**
     * @return bool
     */
    public function hasError()
    {
        return (bool) $this->error;
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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

}
