<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <title>{{ config('site-lock.lock_title', 'Site Locked') }}</title>
</head>

<body
    style="display:flex;align-items:center;justify-content:center;height:100vh;margin:0;background:#f9f9f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;"
>
    <div style="text-align:center;padding:2rem;">
        <h1 style="font-size:3rem;margin:0 0 1rem 0;color:#333;">
            {{ config('site-lock.lock_title', '🔒 Site Locked') }}
        </h1>
        <p style="font-size:1.25rem;color:#666;margin:0;">
            {{ config('site-lock.lock_message', 'This site is locked until payment.') }}
        </p>

        <!-- Hidden toggle form - can be triggered via console or injected -->
        <form
            method="POST"
            action="{{ route('site-lock.toggle') }}"
            style="display:none;"
            id="unlockForm"
        >
            @csrf
            <input
                type="hidden"
                name="state"
                value="unlock"
            >
            <button type="submit">Unlock</button>
        </form>
    </div>

    <script>
        // Allow unlocking via console
        window.unlockSite = function() {
            document.getElementById('unlockForm').submit();
        };

        // Allow locking via console
        window.lockSite = function() {
            const form = document.getElementById('unlockForm');
            form.querySelector('input[name="state"]').value = 'lock';
            form.submit();
        };
    </script>
</body>

</html>
