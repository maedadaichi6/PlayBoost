<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 既存情報の取得
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// 更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = trim($_POST['bio']);
    $profile_path = $user['profile_image'];
    $cover_path = $user['cover_image'];

    // プロフィール画像
    if (!empty($_FILES['profile_image']['name'])) {
        $profile_filename = uniqid() . '_' . basename($_FILES['profile_image']['name']);
        $profile_path = 'uploads/profile_images/' . $profile_filename;
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $profile_path);
    }

    // カバー画像
    if (!empty($_FILES['cover_image']['name'])) {
        $cover_filename = uniqid() . '_' . basename($_FILES['cover_image']['name']);
        $cover_path = 'uploads/cover_images/' . $cover_filename;
        move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover_path);
    }

    // DB更新
    $stmt = $pdo->prepare("UPDATE users SET bio = ?, profile_image = ?, cover_image = ? WHERE id = ?");
    $stmt->execute([$bio, $profile_path, $cover_path, $user_id]);

    header("Location: profile.php");
    exit;
}
?>

<!-- HTMLフォーム部分 -->
<?php include 'includes/header.php'; ?>
<section class="profile-form">
    <h2>プロフィールを編集</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>プロフィール画像</label><br>
        <input type="file" name="profile_image"><br><br>

        <label>カバー画像</label><br>
        <input type="file" name="cover_image"><br><br>

        <label>自己紹介</label><br>
        <textarea name="bio" rows="5" cols="40"><?php echo htmlspecialchars($user['bio']); ?></textarea><br><br>

        <button type="submit" class="btn-primary">保存する</button>
    </form>
</section>
<?php include 'includes/footer.php'; ?>
