<?php

class ServiceconfigurationDH
{

    /**
     * @var anonymous139 $active
     * @access public
     */
    public $active = null;

    /**
     * @var anonymous140 $Days
     * @access public
     */
    public $Days = null;

    /**
     * @param anonymous139 $active
     * @param anonymous140 $Days
     * @access public
     */
    public function __construct($active, $Days)
    {
      $this->active = $active;
      $this->Days = $Days;
    }

}
