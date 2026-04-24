#include "HX711.h"

#define DT 13
#define SCK 16
#define RESET 17
#define LED 18
#define LOCKBTN 26
HX711 scale;

float calibration_factor = 265.53b n;
//224.55 sa maliit
//265.55 sa malaki
//108.55 iba pang stable calibration para sa 20kg
unsigned long lastActiveTime = 0;
const unsigned long idleLimit = 60000;
unsigned long lastWeightTime = 0;
const int weightInterval = 100;

int lastLockState = HIGH;
int lastResetState = HIGH;
int currentButtonState;
unsigned long lastDebounceTimeReset = 0;
unsigned long lastDebounceTimeLockBtn = 0;
const int debounceDelay = 50;

bool isIdle = false;
int counter = 0;
bool weightLocked = false;
bool resetPressed = false;
bool lockLed = false;

float lastStableWeight = 0;

void IRAM_ATTR onLockPress() {
  weightLocked = true;
  digitalWrite(LED, HIGH);
}

void IRAM_ATTR onResetPress() {
  resetPressed = true;
}

void setup() {
  Serial.begin(115200);
  scale.begin(DT, SCK);
  scale.set_scale(calibration_factor);
  scale.tare();

  pinMode(RESET, INPUT_PULLUP);
  pinMode(LED, OUTPUT);
  pinMode(LOCKBTN, INPUT_PULLUP);
  digitalWrite(LED, LOW);

  attachInterrupt(digitalPinToInterrupt(RESET), onResetPress, FALLING);
  attachInterrupt(digitalPinToInterrupt(LOCKBTN), onLockPress, FALLING);
}

void loop() {

  float weight = scale.get_units(5);
//reset sa 0
  if (Serial.available()) {
    String cmd = Serial.readStringUntil('\n');
    cmd.trim();

    if (cmd == "RESET") {
      scale.tare();
    }
  }

//reset
  if (resetPressed) {
    resetPressed = false;
    weight = 0;
    weightLocked = false;
    digitalWrite(LED, LOW);
    Serial.println("RESET DONE");
    delay(50);
    ESP.restart();
  }

//weight checking
  if (!weightLocked){
    if (millis() - lastWeightTime > weightInterval) {
      lastWeightTime = millis();

      if (abs(weight) < 2) weight = 0;

      if (weight > 0) {
        lastActiveTime = millis();
      }

      if (millis() - lastActiveTime >= idleLimit) {
        Serial.println("IDLE RESTART");
        delay(100);
        scale.tare();
        lastActiveTime = millis();
      }

      Serial.print("WEIGHT:");
      Serial.println(weight);
    }
  }
  
}