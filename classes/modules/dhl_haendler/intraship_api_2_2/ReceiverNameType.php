<?php

class ReceiverNameType
{

    /**
     * @var name $name
     * @access public
     */
    public $name = null;

    /**
     * @param name $name
     * @access public
     */
    public function __construct($name)
    {
      $this->name = $name;
    }

}
