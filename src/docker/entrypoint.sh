#!/bin/sh

composer install

php artisan migrate
php artisan passport:install --force

# Generate passport personal client credentials and write them to the .env file
# so that this wouldn't need to be done manually.
php artisan passport:client --personal --no-interaction | awk '/Client ID/ {print "\nPASSPORT_PERSONAL_ACCESS_CLIENT_ID=\"" $NF "\""} /Client secret/ {print "PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=\"" $NF "\""}' >> .env

# Configure all modules and initialize/set their data in the database.
php artisan module:config

# Seed the database.
php artisan db:seed

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
