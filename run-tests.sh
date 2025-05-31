#!/bin/bash

set -e
set -x

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

# Run the tests and capture the exit code
./vendor/bin/pest --stop-on-failure "$@"
STATUS=$?
echo "Test process exit code: $STATUS"
exit $STATUS
