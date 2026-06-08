<?php

class ServiceconfigurationShipmentHandling
{

    /**
     * @var anonymous158 $active
     * @access public
     */
    public $active = null;

    /**
     * @var anonymous159 $type
     * @access public
     */
    public $type = null;

    /**
     * @param anonymous158 $active
     * @param anonymous159 $type
     * @access public
     */
    public function __construct($active, $type)
    {
      $this->active = $active;
      $this->type = $type;
    }

}
