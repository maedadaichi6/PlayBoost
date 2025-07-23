<?php
require 'includes/db.php';
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        // パスワードをハッシュ化
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // ユーザー登録（SQLインジェクション対策）
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password]);
            $message = "✅ 登録が完了しました！ <a href='login.php'>ログインはこちら</a>";
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { // UNIQUE制約違反（メールアドレス重複）
                $message = "⚠ このメールアドレスは既に登録されています。";
            } else {
                $message = "❌ エラーが発生しました: " . $e->getMessage();
            }
        }
    } else {
        $message = "⚠ 全てのフィールドを入力してください。";
    }
}
?>

<?php include 'includes/header.php'; ?>

<section class="register-form">
    <h2>アカウント作成</h2>
    <?php if ($message): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="ユーザー名" required>
        <input type="email" name="email" placeholder="メールアドレス" required>
        <input type="password" name="password" placeholder="パスワード" required>
        <button type="submit" class="btn-primary">登録する</button>
    </form>
    <p>すでにアカウントがありますか？ <a href="login.php">ログイン</a></p>
</section>

<?php include 'includes/footer.php'; ?>
