#!/usr/bin/env bash
set -euo pipefail

PORT="${PORT:-8080}"
PHP_BIN=""

# ── 1. Find or install PHP with mysqli ────────────────────────────────────────
find_php_with_mysqli() {
    for bin in php8.3 php8.2 php8.1 php8.0 php; do
        if command -v "$bin" &>/dev/null; then
            if "$bin" -m 2>/dev/null | grep -qi mysqli; then
                echo "$bin"
                return 0
            fi
        fi
    done
    return 1
}

echo "==> Checking for PHP with mysqli..."

if PHP_BIN=$(find_php_with_mysqli); then
    echo "    Found: $(command -v "$PHP_BIN") ($($PHP_BIN -r 'echo PHP_VERSION;'))"
else
    echo "    mysqli not found in any existing PHP binary. Installing php-mysqli..."
    if command -v apt-get &>/dev/null; then
        sudo apt-get update -qq
        # Try versioned packages first (Ubuntu/Debian)
        if apt-cache show php8.3-mysql &>/dev/null 2>&1; then
            sudo apt-get install -y php8.3 php8.3-mysql
        elif apt-cache show php8.2-mysql &>/dev/null 2>&1; then
            sudo apt-get install -y php8.2 php8.2-mysql
        else
            sudo apt-get install -y php php-mysql
        fi
    elif command -v brew &>/dev/null; then
        brew install php
    else
        echo "ERROR: Cannot install PHP automatically. Please install PHP 8+ with the mysqli extension." >&2
        exit 1
    fi

    PHP_BIN=$(find_php_with_mysqli) || {
        echo "ERROR: mysqli extension still not available after installation." >&2
        exit 1
    }
    echo "    Installed: $(command -v "$PHP_BIN") ($($PHP_BIN -r 'echo PHP_VERSION;'))"
fi

# ── 2. Stop any existing server on the target port ───────────────────────────
if lsof -ti:"$PORT" &>/dev/null; then
    echo "==> Stopping existing process on port $PORT..."
    kill "$(lsof -ti:"$PORT")" 2>/dev/null || true
    sleep 1
fi

# ── 3. Start PHP built-in server ─────────────────────────────────────────────
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo "==> Starting PHP server on http://localhost:$PORT ..."
"$PHP_BIN" -S "localhost:$PORT" "$SCRIPT_DIR/index.php" &
SERVER_PID=$!

sleep 1

if kill -0 "$SERVER_PID" 2>/dev/null; then
    echo ""
    echo "    Server running (PID $SERVER_PID)"
    echo "    URL: http://localhost:$PORT"
    echo ""
    echo "    To stop: kill $SERVER_PID"
    echo "    To use a custom DB, create a .env file:"
    echo "      DB_HOST=127.0.0.1"
    echo "      DB_USER=root"
    echo "      DB_PASSWORD=secret"
    echo "      DB_NAME=malaysia"
else
    echo "ERROR: PHP server failed to start." >&2
    exit 1
fi
