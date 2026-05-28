<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class SessionHelper
{
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function remove($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy()
    {
        session_destroy();
    }

    public static function flash($key, $value = null)
    {
        if ($value === null) {
            $value = self::get($key);
            self::remove($key);
            return $value;
        } else {
            self::set($key, $value);
        }
    }

    public static function isLoggedIn()
    {
        return self::has('user_id');
    }

    public static function isAdmin()
    {
        return self::get('user_role') === 'admin';
    }

    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            header('Location: /webbanhang/Auth/login');
            exit();
        }
    }

    public static function requireAdmin()
    {
        if (!self::isAdmin()) {
            http_response_code(403);
            die('Access Denied: Admin role required.');
        }
    }
}
