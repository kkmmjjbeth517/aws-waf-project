<?php
// register.php
require_once "config.php";

// 1. 폼 데이터 받기
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// 2. 유효성 확인
if ($username === '' || $email === '' || $password === '') {
    die("모든 필드를 입력해야 합니다. <a href='register_form.php'>돌아가기</a>");
}

// 3. username / email 중복 검사
$stmt = $conn->prepare("SELECT id FROM members WHERE username = ? OR email = ? LIMIT 1");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    die("이미 존재하는 아이디 또는 이메일입니다. <a href='register_form.php'>돌아가기</a>");
}
$stmt->close();

// 4. 비밀번호 해시
$pw_hash = password_hash($password, PASSWORD_DEFAULT);

// 5. 이미지 업로드 처리
$upload_path_in_db = null; // DB에 저장할 경로 (문자열)

if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {

    $tmp_name = $_FILES['profile_image']['tmp_name'];
    $orig_name = $_FILES['profile_image']['name'];

    // (a) MIME/확장자 간단 체크
    $allowed_ext = ['jpg','jpeg','png','gif'];
    $path_info = pathinfo($orig_name);
    $ext = strtolower($path_info['extension'] ?? '');

    if (!in_array($ext, $allowed_ext)) {
        die("허용되지 않는 이미지 형식입니다. jpg/png/gif만 가능합니다. <a href='register_form.php'>돌아가기</a>");
    }

    // (b) 유니크 파일명 생성
    // 예: time()_username.ext
    $new_filename = time() . "_" . preg_replace("/[^a-zA-Z0-9_-]/", "_", $username) . "." . $ext;

    // (c) 실제 저장될 서버 경로
    $target_dir = __DIR__ . "/uploads"; // 실제 디렉터리
    if (!is_dir($target_dir)) {
        // 업로드 디렉토리가 없으면 시도해볼 수 있음 (권한 필요)
        mkdir($target_dir, 0755, true);
    }

    $target_path = $target_dir . "/" . $new_filename;

    // (d) 웹에서 접근 가능한 경로 (상대경로)
    $relative_path = "uploads/" . $new_filename;

    // (e) 파일 이동
    if (move_uploaded_file($tmp_name, $target_path)) {
        $upload_path_in_db = $relative_path;
    } else {
        die("이미지 업로드에 실패했습니다. <a href='register_form.php'>돌아가기</a>");
    }
}

// 6. INSERT (profile_image 포함)
$stmt = $conn->prepare("INSERT INTO members (username, email, password_hash, profile_image) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $pw_hash, $upload_path_in_db);

if ($stmt->execute()) {
    echo "회원가입이 완료되었습니다. <a href='login_form.php'>로그인하러 가기</a>";
} else {
    echo "에러가 발생했습니다: " . htmlspecialchars($stmt->error);
}

$stmt->close();
$conn->close();
?>
