<?php

class ReceiverTypeType
{

    /**
     * @var name1 $name1
     * @access public
     */
    public $name1 = null;

    /**
     * @var ReceiverNativeAddressType $Address
     * @access public
     */
    public $Address = null;

    /**
     * @var PackStationType $Packstation
     * @access public
     */
    public $Packstation = null;

    /**
     * @var PostfilialeType $Postfiliale
     * @access public
     */
    public $Postfiliale = null;

    /**
     * @var ParcelShopType $ParcelShop
     * @access public
     */
    public $ParcelShop = null;

    /**
     * @var CommunicationType $Communication
     * @access public
     */
    public $Communication = null;

    /**
     * @param name1 $name1
     * @param ReceiverNativeAddressType $Address
     * @param PackStationType $Packstation
     * @param PostfilialeType $Postfiliale
     * @param ParcelShopType $ParcelShop
     * @param CommunicationType $Communication
     * @access public
     */
    public function __construct($name1, $Address, $Packstation, $Postfiliale, $ParcelShop, $Communication)
    {
      $this->name1 = $name1;
      $this->Address = $Address;
      $this->Packstation = $Packstation;
      $this->Postfiliale = $Postfiliale;
      $this->ParcelShop = $ParcelShop;
      $this->Communication = $Communication;
    }

}
