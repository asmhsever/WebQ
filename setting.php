<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ตั้งค่าระบบ | Smart School</title>

<style>
body{
    margin:0;
    font-family:"Segoe UI",sans-serif;
    background:#f4f6f8;
}

/* ===== Container ===== */
.wrapper{
    max-width:1100px;
    margin:40px auto;
    background:#fff;
    border-radius:18px;
    box-shadow:0 20px 40px rgba(0,0,0,.08);
    padding:30px 36px;
    animation:fadeUp .7s ease;
}
@keyframes fadeUp{
    from{opacity:0;transform:translateY(20px);}
    to{opacity:1;transform:none;}
}

/* ===== Title ===== */
h1{
    margin:0 0 24px;
    font-size:28px;
}

/* ===== Section ===== */
.section{
    margin-bottom:40px;
}
.section-title{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:20px;
    font-weight:700;
    margin-bottom:18px;
    color:#1e3a8a;
}

/* ===== Time Grid ===== */
.time-grid{
    display:grid;
    grid-template-columns:repeat(4, minmax(220px, 1fr));
    gap:32px;
}
.time-box{
    background:#f9fafb;
    padding:16px;
    border-radius:16px;
}
.time-box label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
    color:#444;
}
.time-box input{
    width:100%;
    padding:12px 14px;
    font-size:16px;
    border-radius:12px;
    border:1px solid #ddd;
    transition:.2s;
}
.time-box input:focus{
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.15);
}

/* ===== Day Buttons ===== */
.days{
    display:grid;
    grid-template-columns:repeat(7,1fr);
    gap:12px;
}
.day-btn{
    padding:14px 0;
    border-radius:14px;
    border:none;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    background:#e5e7eb;
    color:#333;
    transition:.2s;
}
.day-btn.active{
    background:#2563eb;
    color:#fff;
}
.day-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 4px 10px rgba(0,0,0,.08);
}

/* ===== Note ===== */
.note{
    margin-top:14px;
    font-size:14px;
    color:#666;
}

/* ===== Footer Buttons ===== */
.footer{
    display:flex;
    justify-content:flex-end;
    gap:16px;
    margin-top:36px;
}
.btn{
    padding:12px 22px;
    border-radius:12px;
    font-size:15px;
    font-weight:600;
    border:none;
    cursor:pointer;
    transition:.2s;
}
.btn-save{
    background:#10b981;
    color:#fff;
}
.btn-save:hover{
    background:#059669;
}
.btn-back{
    background:#2563eb;
    color:#fff;
}
.btn-back:hover{
    background:#1d4ed8;
}

/* ===== Responsive ===== */
@media (max-width:900px){
    .time-grid{
        grid-template-columns:repeat(2,1fr);
    }
    .days{
        grid-template-columns:repeat(4,1fr);
    }
}
@media (max-width:500px){
    .time-grid{
        grid-template-columns:1fr;
    }
    .days{
        grid-template-columns:repeat(2,1fr);
    }
    .footer{
        flex-direction:column;
        align-items:stretch;
    }
}
</style>
</head>

<body>

<div class="wrapper">

    <h1>⚙️ ตั้งค่าระบบ</h1>

    <!-- ===== เวลาแสกน ===== -->
    <div class="section">
        <div class="section-title">⏰ กำหนดเวลาแสกน</div>

        <div class="time-grid">
            <div class="time-box">
                <label>เวลาเริ่มเรียน (ปกติ)</label>
                <input type="time" value="08:00" min="00:00" max="23:59" step="60">
            </div>

            <div class="time-box">
                <label>เริ่มนับสายตั้งแต่</label>
                <input type="time" value="08:30" min="00:00" max="23:59" step="60">
            </div>

            <div class="time-box">
                <label>เวลาเลิกเรียน (สแกนออกได้)</label>
                <input type="time" value="16:00" min="00:00" max="23:59" step="60">
            </div>

            <div class="time-box">
                <label>ปิดระบบแสกน</label>
                <input type="time" value="18:00" min="00:00" max="23:59" step="60">
            </div>
        </div>
    </div>

    <!-- ===== วันเรียน ===== -->
    <div class="section">
        <div class="section-title">📅 วันที่มีการเรียนการสอน (เปิดสแกน)</div>

        <div class="days">
            <button class="day-btn active">วันจันทร์</button>
            <button class="day-btn active">วันอังคาร</button>
            <button class="day-btn active">วันพุธ</button>
            <button class="day-btn active">วันพฤหัสบดี</button>
            <button class="day-btn active">วันศุกร์</button>
            <button class="day-btn">วันเสาร์</button>
            <button class="day-btn">วันอาทิตย์</button>
        </div>

        <div class="note">
            * หากไม่เลือกวัน ระบบจะแจ้งเตือนว่า “วันนี้ไม่มีการเรียนการสอน” เมื่อสแกน
        </div>
    </div>

    <!-- ===== Buttons ===== -->
    <div class="footer">
        <button class="btn btn-back" onclick="location.href='dashboard.php'">
            ⬅ กลับหน้า Dashboard
        </button>
        <button class="btn btn-save">
            💾 บันทึกการตั้งค่า
        </button>
    </div>

</div>

<script>
document.querySelectorAll(".day-btn").forEach(btn=>{
    btn.onclick=()=>btn.classList.toggle("active");
});

document.querySelector(".btn-save").onclick = () => {
    const times = document.querySelectorAll("input[type=time]");
    const days = [];

    document.querySelectorAll(".day-btn.active").forEach(d=>{
        const map={
            "วันจันทร์":"Mon","วันอังคาร":"Tue","วันพุธ":"Wed",
            "วันพฤหัสบดี":"Thu","วันศุกร์":"Fri",
            "วันเสาร์":"Sat","วันอาทิตย์":"Sun"
        };
        days.push(map[d.innerText]);
    });

    fetch("save_settings.php",{
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body:JSON.stringify({
            start:times[0].value,
            late: times[1].value,
            end:  times[2].value,
            close:times[3].value,
            days:days
        })
    }).then(()=>alert("บันทึกเรียบร้อย"));
};
</script>

</body>
</html>
