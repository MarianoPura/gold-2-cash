#include "HX711.h"

#define DT 13
#define SCK 16
#define BUTTON 17

HX711 scale;

// float calibration_factor = 108.55;

void setup() {
  Serial.begin(9600);
  scale.begin(DT, SCK);
  scale.set_scale();
  scale.tare();

  pinMode(BUTTON, INPUT_PULLUP);

}

void loop() {
  float weight = scale.get_units(10);

  Serial.print("WEIGHT:");
  Serial.println(weight);
  delay(500);

  if (digitalRead(BUTTON) == LOW) {
    Serial.println("WINNER");
    delay(500);
  }
  delay(200);

}