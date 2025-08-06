<?php
session_start();

// ログイン認証チェック
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理ダッシュボード | PlayBoost</title>
    <!-- ✅ CSSの正しいパス -->
    <link rel="stylesheet" href="../assets/css/admin.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="admin-page">
<div class="admin-container">
    <!-- ✅ サイドバー -->
    <aside class="sidebar">
        <div class="logo">Play<span class="highlight">Boost</span></div>
        <nav>
            <ul>
                <li><a href="index.php" class="active">ダッシュボード</a></li>
                <li><a href="users.php">ユーザー管理</a></li>
                <li><a href="videos.php">動画管理</a></li>
                <li><a href="logout.php">ログアウト</a></li>
            </ul>
        </nav>
    </aside>

    <!-- ✅ メインコンテンツ -->
    <main class="main-content">
        <h1>管理ダッシュボード</h1>
        <div class="cards">
            <div class="card">
                <h2>総ユーザー数</h2>
                <p>0</p>
            </div>
            <div class="card">
                <h2>総動画数</h2>
                <p>0</p>
            </div>
            <div class="card">
                <h2>本日のアップロード</h2>
                <p>0</p>
            </div>
        </div>
    </main>
</div>
</body>
</html>
