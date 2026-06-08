<?php

class ExportDocPosition
{

    /**
     * @var description $description
     * @access public
     */
    public $description = null;

    /**
     * @var countryCodeOrigin $countryCodeOrigin
     * @access public
     */
    public $countryCodeOrigin = null;

    /**
     * @var customsTariffNumber $customsTariffNumber
     * @access public
     */
    public $customsTariffNumber = null;

    /**
     * @var amount $amount
     * @access public
     */
    public $amount = null;

    /**
     * @var netWeightInKG $netWeightInKG
     * @access public
     */
    public $netWeightInKG = null;

    /**
     * @var customsValue $customsValue
     * @access public
     */
    public $customsValue = null;

    /**
     * @param description $description
     * @param countryCodeOrigin $countryCodeOrigin
     * @param customsTariffNumber $customsTariffNumber
     * @param amount $amount
     * @param netWeightInKG $netWeightInKG
     * @param customsValue $customsValue
     * @access public
     */
    public function __construct($description, $countryCodeOrigin, $customsTariffNumber, $amount, $netWeightInKG, $customsValue)
    {
      $this->description = $description;
      $this->countryCodeOrigin = $countryCodeOrigin;
      $this->customsTariffNumber = $customsTariffNumber;
      $this->amount = $amount;
      $this->netWeightInKG = $netWeightInKG;
      $this->customsValue = $customsValue;
    }

}
