<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Smart School Dashboard</title>

<style>
/* ===== Base ===== */
body{
    margin:0;
    font-family:"Segoe UI",sans-serif;
    background:#f4f6f8;
    overflow-x:hidden;
}

/* ===== Page Animation (ครั้งเดียว) ===== */
.page-animate{
    animation:fadeUp .8s ease;
}
@keyframes fadeUp{
    from{opacity:0;transform:translateY(20px);}
    to{opacity:1;transform:none;}
}

/* ===== Header ===== */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 40px;
    background:#fff;
    box-shadow:0 2px 8px rgba(0,0,0,.06);
}
.header-left h1{margin:0;font-size:26px;}
.header-left small{color:#666;}
.header-right{text-align:right;}
.clock{font-size:36px;font-weight:700;}
.date{font-size:14px;color:#555;}

/* ===== Layout ===== */
.container{
    display:grid;
    grid-template-columns:360px 1fr;
    gap:30px;
    padding:30px 40px;
}

/* ===== Scan Box ===== */
.scan-box{
    background:#fff;
    border-radius:20px;
    padding:20px;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
}
.scan-box video{
    width:100%;
    border-radius:14px;
    background:#000;
}
.scan-btn{
    width:100%;
    margin-top:12px;
    padding:12px;
    font-size:18px;
    font-weight:600;
    border:none;
    border-radius:14px;
    background:#2563eb;
    color:#fff;
    cursor:pointer;
}
.scan-btn:hover{background:#1d4ed8;}

.register-btn{
    width:100%;
    margin-top:10px;
    padding:12px;
    font-size:16px;
    font-weight:600;
    border:none;
    border-radius:14px;
    background:#10b981;
    color:#fff;
    cursor:pointer;
}
.register-btn:hover{background:#059669;}

/* ===== Grid ===== */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
    gap:24px;
}

/* ===== Card ===== */
.card{
    background:#fff;
    border-radius:22px;
    padding:22px;
    box-shadow:0 6px 14px rgba(0,0,0,.08);
    text-align:center;
}
.card.animate{
    animation:cardFade .4s ease;
}
@keyframes cardFade{
    from{opacity:0;transform:scale(.97);}
    to{opacity:1;transform:none;}
}

.avatar{
    width:110px;
    height:110px;
    border-radius:50%;
    border:5px solid #4caf50;
    object-fit:cover;
}
.status{margin-top:10px;font-size:18px;font-weight:700;}
.name{margin-top:6px;font-size:22px;font-weight:700;}
.times{
    margin-top:16px;
    background:#f1f5f9;
    border-radius:14px;
    padding:14px;
    display:flex;
    justify-content:space-between;
}
.time-box{width:48%;}
.label{font-size:14px;color:#555;}
.value{font-size:26px;font-weight:700;}
.date-box{margin-top:12px;font-size:15px;color:#444;}

/* ===== Big Screen ===== */
body.big-mode{zoom:1.2;}
body.big-mode .avatar{width:140px;height:140px;}
body.big-mode .value{font-size:32px;}
body.big-mode::-webkit-scrollbar{display:none;}

/* ===== Buttons ===== */
.bigmode-btn{
    padding:8px 18px;
    border:none;
    border-radius:12px;
    background:#2563eb;
    color:#fff;
    font-weight:600;
    cursor:pointer;
}

.user-box{
    display:flex;
    align-items:center;
    gap:14px;
    margin:20px 40px;
}
.logout-btn{
    padding:8px 14px;
    background:#ef4444;
    color:#fff;
    border-radius:10px;
    text-decoration:none;
}
.logout-btn:hover{background:#dc2626;}
.setting-btn{
    padding:8px 18px;
    border:none;
    border-radius:12px;
    background:#10b981; /* เขียว */
    color:#fff;
    font-weight:600;
    cursor:pointer;
    margin-right:10px;
    transition:.2s;
}

.setting-btn:hover{
    background:#059669;
}
</style>
</head>

<body>
<div class="page-animate">

<!-- ===== HEADER ===== -->
<div class="header">
    <div class="header-left">
        <h1>Smart School</h1>
        <small>ระบบติดตามการเข้าเรียนเรียลไทม์</small>
    </div>
    <div class="header-right">
    <div class="clock" id="clock">--:--:--</div>
    <div class="date" id="date"></div>

    <div style="margin-top:8px;">
        <a href="setting.php">
            <button class="setting-btn">⚙️ ตั้งค่าระบบ</button>
        </a>
        <button class="bigmode-btn" id="bigModeBtn">🖥️ โหมดจอใหญ่</button>
    </div>
</div>

</div>

<!-- ===== CONTENT ===== -->
<div class="container">

    <!-- ===== SCAN ===== -->
    <div class="scan-box">
        <h3>📷 สแกนใบหน้า</h3>
        <video id="video" autoplay></video>
        <canvas id="canvas" hidden></canvas>
        <button class="scan-btn" onclick="scan()">สแกน</button>
        <button class="register-btn" onclick="location.href='register.php'">
            ➕ ลงทะเบียนใบหน้า
        </button>
    </div>

    <!-- ===== DASHBOARD ===== -->
    <div class="grid" id="grid"></div>

</div>

<div class="user-box">
    <strong><?= $_SESSION['fullname'] ?></strong>
    <a href="logout.php" class="logout-btn">ออกจากระบบ</a>
</div>

</div>

<script>
/* ===== Clock ===== */
function updateClock(){
    const d=new Date();
    clock.textContent=d.toLocaleTimeString("th-TH");
    date.textContent=d.toLocaleDateString("th-TH",
        {weekday:"long",year:"numeric",month:"long",day:"numeric"});
}
setInterval(updateClock,1000);
updateClock();

/* ===== Big Mode ===== */
bigModeBtn.onclick=()=>document.body.classList.toggle("big-mode");

/* ===== Camera ===== */
navigator.mediaDevices.getUserMedia({video:true})
.then(s=>video.srcObject=s);

/* ===== Scan ===== */
function scan(){
    canvas.width=video.videoWidth;
    canvas.height=video.videoHeight;
    canvas.getContext("2d").drawImage(video,0,0);

    fetch("scan_api.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:"image="+encodeURIComponent(canvas.toDataURL("image/jpeg"))
    }).then(()=>{
        loadData(true); // animate เฉพาะตอนสแกน
    });
}

/* ===== Load Dashboard ===== */
let firstLoad=true;
function loadData(animate=false){
    fetch("dashboard_api.php")
    .then(r=>r.text())
    .then(html=>{
        grid.innerHTML=html;
        if(firstLoad || animate){
            document.querySelectorAll(".card")
                .forEach(c=>c.classList.add("animate"));
            firstLoad=false;
        }
    });
}

loadData();
setInterval(()=>loadData(false),2000);
</script>

</body>
</html>
