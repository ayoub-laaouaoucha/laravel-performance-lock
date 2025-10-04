<?php

namespace Ayoub\SiteLock;

use Illuminate\Support\Facades\File;

class SiteLock
{
    protected static function getEnvFilePath(): string
    {
        return base_path('.env');
    }

    public static function isLocked(): bool
    {
        return env('SITE_LOCKED', false) === true || env('SITE_LOCKED') === 'true' || env('SITE_LOCKED') === '1';
    }

    public static function lock(): void
    {
        self::setState(true);
    }

    public static function unlock(): void
    {
        self::setState(false);
    }

    public static function toggle(): void
    {
        if (self::isLocked()) {
            self::unlock();
        } else {
            self::lock();
        }
    }

    protected static function setState(bool $locked): void
    {
        $envPath = self::getEnvFilePath();
        
        if (!File::exists($envPath)) {
            return;
        }

        $envContent = File::get($envPath);
        $value = $locked ? 'true' : 'false';
        
        // Check if SITE_LOCKED exists
        if (preg_match('/^SITE_LOCKED=.*/m', $envContent)) {
            // Update existing value
            $envContent = preg_replace(
                '/^SITE_LOCKED=.*/m',
                "SITE_LOCKED={$value}",
                $envContent
            );
        } else {
            // Add new line
            $envContent = rtrim($envContent) . "\n\nSITE_LOCKED={$value}\n";
        }

        File::put($envPath, $envContent);

        // Clear config cache to reflect changes
        if (function_exists('config')) {
            config(['site-lock.locked' => $locked]);
        }
    }
}