<?php

namespace App\Services;

class CloudDocumentService
{
    /**
     * Convert cloud storage links to embeddable/previewable URLs
     */
    public static function convertToPreviewUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        // Google Drive conversion
        if (self::isGoogleDriveUrl($url)) {
            return self::convertGoogleDriveUrl($url);
        }

        // OneDrive conversion
        if (self::isOneDriveUrl($url)) {
            return self::convertOneDriveUrl($url);
        }

        // Dropbox conversion
        if (self::isDropboxUrl($url)) {
            return self::convertDropboxUrl($url);
        }

        // Direct link - no conversion needed
        return $url;
    }

    /**
     * Check if URL is from Google Drive
     */
    public static function isGoogleDriveUrl(string $url): bool
    {
        return str_contains($url, 'drive.google.com') ||
            str_contains($url, 'docs.google.com');
    }

    /**
     * Convert Google Drive URL to preview URL
     */
    public static function convertGoogleDriveUrl(string $url): string
    {
        // Pattern 1: /file/d/{fileId}/view
        if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return "https://drive.google.com/file/d/{$matches[1]}/preview";
        }

        // Pattern 2: /open?id={fileId}
        if (preg_match('/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return "https://drive.google.com/file/d/{$matches[1]}/preview";
        }

        // Pattern 3: Google Docs/Sheets/Slides
        if (preg_match('/docs\.google\.com\/(document|spreadsheets|presentation)\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $type = $matches[1];
            $fileId = $matches[2];
            return "https://docs.google.com/{$type}/d/{$fileId}/preview";
        }

        return $url;
    }

    /**
     * Check if URL is from OneDrive
     */
    public static function isOneDriveUrl(string $url): bool
    {
        return str_contains($url, 'onedrive.live.com') ||
            str_contains($url, '1drv.ms') ||
            str_contains($url, 'sharepoint.com');
    }

    /**
     * Convert OneDrive URL to embed URL
     */
    public static function convertOneDriveUrl(string $url): string
    {
        // Replace view with embed
        if (str_contains($url, 'view.aspx')) {
            return str_replace('view.aspx', 'embed', $url);
        }

        // Add embed parameter
        $separator = str_contains($url, '?') ? '&' : '?';
        return $url . $separator . 'action=embedview';
    }

    /**
     * Check if URL is from Dropbox
     */
    public static function isDropboxUrl(string $url): bool
    {
        return str_contains($url, 'dropbox.com');
    }

    /**
     * Convert Dropbox URL to direct link
     */
    public static function convertDropboxUrl(string $url): string
    {
        // Replace www.dropbox.com with dl.dropboxusercontent.com
        $url = str_replace('www.dropbox.com', 'dl.dropboxusercontent.com', $url);

        // Change dl=0 to dl=1 for direct download
        return str_replace('dl=0', 'dl=1', $url);
    }

    /**
     * Get document type from URL
     */
    public static function getDocumentType(string $url): ?string
    {
        // Extract file extension
        if (preg_match('/\.(pdf|docx?|xlsx?|pptx?)$/i', $url, $matches)) {
            return strtolower($matches[1]);
        }

        // Check by URL patterns
        if (str_contains($url, 'docs.google.com/document')) {
            return 'doc';
        }
        if (str_contains($url, 'docs.google.com/spreadsheets')) {
            return 'xls';
        }
        if (str_contains($url, 'docs.google.com/presentation')) {
            return 'ppt';
        }

        return null;
    }

    /**
     * Validate if URL is accessible
     */
    public static function validateUrl(string $url): bool
    {
        try {
            $headers = @get_headers($url);
            return $headers && str_contains($headers[0], '200');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get cloud provider name from URL
     */
    public static function getProvider(string $url): ?string
    {
        if (self::isGoogleDriveUrl($url)) {
            return 'Google Drive';
        }
        if (self::isOneDriveUrl($url)) {
            return 'OneDrive';
        }
        if (self::isDropboxUrl($url)) {
            return 'Dropbox';
        }
        return null;
    }
}
