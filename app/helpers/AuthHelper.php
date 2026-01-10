<?php

class AuthHelper {
    public static function isLoggedIn() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    public static function getCurrentUser() {
        if (self::isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'role' => $_SESSION['role']
            ];
        }
        return null;
    }

    public static function hasRole($role) {
        $user = self::getCurrentUser();
        return $user && $user['role'] === $role;
    }

    public static function isAdmin() {
        return self::hasRole('admin');
    }

    public static function isUser() {
        return self::hasRole('user');
    }

    public static function requireRole($role) {
        if (!self::hasRole($role)) {
            http_response_code(302);
            header('Location: /access_denied.php'); // Assuming an access denied page
            exit();
        }
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /login');
            exit();
        }
    }
}