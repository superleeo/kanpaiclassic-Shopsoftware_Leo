<?php
/* Menu page template - Miaowei Teppanyaki */
?>
<!-- Menu Hero -->
<section class="tp-page-hero">
  <div class="tp-page-hero-overlay"></div>
  <div class="tp-page-hero-content">
    <h1>菜单</h1>
    <p>精选日本料理，匠心呈现</p>
  </div>
</section>

<!-- Menu Content -->
<section class="tp-section tp-bg-white">
  <div class="tp-container">
    <!-- Category Navigation -->
    <div class="tp-menu-nav">
      <a href="#teppanyaki" class="tp-menu-nav-item active">铁板烧套餐</a>
      <a href="#sushi" class="tp-menu-nav-item">寿司刺身</a>
      <a href="#appetizers" class="tp-menu-nav-item">前菜小食</a>
      <a href="#drinks" class="tp-menu-nav-item">饮品酒水</a>
    </div>

    <!-- Menu Items Grid -->
    <div id="teppanyaki" class="tp-menu-section">
      <h2 class="tp-menu-cat-title">铁板烧套餐 <span class="tp-menu-cat-sub">Teppanyaki Set</span></h2>
      <div class="tp-menu-grid">
        <?php for($i=0;$i<6;$i++): $items = [
          ['name'=>'A5和牛至尊套餐','desc'=>'200g顶级A5和牛，配时令蔬菜、米饭、味增汤','price'=>'€89','badge'=>'招牌'],
          ['name'=>'龙虾铁板烧套餐','desc'=>'整只龙虾、黄油蒜香、时令蔬菜、米饭、汤','price'=>'€75','badge'=>'人气'],
          ['name'=>'海陆双拼套餐','desc'=>'150g和牛+半只龙虾，配蔬菜、米饭、汤','price'=>'€95','badge'=>''],
          ['name'=>'鲜虾扇贝套餐','desc'=>'大虾6只、扇贝4只，配时令蔬菜、米饭、汤','price'=>'€58','badge'=>''],
          ['name'=>'鸡肉铁板烧套餐','desc'=>'250g鸡胸肉、照烧酱、蔬菜、米饭、汤','price'=>'€42','badge'=>''],
          ['name'=>'素食铁板烧套餐','desc'=>'时令蔬菜、豆腐、蘑菇、米饭、汤','price'=>'€35','badge'=>''],
        ]; ?>
        <div class="tp-menu-item">
          <?php if($items[$i]['badge']): ?><span class="tp-badge"><?php echo $items[$i]['badge']; ?></span><?php endif; ?>
          <div class="tp-menu-item-header">
            <h3><?php echo $items[$i]['name']; ?></h3>
            <span class="tp-price"><?php echo $items[$i]['price']; ?></span>
          </div>
          <p class="tp-menu-item-desc"><?php echo $items[$i]['desc']; ?></p>
        </div>
        <?php endfor; ?>
      </div>
    </div>

    <div id="sushi" class="tp-menu-section">
      <h2 class="tp-menu-cat-title">寿司刺身 <span class="tp-menu-cat-sub">Sushi & Sashimi</span></h2>
      <div class="tp-menu-grid">
        <?php $sushi = [
          ['name'=>'特级刺身拼盘','desc'=>'金枪鱼、三文鱼、鲷鱼、章鱼等8种刺身','price'=>'€68','badge'=>'招牌'],
          ['name'=>'寿司豪华拼盘','desc'=>'12贯握寿司+8卷寿司，含金枪鱼、三文鱼等','price'=>'€55','badge'=>''],
          ['name'=>'三文鱼套餐','desc'=>'三文鱼刺身、握寿司、卷寿司组合','price'=>'€42','badge'=>''],
          ['name'=>'金枪鱼套餐','desc'=>'金枪鱼刺身、握寿司、卷寿司组合','price'=>'€48','badge'=>''],
        ];
        foreach($sushi as $item): ?>
        <div class="tp-menu-item">
          <?php if($item['badge']): ?><span class="tp-badge"><?php echo $item['badge']; ?></span><?php endif; ?>
          <div class="tp-menu-item-header">
            <h3><?php echo $item['name']; ?></h3>
            <span class="tp-price"><?php echo $item['price']; ?></span>
          </div>
          <p class="tp-menu-item-desc"><?php echo $item['desc']; ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div id="appetizers" class="tp-menu-section">
      <h2 class="tp-menu-cat-title">前菜小食 <span class="tp-menu-cat-sub">Appetizers</span></h2>
      <div class="tp-menu-grid">
        <?php $apps = [
          ['name'=>'和牛寿喜烧','desc'=>'薄切和牛、蔬菜、寿喜烧汁','price'=>'€28'],
          ['name'=>'日式烤鳗鱼','desc'=>'蒲烧鳗鱼、秘制酱汁','price'=>'€22'],
          ['name'=>'天妇罗拼盘','desc'=>'虾、蔬菜天妇罗，配特制酱汁','price'=>'€18'],
          ['name'=>'毛豆','desc'=>'盐水毛豆','price'=>'€5'],
          ['name'=>'日式饺子','desc'=>'煎饺6个','price'=>'€8'],
        ];
        foreach($apps as $item): ?>
        <div class="tp-menu-item">
          <div class="tp-menu-item-header">
            <h3><?php echo $item['name']; ?></h3>
            <span class="tp-price"><?php echo $item['price']; ?></span>
          </div>
          <p class="tp-menu-item-desc"><?php echo $item['desc']; ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div id="drinks" class="tp-menu-section">
      <h2 class="tp-menu-cat-title">饮品酒水 <span class="tp-menu-cat-sub">Drinks</span></h2>
      <div class="tp-menu-grid">
        <?php $drinks = [
          ['name'=>'清酒套餐','desc'=>'精选日本清酒三种品鉴','price'=>'€35'],
          ['name'=>'梅酒','desc'=>'传统日本梅酒','price'=>'€12'],
          ['name'=>'朝日啤酒','desc'=>'日本进口啤酒500ml','price'=>'€8'],
          ['name'=>'绿茶','desc'=>'热/冷日本绿茶','price'=>'€4'],
          ['name'=>'软饮','desc'=>'可乐、雪碧、橙汁','price'=>'€4'],
        ];
        foreach($drinks as $item): ?>
        <div class="tp-menu-item">
          <div class="tp-menu-item-header">
            <h3><?php echo $item['name']; ?></h3>
            <span class="tp-price"><?php echo $item['price']; ?></span>
          </div>
          <p class="tp-menu-item-desc"><?php echo $item['desc']; ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Info Box -->
    <div class="tp-info-box">
      <h3>重要提示</h3>
      <div class="tp-info-grid">
        <div>
          <h4>过敏原信息</h4>
          <p>我们的菜品可能包含常见过敏原。如有食物过敏或特殊饮食需求，请在点餐时告知服务员。</p>
        </div>
        <div>
          <h4>价格说明</h4>
          <p>所有价格均含德国增值税(MwSt.)。价格可能随季节和食材供应情况调整。</p>
        </div>
        <div>
          <h4>儿童餐</h4>
          <p>我们提供适合儿童的特别菜单，详情请咨询服务员。</p>
        </div>
        <div>
          <h4>外带服务</h4>
          <p>部分菜品提供外带服务，请提前电话预订。</p>
        </div>
      </div>
    </div>
  </div>
</section>
