<?php

class BankType
{

    /**
     * @var accountOwner $accountOwner
     * @access public
     */
    public $accountOwner = null;

    /**
     * @var bankName $bankName
     * @access public
     */
    public $bankName = null;

    /**
     * @var iban $iban
     * @access public
     */
    public $iban = null;

    /**
     * @var note1 $note1
     * @access public
     */
    public $note1 = null;

    /**
     * @var note2 $note2
     * @access public
     */
    public $note2 = null;

    /**
     * @var bic $bic
     * @access public
     */
    public $bic = null;

    /**
     * @var accountreference $accountreference
     * @access public
     */
    public $accountreference = null;

    /**
     * @param accountOwner $accountOwner
     * @param bankName $bankName
     * @param iban $iban
     * @param note1 $note1
     * @param note2 $note2
     * @param bic $bic
     * @param accountreference $accountreference
     * @access public
     */
    public function __construct($accountOwner, $bankName, $iban, $note1, $note2, $bic, $accountreference)
    {
      $this->accountOwner = $accountOwner;
      $this->bankName = $bankName;
      $this->iban = $iban;
      $this->note1 = $note1;
      $this->note2 = $note2;
      $this->bic = $bic;
      $this->accountreference = $accountreference;
    }

}
