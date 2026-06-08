<?php

class ShipmentNotificationType
{

    /**
     * @var recipientEmailAddress $recipientEmailAddress
     * @access public
     */
    public $recipientEmailAddress = null;

    /**
     * @param recipientEmailAddress $recipientEmailAddress
     * @access public
     */
    public function __construct($recipientEmailAddress)
    {
      $this->recipientEmailAddress = $recipientEmailAddress;
    }

}
