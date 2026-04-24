import serial
import time

ser = serial.Serial('/dev/ttyUSB0', 115200, timeout=1)

WEB_PATH = "/srv/http/gold-2-cash/"

current_weight = "0"
started = False

def show_weight (weight):
    with open(WEB_PATH + "/weight.txt", "w") as f:
        weight = float(weight)
        if (weight <= 5):
            f.write("0")
        else:
            f.write(str(int(float(weight))))

while True:
    try:
        line = ser.readline().decode('utf-8').rstrip()

        if line.startswith("WEIGHT:"):
            weight = line.split(":")[1]
            current_weight = weight
            show_weight(weight)
            print("WEIGHT SAVED:", weight)

        if (float(weight) >= 5.0):
            started = True

        if (started):
            if (float(weight) == 5.0):
                time.sleep(1)
                ser.write(b'RESET\n')
                started = False
    except Exception as e:
        pass
        
    time.sleep(0.1)