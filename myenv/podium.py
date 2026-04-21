import serial
import time
import statistics
from collections import Counter

ser = serial.Serial('/dev/ttyUSB1', 57600, timeout=1)
serLimit = []

WEB_PATH = "/var/www/html/gold-2-cash/"

current_weight = "0"
current_winner = "0"
is_locked = False
capture_start_time = None
def show_weight (weight):
    with open(WEB_PATH + "weight.txt", "w") as f:
        weight = float(weight)
        if (weight <= 0):
            f.write("0")
        else:
            f.write(str(int(round(float(weight)))))

def show_winner(force_value=None):
    path = WEB_PATH + "stats.txt"

    if force_value is not None:
        value = str(force_value)
    else:
        try:
            with open(path, "r") as f:
                current = f.read().strip()
            value = "0" if current == "1" else "1"
        except FileNotFoundError:
            value = "1"

    with open(path, "w") as f:
        f.write(value)

while True:
    try:
        line = ser.readline().decode('utf-8', errors='replace').rstrip()
        if line:
            print("RECEIVED:", line)
        
        if line.startswith("WEIGHT:"):
            weight = float(line.split(":")[1])
            curr_time = time.time()
            serLimit.append((curr_time, weight))
            
            # Keep a 3-second rolling window
            while len(serLimit) > 0 and curr_time - serLimit[0][0] > 3.0:
                serLimit.pop(0)

            # Calculate mode (most common value)
            rounded_data = [round(w[1], 1) for w in serLimit]
            if rounded_data:
                mode_weight = Counter(rounded_data).most_common(1)[0][0]
                
                if mode_weight > 2.0: # Weight detected
                    if not is_locked:
                        if capture_start_time is None:
                            capture_start_time = curr_time
                            print("WEIGHING STARTED...")
                        
                        # Live update for the first 3 seconds
                        if curr_time - capture_start_time < 3.0:
                            if current_weight != mode_weight:
                                show_weight(mode_weight)
                                current_weight = mode_weight
                                print("LIVE WEIGHT (MODE 3S):", mode_weight)
                        else:
                            # 3 seconds have passed! Lock the value
                            is_locked = True
                            show_weight(mode_weight)
                            current_weight = mode_weight
                            print("WEIGHT LOCKED AFTER 3S:", mode_weight)
                else:
                    # Weight removed - reset for next object
                    if is_locked or capture_start_time is not None:
                        is_locked = False
                        capture_start_time = None
                        show_weight(0)
                        current_weight = 0
                        serLimit.clear()
                        print("READY FOR NEXT OBJECT")
            
        elif line.startswith("WINNER"):
            show_winner()
            print("WINNER SAVED")
            
        elif line.startswith("TARE"):
            print("TARE DONE")
        elif line.startswith("RESET"):
            is_locked = False
            capture_start_time = None
            show_weight(0)
            current_weight = 0
            current_winner = "0"
            serLimit.clear()
            show_winner(0) # Now works with the updated function
            print("RESET DONE")
            
    except Exception as e:
        print("ERROR:", e)