<?php

class GetManifestRequest
{

    /**
     * @var Version $Version
     * @access public
     */
    public $Version = null;

    /**
     * @var string $manifestDate
     * @access public
     */
    public $manifestDate = null;

    /**
     * @param Version $Version
     * @param string $manifestDate
     * @access public
     */
    public function __construct($Version, $manifestDate)
    {
      $this->Version = $Version;
      $this->manifestDate = $manifestDate;
    }

}
