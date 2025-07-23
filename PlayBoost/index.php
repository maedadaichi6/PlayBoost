<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'includes/db.php';
include 'includes/header.php';
?>

<!-- ===== Hero Section ===== -->
<section class="hero">
    <video autoplay muted loop playsinline class="hero-video">
        <source src="assets/videos/hero.mp4" type="video/mp4">
        お使いのブラウザは動画に対応していません。
    </video>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">Play to Inspire,<br><span class="highlight">Boost Your Future.</span></h1>
        <p class="hero-subtitle">スポーツ動画のSNSプラットフォーム<br>熱狂はここから始まる。</p>
        <div class="hero-buttons">
            <a href="register.php" class="btn-primary">プレイヤー登録</a>
            <a href="#trending" class="btn-outline">動画を見る</a>
        </div>
    </div>
</section>

<section id="service" class="service">
    <div class="section-divider"><h2 class="section-title">サービス</h2></div>
    <div class="service-cards">
        <div class="card">🎥 動画投稿</div>
        <div class="card">⚔️ バトル機能</div>
        <div class="card">🔥 スパチャ応援</div>
    </div>
</section>

<section id="trending" class="trending-videos">
    <div class="section-divider"><h2 class="section-title">人気急上昇動画</h2></div>
    <div class="video-grid">
        <?php
        try {
            $stmt = $pdo->prepare("SELECT * FROM videos ORDER BY last_day_views DESC LIMIT 3");
            $stmt->execute();
            $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($videos) {
                foreach ($videos as $video) {
                    echo '<div class="video-card">';
                    echo '<a href="video.php?id=' . htmlspecialchars($video['id']) . '" class="video-thumb">';
                    echo '<img src="' . htmlspecialchars($video['thumbnail']) . '" alt="サムネイル">';
                    echo '<div class="play-icon"><i class="fas fa-play"></i></div>';
                    echo '</a>';
                    echo '<div class="video-info">';
                    echo '<h3 class="video-title">' . htmlspecialchars($video['title']) . '</h3>';
                    echo '<div class="video-meta">';
                    echo '<span>' . number_format($video['views']) . ' 回再生</span>';
                    echo '<span>👍 ' . rand(10, 500) . '</span>';
                    echo '</div></div></div>';
                }
            } else {
                echo '<p>まだ動画がありません。</p>';
            }
        } catch (PDOException $e) {
            echo '<p>エラー: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        ?>
    </div>
</section>

<section id="vision" class="vision">
    <div class="section-divider"><h2 class="section-title">私たちのビジョン</h2></div>
    <p>
        私たちは、PlayBoostを通じて、新しいスポーツの舞台を創造します。<br>
        プレイヤーの価値を高め、努力や挑戦が正当に評価される世界をつくりたい。<br>
        ここから、次のスターが生まれる。その瞬間を、共に楽しもう。
    </p>
</section>

<section id="register" class="register">
    <h2>今すぐ、あなたのプレイを世界に届けよう</h2>
    <a href="register.php" class="btn-primary">無料で登録する</a>
</section>

<?php include 'includes/footer.php'; ?>
