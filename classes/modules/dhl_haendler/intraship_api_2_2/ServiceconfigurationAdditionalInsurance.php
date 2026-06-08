<?php

class ServiceconfigurationAdditionalInsurance
{

    /**
     * @var anonymous151 $active
     * @access public
     */
    public $active = null;

    /**
     * @var anonymous152 $insuranceAmount
     * @access public
     */
    public $insuranceAmount = null;

    /**
     * @param anonymous151 $active
     * @param anonymous152 $insuranceAmount
     * @access public
     */
    public function __construct($active, $insuranceAmount)
    {
      $this->active = $active;
      $this->insuranceAmount = $insuranceAmount;
    }

}
