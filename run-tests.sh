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
if [ -f .env.testing ] && [ -z "$GITHUB_ACTIONS" ]; then
  # Skip processing if running in GitHub Actions, as .env.testing.ci is already properly configured
  echo "Processing .env.testing file to replace environment variables"

  # Directly modify the .env.testing file with sed to replace variables with their values
  # Create a temporary file for sed operations
  cp .env.testing .env.testing.sed

  # Replace ${DB_HOST:-db} with the actual DB_HOST value or 'db' if not set
  sed "s|\${DB_HOST:-db}|${DB_HOST:-db}|g" .env.testing.sed > .env.testing

  # Replace ${DB_DATABASE:-skillup} with the actual DB_DATABASE value or 'skillup' if not set
  sed "s|\${DB_DATABASE:-skillup}|${DB_DATABASE:-skillup}|g" .env.testing > .env.testing.sed

  # Replace ${DB_PASSWORD:-} with the actual DB_PASSWORD value or empty string if not set
  sed "s|\${DB_PASSWORD:-}|${DB_PASSWORD:-}|g" .env.testing.sed > .env.testing

  # Replace ${APP_NAME} with the actual APP_NAME value
  sed "s|\${APP_NAME}|${APP_NAME:-Laravel}|g" .env.testing > .env.testing.sed
  mv .env.testing.sed .env.testing

  # Replace any other ${VAR} variables using envsubst
  envsubst < .env.testing > .env.testing.tmp
  mv .env.testing.tmp .env.testing

  # Show the processed file
  echo "Processed .env.testing:"
  cat .env.testing
elif [ -n "$GITHUB_ACTIONS" ]; then
  echo "Running in GitHub Actions environment, skipping .env.testing processing"
fi

# Run the tests and capture the exit code
./vendor/bin/phpunit -c $CONFIG_FILE --stop-on-failure "$@"
STATUS=$?
echo "Test process exit code: $STATUS"
exit $STATUS
