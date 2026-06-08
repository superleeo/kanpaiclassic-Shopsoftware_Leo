<?php

class Shipment
{

    /**
     * @var ShipmentDetailsTypeType $ShipmentDetails
     * @access public
     */
    public $ShipmentDetails = null;

    /**
     * @var ShipperType $Shipper
     * @access public
     */
    public $Shipper = null;

    /**
     * @var ReceiverType $Receiver
     * @access public
     */
    public $Receiver = null;

    /**
     * @var ShipperType $ReturnReceiver
     * @access public
     */
    public $ReturnReceiver = null;

    /**
     * @var ExportDocumentType $ExportDocument
     * @access public
     */
    public $ExportDocument = null;

    /**
     * @param ShipmentDetailsTypeType $ShipmentDetails
     * @param ShipperType $Shipper
     * @param ReceiverType $Receiver
     * @param ShipperType $ReturnReceiver
     * @param ExportDocumentType $ExportDocument
     * @access public
     */
    public function __construct($ShipmentDetails, $Shipper, $Receiver, $ReturnReceiver, $ExportDocument)
    {
      $this->ShipmentDetails = $ShipmentDetails;
      $this->Shipper = $Shipper;
      $this->Receiver = $Receiver;
      $this->ReturnReceiver = $ReturnReceiver;
      $this->ExportDocument = $ExportDocument;
    }

}
