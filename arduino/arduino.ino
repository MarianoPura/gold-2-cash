#include "HX711.h"

#define DT 13
#define SCK 16
#define BUTTON 17

HX711 scale;

float calibration_factor = 108.64;
//108.55 iba pang stable calibration

void setup() {
  Serial.begin(9600);
  scale.begin(DT, SCK);
  scale.set_scale(calibration_factor);
  scale.tare();

  pinMode(BUTTON, INPUT_PULLUP);

}

void loop() {
  float weight = scale.get_units(10);

  if (digitalRead(BUTTON) == LOW) {
    Serial.println("WINNER");
    delay(500);
  }

  delay(500);
  Serial.print("WEIGHT:");
  Serial.println(weight);
  
  delay(200);

}