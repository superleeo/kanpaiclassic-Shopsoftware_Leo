<?php

class ShipmentItemType
{

    /**
     * @var weightInKG $weightInKG
     * @access public
     */
    public $weightInKG = null;

    /**
     * @var lengthInCM $lengthInCM
     * @access public
     */
    public $lengthInCM = null;

    /**
     * @var widthInCM $widthInCM
     * @access public
     */
    public $widthInCM = null;

    /**
     * @var heightInCM $heightInCM
     * @access public
     */
    public $heightInCM = null;

    /**
     * @param weightInKG $weightInKG
     * @param lengthInCM $lengthInCM
     * @param widthInCM $widthInCM
     * @param heightInCM $heightInCM
     * @access public
     */
    public function __construct($weightInKG, $lengthInCM, $widthInCM, $heightInCM)
    {
      $this->weightInKG = $weightInKG;
      $this->lengthInCM = $lengthInCM;
      $this->widthInCM = $widthInCM;
      $this->heightInCM = $heightInCM;
    }

}
