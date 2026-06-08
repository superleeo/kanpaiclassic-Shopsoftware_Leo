<?php

class ValidationState
{

    /**
     * @var SequenceNumber $sequenceNumber
     * @access public
     */
    public $sequenceNumber = null;

    /**
     * @var Statusinformation $Status
     * @access public
     */
    public $Status = null;

    /**
     * @param SequenceNumber $sequenceNumber
     * @param Statusinformation $Status
     * @access public
     */
    public function __construct($sequenceNumber, $Status)
    {
      $this->sequenceNumber = $sequenceNumber;
      $this->Status = $Status;
    }

}
