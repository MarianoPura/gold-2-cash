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

while True:
    if ser is None:
        try:
            ser = serial.Serial('/dev/ttyUSB0', 115200, timeout=1)
            print("Connected to USB device.")
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