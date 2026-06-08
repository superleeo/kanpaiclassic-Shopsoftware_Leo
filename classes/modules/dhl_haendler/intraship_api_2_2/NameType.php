<?php

class NameType
{

    /**
     * @var name1 $name1
     * @access public
     */
    public $name1 = null;

    /**
     * @var name2 $name2
     * @access public
     */
    public $name2 = null;

    /**
     * @var name3 $name3
     * @access public
     */
    public $name3 = null;

    /**
     * @param name1 $name1
     * @param name2 $name2
     * @param name3 $name3
     * @access public
     */
    public function __construct($name1, $name2, $name3)
    {
      $this->name1 = $name1;
      $this->name2 = $name2;
      $this->name3 = $name3;
    }

}
