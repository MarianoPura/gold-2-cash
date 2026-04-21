import serial
import time

ser = serial.Serial('/dev/ttyUSB0', 9600, timeout=1)

WEB_PATH = "/opt/lampp/htdocs/arduino-test/myenv/"

current_weight = "0"

def show_weight (weight):
    with open(WEB_PATH + "../weight.txt", "w") as f:
        weight = float(weight)
        if (weight <= 0):
            f.write("0")
        else:
            f.write(str(int(float(weight))))

def show_winner():
    path = WEB_PATH + "../stats.txt"

    with open(path, "r") as f:
        value = f.read().strip()

    with open(path, "w") as f:
        if value == "1":
            f.write("0")
        else:
            f.write("1")

while True:
    line = ser.readline().decode('utf-8').rstrip()

    if line.startswith("WEIGHT:"):
        weight = line.split(":")[1]
        current_weight = weight
        show_weight(weight)
        print("WEIGHT SAVED:", weight)

    elif line.startswith("WINNER"):
        show_winner()
        print("WINNER SAVED")
    time.sleep(0.1)