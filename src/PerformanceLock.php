<?php

namespace Naqla\PerformanceLock;

use Illuminate\Support\Facades\File;

class PerformanceLock
{
    /**
     * Get the lock file path (hidden in vendor package)
     */
    protected static function getLockFilePath(): string
    {
        return __DIR__ . '/../.lock';
    }

    /**
     * Check if site is locked
     */
    public static function isLocked(): bool
    {
        return File::exists(self::getLockFilePath());
    }

    /**
     * Lock the site
     */
    public static function lock(): void
    {
        $lockData = [
            'locked' => true,
            'locked_at' => now()->toDateTimeString(),
            'locked_by_ip' => request()->ip() ?? 'console',
            'user_agent' => request()->userAgent() ?? 'N/A',
        ];

        File::put(self::getLockFilePath(), json_encode($lockData, JSON_PRETTY_PRINT));
    }

    /**
     * Unlock the site
     */
    public static function unlock(): void
    {
        if (File::exists(self::getLockFilePath())) {
            File::delete(self::getLockFilePath());
        }
    }

    /**
     * Toggle lock state
     */
    public static function toggle(): void
    {
        if (self::isLocked()) {
            self::unlock();
        } else {
            self::lock();
        }
    }

    /**
     * Get lock information
     * 
     * @return array|null
     */
    public static function getLockInfo(): ?array
    {
        if (!self::isLocked()) {
            return null;
        }

        try {
            $content = File::get(self::getLockFilePath());
            return json_decode($content, true);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get locked duration in human readable format
     * 
     * @return string|null
     */
    public static function getLockedDuration(): ?string
    {
        $info = self::getLockInfo();
        
        if (!$info || !isset($info['locked_at'])) {
            return null;
        }

        try {
            $lockedAt = \Carbon\Carbon::parse($info['locked_at']);
            return $lockedAt->diffForHumans();
        } catch (\Exception $e) {
            return null;
        }
    }
}