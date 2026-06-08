<?php

class ServiceconfigurationVisualAgeCheck
{

    /**
     * @var anonymous142 $active
     * @access public
     */
    public $active = null;

    /**
     * @var anonymous143 $type
     * @access public
     */
    public $type = null;

    /**
     * @param anonymous142 $active
     * @param anonymous143 $type
     * @access public
     */
    public function __construct($active, $type)
    {
      $this->active = $active;
      $this->type = $type;
    }

}
