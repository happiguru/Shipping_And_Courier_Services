FROM dunglas/frankenphp:php8.4-alpine

# PDO SQLite is used for the persistent Railway volume-backed database.
RUN install-php-extensions pdo_sqlite

WORKDIR /app
COPY . /app/

COPY Caddyfile /etc/frankenphp/Caddyfile

EXPOSE 8080
