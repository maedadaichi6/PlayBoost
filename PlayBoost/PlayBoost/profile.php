<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// プロフィール情報取得
$stmt = $pdo->prepare("SELECT username, email, profile_image, cover_image, bio FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<?php include 'includes/header.php'; ?>

<section class="profile-page">
    <!-- カバー画像 -->
    <div class="cover-photo" style="position: relative;">
        <?php if (!empty($user['cover_image'])): ?>
            <img src="<?php echo htmlspecialchars($user['cover_image']); ?>" alt="カバー画像" style="width:100%; height:250px; object-fit:cover;">
        <?php else: ?>
            <div style="width:100%; height:250px; background:#ccc;"></div>
        <?php endif; ?>

        <!-- プロフィール画像 -->
        <div class="profile-header" style="position: absolute; bottom: -50px; left: 30px;">
            <?php if (!empty($user['profile_image'])): ?>
                <img class="profile-icon" src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="プロフィール画像" style="width:100px; height:100px; border-radius:50%; border:4px solid white; object-fit:cover;">
            <?php else: ?>
                <div style="width:100px; height:100px; border-radius:50%; background:#aaa; border:4px solid white;"></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- プロフィール情報 -->
    <div class="profile-info" style="padding: 70px 20px 20px;">
        <h2><?php echo htmlspecialchars($user['username']); ?></h2>
        <p>メール：<?php echo htmlspecialchars($user['email']); ?></p>
        <?php if (!empty($user['bio'])): ?>
            <p><?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
        <?php endif; ?>
        <a href="edit_profile.php" class="btn-primary" style="margin-top: 15px;">プロフィールを編集</a>
    </div>

    <!-- 自分の動画一覧 -->
    <div class="my-videos-grid" style="margin-top: 40px;">
        <h3>投稿動画</h3>

        <?php
        $stmt = $pdo->prepare("SELECT * FROM videos WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$user_id]);
        $videos = $stmt->fetchAll();
        ?>

        <?php if (count($videos) > 0): ?>
            <div class="video-gallery" style="display: flex; flex-wrap: wrap; gap: 20px;">
                <?php foreach ($videos as $video): ?>
                    <div class="video-card" style="width: 300px;">
                        <video controls width="100%">
                            <source src="<?php echo htmlspecialchars($video['file_path']); ?>" type="video/mp4">
                        </video>
                        <h4><?php echo htmlspecialchars($video['title']); ?></h4>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>まだ動画がありません。</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
