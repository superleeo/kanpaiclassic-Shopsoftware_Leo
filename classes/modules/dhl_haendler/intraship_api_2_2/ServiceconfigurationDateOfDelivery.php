<?php

class ServiceconfigurationDateOfDelivery
{

    /**
     * @var anonymous148 $active
     * @access public
     */
    public $active = null;

    /**
     * @var anonymous149 $details
     * @access public
     */
    public $details = null;

    /**
     * @param anonymous148 $active
     * @param anonymous149 $details
     * @access public
     */
    public function __construct($active, $details)
    {
      $this->active = $active;
      $this->details = $details;
    }

}
