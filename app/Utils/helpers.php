<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

if (!function_exists('formatFileSize')) {
    function formatFileSize(int $sizeInKB)
    {
        $sizeInMB = $sizeInKB / 1024; // Convert KB to MB
        return number_format($sizeInMB, 0) . 'MB'; // Format without decimal places
    }
}

if (!function_exists('user')) {
    function user(): User | null
    {
        return Auth::user();
    }
}

if (!function_exists('format_number')) {
    function format_number($number, $decimals = 0)
    {
        return number_format($number, $decimals, ',', '.');
    }
}

if (!function_exists('decimal_format')) {
    function decimal_format($currency): string
    {
        return number_format($currency, 2, '.', '');
    }
}
