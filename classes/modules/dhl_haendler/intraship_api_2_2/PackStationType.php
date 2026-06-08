<?php

class PackStationType
{

    /**
     * @var postNumber $postNumber
     * @access public
     */
    public $postNumber = null;

    /**
     * @var packstationNumber $packstationNumber
     * @access public
     */
    public $packstationNumber = null;

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
     * @param postNumber $postNumber
     * @param packstationNumber $packstationNumber
     * @param ZipType $zip
     * @param city $city
     * @param CountryType $Origin
     * @access public
     */
    public function __construct($postNumber, $packstationNumber, $zip, $city, $Origin)
    {
      $this->postNumber = $postNumber;
      $this->packstationNumber = $packstationNumber;
      $this->zip = $zip;
      $this->city = $city;
      $this->Origin = $Origin;
    }

}
