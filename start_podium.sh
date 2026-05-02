#!/bin/bash
APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$APP_DIR"

if [ -d "myenv" ]; then
    source myenv/bin/activate
fi

# Run in a loop so that "pkill" from the Control Tower 
# triggers a restart instead of a permanent stop.
while true; do
    echo "Starting Podium Script..."
    python podium.py
    echo "Podium script exited. Restarting in 2 seconds..."
    sleep 2
done