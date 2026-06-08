<?php
/* Merch/Shop page template - Miaowei Teppanyaki */
?>
<!-- Shop Hero -->
<section class="tp-page-hero">
  <div class="tp-page-hero-overlay"></div>
  <div class="tp-page-hero-content">
    <h1>周边商品</h1>
    <p>精选日本料理用品与食材</p>
  </div>
</section>

<!-- Shop Content -->
<section class="tp-section tp-bg-white">
  <div class="tp-container">
    <div class="tp-shop-grid">
      <?php
      $products = [
        ['name'=>'清酒套装','desc'=>'精选三款日本清酒，含酒杯','price'=>'€89.99','icon'=>'🍶'],
        ['name'=>'铁板烧酱料套装','desc'=>'照烧酱、蒜香黄油酱、柚子酱','price'=>'€24.99','icon'=>'🥫'],
        ['name'=>'陶瓷餐具套装','desc'=>'手工陶瓷碗、盘、杯6件套','price'=>'€129.99','icon'=>'🍽️'],
        ['name'=>'高级寿司刀','desc'=>'日本进口专业寿司刀，含刀套','price'=>'€189.99','icon'=>'🔪'],
        ['name'=>'抹茶粉礼盒','desc'=>'京都宇治抹茶粉100g，附茶筅','price'=>'€34.99','icon'=>'🍵'],
        ['name'=>'日本绿茶组合','desc'=>'煎茶、玄米茶、焙茶三种口味','price'=>'€29.99','icon'=>'🍃'],
        ['name'=>'筷子礼盒套装','desc'=>'黑檀木筷子5双，精美包装','price'=>'€45.99','icon'=>'🥢'],
        ['name'=>'寿司制作套装','desc'=>'寿司帘、模具、勺子等全套工具','price'=>'€79.99','icon'=>'🍣'],
      ];
      foreach($products as $p): ?>
      <div class="tp-shop-card">
        <div class="tp-shop-card-img"><?php echo $p['icon']; ?></div>
        <div class="tp-shop-card-body">
          <h3><?php echo $p['name']; ?></h3>
          <p><?php echo $p['desc']; ?></p>
          <div class="tp-shop-card-footer">
            <span class="tp-price"><?php echo $p['price']; ?></span>
            <a href="/kategorie" class="tp-btn tp-btn-primary">加入购物车</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="tp-info-box" style="margin-top:3rem;">
      <h3>配送信息</h3>
      <div class="tp-info-grid">
        <div style="text-align:center;">
          <h4>德国境内配送</h4>
          <p>订单满€50免运费<br>3-5个工作日送达</p>
        </div>
        <div style="text-align:center;">
          <h4>退换货政策</h4>
          <p>14天无理由退货<br>商品需保持原包装</p>
        </div>
        <div style="text-align:center;">
          <h4>支付方式</h4>
          <p>支持多种支付方式<br>安全可靠，受法律保护</p>
        </div>
      </div>
    </div>
  </div>
</section>
