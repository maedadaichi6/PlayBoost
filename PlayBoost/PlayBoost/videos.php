<?php
require 'includes/db.php';
include 'includes/header.php';

// 動画一覧を取得
$stmt = $pdo->query("SELECT * FROM videos ORDER BY id DESC");
$videos = $stmt->fetchAll();
?>

<section class="videos-list">
    <h2>アップロードされた動画一覧</h2>

    <?php if (count($videos) > 0): ?>
        <?php foreach ($videos as $video): ?>
            <div class="video-card" style="margin-bottom:30px;">
                <h3><?php echo htmlspecialchars($video['title']); ?></h3>
                <video width="480" controls>
    <source src="/<?php echo ltrim(htmlspecialchars($video['file_path']),); ?>" type="video/mp4">
    お使いのブラウザは動画再生に対応していません。
</video>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>まだ動画がアップロードされていません。</p>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
