<?php
require_once "config.php";

// 로그인 체크
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die("접근 거부: 로그인 필요");
}

// 요청된 파일명 (예: ?file=1730459012_user1.jpg)
$filename = basename($_GET['file'] ?? '');
if ($filename === '') {
    http_response_code(400);
    die("파일명이 지정되지 않았습니다.");
}

// DB에서 사용자의 이미지 파일명 조회
$stmt = $conn->prepare("SELECT profile_image FROM members WHERE id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($stored_path);
$stmt->fetch();
$stmt->close();

if (!$stored_path) {
    http_response_code(404);
    die("이미지를 찾을 수 없습니다.");
}

// 사용자가 요청한 파일이 자신의 이미지인지 확인
if (basename($stored_path) !== $filename) {
    http_response_code(403);
    die("자신의 이미지 외에는 접근할 수 없습니다.");
}

// 실제 파일 경로
$real_path = __DIR__ . "/" . $stored_path;

// 파일 존재 여부 확인
if (!file_exists($real_path)) {
    http_response_code(404);
    die("파일이 존재하지 않습니다.");
}

// MIME 타입 설정 (jpg/png/gif 자동 판별)
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$content_type = finfo_file($finfo, $real_path);
finfo_close($finfo);

header("Content-Type: $content_type");
header("Content-Length: " . filesize($real_path));

// 파일 출력
readfile($real_path);
exit;
?>
