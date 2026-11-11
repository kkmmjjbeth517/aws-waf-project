<?php
// config.php
$db_host = "";
$db_user = "";
$db_pass = "";
$db_name = "";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 세션 옵션(기본 예시)
if (session_status() === PHP_SESSION_NONE) {
    // 세션 하이재킹 줄이기 위한 httponly 쿠키 설정 예시
    ini_set('session.cookie_httponly', 1);
    session_start();
}
?>
