#include <HX711_ADC.h>

// Pin Definitions
#define DT_PIN 13
#define SCK_PIN 16
#define BUTTON_PIN 17
#define LED_PIN 18

HX711_ADC scale(DT_PIN, SCK_PIN);
float calibration_factor = -7050.0;

void setup() {
  Serial.begin(9600);
  delay(500); // Give serial a moment to stabilize
  Serial.println("\n--- SYSTEM DIAGNOSTICS STARTING ---");

  // 1. Check LED Pin
  pinMode(LED_PIN, OUTPUT);
  Serial.println("Testing LED Pin (18)...");
  digitalWrite(LED_PIN, HIGH);
  delay(200);
  digitalWrite(LED_PIN, LOW);
  Serial.println("-> LED Test pulse sent.");

  // 2. Check Button Pin
  pinMode(BUTTON_PIN, INPUT_PULLUP);
  Serial.print("Testing Button Pin (17)... Status: ");
  if (digitalRead(BUTTON_PIN) == HIGH) {
    Serial.println("OK (Idle High)");
  } else {
    Serial.println("WARNING (Button reads LOW - check for short circuit or stuck button)");
  }

  // 3. Check HX711 Connection
  Serial.println("Testing HX711 Scale Connection (Pins 13, 16)...");
  scale.begin();
  
  unsigned long stabilizingTime = 2000; 
  boolean _tare = true; 
  scale.start(stabilizingTime, _tare);

  if (scale.getTareTimeoutFlag()) {
    Serial.println("!!! SCALE ERROR: Check DT/SCK wiring. No response from HX711.");
  } else {
    scale.setCalFactor(calibration_factor);
    Serial.println("-> Scale initialized successfully.");
  }

  Serial.println("--- DIAGNOSTICS COMPLETE ---\n");
}

void loop() {
  scale.update();

  // Print weight every 500ms
  static unsigned long lastPrint = 0;
  if (millis() - lastPrint > 500) {
    if (scale.getTareTimeoutFlag()) {
      Serial.println("WEIGHT ERROR: Scale Disconnected");
    } else {
      float weight = scale.getData();
      Serial.print("Current Weight: ");
      Serial.println(weight);
    }
    lastPrint = millis();
  }

  // Button interaction
  if (digitalRead(BUTTON_PIN) == LOW) {
    Serial.println(">> BUTTON PRESSED: Triggering LED sequence.");
    for (int i = 0; i < 6; i++) {
      digitalWrite(LED_PIN, HIGH);
      delay(150);
      digitalWrite(LED_PIN, LOW);
      delay(150);
    }
    delay(300);
  }
}