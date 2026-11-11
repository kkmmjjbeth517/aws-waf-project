<?php
// login_form.php
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>로그인</title>
</head>
<body>
    <h1>로그인</h1>
    <form action="login.php" method="POST">
        <label>아이디 (username):</label><br>
        <input type="text" name="username" required><br><br>

        <label>비밀번호 (password):</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">로그인</button>
    </form>

    <p>아직 계정이 없나요? <a href="register_form.php">회원가입</a></p>
</body>
</html>
