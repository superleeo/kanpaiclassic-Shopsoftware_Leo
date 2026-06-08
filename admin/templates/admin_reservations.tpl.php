<?php
/* 管理后台：预订列表（简单草案）
   访问方式：在 admin 后台创建菜单项指向此模板或直接 include
*/
?>
<h1>预订管理</h1>

<?php if (empty($reservations)) { ?>
  <p>当前没有预订。</p>
<?php } else { ?>
  <table class="admin-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>姓名</th>
        <th>邮箱</th>
        <th>电话</th>
        <th>日期</th>
        <th>时间</th>
        <th>人数</th>
        <th>状态</th>
        <th>创建时间</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($reservations as $r) { ?>
      <tr>
        <td><?php echo htmlspecialchars($r['id']); ?></td>
        <td><?php echo htmlspecialchars($r['name']); ?></td>
        <td><?php echo htmlspecialchars($r['email']); ?></td>
        <td><?php echo htmlspecialchars($r['phone']); ?></td>
        <td><?php echo htmlspecialchars($r['date']); ?></td>
        <td><?php echo htmlspecialchars($r['time']); ?></td>
        <td><?php echo htmlspecialchars($r['persons']); ?></td>
        <td><?php echo htmlspecialchars($r['status']); ?></td>
        <td><?php echo htmlspecialchars($r['created_at']); ?></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
<?php } ?>
