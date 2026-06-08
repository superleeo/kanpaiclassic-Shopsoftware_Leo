<?php

class ServiceconfigurationType
{

    /**
     * @var anonymous130 $active
     * @access public
     */
    public $active = null;

    /**
     * @var anonymous131 $Type
     * @access public
     */
    public $Type = null;

    /**
     * @param anonymous130 $active
     * @param anonymous131 $Type
     * @access public
     */
    public function __construct($active, $Type)
    {
      $this->active = $active;
      $this->Type = $Type;
    }

}
