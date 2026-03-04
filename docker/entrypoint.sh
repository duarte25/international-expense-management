#!/usr/bin/env sh
set -eu

if [ ! -f .env ]; then
  cp .env.example .env
fi

set_env_var() {
  key="$1"
  value="$2"

  if grep -q "^${key}=" .env; then
    sed -i "s|^${key}=.*|${key}=${value}|" .env
  else
    printf "%s=%s\n" "$key" "$value" >> .env
  fi
}

for key in DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD APP_URL APP_ENV APP_DEBUG; do
  eval "value=\${$key:-}"
  if [ -n "$value" ]; then
    set_env_var "$key" "$value"
  fi
done

php artisan key:generate --force --no-interaction
php artisan config:clear
php artisan migrate --force --no-interaction

exec php artisan serve --host=0.0.0.0 --port=8000
