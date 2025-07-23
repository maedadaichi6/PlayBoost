<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("UPDATE users SET status = :status WHERE id = :id");
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: users.php');
        exit;
    } catch (PDOException $e) {
        echo '更新エラー: ' . htmlspecialchars($e->getMessage());
    }
}
