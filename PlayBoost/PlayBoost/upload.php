<?php
file_put_contents('debug_log.txt', date('Y-m-d H:i:s') . " POST received\n", FILE_APPEND);
file_put_contents('debug_log.txt', print_r($_POST, true), FILE_APPEND);
file_put_contents('debug_log.txt', print_r($_FILES, true), FILE_APPEND);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'includes/db.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';
$max_size = 50 * 1024 * 1024; // 最大50MB

// 動画削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_video']) && !empty($_POST['delete_path'])) {
    $delete_path = $_POST['delete_path'];
    $full_path = __DIR__ . '/' . $delete_path;

    if (file_exists($full_path)) {
        unlink($full_path);
    }

    $stmt = $pdo->prepare("DELETE FROM videos WHERE file_path = ? AND user_id = ?");
    $stmt->execute([$delete_path, $_SESSION['user_id']]);

    $message = "動画を削除しました。";

    unset($_SESSION['uploaded_video_path']);
}

// 動画アップロード処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_video'])) {
    $title = trim($_POST['title'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (!empty($title) && isset($_FILES['video'])) {
        $file = $_FILES['video'];
        $upload_dir = 'uploads/videos/';
        $allowed_types = ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'];

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if ($file['error'] !== 0) {
            if ($file['error'] === 1) {
                $message = "⚠ ファイルサイズが大きすぎます（サーバー設定の制限）。php.iniの upload_max_filesize を確認してください。";
            } else {
                $message = "❌ アップロードエラー（コード: {$file['error']}）";
            }
        } else {
            if ($file['size'] > $max_size) {
                $message = "⚠ ファイルサイズが大きすぎます（最大 " . round($max_size / (1024 * 1024), 2) . "MB）。現在: " . round($file['size'] / (1024 * 1024), 2) . "MB）";
            } elseif (in_array($file['type'], $allowed_types)) {
                $filename = uniqid() . '_' . basename($file['name']);
                $filepath = $upload_dir . $filename;

                echo '<pre>';
                echo "保存先: " . $filepath . "\n";
                echo "tmp_name: " . $file['tmp_name'] . "\n";
                echo "is_uploaded_file: " . (is_uploaded_file($file['tmp_name']) ? 'YES' : 'NO') . "\n";
                echo "file_exists(tmp_name): " . (file_exists($file['tmp_name']) ? 'YES' : 'NO') . "\n";
                echo '</pre>';

                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO videos (user_id, title, file_path, thumbnail) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$user_id, $title, $filepath, '']);
                        $_SESSION['uploaded_video_path'] = $filepath;
                        header("Location: upload.php?uploaded=1");
                        exit;
                    } catch (PDOException $e) {
                        $message = "❌ SQLエラー: " . $e->getMessage();
                    }
                } else {
                    $message = "❌ ファイルの保存に失敗しました。<br>";
                    $message .= "tmp_name: " . htmlspecialchars($file['tmp_name']) . "<br>";
                    $message .= "is_uploaded_file: " . (is_uploaded_file($file['tmp_name']) ? 'true' : 'false') . "<br>";
                    $message .= "filepath: " . htmlspecialchars($filepath) . "<br>";
                    $message .= "file_exists(tmp_name): " . (file_exists($file['tmp_name']) ? 'ある' : 'ない') . "<br>";
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
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data" action="/PlayBoost/upload.php">
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_size; ?>">
        <input type="text" name="title" placeholder="動画タイトル" required>
        <input type="file" name="video" accept="video/*" required>
        <button type="submit" class="btn-primary">アップロード</button>
    </form>

    <?php if (isset($_GET['uploaded']) && isset($_SESSION['uploaded_video_path'])): ?>
        <div class="uploaded-video">
            <h3>アップロードされた動画</h3>
            <video width="640" height="360" controls>
                <source src="/PlayBoost/<?php echo htmlspecialchars($_SESSION['uploaded_video_path']); ?>" type="video/mp4">
                お使いのブラウザでは動画を再生できません。
            </video>
            <form method="POST" onsubmit="return confirm('本当にこの動画を削除しますか？');">
                <input type="hidden" name="delete_path" value="<?php echo htmlspecialchars($_SESSION['uploaded_video_path']); ?>">
                <button type="submit" name="delete_video" class="btn-delete">動画を削除する</button>
            </form>
            <?php unset($_SESSION['uploaded_video_path']); ?>
        </div>
    <?php endif; ?>

    <p><a href="dashboard.php">ダッシュボードに戻る</a></p>
</section>

<?php include 'includes/footer.php'; ?>
