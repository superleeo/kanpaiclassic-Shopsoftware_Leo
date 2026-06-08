<?php

class ServiceconfigurationCashOnDelivery
{

    /**
     * @var anonymous154 $active
     * @access public
     */
    public $active = null;

    /**
     * @var anonymous155 $addFee
     * @access public
     */
    public $addFee = null;

    /**
     * @var anonymous156 $codAmount
     * @access public
     */
    public $codAmount = null;

    /**
     * @param anonymous154 $active
     * @param anonymous155 $addFee
     * @param anonymous156 $codAmount
     * @access public
     */
    public function __construct($active, $addFee, $codAmount)
    {
      $this->active = $active;
      $this->addFee = $addFee;
      $this->codAmount = $codAmount;
    }

}
