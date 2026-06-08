<?php
namespace KANPAICLASSIC;

class KANPAICLASSIC_reservations {
    protected $db;
    protected $params;

    public function __construct()
    {
        $this->db = Control::getDB();
        $this->params = Control::getParams();
    }

    public function getContent()
    {
        require_once SHOP_PATH.'/classes/reservation.class.php';
        $reservation = new \KANPAICLASSIC\Reservation($this->db);
        $reservations = $reservation->listAll();

        ob_start();
        include ADMIN_PATH . '/templates/admin_reservations.tpl.php';
        $html = ob_get_contents();
        ob_clean();

        return $html;
    }
}
