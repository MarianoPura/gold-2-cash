#include "HX711.h"

#define DT 13
#define SCK 16
#define BUTTON 26
// #define BUTTONWEIGHT 25
#define RESET 17
#define LED 18
HX711 scale;

float calibration_factor = 265.55;
//108.55 iba pang stable calibration
unsigned long lastWeightTime = 0;
const int weightInterval = 100;

int lastButtonState = HIGH;
int lastResetState = HIGH;
int currentButtonState;
unsigned long lastDebounceTimeBtn = 0;
unsigned long lastDebounceTimeReset = 0;
const int debounceDelay = 50;

bool winnerPressed = false;
bool resetPressed = false;
bool lockLed = false;

bool hasWeight = false;
float lastStableWeight = 0;

void IRAM_ATTR onWinnerPress() {
  winnerPressed = true;
}

void IRAM_ATTR onResetPress() {
  resetPressed = true;
}

void setup() {
  Serial.begin(115200);
  scale.begin(DT, SCK);
  scale.set_scale(calibration_factor);
  scale.tare();

  pinMode(BUTTON, INPUT_PULLUP);
  // pinMode(TARE, INPUT_PULLUP);
  // pinMode(BUTTONWEIGHT, INPUT_PULLUP);
  pinMode(RESET, INPUT_PULLUP);
  pinMode(LED, OUTPUT);
  digitalWrite(LED, HIGH);

  attachInterrupt(digitalPinToInterrupt(BUTTON), onWinnerPress, FALLING);
  attachInterrupt(digitalPinToInterrupt(RESET), onResetPress, FALLING);
}

// void TARE_FUNC() {
//   int currentState = digitalRead(TARE);

//   if (lastTareState == HIGH && currentState == LOW) {
//     scale.tare();
//     Serial.println("TARE DONE");
//   }

//   lastTareState = currentState;
// }

void loop() {

  // SERIAL COMMANDS
  if (Serial.available()) {
    String cmd = Serial.readStringUntil('\n');
    cmd.trim();

    if (cmd == "RESET") {
      ESP.restart();
    }
  }

  // HANDLE WINNER BUTTON
  if (winnerPressed) {
    winnerPressed = false;
    Serial.println("WINNER");
  }

  // HANDLE RESET BUTTON
  if (resetPressed) {
    resetPressed = false;
    Serial.println("RESET DONE");
    delay(50);
    ESP.restart();
  }

  // WEIGHT
  if (millis() - lastWeightTime > weightInterval) {
    lastWeightTime = millis();

    float weight = scale.get_units(5);

    if (abs(weight) < 2) weight = 0;

    if (!hasWeight && !lockLed && weight > 5) {
      lockLed = true;
      hasWeight = true;
      digitalWrite(LED, HIGH);
    }

    if (hasWeight && lockLed && weight == 0) {
    hasWeight = false;
    digitalWrite(LED, LOW);
  }

    Serial.print("WEIGHT:");
    Serial.println(weight);
  }
}