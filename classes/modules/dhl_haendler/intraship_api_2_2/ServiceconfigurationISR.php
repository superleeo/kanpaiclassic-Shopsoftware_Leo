<?php

class ServiceconfigurationISR
{

    /**
     * @var anonymous136 $active
     * @access public
     */
    public $active = null;

    /**
     * @var anonymous137 $details
     * @access public
     */
    public $details = null;

    /**
     * @param anonymous136 $active
     * @param anonymous137 $details
     * @access public
     */
    public function __construct($active, $details)
    {
      $this->active = $active;
      $this->details = $details;
    }

}
