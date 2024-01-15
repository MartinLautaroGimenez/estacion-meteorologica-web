#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <Wire.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BMP280.h>
#include "DHT.h"

#define LED D7
#define DHTPIN D4
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);
const char *ssid = "AP-MCP";
const char *password = "5010867250";
const char *mqtt_broker = "34.27.15.81";
const char *topic = "esp8266/led";
const char *topic_temp = "esp8266/temp";
const char *topic_bp = "esp8266/bp";
const char *topic_alt = "esp8266/alt";
const char *topic_hum = "esp8266/hum";
const char *topic_hum = "esp8266/date";
const char *mqtt_username = "estaciones";
const char *mqtt_password = "estaciones2024";
const int mqtt_port = 1883;

bool ledState = false;
float TEMPERATURA;
float PRESION;
float ALTURA;
float HUMEDAD;

WiFiClient espClient;
Adafruit_BMP280 bmp;
PubSubClient client(espClient);

void setup() {
    Serial.begin(115200);
    delay(1000);

    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.println("Connecting to WiFi...");
    }
    Serial.println("Connected to the WiFi network");

    pinMode(LED, OUTPUT);
    digitalWrite(LED, LOW);

    if (!bmp.begin()) {
        Serial.println("BMP280 no encontrado !");
        while (1);
    }
    dht.begin();
    client.setServer(mqtt_broker, mqtt_port);
    client.setCallback(callback);
    while (!client.connected()) {
        String client_id = "esp8266-client-";
        client_id += String(WiFi.macAddress());
        Serial.printf("The client %s connects to the public MQTT broker\n", client_id.c_str());
        if (client.connect(client_id.c_str(), mqtt_username, mqtt_password)) {
            Serial.println("Public EMQX MQTT broker connected");
        } else {
            Serial.print("Failed with state ");
            Serial.print(client.state());
            delay(2000);
        }
    }

    client.publish(topic, "hello emqx");
    client.subscribe(topic);
}

void callback(char *topic, byte *payload, unsigned int length) {
    Serial.print("Message arrived in topic: ");
    Serial.println(topic);
    Serial.print("Message: ");
    String message;
    for (int i = 0; i < length; i++) {
        message += (char) payload[i];  // Convert *byte to string
    }
    Serial.print(message);
    if (message == "on" && !ledState) {
        digitalWrite(LED, HIGH);  // Turn on the LED
        ledState = true;
    }
    if (message == "off" && ledState) {
        digitalWrite(LED, LOW); // Turn off the LED
        ledState = false;
    }
    Serial.println();
    Serial.println("-----------------------");
}

void datosmeteorologicos() {
    TEMPERATURA = bmp.readTemperature();
    PRESION = bmp.readPressure() / 100;
    ALTURA = bmp.readAltitude();
    HUMEDAD = dht.readHumidity();

    Serial.print("Temperatura: ");
    Serial.print(TEMPERATURA);
    Serial.print(" C ");

    Serial.print("Presion: ");
    Serial.print(PRESION);
    Serial.println(" hPa");

    Serial.print("Altitud: ");
    Serial.print(ALTURA);
    Serial.println(" m");

    Serial.print("Humedad: ");
    Serial.print(HUMEDAD);
    Serial.println(" %");
    // Nuevas líneas para imprimir temperatura y presión en el monitor serial
    Serial.print("Publicando temperatura en MQTT: ");
    Serial.println(TEMPERATURA);
    client.publish(topic_temp, (String(TEMPERATURA) + " °C").c_str());

    Serial.print("Publicando presión en MQTT: ");
    Serial.println(PRESION);
    client.publish(topic_bp, (String(PRESION) + " hPa").c_str());

    Serial.print("Publicando altitud en MQTT: ");
    Serial.println(ALTURA);
    client.publish(topic_alt, (String(ALTURA) + " m").c_str());

    Serial.print("Publicando humedad en MQTT: ");
    Serial.println(HUMEDAD);
    client.publish(topic_hum, (String(HUMEDAD) + " %").c_str());

    delay(900000);
}

void loop() {
    client.loop();
    datosmeteorologicos();
    client.loop();
    delay(100);
}
