#!/bin/bash

set -e
set -x

# Debug output
echo "GITHUB_ACTIONS: $GITHUB_ACTIONS"
echo "Current directory: $(pwd)"
echo "Contents of .env.testing:"
cat .env.testing

if [ -n "$GITHUB_ACTIONS" ]; then
  # GitHub Actions environment
  echo "Running in GitHub Actions environment"
  export DB_HOST=127.0.0.1
  export DB_DATABASE=laravel_testing
  export DB_USERNAME=root
  export DB_PASSWORD=root

  # Use the GitHub Actions specific phpunit configuration
  CONFIG_FILE="phpunit.github.xml"
elif [ -n "$DOCKER_ENV" ] || [ -f /.dockerenv ]; then
  # Docker environment
  # Use the database service name from docker-compose.yml
  export DB_HOST=db
  export DB_DATABASE=skillup
  export DB_USERNAME=root
  export DB_PASSWORD=

  # Use the default phpunit configuration
  CONFIG_FILE="phpunit.xml"
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

  # Use the default phpunit configuration
  CONFIG_FILE="phpunit.xml"
fi

# Process .env.testing file to replace environment variables
# This is a workaround for Laravel not supporting ${VAR:-default} syntax in .env files
if [ -f .env.testing ]; then
  echo "Processing .env.testing file to replace environment variables"
  # Create a temporary file with environment variables expanded
  envsubst < .env.testing > .env.testing.tmp
  # Replace the original file with the processed one
  mv .env.testing.tmp .env.testing
  # Show the processed file
  echo "Processed .env.testing:"
  cat .env.testing
fi

# Run the tests and capture the exit code
./vendor/bin/phpunit -c $CONFIG_FILE --stop-on-failure "$@"
STATUS=$?
echo "Test process exit code: $STATUS"
exit $STATUS
