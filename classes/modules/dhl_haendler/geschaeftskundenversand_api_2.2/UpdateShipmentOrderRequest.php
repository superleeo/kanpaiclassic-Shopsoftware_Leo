<?php

class UpdateShipmentOrderRequest
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
     * @var ShipmentOrderType $ShipmentOrder
     * @access public
     */
    public $ShipmentOrder = null;

    /**
     * @param Version $Version
     * @param shipmentNumber $shipmentNumber
     * @param ShipmentOrderType $ShipmentOrder
     * @access public
     */
    public function __construct($Version, $shipmentNumber, $ShipmentOrder)
    {
      $this->Version = $Version;
      $this->shipmentNumber = $shipmentNumber;
      $this->ShipmentOrder = $ShipmentOrder;
    }

}
