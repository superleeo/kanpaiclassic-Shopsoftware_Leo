<?php

class Ident
{

    /**
     * @var surname $surname
     * @access public
     */
    public $surname = null;

    /**
     * @var givenName $givenName
     * @access public
     */
    public $givenName = null;

    /**
     * @var dateOfBirth $dateOfBirth
     * @access public
     */
    public $dateOfBirth = null;

    /**
     * @var minimumAge $minimumAge
     * @access public
     */
    public $minimumAge = null;

    /**
     * @param surname $surname
     * @param givenName $givenName
     * @param dateOfBirth $dateOfBirth
     * @param minimumAge $minimumAge
     * @access public
     */
    public function __construct($surname, $givenName, $dateOfBirth, $minimumAge)
    {
      $this->surname = $surname;
      $this->givenName = $givenName;
      $this->dateOfBirth = $dateOfBirth;
      $this->minimumAge = $minimumAge;
    }

}
