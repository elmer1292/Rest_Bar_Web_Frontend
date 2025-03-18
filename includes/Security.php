<?php
class Security {
    public static function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitizeInput'], $data);
        }
        
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function validateUsername($username) {
        return preg_match('/^[a-zA-Z0-9_]{4,30}$/', $username);
    }

    public static function validatePassword($password) {
        // Minimum 8 characters, at least one letter and one number
        return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password);
    }

    public static function generateToken() {
        return bin2hex(random_bytes(32));
    }

    public static function validateToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}