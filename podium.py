import serial
import time

import os

WEB_PATH = os.path.dirname(os.path.abspath(__file__))

current_weight = "0"
weight = "0"
started = False

def show_weight (weight):
    with open(WEB_PATH + "/weight.txt", "w") as f:
        weight_val = float(weight)
        if (weight_val <= 5):
            f.write("0")
        else:
            f.write(str(int(weight_val)))

ser = None
PORT = '/dev/esp32'  # permanent symlink set by udev rule in setup_endeavour.sh

while True:
    if ser is None:
        try:
            if not os.path.exists(PORT):
                print(f"Waiting for ESP32 on {PORT}...")
                time.sleep(2)
                continue

            # Wait for device to fully enumerate before opening
            print(f"Found {PORT}, waiting for it to settle...")
            time.sleep(3)

            # Open with dsrdtr=False so the port open does NOT toggle DTR
            # (toggling DTR resets the ESP32 silently)
            ser = serial.Serial(
                PORT, 115200, timeout=1,
                dsrdtr=False,   # do NOT toggle DTR on open
                rtscts=False    # do NOT toggle RTS on open
            )
            time.sleep(1)
            ser.reset_input_buffer()  # discard any boot garbage
            print(f"Connected to {PORT}.")
        except Exception:
            print("Waiting for USB device...")
            time.sleep(2)
            continue

    try:
        line = ser.readline().decode('utf-8', errors='replace').rstrip()

        if line.startswith("WEIGHT:"):
            weight = line.split(":")[1]
            current_weight = weight
            show_weight(weight)
            print("WEIGHT SAVED:", weight)

    except serial.SerialException:
        print("USB device disconnected. Reconnecting...")
        if ser:
            ser.close()
        ser = None
    except Exception as e:
        pass
        
    time.sleep(0.1)