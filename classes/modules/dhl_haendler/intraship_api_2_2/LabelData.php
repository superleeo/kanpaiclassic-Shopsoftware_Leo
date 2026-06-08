<?php

class LabelData
{

    /**
     * @var Statusinformation $Status
     * @access public
     */
    public $Status = null;

    /**
     * @var shipmentNumber $shipmentNumber
     * @access public
     */
    public $shipmentNumber = null;

    /**
     * @var string $labelUrl
     * @access public
     */
    public $labelUrl = null;

    /**
     * @var base64Binary $labelData
     * @access public
     */
    public $labelData = null;

    /**
     * @var string $returnLabelUrl
     * @access public
     */
    public $returnLabelUrl = null;

    /**
     * @var base64Binary $returnLabelData
     * @access public
     */
    public $returnLabelData = null;

    /**
     * @var string $exportLabelUrl
     * @access public
     */
    public $exportLabelUrl = null;

    /**
     * @var base64Binary $exportLabelData
     * @access public
     */
    public $exportLabelData = null;

    /**
     * @var string $codLabelUrl
     * @access public
     */
    public $codLabelUrl = null;

    /**
     * @var base64Binary $codLabelData
     * @access public
     */
    public $codLabelData = null;

    /**
     * @param Statusinformation $Status
     * @param shipmentNumber $shipmentNumber
     * @param string $labelUrl
     * @param base64Binary $labelData
     * @param string $returnLabelUrl
     * @param base64Binary $returnLabelData
     * @param string $exportLabelUrl
     * @param base64Binary $exportLabelData
     * @param string $codLabelUrl
     * @param base64Binary $codLabelData
     * @access public
     */
    public function __construct($Status, $shipmentNumber, $labelUrl, $labelData, $returnLabelUrl, $returnLabelData, $exportLabelUrl, $exportLabelData, $codLabelUrl, $codLabelData)
    {
      $this->Status = $Status;
      $this->shipmentNumber = $shipmentNumber;
      $this->labelUrl = $labelUrl;
      $this->labelData = $labelData;
      $this->returnLabelUrl = $returnLabelUrl;
      $this->returnLabelData = $returnLabelData;
      $this->exportLabelUrl = $exportLabelUrl;
      $this->exportLabelData = $exportLabelData;
      $this->codLabelUrl = $codLabelUrl;
      $this->codLabelData = $codLabelData;
    }

}
