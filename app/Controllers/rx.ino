#include <ESP8266HTTPClient.h>
#include <ESP8266WiFi.h>

const char *ssid = "Saturi";
const char *pass = "QWErty1221";
const char *host = "192.168.1.2";

int ppm_min = 0;
int ppm_max = 0;

void setup()
{
    Serial.begin(9600);
    // KONEKSI KE WIFI
    WiFi.begin(ssid, pass);
    while (WiFi.status() != WL_CONNECTED)
    {
        Serial.print(".");
        delay(500);
    }
    Serial.print(" Terhubung ke : ");
    Serial.println(ssid);
}

void loop()
{
    // CEK KONEKSI KE WEB
    WiFiClient client;
    const int port = 80;
    if (!client.connect(host, port))
    {
        Serial.println("Belum koneksi ke web");
        return;
    }
    String Link;
    HTTPClient http;
    Link = "http://" + String(host) + "/cabai/public/ppm/ppm";
    http.begin(client, Link);
    http.GET();
    String statuss = http.getString();
    if (statuss == "" || statuss == "0")
    {
        Serial.println("Belum mendapatkan data ppm");
        return;
    }
    Serial.print("ppm min : ");
    Serial.println(statuss.toInt());
    Link = "http://" + String(host) + "/cabai/public/ppm/ppm";
    http.begin(client, Link);
    http.GET();
    String statuss = http.getString();
    if (statuss == "" || statuss == "0")
    {
        Serial.println("Belum mendapatkan data ppm");
        return;
    }
    Serial.print("ppm max : ");
    Serial.println(statuss.toInt());
//    Serial.println(ppm_max);
    http.end();
    delay(300);
}