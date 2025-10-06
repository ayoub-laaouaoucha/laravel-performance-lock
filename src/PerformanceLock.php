<?php

namespace Naqla\PerformanceLock;

use Illuminate\Support\Facades\File;

class PerformanceLock
{
    protected static function getEnvFilePath(): string
    {
        return base_path('.env');
    }

    public static function isLocked(): bool
    {
        return env('PERFORMANCE_LOCKED', false) === true || env('PERFORMANCE_LOCKED') === 'true' || env('PERFORMANCE_LOCKED') === '1';
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
        
        // Check if PERFORMANCE_LOCKED exists
        if (preg_match('/^PERFORMANCE_LOCKED=.*/m', $envContent)) {
            // Update existing value
            $envContent = preg_replace(
                '/^PERFORMANCE_LOCKED=.*/m',
                "PERFORMANCE_LOCKED={$value}",
                $envContent
            );
        } else {
            // Add new line
            $envContent = rtrim($envContent) . "\n\nPERFORMANCE_LOCKED={$value}\n";
        }

        File::put($envPath, $envContent);

        // Clear config cache to reflect changes
        if (function_exists('config')) {
            config(['performance-lock.locked' => $locked]);
        }
    }
}