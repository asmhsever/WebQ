<?php
session_start();
if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Login | Smart School</title>

<style>
*{
    box-sizing:border-box;
    font-family: "Segoe UI", sans-serif;
}

body{
    margin:0;
    height:100vh;
    background:linear-gradient(135deg,#eaf1ff,#f8fbff);
    display:flex;
    align-items:center;
    justify-content:center;
}

/* ===== Card ===== */
.login-card{
    width:380px;
    background:#fff;
    border-radius:18px;
    padding:40px 35px;
    box-shadow:0 20px 50px rgba(0,0,0,.12);
    animation:fadeIn .8s ease;
}

/* ===== Header ===== */
.login-title{
    text-align:center;
    margin-bottom:30px;
}
.login-title h1{
    margin:0;
    font-size:28px;
    font-weight:700;
}
.login-title span{
    color:#2563eb;
}
.login-title p{
    margin-top:6px;
    font-size:14px;
    color:#666;
}

/* ===== Inputs ===== */
.input-group{
    margin-bottom:18px;
}
.input-group input{
    width:100%;
    padding:14px 16px;
    border-radius:12px;
    border:1px solid #ddd;
    font-size:15px;
    outline:none;
    transition:.2s;
}
.input-group input:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.15);
}

/* ===== Button ===== */
.login-btn{
    width:100%;
    margin-top:10px;
    padding:14px;
    border-radius:14px;
    border:none;
    font-size:16px;
    font-weight:600;
    background:#2563eb;
    color:#fff;
    cursor:pointer;
    transition:.2s;
}
.login-btn:hover{
    background:#1d4ed8;
}

/* ===== Footer ===== */
.login-footer{
    margin-top:24px;
    text-align:center;
    font-size:13px;
    color:#777;
}

/* ===== Animation ===== */
@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(20px);
    }
    to{
        opacity:1;
        transform:none;
    }
}
</style>
</head>

<body>

<form class="login-card" method="post" action="login_api.php">

    <div class="login-title">
        <h1>Smart <span>School</span></h1>
        <p>ระบบจัดการเข้าเรียนอัจฉริยะ</p>
    </div>

    <div class="input-group">
        <input type="text" name="username" placeholder="Username" required>
    </div>

    <div class="input-group">
        <input type="password" name="password" placeholder="Password" required>
    </div>

    <button class="login-btn" type="submit">
        เข้าสู่ระบบ
    </button>

    <div class="login-footer">
        © <?= date("Y") ?> Smart School System
    </div>

</form>

</body>
</html>
