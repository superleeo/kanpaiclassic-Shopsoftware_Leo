<?php

class GetLabelResponse
{

    /**
     * @var Version $Version
     * @access public
     */
    public $Version = null;

    /**
     * @var Statusinformation $Status
     * @access public
     */
    public $Status = null;

    /**
     * @var LabelData $LabelData
     * @access public
     */
    public $LabelData = null;

    /**
     * @param Version $Version
     * @param Statusinformation $Status
     * @param LabelData $LabelData
     * @access public
     */
    public function __construct($Version, $Status, $LabelData)
    {
      $this->Version = $Version;
      $this->Status = $Status;
      $this->LabelData = $LabelData;
    }

}
