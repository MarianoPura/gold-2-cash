#!/bin/bash

# 1. Get the device hostname to use as the SSID
SSID=$(hostname)
PASSWORD="12345678" # WPA2 requires at least 8 characters

echo "Setting up Hotspot for device: $SSID"

# 2. Make sure Wi-Fi is powered on
nmcli radio wifi on

# 3. Find the Wi-Fi interface name (e.g., wlan0)
WIFI_IFACE=$(nmcli device | grep wifi | head -n 1 | awk '{print $1}')

if [ -z "$WIFI_IFACE" ]; then
    echo "Error: No Wi-Fi device found!"
    exit 1
fi

echo "Using interface: $WIFI_IFACE"

# 4. Clean up any old kiosk-hotspot profile if it exists
nmcli con delete "KioskHotspot" > /dev/null 2>&1

# 5. Create the new Hotspot profile
nmcli con add type wifi ifname "$WIFI_IFACE" mode ap con-name "KioskHotspot" autoconnect yes ssid "$SSID"

# 6. Set Security to WPA2
nmcli con modify "KioskHotspot" 802-11-wireless-security.key-mgmt wpa-psk
nmcli con modify "KioskHotspot" 802-11-wireless-security.psk "$PASSWORD"

# 7. Enable Internet/Network sharing
nmcli con modify "KioskHotspot" ipv4.method shared

# 8. Start the Hotspot
nmcli con up "KioskHotspot"

echo "----------------------------------------"
echo "Hotspot is now ACTIVE"
echo "SSID: $SSID"
echo "Password: $PASSWORD"
echo "----------------------------------------"
