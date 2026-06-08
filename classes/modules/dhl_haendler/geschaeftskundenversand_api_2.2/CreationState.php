<?php

class CreationState
{

    /**
     * @var SequenceNumber $sequenceNumber
     * @access public
     */
    public $sequenceNumber = null;

    /**
     * @var LabelData $LabelData
     * @access public
     */
    public $LabelData = null;

    /**
     * @param SequenceNumber $sequenceNumber
     * @param LabelData $LabelData
     * @access public
     */
    public function __construct($sequenceNumber, $LabelData)
    {
      $this->sequenceNumber = $sequenceNumber;
      $this->LabelData = $LabelData;
    }

}
