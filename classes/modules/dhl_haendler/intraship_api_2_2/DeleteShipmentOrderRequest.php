<?php

class DeleteShipmentOrderRequest
{

    /**
     * @var Version $Version
     * @access public
     */
    public $Version = null;

    /**
     * @var shipmentNumber $shipmentNumber
     * @access public
     */
    public $shipmentNumber = null;

    /**
     * @param Version $Version
     * @param shipmentNumber $shipmentNumber
     * @access public
     */
    public function __construct($Version, $shipmentNumber)
    {
      $this->Version = $Version;
      $this->shipmentNumber = $shipmentNumber;
    }

}
