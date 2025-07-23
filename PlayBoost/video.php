<?php
$file = __DIR__ . '/assets/videos/hero.mp4';

if (file_exists($file)) {
    header('Content-Type: video/mp4');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
} else {
    http_response_code(404);
    echo "動画が見つかりません。";
}
