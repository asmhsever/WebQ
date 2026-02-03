<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ลงทะเบียนใบหน้า | Smart School</title>

<style>
/* ===== Base ===== */
body{
    margin:0;
    font-family:"Segoe UI",sans-serif;
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* ===== Card ===== */
.card{
    background:#fff;
    width:420px;
    padding:28px;
    border-radius:22px;
    box-shadow:0 20px 40px rgba(0,0,0,.12);
    animation:fadeUp .8s ease;
}
@keyframes fadeUp{
    from{opacity:0;transform:translateY(30px);}
    to{opacity:1;transform:none;}
}

/* ===== Header ===== */
.card h2{
    margin:0;
    text-align:center;
    font-size:26px;
}
.card small{
    display:block;
    text-align:center;
    color:#666;
    margin-bottom:18px;
}

/* ===== Input ===== */
input{
    width:100%;
    padding:12px 14px;
    font-size:16px;
    border-radius:12px;
    border:1px solid #ddd;
    margin-bottom:16px;
}
input:focus{
    outline:none;
    border-color:#2563eb;
}

/* ===== Video ===== */
video{
    width:100%;
    border-radius:16px;
    background:#000;
    margin-bottom:16px;
}

/* ===== Buttons ===== */
button{
    width:100%;
    padding:12px;
    font-size:16px;
    font-weight:600;
    border:none;
    border-radius:14px;
    cursor:pointer;
    transition:.2s;
}

.btn-save{
    background:#10b981;
    color:#fff;
    margin-bottom:10px;
}
.btn-save:hover{background:#059669;}

.btn-back{
    background:#2563eb;
    color:#fff;
}
.btn-back:hover{background:#1d4ed8;}
</style>
</head>

<body>

<div class="card">

    <h2>📸 ลงทะเบียนใบหน้า</h2>
    <small>บันทึกใบหน้าเพื่อใช้งานระบบ Smart School</small>

    <!-- ❌ logic เดิม -->
    <input id="name" placeholder="ชื่อนักเรียน">

    <video id="video" autoplay></video>
    <canvas id="canvas" style="display:none"></canvas>

    <!-- ❌ logic เดิม -->
    <button class="btn-save" onclick="register()">💾 บันทึกใบหน้า</button>

    <!-- ✅ เพิ่มปุ่มกลับ Dashboard -->
    <button class="btn-back" onclick="location.href='dashboard.php'">
        ⬅ กลับไปหน้า Dashboard
    </button>

</div>

<script>
const video = document.getElementById("video");
const canvas = document.getElementById("canvas");

navigator.mediaDevices.getUserMedia({ video: true })
.then(stream => video.srcObject = stream);

function register(){
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;
  canvas.getContext("2d").drawImage(video,0,0);

  const image = canvas.toDataURL("image/jpeg");
  const name  = document.getElementById("name").value;

  fetch("register_api.php",{
    method:"POST",
    headers:{
      "Content-Type":"application/x-www-form-urlencoded"
    },
    body:
      "name=" + encodeURIComponent(name) +
      "&image=" + encodeURIComponent(image)
  })
  .then(r=>r.text())
  .then(d=>alert(d));
}
</script>

</body>
</html>
