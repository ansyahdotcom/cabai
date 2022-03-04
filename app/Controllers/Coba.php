<?php

namespace App\Controllers;

use App\Models\CobaModel;
use App\Models\HitungModel;
use App\Models\PpmModel;

class Coba extends BaseController
{
    protected $CobaModel;
    protected $HitungModel;
    protected $PpmModel;
    public function __construct()
    {
        $this->CobaModel = new CobaModel;
        $this->HitungModel = new HitungModel;
        $this->PpmModel = new PpmModel;
    }

    public function index()
    {
        $data = [
            'awal' => $this->CobaModel->orderBy('created_dht DESC')->findAll(),
            'olah' => $this->OlahModel->orderBy('created_ol DESC')->findAll()
        ];
        return view('v_coba', $data);
    }

    public function hitung($suhu, $kelembaban)
    {
        $data_awal = [
            'suhu' => $suhu,
            'kelembaban' => $kelembaban
        ];
        $this->CobaModel->save($data_awal);

        // AMBIL DATA RENTANG UMUR
        $get_ppm = $this->PpmModel->where('st_ppm', '1')->first();
        foreach ($get_ppm as $getppm) {
            $ppm_rendah = $getppm['ppm_rendah'];
            $ppm_cukup = $getppm['ppm_cukup'];
            $ppm_tinggi = $getppm['ppm_tinggi'];
        }

        // ATURAN
        // nyala lampu minimal 5 maksimal 20
        // suhu x <= 29 == RENDAH, x = 31 == CUKUP, x >= 33 == LEBIH
        // kelembaban x <= 85 == KERING, x >= 88 == BASAH

        // RULES
        // jika suhu RENDAH && kelembaban BASAH, maka NYALA SEMUA
        // jika suhu CUKUP && kelembaban BASAH, maka NYALA SEMUA
        // jika suhu TINGGI && kelembaban BASAH, maka NYALA SEBAGIAN
        // jika suhu RENDAH && kelembaban KERING, maka NYALA SEMUA
        // jika suhu CUKUP && kelembaban KERING, maka NYALA SEBAGIAN
        // jika suhu TINGGI && kelembaban KERING, maka NYALA SEBAGIAN

        // FUZZIFIKASI
        // miu kelembaban kering
        if ($kelembaban <= 85) {
            $m_klmbban_kering = 1;
        } elseif ($kelembaban > 85 && $kelembaban < 88) {
            $m_klmbban_kering = (88 - $kelembaban) / (88 - 85);
        } elseif ($kelembaban >= 88) {
            $m_klmbban_kering = 0;
        }
        // miu kelembaban basah
        if ($kelembaban >= 88) {
            $m_klmbban_basah = 1;
        } elseif ($kelembaban > 85 && $kelembaban < 88) {
            $m_klmbban_basah = ($kelembaban - 85) / (88 - 85);
        } elseif ($kelembaban <= 85) {
            $m_klmbban_basah = 0;
        }

        // miu suhu rendah
        if ($suhu <= $ppm_rendah) {
            $m_suhu_rendah = 1;
        } elseif ($suhu > $ppm_rendah && $suhu < $ppm_cukup) {
            $m_suhu_rendah = ($ppm_cukup - $suhu) / ($ppm_cukup - $ppm_rendah);
        } elseif ($suhu >= $ppm_cukup) {
            $m_suhu_rendah = 0;
        }
        // miu suhu cukup
        if ($suhu <= $ppm_rendah || $suhu >= $ppm_tinggi) {
            $m_suhu_cukup = 0;
        } elseif ($suhu > $ppm_rendah && $suhu < $ppm_cukup) {
            $m_suhu_cukup = ($suhu - $ppm_rendah) / ($ppm_cukup - $ppm_rendah);
        } elseif ($suhu > $ppm_cukup && $suhu < $ppm_tinggi) {
            $m_suhu_cukup = ($ppm_tinggi - $suhu) / ($ppm_tinggi - $ppm_cukup);
        } elseif ($suhu == $ppm_cukup) {
            $m_suhu_cukup = 1;
        }
        // miu suhu tinggi
        if ($suhu >= $ppm_tinggi) {
            $m_suhu_tinggi = 1;
        } elseif ($suhu > $ppm_cukup && $suhu < $ppm_tinggi) {
            $m_suhu_tinggi = ($suhu - $ppm_cukup) / ($ppm_tinggi - $ppm_cukup);
        } elseif ($suhu <= $ppm_cukup) {
            $m_suhu_tinggi = 0;
        }

        // INFERENSI
        $z1 = 0;
        $z2 = 0;
        $z3 = 0;
        $z4 = 0;
        $z5 = 0;
        $z6 = 0;

        $a1 = min($m_suhu_rendah, $m_klmbban_basah);
        $z1 = ($a1 * (20 - 5)) + 5;
        $a2 = min($m_suhu_cukup, $m_klmbban_basah);
        $z2 = ($a2 * (20 - 5)) + 5;
        $a3 = min($m_suhu_tinggi, $m_klmbban_basah);
        $z3 = 20 - ($a3 * (20 - 5));
        $a4 = min($m_suhu_rendah, $m_klmbban_kering);
        $z4 = ($a4 * (20 - 5)) + 5;
        $a5 = min($m_suhu_cukup, $m_klmbban_kering);
        $z5 = 20 - ($a5 * (20 - 5));
        $a6 = min($m_suhu_tinggi, $m_klmbban_kering);
        $z6 = 20 - ($a6 * (20 - 5));

        // DEFUZZYFIKASI
        $total_AiZi = ($a1 * $z1) + ($a2 * $z2) + ($a3 * $z3) + ($a4 * $z4) + ($a5 * $z5) + ($a6 * $z6);
        $total_a = $a1 + $a2 + $a3 + $a4 + $a5 + $a6;
        $total_Z = $total_AiZi / $total_a;

        $olah_data = [
            'm_klmbban_kering' => $m_klmbban_kering,
            'm_klmbban_basah' => $m_klmbban_basah,
            'm_suhu_rendah' => $m_suhu_rendah,
            'm_suhu_cukup' => $m_suhu_cukup,
            'm_suhu_tinggi' => $m_suhu_tinggi,
            'a1' => $a1,
            'a2' => $a2,
            'a3' => $a3,
            'a4' => $a4,
            'a5' => $a5,
            'a6' => $a6,
            'z1' => $z1,
            'z2' => $z2,
            'z3' => $z3,
            'z4' => $z4,
            'z5' => $z5,
            'z6' => $z6,
            'total_AiZi' => $total_AiZi,
            'total_a' => $total_a,
            'total_Z' => $total_Z,
        ];

        $this->OlahModel->save($olah_data);
    }
}
