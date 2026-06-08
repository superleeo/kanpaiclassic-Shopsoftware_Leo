<?php

class GetLabelRequest
{

    /**
     * @var Version $Version
     * @access public
     */
    public $Version = null;

    /**
     * @var shipmentNumber $shipmentNumber
     * @access public
     */
    public $shipmentNumber = null;

    /**
     * @var labelResponseType $labelResponseType
     * @access public
     */
    public $labelResponseType = null;

    /**
     * @param Version $Version
     * @param shipmentNumber $shipmentNumber
     * @param labelResponseType $labelResponseType
     * @access public
     */
    public function __construct($Version, $shipmentNumber, $labelResponseType)
    {
      $this->Version = $Version;
      $this->shipmentNumber = $shipmentNumber;
      $this->labelResponseType = $labelResponseType;
    }

}
