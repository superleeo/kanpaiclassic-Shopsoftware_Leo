<?php
namespace KANPAICLASSIC;

class Reservation {
    protected $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: Control::getDB();
    }

    /**
     * Create booking from POST data array
     * Returns: ['success' => bool, 'error' => string]
     */
    public function bookFromArray(array $data)
    {
        $name    = isset($data['name']) ? trim($data['name']) : '';
        $email   = isset($data['email']) ? trim($data['email']) : '';
        $phone   = isset($data['phone']) ? trim($data['phone']) : '';
        $date    = isset($data['date']) ? trim($data['date']) : '';
        $time    = isset($data['time']) ? trim($data['time']) : '';
        $persons = isset($data['persons']) ? (int)$data['persons'] : 1;
        $notes   = isset($data['notes']) ? trim($data['notes']) : '';

        // Validate required fields
        if ($name == '' || $email == '' || $date == '' || $time == '') {
            return ['success' => false, 'error' => 'Bitte fullen Sie alle Pflichtfelder aus.'];
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Bitte geben Sie eine gultige E-Mail-Adresse ein.'];
        }

        // Validate name length
        if (strlen($name) < 2 || strlen($name) > 191) {
            return ['success' => false, 'error' => 'Bitte geben Sie einen gultigen Namen ein (2-191 Zeichen).'];
        }

        // Validate date format and range
        $date_obj = \DateTime::createFromFormat('Y-m-d', $date);
        if (!$date_obj || $date_obj->format('Y-m-d') !== $date) {
            return ['success' => false, 'error' => 'Ungultiges Datumsformat.'];
        }
        $today = new \DateTime('today');
        if ($date_obj < $today) {
            return ['success' => false, 'error' => 'Das Datum darf nicht in der Vergangenheit liegen.'];
        }
        // Max 90 days in advance
        $max_date = new \DateTime('+90 days');
        if ($date_obj > $max_date) {
            return ['success' => false, 'error' => 'Reservierungen sind maximal 90 Tage im Voraus moglich.'];
        }

        // Validate time format and range (11:00 - 22:00)
        $time_obj = \DateTime::createFromFormat('H:i', $time);
        if (!$time_obj || $time_obj->format('H:i') !== $time) {
            return ['success' => false, 'error' => 'Ungultiges Zeitformat.'];
        }
        $time_hour = (int)$time_obj->format('H');
        $time_min  = (int)$time_obj->format('i');
        if ($time_hour < 11 || $time_hour > 22 || ($time_hour == 22 && $time_min > 0)) {
            return ['success' => false, 'error' => 'Reservierungen sind zwischen 11:00 und 22:00 moglich.'];
        }

        // Validate persons
        if ($persons < 1 || $persons > 20) {
            return ['success' => false, 'error' => 'Die Personenzahl muss zwischen 1 und 20 liegen.'];
        }

        // Validate phone (optional, basic)
        if ($phone != '' && strlen($phone) < 5) {
            return ['success' => false, 'error' => 'Bitte geben Sie eine gultige Telefonnummer ein.'];
        }

        // Check for duplicate reservation (same email, same date & time)
        $safe_email = $this->db->escape($email);
        $safe_date  = $this->db->escape($date);
        $safe_time  = $this->db->escape($time);
        $existing = $this->db->querySingleObject(
            "SELECT id FROM `reservations` WHERE email = '{$safe_email}' AND date = '{$safe_date}' AND time = '{$safe_time}' AND status != 'cancelled'"
        );
        if ($existing) {
            return ['success' => false, 'error' => 'Sie haben bereits eine Reservierung zu diesem Zeitpunkt.'];
        }

        // Escape all values
        $safe_name    = $this->db->escape($name);
        $safe_phone   = $this->db->escape($phone);
        $safe_notes   = $this->db->escape($notes);

        $sql = "INSERT INTO `reservations` SET "
             . "`name` = '{$safe_name}', "
             . "`email` = '{$safe_email}', "
             . "`phone` = '{$safe_phone}', "
             . "`date` = '{$safe_date}', "
             . "`time` = '{$safe_time}', "
             . "`persons` = {$persons}, "
             . "`notes` = '{$safe_notes}', "
             . "`status` = 'confirmed'";

        $this->db->query($sql);

        return ['success' => true];
    }

    /**
     * List all reservations (for admin)
     */
    public function listAll()
    {
        $res = $this->db->query("SELECT * FROM `reservations` ORDER BY date DESC, time DESC");
        $rows = [];

        if ($res) {
            while ($r = $this->db->fetchAssoc($res)) {
                $rows[] = $r;
            }
        }

        return $rows;
    }

    /**
     * List reservations by date range
     */
    public function listByDate($from, $to)
    {
        $safe_from = $this->db->escape($from);
        $safe_to   = $this->db->escape($to);
        $res = $this->db->query(
            "SELECT * FROM `reservations` WHERE date BETWEEN '{$safe_from}' AND '{$safe_to}' ORDER BY date ASC, time ASC"
        );
        $rows = [];
        if ($res) {
            while ($r = $this->db->fetchAssoc($res)) {
                $rows[] = $r;
            }
        }
        return $rows;
    }

    /**
     * Update reservation status (for admin)
     */
    public function updateStatus($id, $status)
    {
        $safe_id = (int)$id;
        $allowed = ['pending', 'confirmed', 'cancelled', 'completed'];
        if (!in_array($status, $allowed)) {
            return false;
        }
        $safe_status = $this->db->escape($status);
        $this->db->query("UPDATE `reservations` SET `status` = '{$safe_status}' WHERE id = {$safe_id}");
        return true;
    }
}
