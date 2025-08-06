<?php
session_start();
require 'includes/db.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// --- 削除処理 ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_video_id'])) {
    $video_id = $_POST['delete_video_id'];

    // 動画の所有者確認とファイルパス取得
    $stmt = $pdo->prepare("SELECT file_path FROM videos WHERE id = ? AND user_id = ?");
    $stmt->execute([$video_id, $user_id]);
    $video = $stmt->fetch();

    if ($video) {
        // ファイル削除
        if (file_exists($video['file_path'])) {
            unlink($video['file_path']);
        }

        // DBから削除
        $stmt = $pdo->prepare("DELETE FROM videos WHERE id = ? AND user_id = ?");
        $stmt->execute([$video_id, $user_id]);
    }
}
?>

<?php include 'includes/header.php'; ?>

<section class="dashboard">
    <h2>こんにちは、<?php echo htmlspecialchars($username); ?> さん！</h2>
    <p>PlayBoostへようこそ。</p>

    <div class="dashboard-actions">
        <a href="upload.php" class="btn-primary">動画を投稿する</a>
        <a href="battle.php" class="btn-primary">バトルに参加する</a>
        <a href="profile.php" class="btn-primary">プロフィールを編集</a>
    </div>

    <p style="margin-top:30px;">
        <a href="logout.php" class="btn-secondary">ログアウト</a>
    </p>
</section>

<section class="my-videos" style="margin-top: 40px;">
    <h2>あなたのアップロード動画</h2>

    <?php
    $stmt = $pdo->prepare("SELECT * FROM videos WHERE user_id = ? ORDER BY id DESC");
    $stmt->execute([$user_id]);
    $my_videos = $stmt->fetchAll();
    ?>

    <?php if (count($my_videos) > 0): ?>
        <div class="my-videos-grid">
            <?php foreach ($my_videos as $video): ?>
                <div class="video-card">
                    <h3><?php echo htmlspecialchars($video['title']); ?></h3>
                    <video controls>
                        <source src="<?php echo htmlspecialchars($video['file_path']); ?>" type="video/mp4">
                        お使いのブラウザは動画再生に対応していません。
                    </video>
                    <form method="POST" onsubmit="return confirm('本当に削除しますか？');">
                        <input type="hidden" name="delete_video_id" value="<?php echo $video['id']; ?>">
                        <button type="submit" class="delete-button">削除</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>まだアップロードした動画はありません。</p>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
