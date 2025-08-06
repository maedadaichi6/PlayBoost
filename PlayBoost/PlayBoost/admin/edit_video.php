<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM videos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $video = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$video) {
        echo "動画が見つかりません。";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $title = $_POST['title'];

    try {
        $stmt = $pdo->prepare("UPDATE videos SET title = :title WHERE id = :id");
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: videos.php');
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
<title>動画編集 | PlayBoost</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<h1>動画編集</h1>
<form method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($video['id']); ?>">
    <label>タイトル:</label><input type="text" name="title" value="<?= htmlspecialchars($video['title']); ?>"><br>
    <button type="submit">更新</button>
</form>
</body>
</html>
