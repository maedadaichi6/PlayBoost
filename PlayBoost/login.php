<?php
require 'includes/db.php';
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        // ユーザー検索
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // ログイン成功
            session_regenerate_id(true); // セッション固定攻撃対策
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "⚠ メールアドレスまたはパスワードが違います。";
        }
    } else {
        $message = "⚠ メールアドレスとパスワードを入力してください。";
    }
}
?>

<?php include 'includes/header.php'; ?>

<section class="login-form">
    <h2>ログイン</h2>
    <?php if ($message): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="メールアドレス" required>
        <input type="password" name="password" placeholder="パスワード" required>
        <button type="submit" class="btn-primary">ログイン</button>
    </form>
    <p>アカウントをお持ちでない方は <a href="register.php">新規登録</a></p>
</section>

<?php include 'includes/footer.php'; ?>
