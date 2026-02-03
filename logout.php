<?php
session_start();

/* ลบข้อมูล session ทั้งหมด */
$_SESSION = [];
session_destroy();

/* กลับไปหน้า login */
header("Location: login.php");
exit;
