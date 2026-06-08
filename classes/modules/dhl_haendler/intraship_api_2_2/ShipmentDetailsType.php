<?php

class ShipmentDetailsType
{

    /**
     * @var string $product
     * @access public
     */
    public $product = null;

    /**
     * @var accountNumber $accountNumber
     * @access public
     */
    public $accountNumber = null;

    /**
     * @var customerReference $customerReference
     * @access public
     */
    public $customerReference = null;

    /**
     * @var shipmentDate $shipmentDate
     * @access public
     */
    public $shipmentDate = null;

    /**
     * @var returnShipmentAccountNumber $returnShipmentAccountNumber
     * @access public
     */
    public $returnShipmentAccountNumber = null;

    /**
     * @var returnShipmentReference $returnShipmentReference
     * @access public
     */
    public $returnShipmentReference = null;

    /**
     * @param string $product
     * @param accountNumber $accountNumber
     * @param customerReference $customerReference
     * @param shipmentDate $shipmentDate
     * @param returnShipmentAccountNumber $returnShipmentAccountNumber
     * @param returnShipmentReference $returnShipmentReference
     * @access public
     */
    public function __construct($product, $accountNumber, $customerReference, $shipmentDate, $returnShipmentAccountNumber, $returnShipmentReference)
    {
      $this->product = $product;
      $this->accountNumber = $accountNumber;
      $this->customerReference = $customerReference;
      $this->shipmentDate = $shipmentDate;
      $this->returnShipmentAccountNumber = $returnShipmentAccountNumber;
      $this->returnShipmentReference = $returnShipmentReference;
    }

}
