#!/bin/bash

set -e
set -x

if [ -n "$GITHUB_ACTIONS" ]; then
  # GitHub Actions environment
  export DB_HOST=127.0.0.1
  export DB_DATABASE=laravel_testing
  export DB_USERNAME=root
  export DB_PASSWORD=root
elif [ -n "$DOCKER_ENV" ] || [ -f /.dockerenv ]; then
  # Docker environment
  # Use the database service name from docker-compose.yml
  export DB_HOST=db
  export DB_DATABASE=skillup
  export DB_USERNAME=root
  export DB_PASSWORD=
else
  # Local environment (not in Docker)
  # Note: You need to create a local MySQL database named 'laravel_testing'
  # with username 'root' and password 'root' for tests to work locally
  # Example MySQL commands:
  # CREATE DATABASE laravel_testing;
  # GRANT ALL PRIVILEGES ON laravel_testing.* TO 'root'@'localhost' IDENTIFIED BY 'root';
  # FLUSH PRIVILEGES;
  export DB_HOST=127.0.0.1
  export DB_DATABASE=laravel_testing
  export DB_USERNAME=root
  export DB_PASSWORD=root
fi

# Run the tests and capture the exit code
./vendor/bin/phpunit --stop-on-failure "$@"
STATUS=$?
echo "Test process exit code: $STATUS"
exit $STATUS
