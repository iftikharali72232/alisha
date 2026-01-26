<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Get placeholder image URL
     */
    public static function placeholder(int $width = 800, int $height = 600, string $text = 'No Image'): string
    {
        // Using placeholder.com service
        return "https://via.placeholder.com/{$width}x{$height}/f3f4f6/9ca3af?text=" . urlencode($text);
    }

    /**
     * Get image URL with fallback to placeholder
     */
    public static function url(?string $path, int $width = 800, int $height = 600, string $fallbackText = 'No Image'): string
    {
        if ($path && \Storage::disk('public')->exists($path)) {
            return \Storage::url($path);
        }

        return self::placeholder($width, $height, $fallbackText);
    }

    /**
     * Get avatar URL with fallback
     */
    public static function avatar(?string $path, string $name = 'User', int $size = 100): string
    {
        if ($path && \Storage::disk('public')->exists($path)) {
            return \Storage::url($path);
        }

        // Use UI Avatars service
        return "https://ui-avatars.com/api/?name=" . urlencode($name) . "&size={$size}&background=f43f5e&color=fff&bold=true";
    }

    /**
     * Get featured image URL with fallback
     */
    public static function featured(?string $path, string $title = 'Blog Post'): string
    {
        return self::url($path, 1200, 630, $title);
    }

    /**
     * Get thumbnail URL with fallback
     */
    public static function thumbnail(?string $path, string $title = 'Image'): string
    {
        return self::url($path, 400, 300, $title);
    }

    /**
     * Get category image URL with fallback
     */
    public static function category(?string $path, string $name = 'Category'): string
    {
        return self::url($path, 600, 400, $name);
    }
}
