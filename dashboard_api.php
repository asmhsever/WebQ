<?php
$db = new mysqli("localhost","root","","smart_school");
$db->set_charset("utf8");

/* ===== ฟังก์ชันวันที่ไทย ===== */
function thaiDate($date){
    $m=["","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.",
        "ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค."];
    return date("j",strtotime($date))." ".$m[(int)date("n",strtotime($date))]." ".(date("Y",strtotime($date))+543);
}

/* ===== Query ===== */
$sql="
SELECT s.fullname,a.status,a.scan_time,a.out_time,a.face_image
FROM attendance a
JOIN students s ON s.id=a.student_id
ORDER BY a.scan_time DESC
LIMIT 20
";
$r=$db->query($sql);

$base="http://localhost/smart-school/uploads/";

while($row=$r->fetch_assoc()){

    /* สีสถานะ */
    $color="#4caf50";
    if($row['status']=="มาสาย") $color="#ff9800";
    if($row['status']=="กลับบ้าน") $color="#f44336";

    /* รูป */
    $img=$row['face_image']
        ? $base.$row['face_image']
        : "https://via.placeholder.com/90";

    /* เวลา */
    $in=date("H:i",strtotime($row['scan_time']));
    $out=$row['out_time'] ? date("H:i",strtotime($row['out_time'])) : "--:--";

    /* วันที่ */
    $dateThai=thaiDate($row['scan_time']);

    echo "
    <div class='card'>
        <img class='avatar' src='{$img}'
             onerror=\"this.src='https://via.placeholder.com/90'\"
             style='border-color:{$color}'>

        <div class='status' style='color:{$color}'>{$row['status']}</div>
        <div class='name'>{$row['fullname']}</div>

        <div class='times'>
            <div class='time-box'>
                <div class='label'>เข้า</div>
                <div class='value'>{$in}</div>
            </div>
            <div class='time-box'>
                <div class='label'>ออก</div>
                <div class='value'>{$out}</div>
            </div>
        </div>

        <div class='date-box'>วันที่ {$dateThai}</div>
    </div>
    ";
}
