<?php
$host = ''; // さくらサーバーのDBホスト名
$dbname = '';      // データベース名
$user = '';            // ユーザー名
$pass = '';            // パスワード

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ DB接続成功！（さくらサーバー）";
} catch (PDOException $e) {
    die('❌ DB接続エラー: ' . $e->getMessage());
}
?>
