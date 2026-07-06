<?php

class AkunController extends Controller
{
    private User $userModel;
    public function __construct()
    {
        $this->requireAdmin();
        $this->userModel = new User();
    }

    // kelola_akun.php
    public function index(): void
    {
        $flash       = $this->takeFlash();
        $cari        = trim($_GET['cari'] ?? '');
        $filter_role = trim($_GET['role'] ?? '');
        $users = $this->userModel->all($cari, $filter_role);
        $this->render('akun/index', [
            'role'        => $_SESSION['role'] ?? '',
            'username'    => $_SESSION['username'] ?? 'User',
            'msg'         => $flash['msg'],
            'msg_type'    => $flash['msg_type'],
            'cari'        => $cari,
            'filter_role' => $filter_role,
            'users'       => $users,
            'total_rows'  => count($users),
            'stat'        => $this->userModel->stats(),
        ]);
    }

    // Backend/proses_akun.php?aksi=tambah
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('kelola_akun.php'); }
        $username = htmlspecialchars(trim($_POST['username'] ?? ''));
        $password = trim($_POST['password'] ?? '');
        $role     = htmlspecialchars(trim($_POST['role'] ?? ''));
        if ($username !== '' && $password !== '' && $role !== '') {
            $this->userModel->create($username, password_hash($password, PASSWORD_BCRYPT), $role);
            $this->setFlash("Akun $username berhasil didaftarkan.", 'success');
        } else {
            $this->setFlash('Data registrasi tidak lengkap.', 'danger');
        }
        $this->redirect('kelola_akun.php');
    }

    // Backend/proses_akun.php?aksi=edit
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('kelola_akun.php'); }
        $id       = intval($_POST['id'] ?? 0);
        $username = htmlspecialchars(trim($_POST['username'] ?? ''));
        $role     = htmlspecialchars(trim($_POST['role'] ?? ''));
        $password = trim($_POST['password'] ?? '');
        if ($id > 0 && $username !== '' && $role !== '') {
            if ($password !== '') {
                $this->userModel->updateWithPassword($id, $username, password_hash($password, PASSWORD_BCRYPT), $role);
            } else {
                $this->userModel->updateWithoutPassword($id, $username, $role);
            }
            $this->setFlash("Data akun $username berhasil diperbarui.", 'success');
        } else {
            $this->setFlash('Data update tidak valid.', 'danger');
        }
        $this->redirect('kelola_akun.php');
    }

    // Backend/proses_akun.php?aksi=hapus
    public function hapus(): void
    {
        $id = intval($_GET['id'] ?? 0);
        if ($id === (int) ($_SESSION['user_id'] ?? 0)) {
            $this->setFlash('Fatal: Anda tidak bisa menghapus akun yang sedang Anda gunakan sendiri.', 'danger');
            $this->redirect('kelola_akun.php');
        }
        if ($id > 0) {
            $this->userModel->delete($id);
            $this->setFlash('Akun berhasil dihapus.', 'success');
        }
        $this->redirect('kelola_akun.php');
    }
}
