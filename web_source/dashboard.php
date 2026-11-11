<?php
require_once "config.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit;
}

$username = $_SESSION['username'];

// DB에서 파일명 읽기
$stmt = $conn->prepare("SELECT profile_image FROM members WHERE id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($profile_path);
$stmt->fetch();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title>대시보드</title></head>
<body>
<h1><?php echo htmlspecialchars($username); ?> 님 환영합니다</h1>

<?php if ($profile_path): ?>
    <?php $filename = basename($profile_path); ?>
    <img src="serve_image.php?file=<?php echo urlencode($filename); ?>" width="200">
<?php else: ?>
    <p>프로필 이미지가 없습니다.</p>
<?php endif; ?>

<p><a href="logout.php">로그아웃</a></p>
</body>
</html>
