# Laravel Site Lock

A Laravel package to lock your website until client payment. Inspired by Spatie's package architecture. Uses `.env` file for lock state - no database required!

## Features

- 🔒 Lock entire website with middleware
- 📝 ENV-based lock state (no database needed!)
- 🎨 Customizable lock page
- 🔧 Easy toggle via hidden form or console
- ✅ Laravel 11 & 12+ support
- 🚀 Spatie-style architecture

## Installation

```bash
composer require ayoub/laravel-site-lock
```

## Setup

### 1. Publish Configuration & Views

```bash
php artisan vendor:publish --tag=site-lock-config
php artisan vendor:publish --tag=site-lock-views
```

### 2. Add to .env

Add this to your `.env` file:

```env
SITE_LOCKED=false
SITE_LOCK_TITLE="🔒 Site Locked"
SITE_LOCK_MESSAGE="This site is locked until payment."
```

### 3. Apply Middleware

#### Global (Lock entire site)

In `bootstrap/app.php` (Laravel 11+):

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \Ayoub\SiteLock\Http\Middleware\SiteLockMiddleware::class,
    ]);
})
```

#### Or in `app/Http/Kernel.php` (Laravel 10):

```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \Ayoub\SiteLock\Http\Middleware\SiteLockMiddleware::class,
    ],
];
```

#### Route-specific

```php
Route::middleware(['site-lock'])->group(function () {
    // Your routes here
});
```

## Usage

### Lock/Unlock via Code

```php
use Ayoub\SiteLock\SiteLock;

// Lock the site (updates .env file)
SiteLock::lock();

// Unlock the site (updates .env file)
SiteLock::unlock();

// Toggle
SiteLock::toggle();

// Check status
if (SiteLock::isLocked()) {
    // Site is locked
}
```

### Manual .env Update

You can also manually edit your `.env` file:

```env
# Lock the site
SITE_LOCKED=true

# Unlock the site
SITE_LOCKED=false
```

**Important:** After manually changing `.env`, clear the config cache:

```bash
php artisan config:clear
```

### Unlock via Hidden Form

The lock page includes a hidden form. You can trigger it via:

1. **Browser Console:**

```javascript
unlockSite(); // Unlocks the site
lockSite(); // Locks the site
```

2. **Inject & Submit:**

```javascript
// From browser console or bookmarklet
document.getElementById("unlockForm").submit();
```

3. **Direct POST Request:**

```bash
curl -X POST https://yoursite.com/site-lock/toggle \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "state=unlock&_token=YOUR_CSRF_TOKEN"
```

### API Usage

```php
// In your payment webhook/controller
public function handlePayment(Request $request)
{
    if ($request->payment_status === 'paid') {
        SiteLock::unlock();

        // Notify client
        Mail::to($client)->send(new SiteUnlockedMail());
    }
}
```

## Configuration

Edit `config/site-lock.php`:

```php
return [
    'locked' => env('SITE_LOCKED', false),
    'lock_message' => env('SITE_LOCK_MESSAGE', 'This site is locked until payment.'),
    'lock_title' => env('SITE_LOCK_TITLE', '🔒 Site Locked'),
];
```

Or use `.env` variables:

```env
SITE_LOCKED=false
SITE_LOCK_TITLE="Payment Required"
SITE_LOCK_MESSAGE="Please complete payment to access this site."
```

## Customization

### Custom Lock View

After publishing views, edit `resources/views/vendor/site-lock/locked.blade.php`:

```html
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Custom Lock Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="text-center">
      <h1 class="text-4xl font-bold mb-4">
        {{ config('site-lock.lock_title') }}
      </h1>
      <p class="text-xl text-gray-600">
        {{ config('site-lock.lock_message') }}
      </p>

      <form
        method="POST"
        action="{{ route('site-lock.toggle') }}"
        style="display:none;"
        id="unlockForm"
      >
        @csrf
        <input type="hidden" name="state" value="unlock" />
        <button type="submit">Unlock</button>
      </form>
    </div>
  </body>
</html>
```

## How It Works

The package reads the `SITE_LOCKED` value from your `.env` file:

- When you call `SiteLock::lock()`, it writes `SITE_LOCKED=true` to `.env`
- When you call `SiteLock::unlock()`, it writes `SITE_LOCKED=false` to `.env`
- The middleware checks this value on every request

**Note:** .env changes take effect immediately on the next request (no need to restart the server).

## Security Notes

⚠️ **Important:** This package is designed for development/staging environments or specific use cases. For production:

1. **Protect the unlock route** with authentication:

```php
// In your routes/web.php
Route::post('/site-lock/toggle', function() {
    abort(404); // Disable the route
});

// Create your own protected route
Route::post('/admin/site-lock/toggle', [SiteLockController::class, 'toggle'])
    ->middleware(['auth', 'admin']);
```

2. **Or remove the hidden form** from the view and only unlock via code/admin panel

3. **File Permissions:** Ensure your `.env` file is writable by the web server, but not publicly accessible (it shouldn't be in your webroot).

## Testing

```php
use Ayoub\SiteLock\SiteLock;

// In your tests
public function test_site_can_be_locked()
{
    SiteLock::unlock();

    $response = $this->get('/');
    $response->assertStatus(200);

    SiteLock::lock();

    $response = $this->get('/');
    $response->assertStatus(403);
    $response->assertSee('Site Locked');
}
```

## Package Structure

```
laravel-site-lock/
├── config/
│   └── site-lock.php
├── resources/
│   └── views/
│       └── locked.blade.php
├── routes/
│   └── web.php
├── src/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── SiteLockController.php
│   │   └── Middleware/
│   │       └── SiteLockMiddleware.php
│   ├── SiteLock.php
│   └── SiteLockServiceProvider.php
├── composer.json
└── README.md
```

## Advantages of ENV-based Approach

✅ No database migrations needed  
✅ Works immediately after installation  
✅ Easy to check status: just open `.env`  
✅ Can be version controlled (if you want)  
✅ Simple deployment: just change one env variable  
✅ No database queries on every request

## License

MIT

## Credits

Inspired by [Spatie's Laravel packages](https://spatie.be/open-source)
