<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <title>{{ config('performance-lock.lock_title', 'Site Locked') }}</title>
</head>
<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
        background: black;
        color: #411;
        font-family: Consolas, Courier, monospace;
        font-size: 60px;
        text-shadow: 0 0 15px #411;
        height: 100%;
    }

    .glow {
        color: #f00;
        text-shadow: 0px 0px 10px #f00;
    }

    span {
        display: inline-block;
        padding: 0 10px;
    }
</style>

<body>
    <div style="text-align:center;padding:1rem;">
        <div
            id="hacked"
            style="text-wrap-mode: wrap;"
        >
            {{ config('performance-lock.lock_message', 'This website is Blocked !!!') }}
        </div>
        <div class="random"></div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
    let letter_count = 0;
    const el = $("#hacked");
    const word = el.text().trim();
    let finished = false;

    el.html("");
    for (let i = 0; i < word.length; i++) {
        el.append("<span>" + word.charAt(i) + "</span>");
    }

    function write() {
        for (let i = letter_count; i < word.length; i++) {
            const c = Math.floor(Math.random() * alphabet.length);
            $("span")[i].innerHTML = alphabet[c];
        }
        if (!finished) {
            setTimeout(write, 75);
        }
    }

    function inc() {
        $("span")[letter_count].innerHTML = word[letter_count];
        $("span:eq(" + letter_count + ")").addClass("glow");
        letter_count++;
        if (letter_count >= word.length) {
            finished = true;
            setTimeout(reset, 1500);
        } else {
            setTimeout(inc, 100);
        }
    }

    function reset() {
        letter_count = 0;
        finished = false;
        $("span").removeClass("glow");
        setTimeout(write, 75);
        setTimeout(inc, 1000);
    }

    function generateRandomAlphabets(count = 450, selector = '.random') {
        const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        const container = document.querySelector(selector);

        if (!container) return;

        // Clear previous content
        container.innerHTML = '';

        // Generate random characters
        let fragment = document.createDocumentFragment();
        for (let i = 0; i < count; i++) {
            const span = document.createElement('span');
            span.textContent = alphabet[Math.floor(Math.random() * alphabet.length)];
            fragment.appendChild(span);
        }

        container.appendChild(fragment);
    }

    // Start animation
    setInterval(() => generateRandomAlphabets(250, '.random'), 200);
    setTimeout(write, 75);
    setTimeout(inc, 1000);
</script>

</html>
