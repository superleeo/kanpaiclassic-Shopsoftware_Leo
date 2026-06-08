<?php

class AuthentificationType
{

    /**
     * @var user $user
     * @access public
     */
    public $user = null;

    /**
     * @var signature $signature
     * @access public
     */
    public $signature = null;

    /**
     * @param user $user
     * @param signature $signature
     * @access public
     */
    public function __construct($user, $signature)
    {
      $this->user = $user;
      $this->signature = $signature;
    }

}
