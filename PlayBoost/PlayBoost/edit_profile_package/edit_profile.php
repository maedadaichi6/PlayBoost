<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = trim($_POST['bio'] ?? '');
    $profile_image = $_FILES['profile_image'] ?? null;
    $cover_image = $_FILES['cover_image'] ?? null;

    // ファイルアップロード処理
    $upload_dir = 'uploads/profiles/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $updates = [];
    $params = [];

    if ($bio !== '') {
        $updates[] = 'bio = ?';
        $params[] = $bio;
    }

    if ($profile_image && $profile_image['error'] === 0) {
        $filename = uniqid() . '_' . basename($profile_image['name']);
        move_uploaded_file($profile_image['tmp_name'], $upload_dir . $filename);
        $updates[] = 'profile_image = ?';
        $params[] = $upload_dir . $filename;
    }

    if ($cover_image && $cover_image['error'] === 0) {
        $filename = uniqid() . '_' . basename($cover_image['name']);
        move_uploaded_file($cover_image['tmp_name'], $upload_dir . $filename);
        $updates[] = 'cover_image = ?';
        $params[] = $upload_dir . $filename;
    }

    if (!empty($updates)) {
        $sql = 'UPDATE users SET ' . implode(', ', $updates) . ' WHERE id = ?';
        $params[] = $user_id;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $message = '✅ プロフィールを更新しました！';
    } else {
        $message = '⚠ 変更がありません。';
    }
}

// 現在のプロフィールを取得
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<?php include 'includes/header.php'; ?>

<section class="profile-form">
    <h2>プロフィールを編集</h2>
    <?php if ($message): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <!-- カバー画像表示 -->
        <?php if (!empty($user['cover_image'])): ?>
            <div style="margin-bottom: 15px;">
                <img src="<?php echo htmlspecialchars($user['cover_image']); ?>" alt="カバー画像" style="width:100%; max-height:200px; object-fit:cover;">
            </div>
        <?php endif; ?>
        <label>カバー画像を変更:</label>
        <input type="file" name="cover_image" accept="image/*">

        <!-- プロフィール画像表示 -->
        <?php if (!empty($user['profile_image'])): ?>
            <div style="margin: 20px 0;">
                <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="プロフィール画像" style="width:150px; height:150px; border-radius:50%; object-fit:cover;">
            </div>
        <?php endif; ?>
        <label>プロフィール画像を変更:</label>
        <input type="file" name="profile_image" accept="image/*">

        <!-- 自己紹介 -->
        <label>自己紹介:</label>
        <textarea name="bio" rows="5" placeholder="自己紹介を入力"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>

        <button type="submit" class="btn-primary" style="margin-top: 20px;">更新する</button>
    </form>

    <p style="margin-top: 30px;"><a href="dashboard.php">← ダッシュボードに戻る</a></p>
</section>

<?php include 'includes/footer.php'; ?>
