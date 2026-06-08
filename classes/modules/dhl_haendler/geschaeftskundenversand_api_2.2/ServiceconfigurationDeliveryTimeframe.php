<?php

class ServiceconfigurationDeliveryTimeframe
{

    /**
     * @var anonymous145 $active
     * @access public
     */
    public $active = null;

    /**
     * @var anonymous146 $type
     * @access public
     */
    public $type = null;

    /**
     * @param anonymous145 $active
     * @param anonymous146 $type
     * @access public
     */
    public function __construct($active, $type)
    {
      $this->active = $active;
      $this->type = $type;
    }

}
