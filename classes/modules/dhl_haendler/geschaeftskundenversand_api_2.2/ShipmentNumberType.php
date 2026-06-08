<?php

class ShipmentNumberType
{

    /**
     * @var shipmentNumber $shipmentNumber
     * @access public
     */
    public $shipmentNumber = null;

    /**
     * @param shipmentNumber $shipmentNumber
     * @access public
     */
    public function __construct($shipmentNumber)
    {
      $this->shipmentNumber = $shipmentNumber;
    }

}
