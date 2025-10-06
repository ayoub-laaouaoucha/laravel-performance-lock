# Laravel Performance Lock 🔒

[![Latest Version](https://img.shields.io/packagist/v/naqla/laravel-performance-lock.svg?style=flat-square)](https://packagist.org/packages/naqla/laravel-performance-lock)
[![Total Downloads](https://img.shields.io/packagist/dt/naqla/laravel-performance-lock.svg?style=flat-square)](https://packagist.org/packages/naqla/laravel-performance-lock)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

A simple and elegant Laravel package that allows you to lock your entire website until client payment or any other condition is met. Perfect for freelancers and agencies who want to ensure payment before delivering the final product.

## Features

- 🔐 **Lock/Unlock entire website** with a single command
- 🎨 **Customizable lock page** with your own views
- 🔑 **Secret unlock URL** for secure access
- ⚡ **Easy toggle** via routes or programmatically
- 🎯 **Middleware-based** protection
- 📝 **Configurable messages** and titles
- 🚀 **Zero dependencies** (uses only Laravel core)

## Requirements

- PHP 8.2 or higher
- Laravel 11.x or 12.x

## Installation

You can install the package via composer:

```bash
composer require naqla/laravel-performance-lock
```

### Publish Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=performance-lock-config
```

This will create a `config/performance-lock.php` file where you can customize the lock message and title.

### Publish Views (Optional)

If you want to customize the lock page view:

```bash
php artisan vendor:publish --tag=performance-lock-views
```

This will publish the views to `resources/views/vendor/performance-lock/locked.blade.php`.

## Configuration

Add these variables to your `.env` file:

```env
SITE_LOCKED=false
SITE_LOCK_TITLE="Site Locked"
SITE_LOCK_MESSAGE="This site is locked until payment."
```

## Usage

### 1. Apply Middleware

Apply the middleware to your routes that you want to protect:

#### Protect All Routes

In `bootstrap/app.php` (Laravel 11+):

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \Naqla\PerformanceLock\Http\Middleware\PerformanceLockMiddleware::class,
    ]);
})
```

Or in `app/Http/Kernel.php` (Laravel 10):

```php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \Naqla\PerformanceLock\Http\Middleware\PerformanceLockMiddleware::class,
    ],
];
```

#### Protect Specific Routes

```php
Route::middleware(['performance-lock'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'show']);
});
```

### 2. Lock/Unlock Methods

#### Using Built-in Routes

**Lock the site:**

```
https://yourdomain.com/lock
```

**Unlock the site (with secret code):**

```
https://yourdomain.com/unlock/AYOUBdata333@
```

> ⚠️ **Important:** Change the secret code in `routes/web.php` to your own secure code!

#### Programmatically

```php
use Naqla\PerformanceLock\PerformanceLock;

// Lock the site
PerformanceLock::lock();

// Unlock the site
PerformanceLock::unlock();

// Toggle lock state
PerformanceLock::toggle();

// Check if site is locked
if (PerformanceLock::isLocked()) {
    // Site is locked
}
```

#### Using API Endpoint

```bash
# Lock the site
curl -X POST https://yourdomain.com/performance-lock/toggle \
  -H "Content-Type: application/json" \
  -d '{"state": "lock"}'

# Unlock the site
curl -X POST https://yourdomain.com/performance-lock/toggle \
  -H "Content-Type: application/json" \
  -d '{"state": "unlock"}'

# Toggle current state
curl -X POST https://yourdomain.com/performance-lock/toggle
```

### 3. Artisan Commands (Optional)

Create custom Artisan commands for easier management:

```php
// app/Console/Commands/LockSite.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Naqla\PerformanceLock\PerformanceLock;

class LockSite extends Command
{
    protected $signature = 'site:lock';
    protected $description = 'Lock the website';

    public function handle()
    {
        PerformanceLock::lock();
        $this->info('Site has been locked! 🔒');
    }
}
```

```bash
php artisan site:lock
php artisan site:unlock
```

## Customization

### Custom Lock Page

After publishing the views, edit `resources/views/vendor/performance-lock/locked.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('performance-lock.lock_title') }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            color: white;
        }
        .lock-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="lock-icon">🔒</div>
        <h1>{{ config('performance-lock.lock_title') }}</h1>
        <p>{{ config('performance-lock.lock_message') }}</p>
    </div>
</body>
</html>
```

### Change Secret Code

Edit `routes/web.php` and change the secret code:

```php
Route::get('/unlock/{code}', function($code) {
    if ($code !== 'YOUR-SUPER-SECRET-CODE-HERE') {
        abort(404);
    }

    \Naqla\PerformanceLock\PerformanceLock::unlock();
    return redirect('/')->with('status', 'Site has been unlocked 🔓');
})->name('performance-lock.unlock');
```

### Custom Routes

You can disable the default routes by not loading them, and create your own:

```php
// In your routes/web.php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/admin/site/lock', function() {
        \Naqla\PerformanceLock\PerformanceLock::lock();
        return back()->with('success', 'Site locked!');
    });

    Route::post('/admin/site/unlock', function() {
        \Naqla\PerformanceLock\PerformanceLock::unlock();
        return back()->with('success', 'Site unlocked!');
    });
});
```

## Use Cases

- **Freelance Projects:** Lock the site until the client makes the final payment
- **Agency Work:** Ensure milestone payments before deployment
- **Maintenance Mode:** Different from Laravel's maintenance mode, allows specific unlock mechanisms
- **Demo Sites:** Lock after trial period expires
- **Staging Environments:** Restrict access to staging sites

## How It Works

1. The package adds a `SITE_LOCKED` variable to your `.env` file
2. Middleware checks this value on each request
3. If locked, users see a custom lock page (403 response)
4. The unlock route bypasses the middleware
5. Lock state persists even after deployment or server restart

## Security Considerations

- Always use a strong, unique secret code for the unlock URL
- Consider adding additional authentication to lock/unlock routes in production
- The `/lock` route is public by default - protect it if needed:
  ```php
  Route::middleware(['auth'])->get('/lock', function() {
      \Naqla\PerformanceLock\PerformanceLock::lock();
      return redirect('/')->with('status', 'Site has been locked 🔒');
  });
  ```
- Don't share your unlock URL publicly

## Testing

The package automatically bypasses the lock for the toggle routes, allowing you to always access the unlock functionality.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Credits

- [AYOUB LAAOUAOUCHA](https://github.com/naqla)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support

If you find this package helpful, please consider:

- ⭐ Starring the repository
- 🐛 Reporting issues
- 📖 Improving documentation
- 🔀 Contributing code

---

Made with ❤️ by [AYOUB LAAOUAOUCHA](mailto:laaouaoucha333@gmail.com)
