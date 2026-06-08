<?php

class ValidateShipmentResponse
{

    /**
     * @var Version $Version
     * @access public
     */
    public $Version = null;

    /**
     * @var Statusinformation $Status
     * @access public
     */
    public $Status = null;

    /**
     * @var ValidationState $ValidationState
     * @access public
     */
    public $ValidationState = null;

    /**
     * @param Version $Version
     * @param Statusinformation $Status
     * @param ValidationState $ValidationState
     * @access public
     */
    public function __construct($Version, $Status, $ValidationState)
    {
      $this->Version = $Version;
      $this->Status = $Status;
      $this->ValidationState = $ValidationState;
    }

}
