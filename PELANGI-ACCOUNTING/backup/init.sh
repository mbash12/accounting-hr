#!/bin/bash

# FRESH INIT SCRIPT
# This script is for a "Clean Install" of the Docker environment.
# It will:
# 1. DELETE all existing data (Database, Redis, etc.)
# 2. Re-import the starter SQL (accounting0.sql)
# 3. Run migrations and seeds
# 4. Start the application

echo "⚠️  WARNING: This will DESTROY all database data!"
echo "It effectively performs a factory reset of the environment."
echo ""
read -p "Are you sure you want to continue? (y/N) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Cancelled."
    exit 1
fi

echo "Detailed steps:"
echo "1. Stopping containers and removing volumes..."
docker-compose down -v

echo "2. building and Starting all services + init profile..."
# --profile init enables the 'init' container which runs migrations/seeds
# --build ensures we have latest images
# -d runs in detached mode
docker-compose --profile init up -d --build

echo "3. Tailing logs of the initialization process..."
echo "Waiting for database to be ready and migrations to run..."
# We follow logs of the 'init' service to show progress to the user
docker-compose logs -f init &
PID=$!

# Wait for init container to exit
# We can loop and check if it's running
echo "Waiting for init to finish..."
exit_code=0
while true; do
    if ! docker-compose ps init | grep -q "Up"; then
        # Check exit code
        exit_code=$(docker-compose ps -a -q init | xargs docker inspect -f '{{.State.ExitCode}}')
        break
    fi
    sleep 1
done

kill $PID 2>/dev/null

if [ "$exit_code" -eq "0" ]; then
    echo ""
    echo "✅ Initialization completed successfully!"
    echo "Access the application at: http://localhost:8081"
else
    echo ""
    echo "❌ Initialization failed! Check logs above."
fi
