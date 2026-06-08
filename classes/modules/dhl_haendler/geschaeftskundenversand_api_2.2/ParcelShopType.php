<?php

class ParcelShopType
{

    /**
     * @var string $ParcelShopNumber
     * @access public
     */
    public $ParcelShopNumber = null;

    /**
     * @var streetName $streetName
     * @access public
     */
    public $streetName = null;

    /**
     * @var streetNumber $streetNumber
     * @access public
     */
    public $streetNumber = null;

    /**
     * @var Zip $Zip
     * @access public
     */
    public $Zip = null;

    /**
     * @var City $City
     * @access public
     */
    public $City = null;

    /**
     * @param string $ParcelShopNumber
     * @param streetName $streetName
     * @param streetNumber $streetNumber
     * @param Zip $Zip
     * @param City $City
     * @access public
     */
    public function __construct($ParcelShopNumber, $streetName, $streetNumber, $Zip, $City)
    {
      $this->ParcelShopNumber = $ParcelShopNumber;
      $this->streetName = $streetName;
      $this->streetNumber = $streetNumber;
      $this->Zip = $Zip;
      $this->City = $City;
    }

}
