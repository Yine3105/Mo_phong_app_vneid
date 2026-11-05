<?php

class TrangChuController {
    public function index() {
        require_once __DIR__ . '/../views/trang_chu/index.php';
    }

    public function capNhatQuyenKiemThu() {
        if (!laCanBo()) {
            http_response_code(403);
            exit('Chỉ tài khoản Cán bộ mới được thay đổi phân quyền kiểm thử.');
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Phương thức không hợp lệ.');
        }
        kiemTraMaCsrf();
        $maQuyen = $_POST['ma_quyen'] ?? '';
        if (!in_array($maQuyen, ['quan_ly_ho_so', 'duyet_dieu_chinh', 'quan_ly_trang_thai'], true)) {
            http_response_code(400);
            exit('Quyền kiểm thử không hợp lệ.');
        }
        khoiTaoQuyenCanBoKiemThu();
        $_SESSION['quyen_can_bo'][$maQuyen] = $_POST['bat'] === '1';
        header('Location: /');
        exit;
    }

    public function chuyenVaiTroDemo() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Phương thức không hợp lệ.');
        }
        kiemTraMaCsrf();
        $vaiTro = $_POST['vai_tro'] ?? '';
        if (!in_array($vaiTro, ['cong_dan', 'can_bo', 'quan_tri'], true)) {
            http_response_code(400);
            exit('Vai trò demo không hợp lệ.');
        }
        $_SESSION['vai_tro_demo'] = $vaiTro;
        header('Location: /');
        exit;
    }

    public function trangChu() {
        $this->index();
    }
}
