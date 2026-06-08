<?php

class ValidateShipmentOrderType
{

    /**
     * @var SequenceNumber $sequenceNumber
     * @access public
     */
    public $sequenceNumber = null;

    /**
     * @var Shipment $Shipment
     * @access public
     */
    public $Shipment = null;

    /**
     * @var Serviceconfiguration $PrintOnlyIfCodeable
     * @access public
     */
    public $PrintOnlyIfCodeable = null;

    /**
     * @param SequenceNumber $sequenceNumber
     * @param Shipment $Shipment
     * @param Serviceconfiguration $PrintOnlyIfCodeable
     * @access public
     */
    public function __construct($sequenceNumber, $Shipment, $PrintOnlyIfCodeable)
    {
      $this->sequenceNumber = $sequenceNumber;
      $this->Shipment = $Shipment;
      $this->PrintOnlyIfCodeable = $PrintOnlyIfCodeable;
    }

}
