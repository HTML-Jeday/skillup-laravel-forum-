#!/bin/bash

# Check if we're running in a CI environment (GitHub Actions)
if [ -n "$GITHUB_ACTIONS" ]; then
  # Use CI environment settings
  export DB_HOST=127.0.0.1
  export DB_DATABASE=laravel_testing
  export DB_USERNAME=root
  export DB_PASSWORD=root
else
  # Use local Docker environment settings
  export DB_HOST=db
  export DB_DATABASE=skillup
  export DB_USERNAME=root
  export DB_PASSWORD=
fi

# Run the tests with environment variables overriding phpunit.xml settings
php artisan test --env=testing "$@"
