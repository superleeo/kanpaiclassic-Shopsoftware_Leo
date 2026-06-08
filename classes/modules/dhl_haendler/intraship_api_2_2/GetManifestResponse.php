<?php

class GetManifestResponse
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
     * @var base64Binary $manifestData
     * @access public
     */
    public $manifestData = null;

    /**
     * @param Version $Version
     * @param Statusinformation $Status
     * @param base64Binary $manifestData
     * @access public
     */
    public function __construct($Version, $Status, $manifestData)
    {
      $this->Version = $Version;
      $this->Status = $Status;
      $this->manifestData = $manifestData;
    }

}
