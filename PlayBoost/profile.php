<?php
session_start();
require 'includes/db.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// 現在の情報を取得
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// 更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);

    if (!empty($new_username) && !empty($new_email)) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$new_username, $new_email, $user_id]);

            $_SESSION['username'] = $new_username; // セッション更新
            $message = "✅ プロフィールを更新しました！";
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $message = "⚠ このメールアドレスは既に使われています。";
            } else {
                $message = "❌ エラーが発生しました: " . $e->getMessage();
            }
        }
    } else {
        $message = "⚠ ユーザー名とメールを入力してください。";
    }
}
?>

<?php include 'includes/header.php'; ?>

<section class="profile-form">
    <h2>プロフィール編集</h2>
    <?php if ($message): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <button type="submit" class="btn-primary">更新する</button>
    </form>
    <p><a href="dashboard.php">ダッシュボードに戻る</a></p>
</section>

<?php include 'includes/footer.php'; ?>
