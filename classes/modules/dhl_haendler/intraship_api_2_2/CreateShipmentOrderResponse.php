<?php

class CreateShipmentOrderResponse
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
     * @var CreationState $CreationState
     * @access public
     */
    public $CreationState = null;

    /**
     * @param Version $Version
     * @param Statusinformation $Status
     * @param CreationState $CreationState
     * @access public
     */
    public function __construct($Version, $Status, $CreationState)
    {
      $this->Version = $Version;
      $this->Status = $Status;
      $this->CreationState = $CreationState;
    }

}
