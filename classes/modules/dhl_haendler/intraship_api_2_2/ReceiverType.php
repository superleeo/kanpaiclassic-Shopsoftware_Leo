<?php

include_once('ReceiverTypeType.php');

class ReceiverType extends ReceiverTypeType
{

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
      parent::__construct($name1, $Address, $Packstation, $Postfiliale, $ParcelShop, $Communication);
    }

}
