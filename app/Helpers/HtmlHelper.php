<?php

namespace App\Helpers;

class HtmlHelper
{
    /**
     * Encode HTML entities
     */
    public static function encode(?string $text): ?string
    {
        if (empty($text)) {
            return $text;
        }
        
        return htmlentities($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Decode HTML entities
     */
    public static function decode(?string $text): ?string
    {
        if (empty($text)) {
            return $text;
        }
        
        return html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Strip HTML tags
     */
    public static function strip(?string $text): ?string
    {
        if (empty($text)) {
            return $text;
        }
        
        return strip_tags($text);
    }

    /**
     * Purify HTML (remove dangerous tags but keep safe formatting)
     */
    public static function purify(?string $html): ?string
    {
        if (empty($html)) {
            return $html;
        }
        
        // Allow safe HTML tags
        $allowedTags = '<p><br><strong><b><em><i><u><ul><ol><li><a><blockquote><code><pre><h1><h2><h3><h4><h5><h6>';
        
        return strip_tags($html, $allowedTags);
    }

    /**
     * Convert HTML to plain text (preserve line breaks)
     */
    public static function toPlainText(?string $html): ?string
    {
        if (empty($html)) {
            return $html;
        }
        
        // Replace <br> tags with newlines (case insensitive, with or without attributes)
        $text = preg_replace('/<br\s*\/?>$/i', "\n", $html);
        
        // Replace closing </p> tags with double newlines
        $text = preg_replace('/<\/p>/i', "\n\n", $text);
        
        // Remove all other HTML tags
        $text = strip_tags($text);
        
        // Decode HTML entities
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Remove multiple consecutive newlines (keep max 2)
        $text = preg_replace("/\n{3,}/", "\n\n", $text);
        
        // Trim whitespace from each line
        $lines = explode("\n", $text);
        $lines = array_map('trim', $lines);
        $text = implode("\n", $lines);
        
        return trim($text);
    }

    /**
     * Escape for JavaScript
     */
    public static function escapeJs(?string $text): ?string
    {
        if (empty($text)) {
            return $text;
        }
        
        return json_encode($text, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

    /**
     * Truncate HTML safely
     */
    public static function truncate(?string $html, int $length = 100, string $ending = '...'): ?string
    {
        if (empty($html)) {
            return $html;
        }
        
        $text = self::toPlainText($html);
        
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        
        return mb_substr($text, 0, $length) . $ending;
    }

    /**
     * Remove all whitespace and newlines
     */
    public static function minify(?string $html): ?string
    {
        if (empty($html)) {
            return $html;
        }
        
        // Remove comments
        $html = preg_replace('/<!--.*?-->/s', '', $html);
        
        // Remove whitespace between tags
        $html = preg_replace('/>\s+</', '><', $html);
        
        // Remove multiple spaces
        $html = preg_replace('/\s+/', ' ', $html);
        
        return trim($html);
    }
}