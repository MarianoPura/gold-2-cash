#include "HX711.h"

#define DT 13
#define SCK 16
#define BUTTON 17
#define TARE 25 
#define BUTTONWEIGHT 26

HX711 scale;

float calibration_factor = 108.55;
//108.55 iba pang stable calibration
int lastTareState = HIGH;
int lastResetState = HIGH;


void setup() {
  Serial.begin(57600);
  scale.begin(DT, SCK);
  scale.set_scale(calibration_factor);
  scale.tare();

  pinMode(BUTTON, INPUT_PULLUP);
  pinMode(TARE, INPUT_PULLUP);
  pinMode(BUTTONWEIGHT, INPUT_PULLUP);


}

void TARE_FUNC() {
  int currentState = digitalRead(TARE);

  if (lastTareState == HIGH && currentState == LOW) {
    scale.tare();
    Serial.println("TARE DONE");
  }

  lastTareState = currentState;
}

// void RESET_FUNC(){

//   int currentState = digitalRead(RESET);

//   if(lastResetState == HIGH && currentState == LOW){
//     Serial.println("RESET DONE");
//   }

//   lastResetState = currentState;

// }

void loop() {
  TARE_FUNC();
  float weight = scale.get_units(10);

  if (digitalRead(BUTTON) == LOW) {
    Serial.println("WINNER");
    delay(50);
  }

  if (digitalRead(BUTTONWEIGHT) == LOW){
    Serial.print("WEIGHT:");
    Serial.println(weight);
  }

  // Serial.print("WEIGHT:");
  // Serial.println(weight);
  
  delay(50);

}