#!/bin/bash

DIRECTION="${1:-toggle}"

# Detect session type
if [ "$XDG_SESSION_TYPE" = "wayland" ]; then
    SESSION="wayland"
else
    SESSION="x11"
fi

rotate_wayland() {
    # 1. Get the Name (the second field, e.g., DP-2)
    # This avoids the "uuid 1 not found" error
    DISPLAY_NAME=$(kscreen-doctor -o | grep "Output: " | head -n 1 | awk '{print $3}')

    if [ -z "$DISPLAY_NAME" ]; then
        echo "Error: No display found."
        exit 1
    fi

    # 2. Get current rotation safely without the grep -oP error
    # We look for the line 'Rotation: X' and grab the value
    CURRENT=$(kscreen-doctor -o | grep -A 15 "Output: .* $DISPLAY_NAME" | grep "Rotation:" | awk '{print $2}')

    case "$DIRECTION" in
        toggle)
            # If current is 2 (Right) or 'right', go normal. Otherwise, go right.
            if [ "$CURRENT" = "2" ] || [ "$CURRENT" = "right" ]; then
                TARGET="normal"
            else
                TARGET="right"
            fi
            ;;
        right)  TARGET="right" ;;
        left)   TARGET="left" ;;
        normal) TARGET="normal" ;;
        *) echo "Unknown direction: $DIRECTION"; exit 1 ;;
    esac

    echo "Display: $DISPLAY_NAME | Current: $CURRENT | Target: $TARGET"
    
    # Use the name instead of the number '1'
    kscreen-doctor "output.$DISPLAY_NAME.rotation.$TARGET"
}

rotate_x11() {
    DISPLAY_NAME=$(xrandr | grep " connected" | awk '{print $1}' | head -n 1)
    CURRENT=$(xrandr | grep "^$DISPLAY_NAME" | grep -oP '(normal|left|right|inverted)' | head -n 1)
    
    case "$DIRECTION" in
        toggle) [ "$CURRENT" = "right" ] && TARGET="normal" || TARGET="right" ;;
        right)  TARGET="right" ;;
        left)   TARGET="left" ;;
        normal) TARGET="normal" ;;
    esac
    xrandr --output "$DISPLAY_NAME" --rotate "$TARGET"
}

if [ "$SESSION" = "wayland" ]; then
    rotate_wayland
else
    rotate_x11
fi
