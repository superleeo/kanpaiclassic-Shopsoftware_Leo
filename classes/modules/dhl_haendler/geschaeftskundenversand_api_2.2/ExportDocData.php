<?php

class ExportDocData
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
     * @var base64Binary $exportDocData
     * @access public
     */
    public $exportDocData = null;

    /**
     * @var string $exportDocURL
     * @access public
     */
    public $exportDocURL = null;

    /**
     * @param shipmentNumber $shipmentNumber
     * @param Statusinformation $Status
     * @param base64Binary $exportDocData
     * @param string $exportDocURL
     * @access public
     */
    public function __construct($shipmentNumber, $Status, $exportDocData, $exportDocURL)
    {
      $this->shipmentNumber = $shipmentNumber;
      $this->Status = $Status;
      $this->exportDocData = $exportDocData;
      $this->exportDocURL = $exportDocURL;
    }

}
