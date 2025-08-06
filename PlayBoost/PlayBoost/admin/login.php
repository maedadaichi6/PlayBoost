<?php
// エラー表示を有効化（デバッグ用）
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// ✅ DB接続ファイル読み込み
require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQLでメールとパスワードをチェック
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email, md5($password)]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // ログイン成功 → セッションに保存
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $user['username'];
        $_SESSION['admin_status'] = $user['status']; // user / active_user / admin / super_admin
        header('Location: index.php');
        exit();
    } else {
        $error = "メールアドレスまたはパスワードが間違っています。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン | PlayBoost</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Bebas+Neue&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-login-box">
            <h1>Play<span class="highlight">Boost</span><br>Admin</h1>
            <form method="POST" class="login-form">
                <?php if ($error): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <input type="email" name="email" placeholder="メールアドレス" required>
                <input type="password" name="password" placeholder="パスワード" required>
                <button type="submit" class="btn-primary">ログイン</button>
            </form>
        </div>
    </div>
</body>
</html>
