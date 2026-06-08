<?php

include_once('ShipmentDetailsType.php');

class ShipmentDetailsTypeType extends ShipmentDetailsType
{

    /**
     * @var ShipmentItemType $ShipmentItem
     * @access public
     */
    public $ShipmentItem = null;

    /**
     * @var ShipmentService $Service
     * @access public
     */
    public $Service = null;

    /**
     * @var ShipmentNotificationType $Notification
     * @access public
     */
    public $Notification = null;

    /**
     * @var BankType $BankData
     * @access public
     */
    public $BankData = null;

    /**
     * @param string $product
     * @param accountNumber $accountNumber
     * @param customerReference $customerReference
     * @param shipmentDate $shipmentDate
     * @param returnShipmentAccountNumber $returnShipmentAccountNumber
     * @param returnShipmentReference $returnShipmentReference
     * @param ShipmentItemType $ShipmentItem
     * @param ShipmentService $Service
     * @param ShipmentNotificationType $Notification
     * @param BankType $BankData
     * @access public
     */
    public function __construct($product, $accountNumber, $customerReference, $shipmentDate, $returnShipmentAccountNumber, $returnShipmentReference, $ShipmentItem, $Service, $Notification, $BankData)
    {
      parent::__construct($product, $accountNumber, $customerReference, $shipmentDate, $returnShipmentAccountNumber, $returnShipmentReference);
      $this->ShipmentItem = $ShipmentItem;
      $this->Service = $Service;
      $this->Notification = $Notification;
      $this->BankData = $BankData;
    }

}
