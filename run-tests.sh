#!/bin/bash

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

php artisan test --env=testing "$@"
exit $?
