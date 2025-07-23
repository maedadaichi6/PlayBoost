<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        // ファイルパスを取得して実ファイルも削除
        $stmt = $pdo->prepare("SELECT file_path FROM videos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $video = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($video && file_exists("../" . $video['file_path'])) {
            unlink("../" . $video['file_path']);
        }

        // DBから動画削除
        $stmt = $pdo->prepare("DELETE FROM videos WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: videos.php');
        exit;
    } catch (PDOException $e) {
        echo '削除エラー: ' . htmlspecialchars($e->getMessage());
    }
}
