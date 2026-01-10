<?php

require_once __DIR__ . '/JwtHelper.php';
require_once __DIR__ . '/../models/UserModel.php';

class ApiAuthHelper {
    public static function getTokenFromHeader() {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public static function getUserFromToken() {
        $token = self::getTokenFromHeader();
        if (!$token) return null;
        $payload = JwtHelper::decode($token);
        if (!$payload || !isset($payload['exp']) || $payload['exp'] < time()) {
            return null;
        }
        return $payload;
    }

    public static function requireAuth() {
        $user = self::getUserFromToken();
        if (!$user) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }
        return $user;
    }

    public static function requireRole($role) {
        $user = self::requireAuth();
        if ($user['role'] !== $role) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit();
        }
        return $user;
    }
}