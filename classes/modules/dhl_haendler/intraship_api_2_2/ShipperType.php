<?php

include_once('ShipperTypeType.php');

class ShipperType extends ShipperTypeType
{

    /**
     * @param NameType $Name
     * @param NativeAddressType $Address
     * @param CommunicationType $Communication
     * @access public
     */
    public function __construct($Name, $Address, $Communication)
    {
      parent::__construct($Name, $Address, $Communication);
    }

}
