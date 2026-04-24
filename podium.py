import serial
import time

ser = serial.Serial('/dev/ttyUSB0', 115200, timeout=1)
WEB_PATH = "/srv/http/gold-2-cash/"

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

while True:
    try:
        line = ser.readline().decode('utf-8', errors='replace').rstrip()

        if line.startswith("WEIGHT:"):
            weight = line.split(":")[1]
            current_weight = weight
            show_weight(weight)
            print("WEIGHT SAVED:", weight)

        if float(weight) >= 5.0:
            started = True

        if started and float(weight) <= 0.0:
            started = False
            time.sleep(0.5)
            ser.write(b'RESET\n')

    except Exception as e:
        pass
        
    time.sleep(0.1)