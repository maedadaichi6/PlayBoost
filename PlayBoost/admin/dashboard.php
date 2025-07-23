<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ダッシュボード | PlayBoost</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
<header class="admin-header">
    <div class="logo">
        <a href="dashboard.php">Play<span class="highlight">Boost</span> Admin</a>
    </div>
    <nav>
        <a href="logout.php" class="btn-logout">ログアウト</a>
    </nav>
</header>

<section class="admin-dashboard">
    <h1 class="dashboard-title">管理メニュー</h1>
    <div class="dashboard-cards">
        <a href="users.php" class="dashboard-card">
            <h2>👤 ユーザー管理</h2>
            <p>登録ユーザーの一覧・編集・削除を行います</p>
        </a>
        <a href="videos.php" class="dashboard-card">
            <h2>🎥 動画管理</h2>
            <p>投稿された動画の一覧・編集・削除を行います</p>
        </a>
    </div>
</section>

</body>
</html>
