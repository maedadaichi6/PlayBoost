<?php
session_start();
require 'includes/db.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$keyword = '';
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
    $keyword = trim($_GET['q']);
    if (!empty($keyword)) {
        $stmt = $pdo->prepare("SELECT * FROM videos WHERE title LIKE ?");
        $stmt->execute(['%' . $keyword . '%']);
        $results = $stmt->fetchAll();
    }
}
?>

<?php include 'includes/header.php'; ?>

<section class="search-page">
    <h2>動画を検索</h2>
    <form method="GET" class="search-form">
        <input type="text" name="q" placeholder="動画タイトルで検索" value="<?php echo htmlspecialchars($keyword); ?>">
        <button type="submit" class="btn-primary">検索</button>
    </form>

    <?php if (!empty($keyword)): ?>
        <h3>「<?php echo htmlspecialchars($keyword); ?>」の検索結果</h3>
        <?php if (count($results) > 0): ?>
            <div class="search-results">
                <?php foreach ($results as $video): ?>
                    <div class="video-card">
                        <video width="250" controls>
                            <source src="<?php echo htmlspecialchars($video['file_path']); ?>" type="video/mp4">
                        </video>
                        <h4><?php echo htmlspecialchars($video['title']); ?></h4>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>該当する動画は見つかりませんでした。</p>
        <?php endif; ?>
    <?php endif; ?>

    <p><a href="dashboard.php" class="btn-secondary">ダッシュボードに戻る</a></p>
</section>

<?php include 'includes/footer.php'; ?>
