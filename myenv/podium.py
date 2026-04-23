import serial
import time

ser = serial.Serial('/dev/ttyUSB0', 115200, timeout=1)

WEB_PATH = "/opt/lampp/htdocs/arduino-test/myenv/"

current_weight = "0"

def show_weight (weight):
    with open(WEB_PATH + "../weight.txt", "w") as f:
        weight = float(weight)
        if (weight <= 5):
            f.write("0")
        else:
            f.write(str(int(float(weight))))

while True:
    line = ser.readline().decode('utf-8').rstrip()

    if line.startswith("WEIGHT:"):
        weight = line.split(":")[1]
        current_weight = weight
        show_weight(weight)
        print("WEIGHT SAVED:", weight)

    time.sleep(0.1)