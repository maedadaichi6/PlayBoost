document.addEventListener('DOMContentLoaded', function () {
    // ===== 動画アップロードの事前チェック =====
    const fileInput = document.querySelector('input[type="file"][name="video"]');
    if (fileInput) {
        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            const maxSize = 50 * 1024 * 1024; // 50MB

            if (file) {
                // サイズチェック
                if (file.size > maxSize) {
                    alert(`ファイルサイズが大きすぎます！（最大50MB、現在 ${(file.size / (1024 * 1024)).toFixed(2)}MB）`);
                    this.value = ''; // 選択解除
                    return;
                }

                // プレビュー表示
                const previewArea = document.querySelector('.upload-form');
                const oldPreview = previewArea.querySelector('video');
                if (oldPreview) oldPreview.remove();

                const preview = document.createElement('video');
                preview.src = URL.createObjectURL(file);
                preview.controls = true;
                preview.width = 300;

                previewArea.appendChild(preview);
            }
        });
    }
});
