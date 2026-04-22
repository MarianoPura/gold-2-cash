#include "HX711.h"

#define DT 13
#define SCK 16
#define BUTTON 26
// #define BUTTONWEIGHT 25
#define RESET 17

HX711 scale;

float calibration_factor = 265.55;
//108.55 iba pang stable calibration


void setup() {
  Serial.begin(57600);
  scale.begin(DT, SCK);
  scale.set_scale(calibration_factor);
  scale.tare();

  pinMode(BUTTON, INPUT_PULLUP);
  // pinMode(TARE, INPUT_PULLUP);
  // pinMode(BUTTONWEIGHT, INPUT_PULLUP);
  pinMode(RESET, INPUT_PULLUP);


}

// void TARE_FUNC() {
//   int currentState = digitalRead(TARE);

//   if (lastTareState == HIGH && currentState == LOW) {
//     scale.tare();
//     Serial.println("TARE DONE");
//   }

//   lastTareState = currentState;
// }

void RESET_FUNC(){

  int currentState = digitalRead(RESET);

  
  


}

void loop() {

  if (Serial.available()) {
    String cmd = Serial.readStringUntil('\n');
    cmd.trim();
  }

  if(digitalRead(RESET) == LOW){
    Serial.println("RESET DONE");
    ESP.restart();  
  }
  
  float weight = scale.get_units(10);

  if (digitalRead(BUTTON) == LOW) {
    Serial.println("WINNER");
    delay(50);
  }

  // if (digitalRead(BUTTONWEIGHT) == LOW){
  //   Serial.print("WEIGHT:");
  //   Serial.println(weight);
  // }
  delay(300);
  
  Serial.print("WEIGHT:");
  Serial.println(weight);
  
  delay(50);

}