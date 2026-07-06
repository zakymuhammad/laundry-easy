<?php

class AuthController extends Controller
{
    private User $userModel;
    public function __construct() { $this->userModel = new User(); }

    // login.php
    public function showLogin(): void
    {
        if (isset($_SESSION['user_id'])) { $this->redirect('dashboard.php'); }
        $this->render('auth/login');
    }

    // Backend/login_process.php
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars(trim($_POST['username'] ?? ''));
            $password = trim($_POST['password'] ?? '');
            $role     = htmlspecialchars(trim($_POST['role'] ?? ''));

            if ($username !== '' && $password !== '' && $role !== '') {
                $user = $this->userModel->findByCredentials($username, $role);
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id']  = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role']     = $user['role'];
                    $this->redirect('dashboard.php');
                }
            }
            $this->redirect('login.php?error=1');
        }
        $this->redirect('login.php');
    }

    // logout.php
    public function logout(): void
    {
        session_unset();
        session_destroy();
        $this->redirect('login.php');
    }
}
