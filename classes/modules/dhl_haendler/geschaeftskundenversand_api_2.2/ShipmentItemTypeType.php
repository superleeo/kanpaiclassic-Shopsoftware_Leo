<?php

include_once('ShipmentItemType.php');

class ShipmentItemTypeType extends ShipmentItemType
{

    /**
     * @param weightInKG $weightInKG
     * @param lengthInCM $lengthInCM
     * @param widthInCM $widthInCM
     * @param heightInCM $heightInCM
     * @access public
     */
    public function __construct($weightInKG, $lengthInCM, $widthInCM, $heightInCM)
    {
      parent::__construct($weightInKG, $lengthInCM, $widthInCM, $heightInCM);
    }

}
