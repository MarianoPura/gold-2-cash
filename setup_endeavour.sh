#!/bin/bash

# Ensure the script is run with normal user privileges, asking for sudo when needed
if [ "$EUID" -eq 0 ]; then
  echo "Please do not run this script directly as root. Run as your normal user."
  exit 1
fi

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo "========================================"
echo "1. Installing Apache, PHP, PHP-FPM, and Python..."
echo "========================================"
if ! sudo pacman -Syu --needed --noconfirm apache php php-fpm python python-pip python-pyserial chromium; then
    echo "Error: Failed to download or install system packages. Please check your internet connection."
    exit 1
fi

echo "Configuring Apache for PHP-FPM..."
# Enable proxy and proxy_fcgi modules required for PHP-FPM
sudo sed -i 's/^#LoadModule proxy_module/LoadModule proxy_module/' /etc/httpd/conf/httpd.conf
sudo sed -i 's/^#LoadModule proxy_fcgi_module/LoadModule proxy_fcgi_module/' /etc/httpd/conf/httpd.conf

# Add PHP-FPM configuration to Apache if it doesn't exist
if ! grep -q "conf/extra/php-fpm.conf" /etc/httpd/conf/httpd.conf; then
    sudo bash -c 'cat > /etc/httpd/conf/extra/php-fpm.conf <<EOF
DirectoryIndex index.php index.html
<FilesMatch \.php$>
    SetHandler "proxy:unix:/run/php-fpm/php-fpm.sock|fcgi://localhost/"
</FilesMatch>
EOF'
    echo "Include conf/extra/php-fpm.conf" | sudo tee -a /etc/httpd/conf/httpd.conf
fi

echo "Starting and enabling Apache and PHP-FPM services..."
sudo systemctl enable --now php-fpm
sudo systemctl enable --now httpd

# Set up udev rules to allow full access to serial ports without password/group login issues
# Also disable USB autosuspend for serial devices so ESP32 stays alive when idle
echo "Setting up udev rules for serial port access and disabling USB autosuspend..."
sudo bash -c 'cat > /etc/udev/rules.d/50-serial-usb.rules <<EOF
KERNEL=="ttyUSB[0-9]*", MODE="0666"
KERNEL=="ttyACM[0-9]*", MODE="0666"
ACTION=="add", SUBSYSTEM=="usb", DRIVER=="usb-serial", ATTR{../power/autosuspend}="-1"
ACTION=="add", SUBSYSTEM=="usb", DRIVER=="cdc_acm", ATTR{power/autosuspend}="-1"
EOF'

# Disable USB autosuspend globally via modprobe.d
if ! grep -q "autosuspend=-1" /etc/modprobe.d/usbcore.conf 2>/dev/null; then
    echo "options usbcore autosuspend=-1" | sudo tee /etc/modprobe.d/usbcore.conf
    echo "USB autosuspend disabled globally via modprobe.d."
fi
sudo udevadm control --reload-rules
sudo udevadm trigger

echo "========================================"
echo "2. Installing requirements of the python app..."
echo "========================================"
if [ -d "$APP_DIR" ]; then
    cd "$APP_DIR"
    
    # Create virtual environment if it doesn't exist
    if [ ! -d "myenv" ]; then
        python -m venv myenv
    fi
    
    # Activate and install requirements
    source myenv/bin/activate
    if [ -f "requirements.txt" ]; then
        if ! pip install -r requirements.txt; then
            echo "Error: Failed to install Python dependencies from requirements.txt."
            exit 1
        fi
    fi
    deactivate
else
    echo "Error: Directory $APP_DIR does not exist. Please check the path."
fi

echo "========================================"
echo "3. Creating initial data files..."
echo "========================================"
touch "$APP_DIR/weight.txt" "$APP_DIR/stats.txt"
chmod 666 "$APP_DIR/weight.txt" "$APP_DIR/stats.txt"
echo "0" > "$APP_DIR/weight.txt"

echo "========================================"
echo "4 & 5. Adding autostarts..."
echo "========================================"
AUTOSTART_DIR="$HOME/.config/autostart"
mkdir -p "$AUTOSTART_DIR"

# 3. Autostart for default.php
cat > "$AUTOSTART_DIR/gold-2-cash-ui.desktop" <<EOF
[Desktop Entry]
Type=Application
Name=Gold-2-Cash UI (default.php)
Comment=Autostart for default.php on system boot
Exec=chromium --password-store=basic --kiosk --incognito --disable-infobars "http://localhost/gold-2-cash/default.php"
X-GNOME-Autostart-enabled=true
StartupNotify=false
Terminal=false
EOF

# 4. Systemd service for podium.py + udev rule to restart it when USB device appears
APP_USER="$USER"
cat << SVCEOF | sudo tee /etc/systemd/system/gold2cash-podium.service
[Unit]
Description=Gold-2-Cash Podium Weight Reader
After=local-fs.target
StartLimitIntervalSec=0

[Service]
Type=simple
User=$APP_USER
WorkingDirectory=$APP_DIR
ExecStart=$APP_DIR/myenv/bin/python -u $APP_DIR/podium.py
Restart=always
RestartSec=5
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
SVCEOF

# Udev rule: restart service the moment ESP32 USB is detected on boot or plug-in
sudo bash -c 'cat > /etc/udev/rules.d/99-gold2cash-podium.rules <<EOF
ACTION=="add", SUBSYSTEM=="tty", KERNEL=="ttyUSB[0-9]*", RUN+="/bin/systemctl restart gold2cash-podium.service"
ACTION=="add", SUBSYSTEM=="tty", KERNEL=="ttyACM[0-9]*", RUN+="/bin/systemctl restart gold2cash-podium.service"
EOF'

sudo udevadm control --reload-rules
sudo systemctl daemon-reload
sudo systemctl enable --now gold2cash-podium.service
echo "Gold-2-Cash Podium systemd service installed and enabled."

echo "========================================"
echo "Setup Complete!"
echo "Note: The autostart will open the web page and terminal on your next login."
echo "You might need to log out and log back in for group changes (serial ports) to take effect."
echo "========================================"
