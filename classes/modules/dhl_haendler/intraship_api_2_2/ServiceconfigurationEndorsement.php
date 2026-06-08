<?php

class ServiceconfigurationEndorsement
{

    /**
     * @var anonymous133 $active
     * @access public
     */
    public $active = null;

    /**
     * @var anonymous134 $type
     * @access public
     */
    public $type = null;

    /**
     * @param anonymous133 $active
     * @param anonymous134 $type
     * @access public
     */
    public function __construct($active, $type)
    {
      $this->active = $active;
      $this->type = $type;
    }

}
