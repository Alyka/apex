#!/bin/sh

php artisan migrate
php artisan passport:install
php artisan passport:client --personal
php artisan module:config

# Define an array of log file paths
log_files=(
    "/var/log/supervisord.log"
    "/var/log/nginx-error.log"
    "/var/log/nginx-access.log"
    "/var/log/php-access.log"
    "/var/log/php-error.log"
    "/var/log/schedule.log"
    "/var/log/notification.log"
    "/var/log/worker.log"
)

# Loop through the log file paths and create them with chmod 666
for log_file in "${log_files[@]}"; do
    touch "$log_file"
    chmod 666 "$log_file"
done

/usr/bin/supervisord -c ./src/docker/supervisord.conf
