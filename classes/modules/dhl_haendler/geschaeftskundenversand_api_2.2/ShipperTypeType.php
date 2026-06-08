<?php

class ShipperTypeType
{

    /**
     * @var NameType $Name
     * @access public
     */
    public $Name = null;

    /**
     * @var NativeAddressType $Address
     * @access public
     */
    public $Address = null;

    /**
     * @var CommunicationType $Communication
     * @access public
     */
    public $Communication = null;

    /**
     * @param NameType $Name
     * @param NativeAddressType $Address
     * @param CommunicationType $Communication
     * @access public
     */
    public function __construct($Name, $Address, $Communication)
    {
      $this->Name = $Name;
      $this->Address = $Address;
      $this->Communication = $Communication;
    }

}
