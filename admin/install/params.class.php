<?php
/*
###################################################################################
  KANPAI CLASSIC Shopsoftware - Entwicklungsstand 06.2025

  Kanpai Classic - Web Development
  https://www.kanpaiclassic.com
  https://www.kanpaiclassic.com

  c Copyright by Kanpai Classic - Kanpai Classic Web Development


  Copyrightvermerke duerfen NICHT entfernt werden!

  ------------------------------------------------------------------------
  Dieses Programm ist eine Software von Kanpai Classic Web Development.
  Diese Software/Website ist eine Einzelplatzlizenz und für den Betrieb auf einem Speicherplatz 1 Installation berechtigt.
  Die Veroeffentlichung dieses Programms erfolgt OHNE IRGENDEINE GARANTIE, sogar ohne
  die implizite Garantie der MARKTREIFE oder der VERWENDBARKEIT FUER EINEN BESTIMMTEN ZWECK.
  Diese Script darf nicht veroeffentlicht oder weitergeben werden. Es gilt das Urheberrecht.
  Diese Software darf nur mit schritflicher Genehmigung modifizieren werden.
  Es gelten die Ihnen mitgeteilten Lizenzbestimmungen.
  ------------------------------------------------------------------------
  Bei Verstoß gegen die Lizenzbedingungen kann die Lizenz jederzeit entzogen werden. Der Kaufpreises wird nicht erstattet.
  Wer gegen die Lizenzbedingungen verstoesst insbesondere bei illegalem Vertrieb oder Mehrfachnutzung des Scriptes  muss mit einer Vertragsstrafe von 50.000 Euro je Einzeldelikt rechnen!

##################################################################################
  Copyrightvermerke duerfen NICHT entfernt werden!
*/

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}
include '../../classes/base/params_base.class.php';

class KANPAICLASSIC_Params
{
   public $post_params = [];
   public $task = '';
   public $func = '';

   function __construct() {
      $this->get_functions();
   }

   // Parameter nach index.php/../.. auswerten
   private function get_functions() {
      if (isset($_SERVER['REQUEST_URI'])) {
         $my_funcs = $_SERVER['REQUEST_URI'];
         if ($my_funcs) {
            $funcs = explode('/', substr($my_funcs, 1));
            $count = count($funcs);

            if ($funcs[0] == 'index.php') {
               $count--;
               array_shift($funcs);
            }

            // Bei AJAX wird ajax vorangestellt
            if ($funcs[0]) {
               $this->task = $funcs[0];
               if ($funcs[1]) {
                  $this->func = $funcs[0];
               }
               else {
                  $this->func = '';
               }
            }
         }
         else {
            $this->task = '';
            $this->func = '';
         }
      }
      else {
         $this->task = '';
         $this->func = '';
      }

   }

   public function postString ($name, $default = '', $mode = 'all') {
      if (isset($post_params[$name])) {
         return $post_params[$name];
      }

      if (isset($_POST[$name])) {
         $test = $_POST[$name];
         $this->post_params[$name] = $test;
         return $test;
      }
      return $default;
   }

   public function postCheckbox($name){
      if (isset($this->post_params[$name])) {
         return $this->post_params[$name];
      }

      // Rückgabewert ist Array
      if (isset($_POST[$name]) && is_array($_POST[$name])) {
         $test = [];
         foreach ($_POST[$name] as $key => $value) {
            $test1 = $_POST[$value];
            if ( $test == 'on' || $test == 'true') {
               $test[$key] = 'y';
            }
            else {
               $test[$key] = 'n';
            }
         }
      }

      // Rückgabewert ist Einzelwert
      else {
         $test = (isset($_POST[$name]) && ($_POST[$name] == 'on' || $_POST[$name] == 'true')) ? 'y' : 'n';
      }

      $this->post_params[$name] = $test;
      return $test;
   }

   public function postTest ($name) {
      if (isset($_POST[$name])) {
         return true;
      }
      return false;
   }


}
