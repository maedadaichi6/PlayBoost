<?php
session_start();

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<?php include 'includes/header.php'; ?>

<section class="dashboard">
    <h2>こんにちは、<?php echo htmlspecialchars($username); ?> さん！</h2>
    <p>PlayBoostへようこそ。</p>

    <div class="dashboard-actions">
        <a href="upload.php" class="btn-primary">動画を投稿する</a>
        <a href="battle.php" class="btn-primary">バトルに参加する</a>
        <a href="profile.php" class="btn-primary">プロフィールを編集</a>
    </div>

    <p style="margin-top:30px;">
        <a href="logout.php" class="btn-secondary">ログアウト</a>
    </p>
</section>

<?php include 'includes/footer.php'; ?>
