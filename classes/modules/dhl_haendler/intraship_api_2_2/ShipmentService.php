<?php

class ShipmentService
{

    /**
     * @var ServiceconfigurationDateOfDelivery $DayOfDelivery
     * @access public
     */
    public $DayOfDelivery = null;

    /**
     * @var ServiceconfigurationDeliveryTimeframe $DeliveryTimeframe
     * @access public
     */
    public $DeliveryTimeframe = null;

    /**
     * @var ServiceconfigurationDeliveryTimeframe $PreferredTime
     * @access public
     */
    public $PreferredTime = null;

    /**
     * @var ServiceconfigurationISR $IndividualSenderRequirement
     * @access public
     */
    public $IndividualSenderRequirement = null;

    /**
     * @var Serviceconfiguration $PackagingReturn
     * @access public
     */
    public $PackagingReturn = null;

    /**
     * @var Serviceconfiguration $ReturnImmediately
     * @access public
     */
    public $ReturnImmediately = null;

    /**
     * @var Serviceconfiguration $NoticeOfNonDeliverability
     * @access public
     */
    public $NoticeOfNonDeliverability = null;

    /**
     * @var ServiceconfigurationShipmentHandling $ShipmentHandling
     * @access public
     */
    public $ShipmentHandling = null;

    /**
     * @var ServiceconfigurationEndorsement $Endorsement
     * @access public
     */
    public $Endorsement = null;

    /**
     * @var ServiceconfigurationVisualAgeCheck $VisualCheckOfAge
     * @access public
     */
    public $VisualCheckOfAge = null;

    /**
     * @var ServiceconfigurationDetails $PreferredLocation
     * @access public
     */
    public $PreferredLocation = null;

    /**
     * @var ServiceconfigurationDetails $PreferredNeighbour
     * @access public
     */
    public $PreferredNeighbour = null;

    /**
     * @var ServiceconfigurationDetails $PreferredDay
     * @access public
     */
    public $PreferredDay = null;

    /**
     * @var Serviceconfiguration $GoGreen
     * @access public
     */
    public $GoGreen = null;

    /**
     * @var Serviceconfiguration $Perishables
     * @access public
     */
    public $Perishables = null;

    /**
     * @var Serviceconfiguration $Personally
     * @access public
     */
    public $Personally = null;

    /**
     * @var Serviceconfiguration $NoNeighbourDelivery
     * @access public
     */
    public $NoNeighbourDelivery = null;

    /**
     * @var Serviceconfiguration $NamedPersonOnly
     * @access public
     */
    public $NamedPersonOnly = null;

    /**
     * @var Serviceconfiguration $ReturnReceipt
     * @access public
     */
    public $ReturnReceipt = null;

    /**
     * @var Serviceconfiguration $Premium
     * @access public
     */
    public $Premium = null;

    /**
     * @var ServiceconfigurationCashOnDelivery $CashOnDelivery
     * @access public
     */
    public $CashOnDelivery = null;

    /**
     * @var ServiceconfigurationAdditionalInsurance $AdditionalInsurance
     * @access public
     */
    public $AdditionalInsurance = null;

    /**
     * @var Serviceconfiguration $BulkyGoods
     * @access public
     */
    public $BulkyGoods = null;

    /**
     * @var ServiceconfigurationIC $IdentCheck
     * @access public
     */
    public $IdentCheck = null;

    /**
     * @param ServiceconfigurationDateOfDelivery $DayOfDelivery
     * @param ServiceconfigurationDeliveryTimeframe $DeliveryTimeframe
     * @param ServiceconfigurationDeliveryTimeframe $PreferredTime
     * @param ServiceconfigurationISR $IndividualSenderRequirement
     * @param Serviceconfiguration $PackagingReturn
     * @param Serviceconfiguration $ReturnImmediately
     * @param Serviceconfiguration $NoticeOfNonDeliverability
     * @param ServiceconfigurationShipmentHandling $ShipmentHandling
     * @param ServiceconfigurationEndorsement $Endorsement
     * @param ServiceconfigurationVisualAgeCheck $VisualCheckOfAge
     * @param ServiceconfigurationDetails $PreferredLocation
     * @param ServiceconfigurationDetails $PreferredNeighbour
     * @param ServiceconfigurationDetails $PreferredDay
     * @param Serviceconfiguration $GoGreen
     * @param Serviceconfiguration $Perishables
     * @param Serviceconfiguration $Personally
     * @param Serviceconfiguration $NoNeighbourDelivery
     * @param Serviceconfiguration $NamedPersonOnly
     * @param Serviceconfiguration $ReturnReceipt
     * @param Serviceconfiguration $Premium
     * @param ServiceconfigurationCashOnDelivery $CashOnDelivery
     * @param ServiceconfigurationAdditionalInsurance $AdditionalInsurance
     * @param Serviceconfiguration $BulkyGoods
     * @param ServiceconfigurationIC $IdentCheck
     * @access public
     */
    public function __construct($DayOfDelivery, $DeliveryTimeframe, $PreferredTime, $IndividualSenderRequirement, $PackagingReturn, $ReturnImmediately, $NoticeOfNonDeliverability, $ShipmentHandling, $Endorsement, $VisualCheckOfAge, $PreferredLocation, $PreferredNeighbour, $PreferredDay, $GoGreen, $Perishables, $Personally, $NoNeighbourDelivery, $NamedPersonOnly, $ReturnReceipt, $Premium, $CashOnDelivery, $AdditionalInsurance, $BulkyGoods, $IdentCheck)
    {
      $this->DayOfDelivery = $DayOfDelivery;
      $this->DeliveryTimeframe = $DeliveryTimeframe;
      $this->PreferredTime = $PreferredTime;
      $this->IndividualSenderRequirement = $IndividualSenderRequirement;
      $this->PackagingReturn = $PackagingReturn;
      $this->ReturnImmediately = $ReturnImmediately;
      $this->NoticeOfNonDeliverability = $NoticeOfNonDeliverability;
      $this->ShipmentHandling = $ShipmentHandling;
      $this->Endorsement = $Endorsement;
      $this->VisualCheckOfAge = $VisualCheckOfAge;
      $this->PreferredLocation = $PreferredLocation;
      $this->PreferredNeighbour = $PreferredNeighbour;
      $this->PreferredDay = $PreferredDay;
      $this->GoGreen = $GoGreen;
      $this->Perishables = $Perishables;
      $this->Personally = $Personally;
      $this->NoNeighbourDelivery = $NoNeighbourDelivery;
      $this->NamedPersonOnly = $NamedPersonOnly;
      $this->ReturnReceipt = $ReturnReceipt;
      $this->Premium = $Premium;
      $this->CashOnDelivery = $CashOnDelivery;
      $this->AdditionalInsurance = $AdditionalInsurance;
      $this->BulkyGoods = $BulkyGoods;
      $this->IdentCheck = $IdentCheck;
    }

}
