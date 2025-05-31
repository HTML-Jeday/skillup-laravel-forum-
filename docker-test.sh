#!/bin/bash

set -e

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
  echo "Error: Docker is not running or not installed."
  exit 1
fi

# Check if the containers are running
if ! docker ps | grep -q my_php; then
  echo "Starting Docker containers..."
  docker-compose up -d
fi

echo "Running tests in Docker container..."
# Execute the run-tests.sh script inside the PHP container
docker exec -it my_php bash -c "cd /laravel && DOCKER_ENV=1 ./run-tests.sh \"$*\""

# Capture the exit code
EXIT_CODE=$?

echo "Tests completed with exit code: $EXIT_CODE"
exit $EXIT_CODE
