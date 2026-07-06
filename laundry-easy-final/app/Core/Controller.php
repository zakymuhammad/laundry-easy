<?php

// Base controller: render(), guard, flash, redirect
abstract class Controller
{
    // Render view dari /views (.view.php)
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $file = APP_ROOT . '/views/' . $view . '.view.php';
        if (!is_file($file)) {
            die('View tidak ditemukan: ' . $view);
        }
        require $file;
    }

    // Redirect relatif terhadap root proyek
    protected function redirect(string $page): void
    {
        header('Location: ' . BASE_URL . '/' . ltrim($page, '/'));
        exit;
    }

    // Guard: wajib login
    protected function requireLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login.php');
        }
    }

    // Guard: wajib Admin
    protected function requireAdmin(): void
    {
        $this->requireLogin();
        if (($_SESSION['role'] ?? '') !== 'Admin') {
            $this->redirect('dashboard.php');
        }
    }

    protected function isAdmin(): bool
    {
        return ($_SESSION['role'] ?? '') === 'Admin';
    }

    // Flash message
    protected function setFlash(string $msg, string $type = 'success'): void
    {
        $_SESSION['msg']      = $msg;
        $_SESSION['msg_type'] = $type;
    }

    protected function takeFlash(): array
    {
        $msg  = $_SESSION['msg']      ?? '';
        $type = $_SESSION['msg_type'] ?? '';
        unset($_SESSION['msg'], $_SESSION['msg_type']);
        return ['msg' => $msg, 'msg_type' => $type];
    }
}
