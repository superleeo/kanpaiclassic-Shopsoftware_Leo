<?php
/* Reservation page — Miaowei Teppanyaki Tepan Design */
if (!isset($res_ok)) $res_ok = false;
if (!isset($_SESSION['reservation_token'])) $_SESSION['reservation_token'] = bin2hex(random_bytes(32));
$token = $_SESSION['reservation_token'];
?>
<!-- Page Hero -->
<section class="tp-page-hero tp-page-hero-red">
  <div class="tp-page-hero-content">
    <h1>在线预订</h1>
    <p>预订您的专属用餐时光</p>
  </div>
</section>

<section class="tp-section tp-bg-white">
  <div class="tp-container tp-container-sm">
    <?php if ($res_ok): ?>
    <div class="tp-success-box">
      <div class="tp-success-icon">&#10003;</div>
      <h3>预订成功！</h3>
      <p>感谢您的预订，我们会尽快与您确认。</p>
      <p>确认邮件已发送至您的邮箱。</p>
      <a href="/restaurant_home" class="tp-btn tp-btn-primary">返回首页</a>
    </div>
    <?php else: ?>
    <div class="tp-card">
      <form method="post" action="/reservation?func=book" class="tp-form">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
        <div class="tp-form-row">
          <div class="tp-form-group">
            <label>&#128100; 姓名 *</label>
            <input type="text" name="name" required placeholder="请输入您的姓名">
          </div>
          <div class="tp-form-group">
            <label>&#9993; 电子邮箱 *</label>
            <input type="email" name="email" required placeholder="example@email.com">
          </div>
        </div>
        <div class="tp-form-group">
          <label>&#128222; 联系电话 *</label>
          <input type="tel" name="phone" required placeholder="+49 XXX XXXXXXX">
        </div>
        <div class="tp-form-row tp-form-row-3">
          <div class="tp-form-group">
            <label>&#128197; 日期 *</label>
            <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+90 days')); ?>">
          </div>
          <div class="tp-form-group">
            <label>&#128338; 时间 *</label>
            <select name="time" required>
              <option value="">选择时间</option>
              <?php foreach(['17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30'] as $t): ?>
              <option value="<?php echo $t; ?>"><?php echo $t; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="tp-form-group">
            <label>&#128101; 人数 *</label>
            <select name="persons" required>
              <?php for($i=1;$i<=10;$i++): ?>
              <option value="<?php echo $i; ?>" <?php echo $i==2?'selected':''; ?>><?php echo $i; ?> 位</option>
              <?php endfor; ?>
            </select>
          </div>
        </div>
        <div class="tp-form-group">
          <label>特殊要求（可选）</label>
          <textarea name="notes" rows="4" placeholder="如有食物过敏、特殊饮食需求或其他要求，请在此说明"></textarea>
        </div>
        <div class="tp-privacy-notice">
          <p><strong>根据德国数据保护法(DSGVO)</strong>，您的个人信息将仅用于预订确认和餐厅服务。我们不会将您的信息分享给第三方。提交此表单即表示您同意我们的隐私政策。</p>
        </div>
        <button type="submit" class="tp-btn tp-btn-primary tp-btn-block">确认预订</button>
      </form>
    </div>
    <div class="tp-info-row">
      <div class="tp-info-card"><h3>预订须知</h3><ul><li>我们会在24小时内通过邮件或电话确认您的预订</li><li>如需取消或更改预订，请至少提前24小时通知</li><li>超过预订时间15分钟未到，预订将自动取消</li><li>10人以上的团体预订请直接电话联系</li><li>节假日期间建议提前3-7天预订</li></ul></div>
      <div class="tp-info-card"><h3>电话预订</h3><p>您也可以直接致电预订：</p><div class="tp-phone-number"><span class="tp-phone-icon">&#128222;</span><strong><?php echo isset($params->firma['telefon']) ? $params->firma['telefon'] : '+49 621 XXXXXX'; ?></strong></div><p class="tp-text-sm">营业时间内接听预订电话</p></div>
    </div>
    <?php endif; ?>
  </div>
</section>
