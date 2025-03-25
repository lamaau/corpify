<?php

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
