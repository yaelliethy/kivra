#!/usr/bin/env bash
set -euo pipefail

# Helpers
retry() {
  local -r max_attempts="$1"; shift
  local -r delay_secs="$1"; shift
  local attempt=1
  until "$@"; do
    if (( attempt >= max_attempts )); then
      return 1
    fi
    attempt=$(( attempt + 1 ))
    sleep "$delay_secs"
  done
}

cd /var/www/html

# Ensure .env exists
if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    cp .env.example .env || true
  else
    touch .env
  fi
fi

# Ensure required keys exist in .env so artisan can write to them
if ! grep -q '^APP_KEY=' .env; then
  echo 'APP_KEY=' >> .env
fi
if ! grep -q '^JWT_SECRET=' .env; then
  echo 'JWT_SECRET=' >> .env
fi

# Ensure runtime directories
mkdir -p storage/framework/views || true
mkdir -p storage/framework/cache || true

# Ensure permissions (volume may override image ownership)
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage/bootstrap/cache || true
chmod -R 775 storage || true

# Generate APP_KEY if not set (write directly to .env safely using PHP)
if ! grep -q '^APP_KEY=base64:' .env; then
  new_key="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
  NEW_KEY="$new_key" php -r '
    $k = getenv("NEW_KEY");
    $f = ".env";
    $s = file_exists($f) ? file_get_contents($f) : "";
    if (preg_match("/^APP_KEY=/m", $s)) {
      $s = preg_replace("/^APP_KEY=.*/m", "APP_KEY=".$k, $s);
    } else {
      $s .= (strlen($s)?"\n":"")."APP_KEY=".$k."\n";
    }
    file_put_contents($f, $s);
  '
fi

# Generate JWT secret if not present or empty in .env
current_jwt_secret=$(grep '^JWT_SECRET=' .env | cut -d'=' -f2- || true)
if [ -z "${current_jwt_secret}" ]; then
  php artisan jwt:secret --force || true
fi

# Cache config and routes (best effort)
php artisan config:clear || true
php artisan config:cache || true
php artisan route:cache || true

# Wait for DB to be ready if using a networked DB
if [ "${DB_CONNECTION:-}" = "mysql" ] || [ "${DB_CONNECTION:-}" = "mariadb" ] || [ "${DB_CONNECTION:-}" = "pgsql" ]; then
  echo "Waiting for database to be ready..."
  retry 30 2 php artisan migrate:status > /dev/null 2>&1 || echo "Database not ready after retries; continuing"
fi

# Run migrations and seeders (best effort in prod)
php artisan migrate:fresh --seed --force || true

# Hand off to the provided command
exec "$@"
