<?php

class ReceiverNativeAddressType
{

    /**
     * @var name2 $name2
     * @access public
     */
    public $name2 = null;

    /**
     * @var name3 $name3
     * @access public
     */
    public $name3 = null;

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
     * @param name2 $name2
     * @param name3 $name3
     * @param streetName $streetName
     * @param streetNumber $streetNumber
     * @param addressAddition $addressAddition
     * @param dispatchingInformation $dispatchingInformation
     * @param ZipType $zip
     * @param city $city
     * @param CountryType $Origin
     * @access public
     */
    public function __construct($name2, $name3, $streetName, $streetNumber, $addressAddition, $dispatchingInformation, $zip, $city, $Origin)
    {
      $this->name2 = $name2;
      $this->name3 = $name3;
      $this->streetName = $streetName;
      $this->streetNumber = $streetNumber;
      $this->addressAddition = $addressAddition;
      $this->dispatchingInformation = $dispatchingInformation;
      $this->zip = $zip;
      $this->city = $city;
      $this->Origin = $Origin;
    }

}
