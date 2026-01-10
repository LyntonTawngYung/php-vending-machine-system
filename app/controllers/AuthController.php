<?php

require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    #[Route('/login', methods: ['GET'])]
    public function showLogin() {
        // Show login form
        include __DIR__ . '/../views/login.php';
    }

    #[Route('/login', methods: ['POST'])]
    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = $this->userModel->verifyPassword($username, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: /products');
            exit;
        } else {
            $_SESSION['login_error'] = 'Invalid username or password';
            header('Location: /login');
            exit;
        }
    }

    #[Route('/logout', methods: ['POST'])]
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /login');
        exit;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'role' => $_SESSION['role']
            ];
        }
        return null;
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: /login'); // Assuming a login page
            exit();
        }
    }
}