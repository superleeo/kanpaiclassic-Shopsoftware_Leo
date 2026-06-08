<?php

class ShipmentOrderType
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
     * @var labelResponseType $labelResponseType
     * @access public
     */
    public $labelResponseType = null;

    /**
     * @param SequenceNumber $sequenceNumber
     * @param Shipment $Shipment
     * @param Serviceconfiguration $PrintOnlyIfCodeable
     * @param labelResponseType $labelResponseType
     * @access public
     */
    public function __construct($sequenceNumber, $Shipment, $PrintOnlyIfCodeable, $labelResponseType)
    {
      $this->sequenceNumber = $sequenceNumber;
      $this->Shipment = $Shipment;
      $this->PrintOnlyIfCodeable = $PrintOnlyIfCodeable;
      $this->labelResponseType = $labelResponseType;
    }

}
