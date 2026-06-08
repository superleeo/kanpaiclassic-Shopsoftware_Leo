<?php

class CreateShipmentOrderRequest
{

    /**
     * @var Version $Version
     * @access public
     */
    public $Version = null;

    /**
     * @var ShipmentOrderType $ShipmentOrder
     * @access public
     */
    public $ShipmentOrder = null;

    /**
     * @param Version $Version
     * @param ShipmentOrderType $ShipmentOrder
     * @access public
     */
    public function __construct($Version, $ShipmentOrder)
    {
      $this->Version = $Version;
      $this->ShipmentOrder = $ShipmentOrder;
    }

}
