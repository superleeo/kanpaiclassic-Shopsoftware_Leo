<?php

class Serviceconfiguration
{

    /**
     * @var anonymous125 $active
     * @access public
     */
    public $active = null;

    /**
     * @param anonymous125 $active
     * @access public
     */
    public function __construct($active)
    {
      $this->active = $active;
    }

}
