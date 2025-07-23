<?php
session_start();
require 'includes/db.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';
$max_size = 50 * 1024 * 1024; // 最大50MB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $user_id = $_SESSION['user_id'];

    if (!empty($title) && isset($_FILES['video'])) {
        $file = $_FILES['video'];
        $upload_dir = 'uploads/videos/';
        $allowed_types = ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'];

        // PHPエラーコードのチェック
        if ($file['error'] !== 0) {
            if ($file['error'] === 1) {
                $message = "⚠ ファイルサイズが大きすぎます（サーバー設定の制限）。php.iniの upload_max_filesize を確認してください。";
            } else {
                $message = "❌ アップロードエラー（コード: {$file['error']}）";
            }
        } else {
            // サイズチェック（アプリ独自）
            if ($file['size'] > $max_size) {
                $message = "⚠ ファイルサイズが大きすぎます（最大 " . round($max_size / (1024 * 1024), 2) . "MB）。現在: " . round($file['size'] / (1024 * 1024), 2) . "MB";
            } elseif (in_array($file['type'], $allowed_types)) {
                $filename = uniqid() . '_' . basename($file['name']);
                $filepath = $upload_dir . $filename;

                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    $stmt = $pdo->prepare("INSERT INTO videos (user_id, title, file_path) VALUES (?, ?, ?)");
                    $stmt->execute([$user_id, $title, $filepath]);
                    $message = "✅ 動画をアップロードしました！";
                } else {
                    $message = "❌ ファイルの保存に失敗しました。";
                }
            } else {
                $message = "⚠ MP4, WEBM, OGG, MOV形式の動画のみアップロード可能です。";
            }
        }
    } else {
        $message = "⚠ タイトルと動画を選択してください。";
    }
}
?>

<?php include 'includes/header.php'; ?>

<section class="upload-form">
    <h2>動画をアップロード</h2>
    <?php if ($message): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_size; ?>">
        <input type="text" name="title" placeholder="動画タイトル" required>
        <input type="file" name="video" accept="video/*" required>
        <button type="submit" class="btn-primary">アップロード</button>
    </form>
    <p><a href="dashboard.php">ダッシュボードに戻る</a></p>
</section>

<?php include 'includes/footer.php'; ?>
