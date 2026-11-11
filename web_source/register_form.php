<?php
// register_form.php
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원가입</title>
</head>
<body>
    <h1>회원가입</h1>
    <form action="register.php" method="POST" enctype="multipart/form-data">
        <label>아이디 (username):</label><br>
        <input type="text" name="username" required><br><br>

        <label>이메일 (email):</label><br>
        <input type="email" name="email" required><br><br>

        <label>비밀번호 (password):</label><br>
        <input type="password" name="password" required><br><br>

        <label>프로필 이미지 (jpg/png/gif):</label><br>
        <input type="file" name="profile_image" accept="image/*"><br><br>

        <button type="submit">가입하기</button>
    </form>

    <p>이미 계정이 있나요? <a href="login_form.php">로그인</a></p>
</body>
</html>
