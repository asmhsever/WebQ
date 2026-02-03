<?php
date_default_timezone_set("Asia/Bangkok");
$db = new mysqli("localhost","root","","smart_school");
$db->set_charset("utf8");

/* ---------- รับรูป ---------- */
if (!isset($_POST['image'])) die("❌ no image");

$image = $_POST['image'];
$imgData = explode(",", $image)[1];
$imgBin  = base64_decode($imgData);

/* ---------- เรียก Python ---------- */
$ch = curl_init("http://127.0.0.1:8000/scan");
curl_setopt_array($ch,[
    CURLOPT_RETURNTRANSFER=>true,
    CURLOPT_POST=>true,
    CURLOPT_HTTPHEADER=>["Content-Type: application/json"],
    CURLOPT_POSTFIELDS=>json_encode(["image"=>$image])
]);
$res = curl_exec($ch);
curl_close($ch);

$result = json_decode($res,true);
if(!$result || !$result['found']) die("❌ ไม่รู้จัก");

$student_id = $result['student_id'];
$name = $result['name'];

$today = date("Y-m-d");
$now   = date("Y-m-d H:i:s");
$time  = date("H:i:s");

/* ---------- เช็ควันนี้ ---------- */
$chk = $db->prepare("
    SELECT id, scan_time, out_time 
    FROM attendance 
    WHERE student_id=? AND DATE(scan_time)=?
");
$chk->bind_param("is",$student_id,$today);
$chk->execute();
$r = $chk->get_result();

/* ===== เคยเข้าแล้ว ===== */
if($row = $r->fetch_assoc()){

    if($row['out_time']===NULL){
        // ---- บันทึกออก ----
        $status="กลับบ้าน";
        $up=$db->prepare("
            UPDATE attendance 
            SET out_time=?,status=? 
            WHERE id=?
        ");
        $up->bind_param("ssi",$now,$status,$row['id']);
        $up->execute();

        echo "👋 {$name}\nออกเวลา ".date("H:i");
        exit;
    }

    die("⚠️ วันนี้สแกนครบแล้ว");
}

/* ===== เข้าเรียน ===== */
if($time<="08:30:00")      $status="ปกติ";
elseif($time<="09:00:00") $status="มาสาย";
else                       $status="สายมาก";

/* ---------- เซฟรูป ---------- */
$filename="face_".time()."_".rand(1000,9999).".jpg";
$dir=__DIR__."/uploads/";
if(!is_dir($dir)) mkdir($dir,0777,true);
file_put_contents($dir.$filename,$imgBin);

/* ---------- บันทึก DB ---------- */
$ins=$db->prepare("
    INSERT INTO attendance(student_id,scan_time,status,face_image)
    VALUES (?,?,?,?)
");
$ins->bind_param("isss",$student_id,$now,$status,$filename);
$ins->execute();

echo "✅ {$name}\nเข้าเวลา ".date("H:i");
