<?php
// Biến từ controller: $giay_to (kèm ten_loai, cac_truong, thong_tin dạng mảng)
$nhan = layNhanTrangThaiGiayTo($giay_to['trang_thai']);
?>
<a class="lien-ket-quay-lai" href="/giay_to_vi/danh_sach">&larr; Quay lại ví giấy tờ</a>

<div class="khoi">
    <h2>
        <?= e($giay_to['ten_loai']) ?>
        <span class="nhan-trang-thai <?= e($nhan['lop']) ?>"><?= e($nhan['nhan']) ?></span>
    </h2>

    <table class="bang-thong-tin">
        <tr><th>Số giấy tờ</th><td><?= e($giay_to['so_giay_to']) ?></td></tr>
        <tr><th>Chủ sở hữu</th><td><?= e($giay_to['ten_chu_so_huu']) ?></td></tr>
        <?php foreach ($giay_to['cac_truong'] as $ten_truong): ?>
            <tr>
            
                <th><?= e(layNhanTruong($ten_truong)) ?></th>
                <td><?= e($giay_to['thong_tin'][$ten_truong] ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr><th>Ngày cấp</th><td><?= e(dinhDangNgay($giay_to['ngay_cap'], 'Không có')) ?></td></tr>
        <tr><th>Ngày hết hạn</th><td><?= e(dinhDangNgay($giay_to['ngay_het_han'], 'Không thời hạn')) ?></td></tr>
        <tr><th>Nơi cấp</th><td><?= e($giay_to['noi_cap'] ?: 'Không có') ?></td></tr>
    </table>

    <form method="post" action="/giay_to_vi/xoa" style="margin-top:20px"
          onsubmit="return confirm('Xóa giấy tờ này khỏi ví?');">
        <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">
        <input type="hidden" name="ma_giay_to" value="<?= (int) $giay_to['ma_giay_to'] ?>">
        <button type="submit" class="nut nut-nguy-hiem nut-rong">Xóa khỏi ví</button>
    </form>
</div>