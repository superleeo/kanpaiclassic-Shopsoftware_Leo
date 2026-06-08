<?php

class CommunicationType
{

    /**
     * @var phone $phone
     * @access public
     */
    public $phone = null;

    /**
     * @var email $email
     * @access public
     */
    public $email = null;

    /**
     * @var contactPerson $contactPerson
     * @access public
     */
    public $contactPerson = null;

    /**
     * @param phone $phone
     * @param email $email
     * @param contactPerson $contactPerson
     * @access public
     */
    public function __construct($phone, $email, $contactPerson)
    {
      $this->phone = $phone;
      $this->email = $email;
      $this->contactPerson = $contactPerson;
    }

}
