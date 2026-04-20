#include <HX711_ADC.h>

#define DT 3
#define SCK 2
#define BUTTON 7

HX711 scale;

float calibration_factor = -7050;

void setup() {
  Serial.begin(9600);
  scale.begin(DT, SCK);
  scale.set_scale(calibration_factor);
  scale.tare();

  pinMode(BUTTON, INPUT_PULLUP);

}

void loop() {
  float weight = scale.get_units(5);

  Serial.print('WEIGHT:');
  Serial.println(weight);
  delay(500);

  if (digitalRead(BUTTON) == LOW) {
    Serial.println("WINNER");
    delay(500);
  }
}

delay(200);
