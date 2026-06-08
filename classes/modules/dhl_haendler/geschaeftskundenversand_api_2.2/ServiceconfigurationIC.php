<?php

class ServiceconfigurationIC
{

    /**
     * @var Ident $Ident
     * @access public
     */
    public $Ident = null;

    /**
     * @var anonymous170 $active
     * @access public
     */
    public $active = null;

    /**
     * @param Ident $Ident
     * @param anonymous170 $active
     * @access public
     */
    public function __construct($Ident, $active)
    {
      $this->Ident = $Ident;
      $this->active = $active;
    }

}
