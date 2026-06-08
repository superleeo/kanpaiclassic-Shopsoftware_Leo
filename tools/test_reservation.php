<?php
// 使用方法：在运行前设置环境变量 DB_HOST, DB_USER, DB_PASS, DB_NAME
// 例如： DB_HOST=127.0.0.1 DB_USER=root DB_PASS=secret DB_NAME=flow_shop_test php tools/test_reservation.php

$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'flow_shop_test';
$port = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306;

$mysqli = new mysqli($host, $user, $pass, $db, $port);
if ($mysqli->connect_errno) {
    echo "连接数据库失败: (".$mysqli->connect_errno.") ".$mysqli->connect_error."\n";
    exit(1);
}

// 插入测试预订
$name = $mysqli->real_escape_string('测试用户');
$email = $mysqli->real_escape_string('test@example.com');
$phone = $mysqli->real_escape_string('+49 123456789');
$date = date('Y-m-d', strtotime('+3 days'));
$time = '19:30:00';
$persons = 4;
$notes = $mysqli->real_escape_string('自动化测试预订');

$sql = "INSERT INTO `reservations` (`name`,`email`,`phone`,`date`,`time`,`persons`,`notes`,`status`) VALUES ('{$name}','{$email}','{$phone}','{$date}','{$time}',{$persons},'{$notes}','pending')";

if (!$mysqli->query($sql)) {
    echo "插入失败: (".$mysqli->errno.") ".$mysqli->error."\n";
    exit(1);
}

$insertId = $mysqli->insert_id;
echo "插入成功，ID={$insertId}\n";

$res = $mysqli->query("SELECT * FROM `reservations` WHERE id = " . (int)$insertId);
if ($res && $row = $res->fetch_assoc()) {
    echo "查询到记录:\n";
    print_r($row);
}
else {
    echo "查询失败或记录不存在\n";
}

$mysqli->close();

return 0;
