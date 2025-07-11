#!/bin/sh
set -e
chown -R www-data:www-data /app/storage /app/bootstrap/cache
exec "$@"