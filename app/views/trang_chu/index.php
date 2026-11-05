<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VNeID - Trang chủ</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Times New Roman', Times, serif !important;
            background: #E9EAE8;
            color: #222;
        }
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1100px;
            margin: 20px auto 0;
            padding: 18px 28px;
            background: #fff;
            border: 1px solid #d2d4d2;
            border-top: 5px solid #b91c1c;
            border-radius: 14px;
            box-shadow: 0 10px 28px rgba(36, 40, 38, .10);
        }
        .brand { color: #b91c1c; text-decoration: none; }
        .brand strong { display: block; font-size: 20px; text-transform: uppercase; }
        .brand span { color: #5f6260; font-size: 13px; }
        main {
            max-width: 1100px;
            margin: 18px auto 40px;
            padding: 30px 28px 36px;
            background: #fff;
            border: 1px solid #d2d4d2;
            border-radius: 14px;
            box-shadow: 0 14px 34px rgba(36, 40, 38, .12);
        }
        .welcome {
            text-align: center;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e4e2;
        }
        .welcome h1 {
            margin: 0 0 8px;
            font-size: 32px;
            color: #b91c1c;
            letter-spacing: .3px;
            font-weight: 800;
        }
        .welcome p { margin: 0; color: #5f6260; font-size: 16px; }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }
        .menu-card {
            grid-column: span 1;
            min-height: 190px;
            padding: 24px;
            border-radius: 10px;
            background: #fff;
            color: #222;
            text-decoration: none;
            border: 1px solid #d8dad8;
            border-top: 4px solid #b91c1c;
            box-shadow: 0 8px 20px rgba(36,40,38,.10);
            display: flex;
            flex-direction: column;
            transition: transform .2s, box-shadow .2s;
        }
        .menu-card:nth-last-child(1):nth-child(4) { grid-column: 2 / span 1; }
        .menu-card:hover { transform: translateY(-5px); border-color: #b91c1c; box-shadow: 0 14px 28px rgba(36,40,38,.16); }
        .menu-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #fee2e2;
            color: #e3261f;
            display: grid;
            place-items: center;
            font-size: 24px;
            margin-bottom: 16px;
        }
        .menu-card h2 {
            margin: 0 0 8px;
            font-size: 20px;
            color: #b91c1c;
            font-weight: 800;
        }
        .menu-card p { margin: 0; color: #64748b; font-size: 13px; line-height: 1.55; }
        .menu-card .open {
            margin-top: auto;
            padding-top: 14px;
            color: #b91c1c;
            font-size: 13px;
            font-weight: 700;
        }
        .role-switcher {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .role-switcher strong { width: 100%; text-align: right; font-size: 11px; color: #b91c1c; }
        .role-switcher form { margin: 0; }
        .role-switcher button {
            border: 1px solid #cfd2cf;
            border-radius: 999px;
            padding: 7px 10px;
            background: #f7f7f6;
            color: #333;
            cursor: pointer;
            font-size: 12px;
        }
        .role-switcher button.active { background: #b91c1c; border-color: #b91c1c; color: #fff; font-weight: 700; }
        @media (max-width: 760px) {
            .menu-grid { grid-template-columns: 1fr; }
            .menu-card, .menu-card:nth-last-child(1):nth-child(4) { grid-column: auto; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="/">
            <strong>Bộ Công An</strong>
            <span>Trung tâm dữ liệu Quốc gia về dân cư</span>
        </a>
        <?php $vaiTroDemo = layVaiTroDemo(); ?>
        <div class="role-switcher">
            <strong>CHẾ ĐỘ DEMO - <?php echo $vaiTroDemo === 'can_bo' ? 'CÁN BỘ' : ($vaiTroDemo === 'quan_tri' ? 'QUẢN TRỊ VIÊN' : 'CÔNG DÂN'); ?></strong>
            <?php foreach (['cong_dan'=>'Công dân','can_bo'=>'Cán bộ','quan_tri'=>'Quản trị viên'] as $ma => $ten): ?>
                <form method="POST" action="/trang_chu/chuyen_vai_tro_demo">
                    <input type="hidden" name="ma_csrf" value="<?php echo e(taoMaCsrf()); ?>">
                    <input type="hidden" name="vai_tro" value="<?php echo $ma; ?>">
                    <button type="submit" class="<?php echo $vaiTroDemo === $ma ? 'active' : ''; ?>"><?php echo $ten; ?></button>
                </form>
            <?php endforeach; ?>
        </div>
    </header>

    <main>
        <section class="welcome">
            <h1>Quản lý căn cước công dân</h1>
            <p>Chọn chức năng để bắt đầu hoặc tiếp tục quy trình</p>
        </section>

        <div class="menu-grid">
            <?php if ($vaiTroDemo === 'cong_dan'): ?>
                <a class="menu-card" href="/the_can_cuoc/xem_cua_toi"><div class="menu-icon">▣</div><h2>Xem căn cước của tôi</h2><p>Xem thông tin, số định danh và trạng thái thẻ của công dân.</p><div class="open">Xem chi tiết →</div></a>
                <a class="menu-card" href="/the_can_cuoc/quan_ly_ho_so_cua_toi"><div class="menu-icon">👤</div><h2>Quản lý hồ sơ căn cước</h2><p>Xem hồ sơ của chính công dân hoặc đề nghị điều chỉnh thông tin.</p><div class="open">Quản lý hồ sơ →</div></a>
                <a class="menu-card" href="/the_can_cuoc/de_nghi_dieu_chinh"><div class="menu-icon">◉</div><h2>Đề nghị điều chỉnh</h2><p>Gửi yêu cầu thay đổi họ tên, ngày sinh, giới tính hoặc nơi thường trú.</p><div class="open">Gửi yêu cầu →</div></a>
                <a class="menu-card" href="/the_can_cuoc/yeu_cau_cap_the"><div class="menu-icon">▤</div><h2>Yêu cầu cấp thẻ</h2><p>Mô phỏng cấp mới, cấp đổi hoặc cấp lại thẻ căn cước.</p><div class="open">Nộp yêu cầu →</div></a>
                <a class="menu-card" href="/the_can_cuoc/yeu_cau_cua_toi"><div class="menu-icon">✓</div><h2>Theo dõi yêu cầu</h2><p>Xem trạng thái xử lý và ghi chú kết quả của các yêu cầu.</p><div class="open">Xem trạng thái →</div></a>
                <a class="menu-card" href="/the_can_cuoc/trang_thai_cua_toi"><div class="menu-icon">◆</div><h2>Quản lý trạng thái thẻ</h2><p>Xem trạng thái thẻ, ngày cấp, ngày hết hạn và gửi yêu cầu thay đổi trạng thái.</p><div class="open">Quản lý thẻ →</div></a>
            <?php elseif ($vaiTroDemo === 'can_bo'): ?>
                <a class="menu-card" href="/the_can_cuoc/danh_sach"><div class="menu-icon">⌕</div><h2>Tra cứu hồ sơ</h2><p>Tra cứu căn cước theo số CCCD hoặc họ tên.</p><div class="open">Tra cứu →</div></a>
                <a class="menu-card" href="/the_can_cuoc/danh_sach_yeu_cau_dieu_chinh"><div class="menu-icon">◉</div><h2>Yêu cầu điều chỉnh</h2><p>So sánh thông tin cũ và mới, rồi duyệt hoặc từ chối.</p><div class="open">Xử lý điều chỉnh →</div></a>
                <a class="menu-card" href="/the_can_cuoc/danh_sach_yeu_cau_trang_thai"><div class="menu-icon">◆</div><h2>Trạng thái thẻ</h2><p>Xử lý yêu cầu báo mất/cấp lại và quản lý trạng thái thẻ, gồm lịch sử thay đổi.</p><div class="open">Quản lý →</div></a>
            <?php else: ?>
                <a class="menu-card" href="/quan_tri/tong_quan"><div class="menu-icon">⌂</div><h2>Dashboard quản trị</h2><p>Tổng quan tài khoản, hồ sơ, yêu cầu và hoạt động hệ thống.</p><div class="open">Mở dashboard →</div></a>
                <a class="menu-card" href="/quan_tri/tai_khoan"><div class="menu-icon">♙</div><h2>Quản lý tài khoản</h2><p>Tìm kiếm, xem, chỉnh sửa, khóa hoặc mở khóa tài khoản.</p><div class="open">Quản lý tài khoản →</div></a>
                <a class="menu-card" href="/quan_tri/phan_quyen_can_bo"><div class="menu-icon">⚙</div><h2>Phân quyền cán bộ</h2><p>Bật hoặc tắt ba quyền nghiệp vụ độc lập của cán bộ.</p><div class="open">Thiết lập quyền →</div></a>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
