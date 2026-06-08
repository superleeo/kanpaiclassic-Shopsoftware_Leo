<?php

class ExportDocumentType
{

    /**
     * @var invoiceNumber $invoiceNumber
     * @access public
     */
    public $invoiceNumber = null;

    /**
     * @var exportType $exportType
     * @access public
     */
    public $exportType = null;

    /**
     * @var exportTypeDescription $exportTypeDescription
     * @access public
     */
    public $exportTypeDescription = null;

    /**
     * @var termsOfTrade $termsOfTrade
     * @access public
     */
    public $termsOfTrade = null;

    /**
     * @var placeOfCommital $placeOfCommital
     * @access public
     */
    public $placeOfCommital = null;

    /**
     * @var additionalFee $additionalFee
     * @access public
     */
    public $additionalFee = null;

    /**
     * @var permitNumber $permitNumber
     * @access public
     */
    public $permitNumber = null;

    /**
     * @var attestationNumber $attestationNumber
     * @access public
     */
    public $attestationNumber = null;

    /**
     * @var Serviceconfiguration $WithElectronicExportNtfctn
     * @access public
     */
    public $WithElectronicExportNtfctn = null;

    /**
     * @var ExportDocPosition $ExportDocPosition
     * @access public
     */
    public $ExportDocPosition = null;

    /**
     * @param invoiceNumber $invoiceNumber
     * @param exportType $exportType
     * @param exportTypeDescription $exportTypeDescription
     * @param termsOfTrade $termsOfTrade
     * @param placeOfCommital $placeOfCommital
     * @param additionalFee $additionalFee
     * @param permitNumber $permitNumber
     * @param attestationNumber $attestationNumber
     * @param Serviceconfiguration $WithElectronicExportNtfctn
     * @param ExportDocPosition $ExportDocPosition
     * @access public
     */
    public function __construct($invoiceNumber, $exportType, $exportTypeDescription, $termsOfTrade, $placeOfCommital, $additionalFee, $permitNumber, $attestationNumber, $WithElectronicExportNtfctn, $ExportDocPosition)
    {
      $this->invoiceNumber = $invoiceNumber;
      $this->exportType = $exportType;
      $this->exportTypeDescription = $exportTypeDescription;
      $this->termsOfTrade = $termsOfTrade;
      $this->placeOfCommital = $placeOfCommital;
      $this->additionalFee = $additionalFee;
      $this->permitNumber = $permitNumber;
      $this->attestationNumber = $attestationNumber;
      $this->WithElectronicExportNtfctn = $WithElectronicExportNtfctn;
      $this->ExportDocPosition = $ExportDocPosition;
    }

}
