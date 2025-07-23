<?php
session_start();
require_once '../includes/db.php'; // DB接続

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

// 動画一覧取得
try {
    $stmt = $pdo->query("SELECT id, title, file_path, views, created_at FROM videos ORDER BY created_at DESC");
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('データ取得エラー: ' . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>動画管理 | PlayBoost</title>
<link rel="stylesheet" href="../assets/css/admin.css">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
<div class="admin-container">
    <!-- サイドバー -->
    <aside class="sidebar">
        <div class="logo">Play<span class="highlight">Boost</span></div>
        <nav>
            <ul>
                <li><a href="dashboard.php">ダッシュボード</a></li>
                <li><a href="users.php">ユーザー管理</a></li>
                <li><a href="videos.php" class="active">動画管理</a></li>
                <li><a href="logout.php">ログアウト</a></li>
            </ul>
        </nav>
    </aside>

    <!-- メインコンテンツ -->
    <main class="main-content">
        <h1>動画管理</h1>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>タイトル</th>
                    <th>再生数</th>
                    <th>アップロード日</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($videos)): ?>
                    <?php foreach ($videos as $video): ?>
                        <tr>
                            <td><?= htmlspecialchars($video['id']); ?></td>
                            <td><?= htmlspecialchars($video['title']); ?></td>
                            <td><?= htmlspecialchars($video['views']); ?> 回</td>
                            <td><?= htmlspecialchars($video['created_at']); ?></td>
                            <td>
                                <a href="../<?= htmlspecialchars($video['file_path']); ?>" target="_blank" class="btn-view">再生</a>
                                <a href="edit_video.php?id=<?= urlencode($video['id']); ?>" class="btn-edit">編集</a>
                                <a href="delete_video.php?id=<?= urlencode($video['id']); ?>" class="btn-delete" onclick="return confirm('本当に削除しますか？')">削除</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">動画が登録されていません。</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>