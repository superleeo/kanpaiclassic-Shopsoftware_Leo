<?php
/* Reservation page template - Miaowei Teppanyaki
   Style: Dark luxury Japanese, inspired by kanpaiclassic.tw
*/
if (!isset($res_ok)) $res_ok = false;
if (!isset($_SESSION['reservation_token'])) $_SESSION['reservation_token'] = bin2hex(random_bytes(32));
$token = $_SESSION['reservation_token'];
?>
<section class="page-section reservation-page">
  <div class="section-header">
    <h2 class="section-title">Reservierung</h2>
    <p class="section-subtitle">Tischreservierung . テーブル予約</p>
  </div>

  <?php if ($res_ok): ?>
    <div class="reservation-success">
      <div class="success-icon">&#10003;</div>
      <h3>Vielen Dank fur Ihre Reservierung!</h3>
      <p>Wir haben Ihre Anfrage erhalten und werden sie in Kurze bestatigen.</p>
      <p>Eine Bestatigungs-E-Mail wurde an Ihre Adresse gesendet.</p>
      <a href="/" class="btn btn-primary">Zuruck zur Startseite</a>
    </div>
  <?php else: ?>
    <form method="post" action="/index.php?task=reservation&func=book" class="reservation-form">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">

      <div class="form-row">
        <div class="form-group">
          <label for="res_name">Name <span class="required">*</span></label>
          <input type="text" id="res_name" name="name" required maxlength="191" placeholder="Ihr Name">
        </div>
        <div class="form-group">
          <label for="res_email">E-Mail <span class="required">*</span></label>
          <input type="email" id="res_email" name="email" required placeholder="ihre@email.de">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="res_phone">Telefon</label>
          <input type="text" id="res_phone" name="phone" placeholder="+49 621 123456">
        </div>
        <div class="form-group">
          <label for="res_persons">Personen <span class="required">*</span></label>
          <select id="res_persons" name="persons" required>
            <option value="1">1 Person</option>
            <option value="2" selected>2 Personen</option>
            <option value="3">3 Personen</option>
            <option value="4">4 Personen</option>
            <option value="5">5 Personen</option>
            <option value="6">6 Personen</option>
            <option value="7">7 Personen</option>
            <option value="8">8 Personen</option>
            <option value="9">9 Personen</option>
            <option value="10">10 Personen</option>
            <option value="12">12 Personen</option>
            <option value="14">14 Personen</option>
            <option value="16">16 Personen</option>
            <option value="20">20 Personen</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="res_date">Datum <span class="required">*</span></label>
          <input type="date" id="res_date" name="date" required min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+90 days')); ?>">
        </div>
        <div class="form-group">
          <label for="res_time">Zeit <span class="required">*</span></label>
          <select id="res_time" name="time" required>
            <option value="">-- Bitte wahlen --</option>
            <option value="11:00">11:00</option>
            <option value="11:30">11:30</option>
            <option value="12:00">12:00</option>
            <option value="12:30">12:30</option>
            <option value="13:00">13:00</option>
            <option value="13:30">13:30</option>
            <option value="14:00">14:00</option>
            <option value="14:30">14:30</option>
            <option value="17:00">17:00</option>
            <option value="17:30">17:30</option>
            <option value="18:00">18:00</option>
            <option value="18:30">18:30</option>
            <option value="19:00">19:00</option>
            <option value="19:30">19:30</option>
            <option value="20:00">20:00</option>
            <option value="20:30">20:30</option>
            <option value="21:00">21:00</option>
            <option value="21:30">21:30</option>
          </select>
        </div>
      </div>

      <div class="form-group form-group-full">
        <label for="res_notes">Besondere Wunsche</label>
        <textarea id="res_notes" name="notes" rows="3" placeholder="Allergien, besondere Anlasse, Sitzplatzwunsch..."></textarea>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-large">Reservierung absenden</button>
      </div>
    </form>

    <div class="reservation-info">
      <div class="info-item">
        <span class="info-icon">&#128338;</span>
        <p><strong>Offnungszeiten</strong><br>Di-Do: 11:00-22:00<br>Fr-So: 11:00-23:00<br>Montag: Ruhetag</p>
      </div>
      <div class="info-item">
        <span class="info-icon">&#128222;</span>
        <p><strong>Telefonische Reservierung</strong><br>+49 621 XXXXXX</p>
      </div>
      <div class="info-item">
        <span class="info-icon">&#128205;</span>
        <p><strong>Adresse</strong><br>Augustaanlage 15<br>68161 Mannheim</p>
      </div>
    </div>
  <?php endif; ?>
</section>
