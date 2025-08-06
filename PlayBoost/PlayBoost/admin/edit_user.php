<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "ユーザーが見つかりません。";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $username = $_POST['username'];
    $email = $_POST['email'];

    try {
        $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email WHERE id = :id");
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: users.php');
        exit;
    } catch (PDOException $e) {
        echo '更新エラー: ' . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ユーザー編集 | PlayBoost</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<h1>ユーザー編集</h1>
<form method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
    <label>ユーザー名:</label><input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>"><br>
    <label>メール:</label><input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>"><br>
    <button type="submit">更新</button>
</form>
</body>
</html>
