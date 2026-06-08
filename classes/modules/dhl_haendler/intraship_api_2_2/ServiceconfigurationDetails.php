<?php

class ServiceconfigurationDetails
{

    /**
     * @var anonymous127 $active
     * @access public
     */
    public $active = null;

    /**
     * @var anonymous128 $details
     * @access public
     */
    public $details = null;

    /**
     * @param anonymous127 $active
     * @param anonymous128 $details
     * @access public
     */
    public function __construct($active, $details)
    {
      $this->active = $active;
      $this->details = $details;
    }

}
