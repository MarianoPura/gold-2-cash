#!/bin/bash
APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$APP_DIR"

if [ -d "myenv" ]; then
    source myenv/bin/activate
fi

exec python podium.py