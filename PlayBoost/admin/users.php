<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/db.php';

// ユーザー一覧取得（usernameカラムを使う）
$stmt = $pdo->query("SELECT id, username, email, status, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー管理 | PlayBoost</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-container">
    <aside class="sidebar">
        <div class="logo">Play<span class="highlight">Boost</span></div>
        <nav>
            <ul>
                <li><a href="index.php">ダッシュボード</a></li>
                <li><a href="users.php" class="active">ユーザー管理</a></li>
                <li><a href="videos.php">動画管理</a></li>
                <li><a href="logout.php">ログアウト</a></li>
            </ul>
        </nav>
    </aside>
    <main class="main-content">
        <h1>ユーザー管理</h1>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ユーザー名</th>
                    <th>メール</th>
                    <th>ステータス</th>
                    <th>登録日</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <form action="update_status.php" method="POST" class="status-form">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <select name="status" onchange="this.form.submit()">
                                <option value="user" <?= $user['status']=='user'?'selected':'' ?>>ユーザー</option>
                                <option value="active_user" <?= $user['status']=='active_user'?'selected':'' ?>>積極ユーザー</option>
                                <option value="admin" <?= $user['status']=='admin'?'selected':'' ?>>管理者</option>
                                <option value="super_admin" <?= $user['status']=='super_admin'?'selected':'' ?>>最高責任者</option>
                            </select>
                        </form>
                    </td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn-edit">編集</a>
                        <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn-delete" onclick="return confirm('削除しますか？')">削除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>
