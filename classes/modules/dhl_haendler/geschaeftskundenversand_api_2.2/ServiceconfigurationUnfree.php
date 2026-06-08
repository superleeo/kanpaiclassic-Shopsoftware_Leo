<?php

class ServiceconfigurationUnfree
{

    /**
     * @var anonymous161 $active
     * @access public
     */
    public $active = null;

    /**
     * @var anonymous162 $PaymentType
     * @access public
     */
    public $PaymentType = null;

    /**
     * @var anonymous163 $CustomerNumber
     * @access public
     */
    public $CustomerNumber = null;

    /**
     * @param anonymous161 $active
     * @param anonymous162 $PaymentType
     * @param anonymous163 $CustomerNumber
     * @access public
     */
    public function __construct($active, $PaymentType, $CustomerNumber)
    {
      $this->active = $active;
      $this->PaymentType = $PaymentType;
      $this->CustomerNumber = $CustomerNumber;
    }

}
