#!/bin/bash

# Exit immediately on error
set -e

# Print each command before executing (for debugging)
set -x

# Detect CI environment
if [ -n "$GITHUB_ACTIONS" ]; then
  export DB_HOST=127.0.0.1
  export DB_DATABASE=laravel_testing
  export DB_USERNAME=root
  export DB_PASSWORD=root
else
  export DB_HOST=db
  export DB_DATABASE=skillup
  export DB_USERNAME=root
  export DB_PASSWORD=
fi

# Run Pest directly instead of Laravel's wrapper
exec ./vendor/bin/pest --stop-on-failure "$@"
