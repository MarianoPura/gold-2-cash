import serial
import time

ser = serial.Serial('/dev/ttyUSB0', 9600, timeout=1)

WEB_PATH = "/opt/lampp/htdocs/gold-2-cash/"

current_weight = "0"

def show_weight (weight):
    with open(WEB_PATH + "../weight.txt", "w") as f:
        f.write(str(weight))

def show_winner ():
    with open(WEB_PATH + "../stats.txt", "w") as f:
        f.write(str(1))

while True:
    line = ser.readline().decode('utf-8').rstrip()

    if line.startswith("WEIGHT:"):
        weight = line.split(":")[1]
        current_weight = weight
        show_weight(weight)

    elif line.startswith("WINNER:"):
        winner = line.split(":")[1]
        show_winner(winner)
    time.sleep(0.1)