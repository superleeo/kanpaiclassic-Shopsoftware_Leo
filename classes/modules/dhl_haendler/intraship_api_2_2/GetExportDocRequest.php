<?php

class GetExportDocRequest
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
     * @var exportDocResponseType $exportDocResponseType
     * @access public
     */
    public $exportDocResponseType = null;

    /**
     * @param Version $Version
     * @param shipmentNumber $shipmentNumber
     * @param exportDocResponseType $exportDocResponseType
     * @access public
     */
    public function __construct($Version, $shipmentNumber, $exportDocResponseType)
    {
      $this->Version = $Version;
      $this->shipmentNumber = $shipmentNumber;
      $this->exportDocResponseType = $exportDocResponseType;
    }

}
