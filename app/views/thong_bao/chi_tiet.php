<?php // Biến từ controller: $thong_bao ?>
<a class="lien-ket-quay-lai" href="/thong_bao/danh_sach">&larr; Quay lại thông báo</a>

<div class="khoi">
    <h2><?= e($thong_bao['tieu_de']) ?></h2>
    <small style="color:#999">
        <?= e(layNhanLoaiThongBao($thong_bao['loai'])) ?> · <?= e(date('d/m/Y H:i', strtotime($thong_bao['ngay_tao']))) ?>
    </small>
    <div class="noi-dung-thong-bao"><?= nl2br(e($thong_bao['noi_dung'])) ?></div>

    <form method="post" action="/thong_bao/xoa" onsubmit="return confirm('Xóa thông báo này?');">
        <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">
        <input type="hidden" name="ma_thong_bao" value="<?= (int) $thong_bao['ma_thong_bao'] ?>">
        <button type="submit" class="nut nut-nguy-hiem nut-rong">Xóa thông báo</button>
    </form>
</div>