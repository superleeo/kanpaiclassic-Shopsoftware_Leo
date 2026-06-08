<?php

class ValidateShipmentOrderRequest
{

    /**
     * @var Version $Version
     * @access public
     */
    public $Version = null;

    /**
     * @var ValidateShipmentOrderType $ShipmentOrder
     * @access public
     */
    public $ShipmentOrder = null;

    /**
     * @param Version $Version
     * @param ValidateShipmentOrderType $ShipmentOrder
     * @access public
     */
    public function __construct($Version, $ShipmentOrder)
    {
      $this->Version = $Version;
      $this->ShipmentOrder = $ShipmentOrder;
    }

}
