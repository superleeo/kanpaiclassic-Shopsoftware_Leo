<?php

class ManifestState
{

    /**
     * @var shipmentNumber $shipmentNumber
     * @access public
     */
    public $shipmentNumber = null;

    /**
     * @var Statusinformation $Status
     * @access public
     */
    public $Status = null;

    /**
     * @param shipmentNumber $shipmentNumber
     * @param Statusinformation $Status
     * @access public
     */
    public function __construct($shipmentNumber, $Status)
    {
      $this->shipmentNumber = $shipmentNumber;
      $this->Status = $Status;
    }

}
