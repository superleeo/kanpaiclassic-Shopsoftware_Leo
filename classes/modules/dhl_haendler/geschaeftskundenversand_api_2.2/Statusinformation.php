<?php

class Statusinformation
{

    /**
     * @var int $statusCode
     * @access public
     */
    public $statusCode = null;

    /**
     * @var string $statusText
     * @access public
     */
    public $statusText = null;

    /**
     * @var string $statusMessage
     * @access public
     */
    public $statusMessage = null;

    /**
     * @param int $statusCode
     * @param string $statusText
     * @param string $statusMessage
     * @access public
     */
    public function __construct($statusCode, $statusText, $statusMessage)
    {
      $this->statusCode = $statusCode;
      $this->statusText = $statusText;
      $this->statusMessage = $statusMessage;
    }

}
