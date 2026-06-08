<?php
/* Vouchers page template - Miaowei Teppanyaki */
?>
<!-- Voucher Hero -->
<section class="tp-page-hero tp-page-hero-red">
  <div class="tp-page-hero-content">
    <h1>礼品代金券</h1>
    <p>送给亲朋好友的完美礼物</p>
  </div>
</section>

<!-- Voucher Content -->
<section class="tp-section tp-bg-white">
  <div class="tp-container tp-container-sm">
    <h2 class="tp-section-title" style="text-align:center;margin-bottom:2rem;">选择金额</h2>

    <div class="tp-voucher-options">
      <div class="tp-voucher-option selected">
        <div class="tp-voucher-value">&euro;25</div>
        <div class="tp-voucher-desc">适合前菜或饮品</div>
      </div>
      <div class="tp-voucher-option">
        <div class="tp-voucher-value">&euro;50</div>
        <div class="tp-voucher-desc">适合单人套餐</div>
      </div>
      <div class="tp-voucher-option">
        <div class="tp-voucher-value">&euro;100</div>
        <div class="tp-voucher-desc">适合双人用餐</div>
      </div>
      <div class="tp-voucher-option">
        <div class="tp-voucher-value">&euro;150</div>
        <div class="tp-voucher-desc">适合豪华套餐</div>
      </div>
    </div>

    <div class="tp-card" style="margin-bottom:2rem;">
      <form class="tp-form">
        <h3 style="margin:0 0 1rem;">购买信息</h3>
        <div class="tp-form-row">
          <div class="tp-form-group">
            <label>您的姓名 *</label>
            <input type="text" required placeholder="姓名">
          </div>
          <div class="tp-form-group">
            <label>您的邮箱 *</label>
            <input type="email" required placeholder="email@example.com">
          </div>
        </div>
        <div class="tp-form-row">
          <div class="tp-form-group">
            <label>收件人姓名（可选）</label>
            <input type="text" placeholder="收件人姓名">
          </div>
          <div class="tp-form-group">
            <label>收件人邮箱（可选）</label>
            <input type="email" placeholder="收件人邮箱">
          </div>
        </div>
        <div class="tp-form-group">
          <label>祝福信息（可选，最多200字）</label>
          <textarea rows="3" placeholder="添加个性化祝福信息..."></textarea>
        </div>
        <div class="tp-privacy-notice">
          <p><strong>根据德国数据保护法(DSGVO)</strong>，您的个人信息将仅用于代金券购买和发送。</p>
        </div>
        <div style="background:var(--tp-gray-50);padding:1.5rem;border-radius:var(--tp-radius);display:flex;justify-content:space-between;align-items:center;">
          <span>代金券金额：<strong style="font-size:1.4rem;color:var(--tp-red);">&euro;50</strong></span>
          <button type="submit" class="tp-btn tp-btn-primary">前往支付</button>
        </div>
      </form>
    </div>

    <div class="tp-info-box">
      <h3>代金券优势</h3>
      <div class="tp-info-grid">
        <div><h4>&#10003; 12个月有效</h4><p>充足时间使用，自购买日起计算</p></div>
        <div><h4>&#10003; 全场通用</h4><p>可用于所有菜单项目</p></div>
        <div><h4>&#10003; 即刻发送</h4><p>电子版代金券，即刻发送至邮箱</p></div>
        <div><h4>&#10003; 合规保障</h4><p>符合德国税法规定，可开具发票</p></div>
      </div>
    </div>
  </div>
</section>
