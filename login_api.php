<?php
session_start();
$db = new mysqli("localhost","root","","smart_school");
$db->set_charset("utf8");

$user = $_POST['username'];
$pass = hash("sha256", $_POST['password']);

$sql = "SELECT * FROM users WHERE username=? AND password=?";
$stmt = $db->prepare($sql);
$stmt->bind_param("ss",$user,$pass);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows){
    $u = $res->fetch_assoc();
    $_SESSION['user_id'] = $u['id'];
    $_SESSION['fullname'] = $u['fullname'];

    header("Location: dashboard.php");
    exit;
}else{
    echo "<script>alert('Username หรือ Password ไม่ถูกต้อง');history.back();</script>";
}
