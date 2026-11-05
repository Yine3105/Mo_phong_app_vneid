<?php

class QuanTriController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->khoiTaoBangPhanQuyen();
    }

    private function khoiTaoBangPhanQuyen() {
        try {
            $this->pdo->exec(
                "CREATE TABLE IF NOT EXISTS quyen_can_bo (
                    ma_vai_tro INT PRIMARY KEY,
                    quan_ly_ho_so TINYINT(1) NOT NULL DEFAULT 1,
                    duyet_dieu_chinh TINYINT(1) NOT NULL DEFAULT 1,
                    quan_ly_trang_thai TINYINT(1) NOT NULL DEFAULT 1,
                    ngay_cap_nhat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    CONSTRAINT fk_quyen_can_bo_vai_tro FOREIGN KEY (ma_vai_tro) REFERENCES vai_tro(ma_vai_tro)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            );
            $stmt = $this->pdo->prepare(
                "INSERT IGNORE INTO quyen_can_bo
                 (ma_vai_tro, quan_ly_ho_so, duyet_dieu_chinh, quan_ly_trang_thai)
                 VALUES (2, 1, 1, 1)"
            );
            $stmt->execute();
        } catch (PDOException $e) {
        }
    }

    private function yeuCauQuanTri() {
        yeuCauVaiTroDemo(['quan_tri'], 'Chỉ quản trị viên được truy cập chức năng này.');
    }

    public function tongQuan() {
        $this->dashboard();
    }

    public function dashboard() {
        $this->yeuCauQuanTri();
        $stats = [
            'tai_khoan' => $this->pdo->query("SELECT COUNT(*) FROM nguoi_dung")->fetchColumn(),
            'cong_dan' => $this->pdo->query("SELECT COUNT(*) FROM nguoi_dung WHERE ma_vai_tro = 1")->fetchColumn(),
            'can_bo' => $this->pdo->query("SELECT COUNT(*) FROM nguoi_dung WHERE ma_vai_tro = 2")->fetchColumn(),
            'hoat_dong' => $this->pdo->query("SELECT COUNT(*) FROM nguoi_dung WHERE trang_thai = 'hoat_dong'")->fetchColumn(),
            'bi_khoa' => $this->pdo->query("SELECT COUNT(*) FROM nguoi_dung WHERE trang_thai = 'bi_khoa'")->fetchColumn(),
            'ho_so' => $this->pdo->query("SELECT COUNT(*) FROM the_can_cuoc")->fetchColumn(),
            'cho_xu_ly' => 0
        ];
        try {
            $stats['cho_xu_ly'] = (int)$this->pdo->query(
                "SELECT COUNT(*) FROM yeu_cau_cap_the WHERE trang_thai = 'cho_tiep_nhan'"
            )->fetchColumn();
            try {
                $stats['cho_xu_ly'] += (int)$this->pdo->query(
                    "SELECT COUNT(*) FROM yeu_cau_dieu_chinh WHERE trang_thai = 'cho_tiep_nhan'"
                )->fetchColumn();
            } catch (PDOException $e) {}
        } catch (PDOException $e) {}

        $hoatDong = [];
        try {
            $hoatDong = $this->pdo->query(
                "SELECT ngay_tao AS thoi_gian, ho_ten, 'Tạo tài khoản' AS hanh_dong, trang_thai AS chi_tiet
                 FROM nguoi_dung ORDER BY ngay_tao DESC LIMIT 8"
            )->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}
        require_once __DIR__ . '/../views/quan_tri/dashboard.php';
    }

    public function taiKhoan() {
        $this->yeuCauQuanTri();
        $search = trim($_GET['search'] ?? '');
        $vaiTro = trim($_GET['vai_tro'] ?? '');
        $trangThai = trim($_GET['trang_thai'] ?? '');

        $sql = "SELECT n.*, v.ten_vai_tro FROM nguoi_dung n JOIN vai_tro v ON v.ma_vai_tro = n.ma_vai_tro WHERE 1=1";
        $params = [];
        if ($search !== '') {
            $sql .= " AND (n.ho_ten LIKE :search OR n.email LIKE :search OR n.so_dinh_danh LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        if ($vaiTro !== '') {
            $sql .= " AND n.ma_vai_tro = :vai_tro";
            $params[':vai_tro'] = (int)$vaiTro;
        }
        if ($trangThai !== '') {
            $sql .= " AND n.trang_thai = :trang_thai";
            $params[':trang_thai'] = $trangThai;
        }
        $sql .= " ORDER BY n.ngay_tao DESC, n.ma_nguoi_dung DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $danhSach = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/quan_tri/tai_khoan.php';
    }

    public function chiTietTaiKhoan() {
        $this->yeuCauQuanTri();
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->pdo->prepare(
            "SELECT n.*, v.ten_vai_tro FROM nguoi_dung n JOIN vai_tro v ON v.ma_vai_tro = n.ma_vai_tro WHERE n.ma_nguoi_dung = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $taiKhoan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$taiKhoan) { http_response_code(404); exit('Không tìm thấy tài khoản.'); }
        require_once __DIR__ . '/../views/quan_tri/chi_tiet_tai_khoan.php';
    }

    public function themTaiKhoan() {
        $this->yeuCauQuanTri();
        $thongBao = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            $hoTen = trim($_POST['ho_ten'] ?? '');
            $soCccd = trim($_POST['so_dinh_danh'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $maVaiTro = (int)($_POST['ma_vai_tro'] ?? 0);
            if (!preg_match('/^\d{12}$/', $soCccd)) { $thongBao = 'Số định danh phải gồm đúng 12 chữ số.'; }
            elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) { $thongBao = 'Email không hợp lệ.'; }
            elseif (!in_array($maVaiTro, [1,2,3], true)) { $thongBao = 'Vai trò không hợp lệ.'; }
            else {
                $check = $this->pdo->prepare(
                    "SELECT ma_nguoi_dung FROM nguoi_dung WHERE so_dinh_danh = :cccd OR (:email != '' AND email = :email) LIMIT 1"
                );
                $check->execute([':cccd'=>$soCccd, ':email'=>$email]);
                if ($check->fetch()) { $thongBao = 'Số định danh hoặc email đã tồn tại.'; }
                else {
                    $matKhau = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
                    $stmt = $this->pdo->prepare(
                        "INSERT INTO nguoi_dung (ma_vai_tro, so_dinh_danh, ho_ten, email, mat_khau, trang_thai)
                         VALUES (:vai_tro, :cccd, :ho_ten, :email, :mat_khau, 'hoat_dong')"
                    );
                    $stmt->execute([':vai_tro'=>$maVaiTro, ':cccd'=>$soCccd, ':ho_ten'=>$hoTen, ':email'=>$email ?: null, ':mat_khau'=>$matKhau]);
                    header('Location: /quan_tri/tai_khoan?created=1');
                    exit;
                }
            }
        }
        require_once __DIR__ . '/../views/quan_tri/them_tai_khoan.php';
    }

    public function suaTaiKhoan() {
        $this->yeuCauQuanTri();
        http_response_code(403);
        exit('Quản trị viên không được sửa trực tiếp thông tin tài khoản.');
    }

    public function xoaTaiKhoan() {
        $this->yeuCauQuanTri();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /quan_tri/tai_khoan');
            exit;
        }
        kiemTraMaCsrf();
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $this->pdo->prepare(
            "SELECT ma_vai_tro, trang_thai FROM nguoi_dung WHERE ma_nguoi_dung=:id LIMIT 1"
        );
        $stmt->execute([':id'=>$id]);
        $taiKhoan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$taiKhoan) {
            header('Location: /quan_tri/tai_khoan?error=not_found');
            exit;
        }
        if ((int)$taiKhoan['ma_vai_tro'] === 3) {
            $admins = $this->pdo->query(
                "SELECT COUNT(*) FROM nguoi_dung WHERE ma_vai_tro=3 AND trang_thai='hoat_dong'"
            )->fetchColumn();
            if ((int)$admins <= 1) {
                header('Location: /quan_tri/tai_khoan?error=last_admin');
                exit;
            }
        }

        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("DELETE FROM nguoi_dung WHERE ma_nguoi_dung=:id LIMIT 1");
            $stmt->execute([':id'=>$id]);
            $this->pdo->commit();
            header('Location: /quan_tri/tai_khoan?deleted=1');
            exit;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            header('Location: /quan_tri/tai_khoan?error=linked_data');
            exit;
        }
    }

    public function khoaTaiKhoan() {
        $this->yeuCauQuanTri();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /quan_tri/tai_khoan'); exit; }
        kiemTraMaCsrf();
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $this->pdo->prepare("SELECT ma_vai_tro FROM nguoi_dung WHERE ma_nguoi_dung=:id");
        $stmt->execute([':id'=>$id]);
        $taiKhoan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$taiKhoan) { exit('Không tìm thấy tài khoản.'); }
        if ((int)$taiKhoan['ma_vai_tro'] === 3) {
            $admins = $this->pdo->query("SELECT COUNT(*) FROM nguoi_dung WHERE ma_vai_tro=3 AND trang_thai='hoat_dong'")->fetchColumn();
            if ((int)$admins <= 1) { exit('Không thể khóa tài khoản quản trị viên cuối cùng trong hệ thống.'); }
        }
        $stmt = $this->pdo->prepare("UPDATE nguoi_dung SET trang_thai='bi_khoa' WHERE ma_nguoi_dung=:id");
        $stmt->execute([':id'=>$id]);
        header('Location: /quan_tri/tai_khoan');
        exit;
    }

    public function moKhoaTaiKhoan() {
        $this->yeuCauQuanTri();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /quan_tri/tai_khoan'); exit; }
        kiemTraMaCsrf();
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $this->pdo->prepare("UPDATE nguoi_dung SET trang_thai='hoat_dong', so_lan_sai=0 WHERE ma_nguoi_dung=:id");
        $stmt->execute([':id'=>$id]);
        header('Location: /quan_tri/tai_khoan');
        exit;
    }

    public function phanQuyenCanBo() {
        $this->yeuCauQuanTri();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            $quyen = $_POST['quyen'] ?? '';
            if (!in_array($quyen, ['quan_ly_ho_so','duyet_dieu_chinh','quan_ly_trang_thai'], true)) {
                http_response_code(400); exit('Quyền không hợp lệ.');
            }
            $bat = ($_POST['bat'] ?? '0') === '1' ? 1 : 0;
            $this->pdo->prepare(
                "UPDATE quyen_can_bo SET `$quyen`=:bat WHERE ma_vai_tro=2"
            )->execute([':bat'=>$bat]);
            $_SESSION['quyen_can_bo'] = $this->layQuyen();
            header('Location: /quan_tri/phan_quyen_can_bo');
            exit;
        }
        $quyen = $this->layQuyen();
        require_once __DIR__ . '/../views/quan_tri/phan_quyen_can_bo.php';
    }

    private function layQuyen() {
        $stmt = $this->pdo->prepare("SELECT * FROM quyen_can_bo WHERE ma_vai_tro=2 LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return [
            'quan_ly_ho_so' => (bool)($row['quan_ly_ho_so'] ?? 1),
            'duyet_dieu_chinh' => (bool)($row['duyet_dieu_chinh'] ?? 1),
            'quan_ly_trang_thai' => (bool)($row['quan_ly_trang_thai'] ?? 1)
        ];
    }
}
