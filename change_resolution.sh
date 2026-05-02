#!/bin/bash

# Script to change display scaling (UI resolution)
# Usage: ./change_resolution.sh [percentage]
# Example: ./change_resolution.sh 150
# If no argument is provided, it cycles through: 100, 125, 150, 175, 200

TARGET_PERCENT="$1"

# Detect session type
if [ "$XDG_SESSION_TYPE" = "wayland" ]; then
    SESSION="wayland"
else
    SESSION="x11"
fi

# Define the cycle of scales (in percentages)
SCALES=(50 100 150 175 200)

get_next_scale() {
    local current=$1
    local length=${#SCALES[@]}
    for i in "${!SCALES[@]}"; do
        # Check if current scale (converted to percent) matches
        # We round to avoid floating point mismatch issues
        local scale_pct=$(awk "BEGIN {print int($current * 100 + 0.5)}")
        if [ "$scale_pct" -eq "${SCALES[$i]}" ]; then
            local next_idx=$(( (i + 1) % length ))
            echo "${SCALES[$next_idx]}"
            return
        fi
    done
    # Default if current scale not in list
    echo "${SCALES[0]}"
}

change_scale_wayland() {
    DISPLAY_NAME=$(kscreen-doctor -o | grep "Output: " | head -n 1 | awk '{print $3}')
    if [ -z "$DISPLAY_NAME" ]; then
        echo "Error: No display found."
        exit 1
    fi

    # Get current scale
    CURRENT_FACTOR=$(kscreen-doctor -o | grep -A 20 "Output: .* $DISPLAY_NAME" | grep "Scale:" | awk '{print $2}')
    [ -z "$CURRENT_FACTOR" ] && CURRENT_FACTOR=1

    if [ -z "$TARGET_PERCENT" ]; then
        TARGET_PERCENT=$(get_next_scale "$CURRENT_FACTOR")
    fi

    # Convert percentage to decimal for kscreen-doctor
    SCALE_FACTOR=$(awk "BEGIN {print $TARGET_PERCENT / 100}")
    
    echo "Display: $DISPLAY_NAME | Current Scale: $CURRENT_FACTOR | Target: $TARGET_PERCENT% ($SCALE_FACTOR)"
    kscreen-doctor "output.$DISPLAY_NAME.scale.$SCALE_FACTOR"
}

change_scale_x11() {
    DISPLAY_NAME=$(xrandr | grep " connected" | awk '{print $1}' | head -n 1)
    if [ -z "$DISPLAY_NAME" ]; then
        echo "Error: No display found."
        exit 1
    fi

    # xrandr doesn't have a simple "Scale" property to read back easily in the same way
    # We'll just assume 100 if we can't detect it, or use the argument
    if [ -z "$TARGET_PERCENT" ]; then
        # For simplicity in X11, if no target, default to 100 or 150 toggle
        TARGET_PERCENT=100
    fi

    # xrandr scaling is inverse: factor 0.66 makes UI 1.5x larger
    SCALE_FACTOR=$(awk "BEGIN {print 1 / ($TARGET_PERCENT / 100)}")
    
    echo "Display: $DISPLAY_NAME | Target: $TARGET_PERCENT% (xrandr scale: $SCALE_FACTOR)"
    xrandr --output "$DISPLAY_NAME" --scale "${SCALE_FACTOR}x${SCALE_FACTOR}"
}

if [ "$SESSION" = "wayland" ]; then
    change_scale_wayland
else
    change_scale_x11
fi
