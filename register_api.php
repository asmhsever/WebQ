<?php
session_start();

/* =========================
   ตรวจข้อมูลพื้นฐาน
========================= */
if (!isset($_POST['name'], $_POST['image'])) {
    die("ข้อมูลไม่ครบ");
}

$name  = $_POST['name'];
$image = $_POST['image'];

/* =========================
   ส่งรูปไป Python (/register)
========================= */
$data = json_encode([
    "image" => $image
]);

$ch = curl_init("http://127.0.0.1:8000/register");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    CURLOPT_POSTFIELDS => $data
]);

$res = curl_exec($ch);
curl_close($ch);

$result = json_decode($res, true);

if (!$result || !isset($result['success']) || !$result['success']) {
    die("❌ ไม่พบใบหน้า");
}

/* =========================
   เก็บ embedding ลง session
========================= */
if (!isset($_SESSION['embeddings'])) {
    $_SESSION['embeddings'] = [];
}

$_SESSION['embeddings'][] = $result['embedding'];

$currentCount = count($_SESSION['embeddings']);
$targetCount  = 5;

/* =========================
   ถ้ายังไม่ครบ 5 ครั้ง
========================= */
if ($currentCount < $targetCount) {
    $remain = $targetCount - $currentCount;
    die("📸 กรุณาสแกนอีก {$remain} ครั้ง");
}

/* =========================
   ฟังก์ชันเฉลี่ย embedding
========================= */
function averageEmbedding($embeddings) {
    $count  = count($embeddings);
    $length = count($embeddings[0]);

    $avg = array_fill(0, $length, 0);

    foreach ($embeddings as $emb) {
        for ($i = 0; $i < $length; $i++) {
            $avg[$i] += $emb[$i];
        }
    }

    for ($i = 0; $i < $length; $i++) {
        $avg[$i] /= $count;
    }

    return $avg;
}

/* =========================
   เฉลี่ย embedding
========================= */
$finalEmbedding = averageEmbedding($_SESSION['embeddings']);
$embeddingJson  = json_encode($finalEmbedding);

/* =========================
   บันทึกลง Database
========================= */
$db = new mysqli("localhost", "root", "", "smart_school");
if ($db->connect_error) {
    die("❌ DB connection failed");
}

$stmt = $db->prepare(
    "INSERT INTO students (fullname, embedding) VALUES (?, ?)"
);
$stmt->bind_param("ss", $name, $embeddingJson);
$stmt->execute();

/* =========================
   ล้าง session
========================= */
unset($_SESSION['embeddings']);

/* =========================
   เสร็จสมบูรณ์
========================= */
echo "✅ บันทึกใบหน้าสำเร็จ (เฉลี่ย {$targetCount} ภาพ)";
