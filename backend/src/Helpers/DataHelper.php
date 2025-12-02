<?php
namespace App\Helpers;
class DataHelper
{
    public static function sanitizeString($input)
    {
        return filter_var(trim($input), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    public static function sanitizeEmail($input)
    {
        return filter_var(trim($input), FILTER_SANITIZE_EMAIL);
        
    }
    public static function sanitizeInt($input)
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }
}