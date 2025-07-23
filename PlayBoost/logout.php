<?php
session_start();
$_SESSION = array(); // セッション変数を空に
session_destroy();   // セッション破棄
?>

<?php include 'includes/header.php'; ?>

<section class="logout-message" style="text-align:center; margin:80px auto;">
    <h2>ログアウトしました</h2>
    <p><a href="login.php" class="btn-primary">ログインページに戻る</a></p>
</section>

<?php include 'includes/footer.php'; ?>
