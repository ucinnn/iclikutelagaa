<?php

use App\Helpers\HtmlHelper;

if (!function_exists('convertExternalImageUrl')) {
    function convertExternalImageUrl(string $url): string
    {
        // 🔹 Google Drive
        if (preg_match('/drive\.google\.com\/file\/d\/([^\/]+)/', $url, $m)) {
            return "https://drive.google.com/uc?export=view&id={$m[1]}";
        }

        // 🔹 OneDrive
        if (str_contains($url, '1drv.ms') || str_contains($url, 'onedrive.live.com')) {
            $parsed = parse_url($url);
            parse_str($parsed['query'] ?? '', $q);
            if (isset($q['resid'])) {
                return "https://onedrive.live.com/download?resid={$q['resid']}";
            }
        }

        return $url;
    }
}


if (!function_exists('html_encode')) {
    function html_encode(?string $text): ?string
    {
        return HtmlHelper::encode($text);
    }
}

if (!function_exists('html_decode')) {
    function html_decode(?string $text): ?string
    {
        return HtmlHelper::decode($text);
    }
}

if (!function_exists('html_strip')) {
    function html_strip(?string $text): ?string
    {
        return HtmlHelper::strip($text);
    }
}

if (!function_exists('html_purify')) {
    function html_purify(?string $html): ?string
    {
        return HtmlHelper::purify($html);
    }
}

if (!function_exists('html_to_text')) {
    function html_to_text(?string $html): ?string
    {
        return HtmlHelper::toPlainText($html);
    }
}