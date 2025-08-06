


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // 接続成功時は何も出さない（静かに）
} catch (PDOException $e) {
    die('❌ DB接続エラー: ' . $e->getMessage());
}
?>
