<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard quản trị viên</title>
<link rel="stylesheet" href="/css/professional-form.css">
<style>
body{margin:0;background:#E9EAE8;font-family:'Times New Roman',Times,serif;color:#202020}.admin-shell{max-width:1180px;margin:24px auto}.admin-header{background:#fff;border:1px solid #d6d8d6;border-top:5px solid #b91c1c;border-radius:14px;padding:22px 26px;display:flex;justify-content:space-between;align-items:center}.admin-header h1{margin:0;color:#a31515;font-size:30px}.admin-nav a{margin-left:8px;color:#fff;background:#a31515;text-decoration:none;border-radius:8px;padding:9px 12px;font-weight:700}.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin:18px 0}.stat{background:#fff;border:1px solid #d8dad8;border-radius:12px;padding:18px;border-left:5px solid #b91c1c}.stat strong{display:block;font-size:30px;color:#a31515}.panel{background:#fff;border:1px solid #d8dad8;border-radius:12px;padding:20px}.activity{width:100%;border-collapse:collapse}.activity th,.activity td{border-bottom:1px solid #e7e7e7;padding:12px;text-align:left}@media(max-width:760px){.stats{grid-template-columns:repeat(2,1fr)}.admin-header{align-items:flex-start;flex-direction:column;gap:12px}.admin-nav a{display:inline-block;margin:0 6px 6px 0}}
</style>
</head>
<body><div class="admin-shell">
<header class="admin-header"><div><h1>Dashboard quản trị viên</h1><p>Giám sát dữ liệu và hoạt động hệ thống thực tế</p></div><nav class="admin-nav"><a href="/">Trang chủ</a><a href="/quan_tri/tai_khoan">Tài khoản</a><a href="/quan_tri/phan_quyen_can_bo">Phân quyền cán bộ</a></nav></header>
<section class="stats">
<?php foreach ([['Tổng tài khoản',$stats['tai_khoan']],['Công dân',$stats['cong_dan']],['Cán bộ',$stats['can_bo']],['Đang hoạt động',$stats['hoat_dong']],['Bị khóa',$stats['bi_khoa']],['Hồ sơ căn cước',$stats['ho_so']],['Yêu cầu chờ xử lý',$stats['cho_xu_ly']]] as $item): ?>
<article class="stat"><span><?php echo e($item[0]); ?></span><strong><?php echo e((string)$item[1]); ?></strong></article>
<?php endforeach; ?>
</section>
<section class="panel"><h2 style="color:#a31515">Hoạt động hệ thống gần đây</h2><table class="activity"><thead><tr><th>Thời gian</th><th>Tài khoản</th><th>Hoạt động</th><th>Trạng thái</th></tr></thead><tbody>
<?php if (!$hoatDong): ?><tr><td colspan="4">Chưa có dữ liệu hoạt động.</td></tr><?php endif; ?>
<?php foreach ($hoatDong as $row): ?><tr><td><?php echo e($row['thoi_gian'] ?? ''); ?></td><td><?php echo e($row['ho_ten'] ?? ''); ?></td><td><?php echo e($row['hanh_dong'] ?? ''); ?></td><td><?php echo e($row['chi_tiet'] ?? ''); ?></td></tr><?php endforeach; ?>
</tbody></table></section>
</div></body></html>
