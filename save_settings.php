<?php
$db = new mysqli("localhost","root","","smart_school");
$db->set_charset("utf8");

$data = json_decode(file_get_contents("php://input"), true);

$start = $data['start'];
$late  = $data['late'];
$end   = $data['end'];
$close = $data['close'];
$days  = json_encode($data['days'], JSON_UNESCAPED_UNICODE);

$stmt = $db->prepare("
    UPDATE settings SET
    start_time=?, late_time=?, end_time=?, close_time=?, days=?
    WHERE id=1
");
$stmt->bind_param("sssss",$start,$late,$end,$close,$days);
$stmt->execute();

echo "ok";
// ===== Load settings =====
$set = $db->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();

$nowTime = date("H:i:s");
$today   = date("D"); // Mon Tue Wed
$days    = json_decode($set['days'], true);

// ❌ ไม่ใช่วันเรียน
if(!in_array($today,$days)){
    echo "วันนี้ไม่มีการเรียนการสอน";
    exit;
}

// ❌ ปิดระบบ
if($nowTime > $set['close_time']){
    echo "หมดเวลาแสกน";
    exit;
}

// 🟢 ปกติ / 🟠 สาย
$status = "เข้าเรียน";
if($nowTime >= $set['late_time']){
    $status = "มาสาย";
}
if($nowTime >= $set['end_time']){
    $status = "กลับบ้าน";
}
