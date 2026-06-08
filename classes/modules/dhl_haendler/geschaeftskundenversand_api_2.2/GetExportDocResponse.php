<?php

class GetExportDocResponse
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
     * @var ExportDocData $ExportDocData
     * @access public
     */
    public $ExportDocData = null;

    /**
     * @param Version $Version
     * @param Statusinformation $Status
     * @param ExportDocData $ExportDocData
     * @access public
     */
    public function __construct($Version, $Status, $ExportDocData)
    {
      $this->Version = $Version;
      $this->Status = $Status;
      $this->ExportDocData = $ExportDocData;
    }

}
