<?php
/* Home page template - Miaowei Teppanyaki
   Design: Clean Japanese restaurant style with bold red accents */
?>
<!-- Hero Section -->
<section class="tp-hero">
  <div class="tp-hero-overlay"></div>
  <div class="tp-hero-content">
    <h1 class="tp-hero-title"><?php echo $params->firma['shop_name']; ?></h1>
    <p class="tp-hero-subtitle">正宗日本铁板烧 · Authentic Japanese Teppanyaki</p>
    <p class="tp-hero-desc">体验顶级日式料理艺术 · 柏林市中心</p>
    <div class="tp-hero-actions">
      <a href="/reservation" class="tp-btn tp-btn-primary tp-btn-lg">立即预订</a>
      <a href="/menu" class="tp-btn tp-btn-white tp-btn-lg">查看菜单</a>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="tp-section tp-bg-white">
  <div class="tp-container">
    <div class="tp-section-header">
      <h2 class="tp-section-title">为什么选择我们</h2>
      <p class="tp-section-desc">我们致力于为每一位客人提供难忘的日式料理体验</p>
    </div>
    <div class="tp-features-grid">
      <div class="tp-feature-card">
        <div class="tp-feature-icon">&#127859;</div>
        <h3>专业大厨</h3>
        <p>超过20年经验的日本铁板烧大厨，为您现场烹饪精致料理</p>
      </div>
      <div class="tp-feature-card">
        <div class="tp-feature-icon">&#127869;</div>
        <h3>顶级食材</h3>
        <p>精选A5和牛、新鲜海鲜，每日直送的优质食材</p>
      </div>
      <div class="tp-feature-card">
        <div class="tp-feature-icon">&#128338;</div>
        <h3>灵活预订</h3>
        <p>在线预订系统，轻松安排您的用餐时间</p>
      </div>
      <div class="tp-feature-card">
        <div class="tp-feature-icon">&#128205;</div>
        <h3>曼海姆市中心</h3>
        <p>位于曼海姆市中心，交通便利，环境优雅</p>
      </div>
    </div>
  </div>
</section>

<!-- Specialties Section -->
<section class="tp-section tp-bg-gray">
  <div class="tp-container">
    <div class="tp-section-header">
      <h2 class="tp-section-title">招牌料理</h2>
      <p class="tp-section-desc">精选我们最受欢迎的铁板烧料理</p>
    </div>
    <div class="tp-specialties-grid">
      <div class="tp-specialty-card">
        <div class="tp-specialty-img" style="background:#1a1a1a url('https://images.unsplash.com/photo-1512132411229-c30391241dd8?w=600&q=80') center/cover;"></div>
        <div class="tp-specialty-body">
          <div class="tp-specialty-header">
            <h3>A5和牛套餐</h3>
            <span class="tp-price">&euro;89</span>
          </div>
          <p>顶级日本和牛配时令蔬菜</p>
        </div>
      </div>
      <div class="tp-specialty-card">
        <div class="tp-specialty-img" style="background:#1a1a1a url('https://images.unsplash.com/photo-1635452065566-9c89471bb86c?w=600&q=80') center/cover;"></div>
        <div class="tp-specialty-body">
          <div class="tp-specialty-header">
            <h3>龙虾铁板烧</h3>
            <span class="tp-price">&euro;75</span>
          </div>
          <p>新鲜龙虾配黄油蒜香</p>
        </div>
      </div>
      <div class="tp-specialty-card">
        <div class="tp-specialty-img" style="background:#1a1a1a url('https://images.unsplash.com/photo-1700324828870-43027cba6d18?w=600&q=80') center/cover;"></div>
        <div class="tp-specialty-body">
          <div class="tp-specialty-header">
            <h3>海鲜拼盘</h3>
            <span class="tp-price">&euro;68</span>
          </div>
          <p>多种海鲜组合，精致呈现</p>
        </div>
      </div>
    </div>
    <div class="tp-section-footer">
      <a href="/menu" class="tp-btn tp-btn-primary">查看完整菜单</a>
    </div>
  </div>
</section>

<!-- Interior Section -->
<section class="tp-section tp-bg-white">
  <div class="tp-container">
    <div class="tp-interior-grid">
      <div class="tp-interior-text">
        <h2 class="tp-section-title">优雅的用餐环境</h2>
        <p>我们的餐厅融合了传统日式美学与现代设计理念，为您营造舒适优雅的用餐氛围。</p>
        <p>每个铁板烧台都经过精心设计，让您近距离欣赏大厨的精湛技艺。无论是商务宴请、家庭聚会还是浪漫约会，我们都能为您提供完美的用餐体验。</p>
        <a href="/kontakt" class="tp-btn tp-btn-dark">了解更多</a>
      </div>
      <div class="tp-interior-images">
        <div class="tp-interior-img-tall" style="background:#333 url('https://images.unsplash.com/photo-1651440204216-548382747b40?w=600&q=80') center/cover;"></div>
        <div class="tp-interior-img" style="background:#333 url('https://images.unsplash.com/photo-1666032119084-82351976a922?w=600&q=80') center/cover;"></div>
        <div class="tp-interior-img-wide" style="background:#333 url('https://images.unsplash.com/photo-1679312061521-d7d619a8cfb7?w=1200&q=80') center/cover;"></div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="tp-cta">
  <div class="tp-container">
    <div class="tp-cta-content">
      <h2>准备好开启您的美食之旅了吗？</h2>
      <p>立即预订，体验正宗的日本铁板烧料理</p>
      <div class="tp-cta-actions">
        <a href="/reservation" class="tp-btn tp-btn-white">在线预订</a>
        <a href="/vouchers" class="tp-btn tp-btn-outline-white">购买代金券</a>
      </div>
    </div>
  </div>
</section>
