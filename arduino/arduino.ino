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

  if (Serial.available()) {
    String cmd = Serial.readStringUntil('\n');
    cmd.trim();

    if (cmd == "RESET") {
      ESP.restart();
    }

    if (cmd == "TARE") {
      scale.tare();
    }
  }

  if (digitalRead(RESET) == LOW) {
    Serial.println("RESET DONE");
    delay(50);
    ESP.restart();
  }

  if (digitalRead(BUTTON) == LOW) {
    Serial.println("WINNER");
  }

  if (millis() - lastWeightTime > weightInterval) {
    lastWeightTime = millis();

    float weight = scale.get_units(5); 

    Serial.print("WEIGHT:");
    Serial.println(weight);
  }
}