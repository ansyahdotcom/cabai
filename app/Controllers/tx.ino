#include <OneWire.h>
#include <DallasTemperature.h>

#define ONE_WIRE_BUS 7
#define TdsSensorPin A0
#define VREF 5.0     // analog reference voltage(Volt) of the ADC
#define SCOUNT 30    // sum of sample point
#define SensorPin A1 // pH meter Analog output to Arduino Analog Input 0
#define Offset 0.00  // deviation compensate
#define samplingInterval 20
#define printInterval 800
#define ArrayLenth 40 // times of collection

OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensorSuhu(&oneWire);

float suhu, ph;
int ppm;
float m_suhu_dngin, m_suhu_nrmal, m_suhu_pnas;
float m_ppm_rndah, m_ppm_ckup, m_ppm_tnggi;
float m_ph_asam, m_ph_ntral, m_ph_basa;
float suhu_a1, suhu_z1, suhu_a2, suhu_z2, suhu_a3, suhu_z3;
float suhu_AiZi, suhu_a, suhu_Z;
float ppm_a1, ppm_z1, ppm_a2, ppm_z2, ppm_a3, ppm_z3;
float ppm_AiZi, ppm_a, ppm_Z;
float ph_a1, ph_z1, ph_a2, ph_z2, ph_a3, ph_z3;
float ph_AiZi, ph_a, ph_Z;
int st_suhu, st_ppm, st_ph;
int ppm_cukup_min = 1200;
int ppm_cukup_max = 1400;
int ppm_rendah = ppm_cukup_min + 50;
int ppm_tinggi = ppm_cukup_max - 50;

int analogBuffer[SCOUNT]; // store the analog value in the array, read from ADC
int analogBufferTemp[SCOUNT];
int analogBufferIndex = 0, copyIndex = 0;
float averageVoltage = 0, tdsValue = 0, temperature = 25;
int pHArray[ArrayLenth]; // Store the average value of the sensor feedback
int pHArrayIndex = 0;

void setup()
{
    Serial.begin(9600);
    sensorSuhu.begin();
    pinMode(TdsSensorPin, INPUT);
    pinMode(2, OUTPUT); // larutan ab mix
    pinMode(3, OUTPUT); // KOH
    pinMode(4, OUTPUT); // HCl
    pinMode(5, OUTPUT); // hidupkan pompa bak
    pinMode(6, OUTPUT); // hidupkan pompa air
}

void loop()
{
    // fuzzy();
    suhu = ambilSuhu();
    ppm = ambilTds();
    ph = ambilPh();
    Serial.print("Suhu : ");
    Serial.println(suhu);
    Serial.print("PPM : ");
    Serial.println(ppm);
    Serial.print("PH : ");
    Serial.println(ph);
    delay(2000);
}

void fuzzy()
{
    // FUZZYFIKASI
    //     suhu = ambilSuhu();
    //     ppm = ambilTds();
    //     ph = ambilPh();
    suhu = 25.0;
    ppm = 1249;
    ph = 6;
    // SUHU
    // miu suhu dngin
    if (suhu >= 19)
    {
        m_suhu_dngin = 0;
    }
    else if (18 < suhu && suhu < 19)
    {
        m_suhu_dngin = (19 - suhu) / (19 - 18);
    }
    else if (suhu <= 18)
    {
        m_suhu_dngin = 1;
    }
    // miu suhu nrmal
    if (suhu <= 18 || suhu >= 29)
    {
        m_suhu_nrmal = 0;
    }
    else if (18 < suhu && suhu < 19)
    {
        m_suhu_nrmal = (suhu - 18) / (19 - 18);
    }
    else if (28 < suhu && suhu < 29)
    {
        m_suhu_nrmal = (29 - suhu) / (29 - 28);
    }
    else if (19 <= suhu && suhu <= 28)
    {
        m_suhu_nrmal = 1;
    }
    // miu suhu pnas
    if (suhu <= 28)
    {
        m_suhu_pnas = 0;
    }
    else if (28 < suhu && suhu < 29)
    {
        m_suhu_pnas = (suhu - 28) / (29 - 28);
    }
    else if (suhu >= 29)
    {
        m_suhu_pnas = 1;
    }

    // INFERENSI SUHU
    suhu_a1 = m_suhu_dngin;
    suhu_z1 = 50;
    suhu_a2 = m_suhu_nrmal;
    suhu_z2 = 50 * 2;
    suhu_a3 = m_suhu_pnas;
    suhu_z3 = 50 * 3;

    // DEFUZZYFIKASI SUHU
    suhu_AiZi = (suhu_a1 * suhu_z1) + (suhu_a2 * suhu_z2) + (suhu_a3 * suhu_z3);
    suhu_a = suhu_a1 + suhu_a2 + suhu_a3;
    suhu_Z = suhu_AiZi / suhu_a;

    // PENENTUAN KONDISI SUHU
    if (suhu_Z <= 50)
    {
        //        Serial.println("suhu terlalu rendah");
    }
    else if (suhu_Z > 50 && suhu_Z < 150)
    {
        //        Serial.println("suhu normal");
    }
    else if (suhu_Z >= 150)
    {
        //        Serial.println("suhu terlalu tinggi, hidupkan pompa air");
        digitalWrite(5, HIGH);
        delay(30000);
    }

    // PPM
    // miu ppm rndah
    if (ppm >= ppm_rendah)
    {
        m_ppm_rndah = 0;
    }
    else if (ppm_cukup_min < ppm && ppm < ppm_rendah)
    {
        m_ppm_rndah = (ppm_rendah - ppm) / (ppm_rendah - ppm_cukup_min);
    }
    else if (ppm <= ppm_cukup_min)
    {
        m_ppm_rndah = 1;
    }
    // // miu ppm ckup
    if (ppm <= ppm_cukup_min || ppm >= ppm_cukup_max)
    {
        m_ppm_ckup = 0;
    }
    else if (ppm_cukup_min < ppm && ppm < ppm_rendah)
    {
        m_ppm_ckup = (ppm - ppm_cukup_min) / (ppm_rendah - ppm_cukup_min);
    }
    else if (ppm_tinggi < ppm && ppm < ppm_cukup_max)
    {
        m_ppm_ckup = (ppm_cukup_max - ppm) / (ppm_cukup_max - ppm_tinggi);
    }
    else if (ppm_rendah <= ppm && ppm <= ppm_tinggi)
    {
        m_ppm_ckup = 1;
    }
    // // miu ppm tnggi
    if (ppm <= ppm_tinggi)
    {
        m_ppm_tnggi = 0;
    }
    else if (ppm_tinggi < ppm && ppm < ppm_cukup_max)
    {
        m_ppm_tnggi = (ppm - ppm_tinggi) / (ppm_cukup_max - ppm_tinggi);
    }
    else if (ppm >= ppm_cukup_max)
    {
        m_ppm_tnggi = 1;
    }

    // INFERENSI PPM
    ppm_a1 = m_ppm_rndah;
    ppm_z1 = 50;
    ppm_a2 = m_ppm_ckup;
    ppm_z2 = 50 * 2;
    ppm_a3 = m_ppm_tnggi;
    ppm_z3 = 50 * 3;

    // DEFUZZYFIKASI PPM
    ppm_AiZi = (ppm_a1 * ppm_z1) + (ppm_a2 * ppm_z2) + (ppm_a3 * ppm_z3);
    ppm_a = ppm_a1 + ppm_a2 + ppm_a3;
    ppm_Z = ppm_AiZi / ppm_a;

    // PENENTUAN KONDISI PPM
    if (ppm_Z <= 50)
    {
        //        Serial.println("ppm terlalu rendah, tambahkan ab mix");
        digitalWrite(2, HIGH);
        delay(5000);
    }
    else if (ppm_Z > 50 && ppm_Z < 150)
    {
        //        Serial.println("ppm cukup");
    }
    else if (ppm_Z >= 150)
    {
        //        Serial.println("ppm terlalu tinggi, hidupkan pompa air");
        digitalWrite(6, HIGH);
        delay(5000);
    }

    // PH
    // miu ppm asam
    if (ph >= 6.7)
    {
        m_ph_asam = 0;
    }
    else if (6.5 < ph && ph < 6.7)
    {
        m_ph_asam = (6.7 - ph) / (6.7 - 6.5);
    }
    else if (ph <= 6.5)
    {
        m_ph_asam = 1;
    }
    // miu ph ntral
    if (ph <= 6.5 || ph >= 7.5)
    {
        m_ph_ntral = 0;
    }
    else if (6.5 < ph && ph < 6.7)
    {
        m_ph_ntral = (ph - 6.5) / (6.7 - 6.5);
    }
    else if (7.3 < ph && ph < 7.5)
    {
        m_ph_ntral = (7.5 - ph) / (7.5 - 7.3);
    }
    else if (6.7 <= ph && ph <= 7.3)
    {
        m_ph_ntral = 1;
    }
    // miu ph basa
    if (ph <= 7.3)
    {
        m_ph_basa = 0;
    }
    else if (7.3 < ph && ph < 7.5)
    {
        m_ph_basa = (ph - 7.3) / (7.5 - 7.3);
    }
    else if (ph >= 7.5)
    {
        m_ph_basa = 1;
    }

    // INFERENSI PH
    ph_a1 = m_ph_asam;
    ph_z1 = 50;
    ph_a2 = m_ph_ntral;
    ph_z2 = 50 * 2;
    ph_a3 = m_ph_basa;
    ph_z3 = 50 * 3;

    // DEFUZZYFIKASI PH
    ph_AiZi = (ph_a1 * ph_z1) + (ph_a2 * ph_z2) + (ph_a3 * ph_z3);
    ph_a = ph_a1 + ph_a2 + ph_a3;
    ph_Z = ph_AiZi / ph_a;

    // PENENTUAN KONDISI PH
    if (ph_Z <= 50)
    {
        //        Serial.println("ph asam, tambahkan KOH");
        digitalWrite(3, HIGH);
        delay(2000);
    }
    else if (ph_Z > 50 && ph_Z < 150)
    {
        Serial.println("ph netral");
    }
    else if (ph_Z >= 150)
    {
        //        Serial.println("ppm basa, tambahkan HCl");
        digitalWrite(4, HIGH);
        delay(2000);
    }
    /*
    Serial.print("m_suhu_dngin: ");
    Serial.println(m_suhu_dngin);
    Serial.print("m_suhu_nrmal: ");
    Serial.println(m_suhu_nrmal);
    Serial.print("m_suhu_pnas: ");
    Serial.println(m_suhu_pnas);
    Serial.print("m_ppm_rndah: ");
    Serial.println(m_ppm_rndah);
    Serial.print("m_ppm_ckup: ");
    Serial.println(m_ppm_ckup);
    Serial.print("m_ppm_tnggi: ");
    Serial.println(m_ppm_tnggi);
    Serial.print("m_ph_asam: ");
    Serial.println(m_ph_asam);
    Serial.print("m_ph_ntral: ");
    Serial.println(m_ph_ntral);
    Serial.print("m_ph_basa: ");
    Serial.println(m_ph_basa);
    */

    //    Serial.println("hidupkan pompa bak");
    digitalWrite(5, HIGH);
    delay(5000);
    digitalWrite(5, LOW);
    delay(1000 * 5);
    String kirimdata = String(suhu) + "/" + String(ppm) + "/" + String(ph);
    Serial.println(kirimdata);
    delay(1000);
}

// BACA SENSOR
float ambilSuhu()
{
    sensorSuhu.requestTemperatures();
    float temperatur = sensorSuhu.getTempCByIndex(0);
    return temperatur;
}

float ambilTds()
{
    static unsigned long analogSampleTimepoint = millis();
    if (millis() - analogSampleTimepoint > 40U) // every 40 milliseconds,read the analog value from the ADC
    {
        analogSampleTimepoint = millis();
        analogBuffer[analogBufferIndex] = analogRead(TdsSensorPin); // read the analog value and store into the buffer
        analogBufferIndex++;
        if (analogBufferIndex == SCOUNT)
            analogBufferIndex = 0;
    }
    static unsigned long printTimepoint = millis();
    if (millis() - printTimepoint > 800U)
    {
        printTimepoint = millis();
        for (copyIndex = 0; copyIndex < SCOUNT; copyIndex++)
            analogBufferTemp[copyIndex] = analogBuffer[copyIndex];
        averageVoltage = getMedianNum(analogBufferTemp, SCOUNT) * (float)VREF / 1024.0;                                                                                                        // read the analog value more stable by the median filtering algorithm, and convert to voltage value
        float compensationCoefficient = 1.0 + 0.02 * (temperature - 25.0);                                                                                                                     // temperature compensation formula: fFinalResult(25^C) = fFinalResult(current)/(1.0+0.02*(fTP-25.0));
        float compensationVolatge = averageVoltage / compensationCoefficient;                                                                                                                  // temperature compensation
        float tdsValue = (133.42 * compensationVolatge * compensationVolatge * compensationVolatge - 255.86 * compensationVolatge * compensationVolatge + 857.39 * compensationVolatge) * 0.5; // convert voltage value to tds value
        return tdsValue;
        // Serial.print("voltage:");
        // Serial.print(averageVoltage,2);
        // Serial.print("V   ");
    }
}

int getMedianNum(int bArray[], int iFilterLen)
{
    int bTab[iFilterLen];
    for (byte i = 0; i < iFilterLen; i++)
        bTab[i] = bArray[i];
    int i, j, bTemp;
    for (j = 0; j < iFilterLen - 1; j++)
    {
        for (i = 0; i < iFilterLen - j - 1; i++)
        {
            if (bTab[i] > bTab[i + 1])
            {
                bTemp = bTab[i];
                bTab[i] = bTab[i + 1];
                bTab[i + 1] = bTemp;
            }
        }
    }
    if ((iFilterLen & 1) > 0)
        bTemp = bTab[(iFilterLen - 1) / 2];
    else
        bTemp = (bTab[iFilterLen / 2] + bTab[iFilterLen / 2 - 1]) / 2;
    return bTemp;
}

float ambilPh()
{
    static unsigned long samplingTime = millis();
    static unsigned long printTime = millis();
    static float pHValue, voltage;
    if (millis() - samplingTime > samplingInterval)
    {
        pHArray[pHArrayIndex++] = analogRead(SensorPin);
        if (pHArrayIndex == ArrayLenth)
            pHArrayIndex = 0;
        voltage = avergearray(pHArray, ArrayLenth) * 5.0 / 1024;
        pHValue = 3.5 * voltage + Offset;
        samplingTime = millis();
    }
    if (millis() - printTime > printInterval) // Every 800 milliseconds, print a numerical, convert the state of the LED indicator
    {
        //      Serial.print("Voltage:");
        //      Serial.print(voltage,2);
        return pHValue;
        printTime = millis();
    }
}

double avergearray(int *arr, int number)
{
    int i;
    int max, min;
    double avg;
    long amount = 0;
    if (number <= 0)
    {
        Serial.println("Error number for the array to avraging!/n");
        return 0;
    }
    if (number < 5)
    { // less than 5, calculated directly statistics
        for (i = 0; i < number; i++)
        {
            amount += arr[i];
        }
        avg = amount / number;
        return avg;
    }
    else
    {
        if (arr[0] < arr[1])
        {
            min = arr[0];
            max = arr[1];
        }
        else
        {
            min = arr[1];
            max = arr[0];
        }
        for (i = 2; i < number; i++)
        {
            if (arr[i] < min)
            {
                amount += min; // arr<min
                min = arr[i];
            }
            else
            {
                if (arr[i] > max)
                {
                    amount += max; // arr>max
                    max = arr[i];
                }
                else
                {
                    amount += arr[i]; // min<=arr<=max
                }
            } // if
        }     // for
        avg = (double)amount / (number - 2);
    } // if
    return avg;
}