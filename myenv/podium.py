import serial
import time
import statistics
from collections import deque

ser = serial.Serial('/dev/ttyUSB0', 115200, timeout=1)
serBuffer = deque()

WEB_PATH = "/opt/lampp/htdocs/arduino-test/"

current_weight = "0"
current_winner = "0"
is_locked = False
capture_start_time = None

def show_weight(weight):
    with open(WEB_PATH + "weight.txt", "w") as f:
        weight = float(weight)
        if weight <= 0:
            f.write("0")
        else:
            f.write(str(int(float(weight))))

def show_winner():
    path = WEB_PATH + "stats.txt"
    with open(path, "r") as f:
        value = f.read().strip()
    with open(path, "w") as f:
        if value == "1":
            f.write("0")
        else:
            f.write("1")

while True:
    try:
        line = ser.readline().decode('utf-8').rstrip()
        
        if line.startswith("WEIGHT:"):
            weight = float(line.split(":")[1])
            curr_time = time.time()
            
            # Prevent empty scale readings from polluting the start of a weigh-in
            if weight > 5.0 or capture_start_time is not None:
                serBuffer.append((curr_time, weight))
            else:
                serBuffer.clear()
            
            # Keep a 3-second rolling window
            while len(serBuffer) > 0 and curr_time - serBuffer[0][0] > 0.0:
                serBuffer.popleft()
            
            # Calculate average of all readings in the window
            if serBuffer:
                weights = [w[1] for w in serBuffer]
                avg_weight = sum(weights) / len(weights)
                
                if avg_weight > 5.0:  # Weight detected
                    if not is_locked:
                        if capture_start_time is None:
                            capture_start_time = curr_time
                            print("WEIGHING STARTED...")
                        
                        # Live update for the first 3 seconds
                        if curr_time - capture_start_time < 0.0:
                            if current_weight != avg_weight:
                                current_weight = avg_weight
                                print(f"LIVE WEIGHT (AVG 3S): {avg_weight:.1f}g, Samples: {len(weights)}")
                        else:
                            # 3 seconds have passed! Lock the value
                            # is_locked = True
                            show_weight(avg_weight)
                            current_weight = avg_weight
                            print(f"WEIGHT LOCKED AFTER 3S: {avg_weight:.1f}g (avg of {len(weights)} samples)")
                else:
                    # Weight removed - reset for next object
                    if is_locked or capture_start_time is not None:
                        is_locked = False
                        capture_start_time = None
                        show_weight(0)
                        current_weight = 0
                        serBuffer.clear()
                        # ser.write(b'RESET\n') removed for now to prevent reset every time weight is removed
                        print("READY FOR NEXT OBJECT")
            
        elif line.startswith("WINNER"):
            show_winner()
            print("WINNER SAVED")
            
        elif line.startswith("TARE DONE"):
            print("TARE DONE")
        elif line.startswith("RESET DONE"):
            is_locked = False
            capture_start_time = None
            show_weight(0)
            current_weight = 0
            current_winner = "0"
            serBuffer.clear()
            # Directly reset the winner file to "0"
            with open(WEB_PATH + "stats.txt", "w") as f:
                f.write("0")
            print("RESET DONE")
            
    except Exception as e:
        pass