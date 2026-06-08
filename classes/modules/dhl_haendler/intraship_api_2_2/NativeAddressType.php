<?php

class NativeAddressType
{

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
     * @var addressAddition $addressAddition
     * @access public
     */
    public $addressAddition = null;

    /**
     * @var dispatchingInformation $dispatchingInformation
     * @access public
     */
    public $dispatchingInformation = null;

    /**
     * @var ZipType $zip
     * @access public
     */
    public $zip = null;

    /**
     * @var city $city
     * @access public
     */
    public $city = null;

    /**
     * @var CountryType $Origin
     * @access public
     */
    public $Origin = null;

    /**
     * @param streetName $streetName
     * @param streetNumber $streetNumber
     * @param addressAddition $addressAddition
     * @param dispatchingInformation $dispatchingInformation
     * @param ZipType $zip
     * @param city $city
     * @param CountryType $Origin
     * @access public
     */
    public function __construct($streetName, $streetNumber, $addressAddition, $dispatchingInformation, $zip, $city, $Origin)
    {
      $this->streetName = $streetName;
      $this->streetNumber = $streetNumber;
      $this->addressAddition = $addressAddition;
      $this->dispatchingInformation = $dispatchingInformation;
      $this->zip = $zip;
      $this->city = $city;
      $this->Origin = $Origin;
    }

}
