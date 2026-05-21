<?php
class Utility
{
    public static function formatPrice($price)
    {
        return number_format($price, 2, '.', ',');
    }

    public static function truncate($text, $length = 100, $suffix = '...')
    {
        if (strlen($text) > $length) {
            return substr($text, 0, $length) . $suffix;
        }
        return $text;
    }

    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function sanitize($data)
    {
        return htmlspecialchars(strip_tags($data), ENT_QUOTES, 'UTF-8');
    }

    public static function redirect($url)
    {
        header("Location: " . $url);
        exit();
    }

    public static function baseUrl()
    {
        return 'http://' . $_SERVER['HTTP_HOST'] . '/webbanhang/';
    }
}
