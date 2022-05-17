<?php

namespace App\Controllers;

use App\Models\NilaiAwalModel;
use App\Models\HitungModel;
use App\Models\PpmModel;

class Hitung extends BaseController
{
    protected $NilaiAwalModel;
    protected $HitungModel;
    protected $PpmModel;
    public function __construct()
    {
        $this->NilaiAwalModel = new NilaiAwalModel;
        $this->HitungModel = new HitungModel;
        $this->PpmModel = new PpmModel;
    }

    public function index()
    {
        $data = [
            'awal' => $this->NilaiAwalModel->orderBy('created_awal DESC')->findAll(),
            'hitung' => $this->HitungModel->orderBy('created_ht DESC')->findAll(),
            'nilai' => $this->NilaiAwalModel->findAll(),
        ];
        return view('v_perhitungan', $data);
    }
    /*
    public function hitung($suhu, $ppm, $ph)
    {
        // http://localhost:8080/hitung/hitung/24/1230/6
        $data_awal = [
            'suhu' => $suhu,
            'ppm' => $ppm,
            'ph' => $ph
        ];
        $this->NilaiAwalModel->save($data_awal);

        // AMBIL DATA RENTANG PPM
        $get_ppm = $this->PpmModel->where('st_ppm', '1')->first();
        $ppm_rendah = $get_ppm['ppm_rendah'];
        $ppm_cukup_min = $get_ppm['ppm_cukup_min'];
        $ppm_cukup_max = $get_ppm['ppm_cukup_max'];
        $ppm_tinggi = $get_ppm['ppm_tinggi'];

        // SUHU
        // miu suhu dngin
        if ($suhu >= 19) {
            $m_suhu_dngin = 0;
        } elseif (18 < $suhu && $suhu < 19) {
            $m_suhu_dngin = (19 - $suhu) / (19 - 18);
        } elseif ($suhu <= 18) {
            $m_suhu_dngin = 1;
        }
        // miu suhu nrmal
        if ($suhu <= 18 || $suhu >= 29) {
            $m_suhu_nrmal = 0;
        } elseif (18 < $suhu && $suhu < 19) {
            $m_suhu_nrmal = ($suhu - 18) / (19 - 18);
        } elseif (28 < $suhu && $suhu < 29) {
            $m_suhu_nrmal = (29 - $suhu) / (29 - 28);
        } elseif (19 <= $suhu && $suhu <= 28) {
            $m_suhu_nrmal = 1;
        }
        // miu suhu pnas
        if ($suhu <= 28) {
            $m_suhu_pnas = 0;
        } elseif (28 < $suhu && $suhu < 29) {
            $m_suhu_pnas = ($suhu - 28) / (29 - 28);
        } elseif ($suhu >= 29) {
            $m_suhu_pnas = 1;
        }

        // PPM
        // miu ppm rndah
        if ($ppm >= $ppm_rendah) {
            $m_ppm_rndah = 0;
        } elseif ($ppm_cukup_min < $ppm && $ppm < $ppm_rendah) {
            $m_ppm_rndah = ($ppm_rendah - $ppm) / ($ppm_rendah - $ppm_cukup_min);
        } elseif ($ppm <= $ppm_cukup_min) {
            $m_ppm_rndah = 1;
        }
        // miu ppm ckup
        if ($ppm <= $ppm_cukup_min || $ppm >= $ppm_cukup_max) {
            $m_ppm_ckup = 0;
        } elseif ($ppm_cukup_min < $ppm && $ppm < $ppm_rendah) {
            $m_ppm_ckup = ($ppm - $ppm_cukup_min) / ($ppm_rendah - $ppm_cukup_min);
        } elseif ($ppm_tinggi < $ppm && $ppm < $ppm_cukup_max) {
            $m_ppm_ckup = ($ppm_cukup_max - $ppm) / ($ppm_cukup_max - $ppm_tinggi);
        } elseif ($ppm_rendah <= $ppm && $ppm <= $ppm_tinggi) {
            $m_ppm_ckup = 1;
        }
        // miu ppm tnggi
        if ($ppm <= $ppm_tinggi) {
            $m_ppm_tnggi = 0;
        } elseif ($ppm_tinggi < $ppm && $ppm < $ppm_cukup_max) {
            $m_ppm_tnggi = ($ppm - $ppm_tinggi) / ($ppm_cukup_max - $ppm_tinggi);
        } elseif ($ppm >= $ppm_cukup_max) {
            $m_ppm_tnggi = 1;
        }

        // PH
        // miu ppm asam
        if ($ph >= 6.7) {
            $m_ph_asam = 0;
        } elseif (6.5 < $ph && $ph < 6.7) {
            $m_ph_asam = (6.7 - $ph) / (6.7 - 6.5);
        } elseif ($ph <= 6.5) {
            $m_ph_asam = 1;
        }
        // miu ph ntral
        if ($ph <= 6.5 || $ph >= 7.5) {
            $m_ph_ntral = 0;
        } elseif (6.5 < $ph && $ph < 6.7) {
            $m_ph_ntral = ($ph - 6.5) / (6.7 - 6.5);
        } elseif (7.3 < $ph && $ph < 7.5) {
            $m_ph_ntral = (7.5 - $ph) / (7.5 - 7.3);
        } elseif (6.7 <= $ph && $ph <= 7.3) {
            $m_ph_ntral = 1;
        }
        // miu ph basa
        if ($ph <= 7.3) {
            $m_ph_basa = 0;
        } elseif (7.3 < $ph && $ph < 7.5) {
            $m_ph_basa = ($ph - 7.3) / (7.5 - 7.3);
        } elseif ($ph >= 7.5) {
            $m_ph_basa = 1;
        }

        // INFERENSI
        $z1 = 0;
        $z2 = 0;
        $z3 = 0;
        $z4 = 0;
        $z5 = 0;
        $z6 = 0;
        $z7 = 0;
        $z8 = 0;
        $z9 = 0;
        $z10 = 0;
        $z11 = 0;
        $z12 = 0;
        $z13 = 0;
        $z14 = 0;
        $z15 = 0;
        $z16 = 0;
        $z17 = 0;
        $z18 = 0;
        $z19 = 0;
        $z20 = 0;
        $z21 = 0;
        $z22 = 0;
        $z23 = 0;
        $z24 = 0;
        $z25 = 0;
        $z26 = 0;
        $z27 = 0;

        $a1 = min($m_suhu_dngin, $m_ppm_rndah, $m_ph_asam); // kondisi kurang bagus
        $z1 = 50;
        $a2 = min($m_suhu_dngin, $m_ppm_rndah, $m_ph_ntral); // kondisi kurang bagus
        $z2 = 50;
        $a3 = min($m_suhu_dngin, $m_ppm_rndah, $m_ph_basa); // kondisi kurang bagus
        $z3 = 50;
        $a4 = min($m_suhu_dngin, $m_ppm_ckup, $m_ph_asam); // kondisi kurang bagus
        $z4 = 50;
        $a5 = min($m_suhu_dngin, $m_ppm_ckup, $m_ph_ntral); // kondisi kurang bagus
        $z5 = 50;
        $a6 = min($m_suhu_dngin, $m_ppm_ckup, $m_ph_basa); // kondisi kurang bagus
        $z6 = 50;
        $a7 = min($m_suhu_dngin, $m_ppm_tnggi, $m_ph_asam); // kondisi kurang bagus
        $z7 = 50;
        $a8 = min($m_suhu_dngin, $m_ppm_tnggi, $m_ph_ntral); // kondisi kurang bagus
        $z8 = 50;
        $a9 = min($m_suhu_dngin, $m_ppm_tnggi, $m_ph_basa); // kondisi kurang bagus
        $z9 = 50;
        $a10 = min($m_suhu_nrmal, $m_ppm_rndah, $m_ph_asam); // kondisi kurang bagus
        $z10 = 50;
        $a11 = min($m_suhu_nrmal, $m_ppm_rndah, $m_ph_ntral); // kondisi kurang bagus
        $z11 = 50;
        $a12 = min($m_suhu_nrmal, $m_ppm_rndah, $m_ph_basa); // kondisi kurang bagus
        $z12 = 50;
        $a13 = min($m_suhu_nrmal, $m_ppm_ckup, $m_ph_asam); // kondisi OK
        $z13 = 50 * 2;
        $a14 = min($m_suhu_nrmal, $m_ppm_ckup, $m_ph_ntral); // kondisi OK
        $z14 = 50 * 2;
        $a15 = min($m_suhu_nrmal, $m_ppm_ckup, $m_ph_basa); // kondisi OK
        $z15 = 50 * 2;
        $a16 = min($m_suhu_nrmal, $m_ppm_tnggi, $m_ph_asam); // kondisi kurang bagus
        $z16 = 50;
        $a17 = min($m_suhu_nrmal, $m_ppm_tnggi, $m_ph_ntral); // kondisi kurang bagus
        $z17 = 50;
        $a18 = min($m_suhu_nrmal, $m_ppm_tnggi, $m_ph_basa); // kondisi kurang bagus
        $z18 = 50;
        $a19 = min($m_suhu_pnas, $m_ppm_rndah, $m_ph_asam); // kondisi kurang bagus
        $z19 = 50;
        $a20 = min($m_suhu_pnas, $m_ppm_rndah, $m_ph_ntral); // kondisi kurang bagus
        $z20 = 50;
        $a21 = min($m_suhu_pnas, $m_ppm_rndah, $m_ph_basa); // kondisi kurang bagus
        $z21 = 50;
        $a22 = min($m_suhu_pnas, $m_ppm_ckup, $m_ph_asam); // kondisi kurang bagus
        $z22 = 50;
        $a23 = min($m_suhu_pnas, $m_ppm_ckup, $m_ph_ntral); // kondisi kurang bagus
        $z23 = 50;
        $a24 = min($m_suhu_pnas, $m_ppm_ckup, $m_ph_basa); // kondisi kurang bagus
        $z24 = 50;
        $a25 = min($m_suhu_pnas, $m_ppm_tnggi, $m_ph_asam); // kondisi kurang bagus
        $z25 = 50;
        $a26 = min($m_suhu_pnas, $m_ppm_tnggi, $m_ph_ntral); // kondisi kurang bagus
        $z26 = 50;
        $a27 = min($m_suhu_pnas, $m_ppm_tnggi, $m_ph_basa); // kondisi kurang bagus
        $z27 = 50;

        // DEFUZZYFIKASI
        $total_AiZi = ($a1 * $z1) + ($a2 * $z2) + ($a3 * $z3) + ($a4 * $z4) + ($a5 * $z5) + ($a6 * $z6)
            + ($a7 * $z7) + ($a8 * $z8) + ($a9 * $z9) + ($a10 * $z10) + ($a11 * $z11) + ($a12 * $z12)
            + ($a13 * $z13) + ($a14 * $z14) + ($a15 * $z15) + ($a16 * $z16) + ($a17 * $z17) + ($a18 * $z18)
            + ($a19 * $z19) + ($a20 * $z20) + ($a21 * $z21) + ($a22 * $z22) + ($a23 * $z23) + ($a24 * $z24)
            + ($a25 * $z25) + ($a26 * $z26) + ($a27 * $z27);

        $total_a = $a1 + $a2 + $a3 + $a4 + $a5 + $a6 + $a7 + $a8 + $a9 + $a10 + $a11 + $a12 + $a13 + $a14
            + $a15 + $a16 + $a17 + $a18 + $a19 + $a20 + $a21 + $a22 + $a23 + $a24 + $a25 + $a26 + $a27;

        $total_Z = $total_AiZi / $total_a;

        // PENENTUAN KONDISI
        if ($total_Z <= 50) {
            $kondisi = 2;
        } elseif ($total_Z > 50) {
            $kondisi = 1;
        }

        $id = $this->NilaiAwalModel->select('id_awal')->orderBy('id_awal DESC')->first();

        $olah_data = [
            'total_AiZi' => $total_AiZi,
            'total_a' => $total_a,
            'total_Z' => $total_Z,
            'id_knd' => $kondisi,
            'id_awal' => $id['id_awal'],
            'm_suhu_dngin' => $m_suhu_dngin,
            'm_suhu_nrmal' => $m_suhu_nrmal,
            'm_suhu_pnas' => $m_suhu_pnas,
            'm_ppm_rndah' => $m_ppm_rndah,
            'm_ppm_ckup' => $m_ppm_ckup,
            'm_ppm_tnggi' => $m_ppm_tnggi,
            'm_ph_asam' => $m_ph_asam,
            'm_ph_ntral' => $m_ph_ntral,
            'm_ph_basa' => $m_ph_basa,
            'a1' => $a1,
            'a2' => $a2,
            'a3' => $a3,
            'a4' => $a4,
            'a5' => $a5,
            'a6' => $a6,
            'a7' => $a7,
            'a8' => $a8,
            'a9' => $a9,
            'a10' => $a10,
            'a11' => $a11,
            'a12' => $a12,
            'a13' => $a13,
            'a14' => $a14,
            'a15' => $a15,
            'a16' => $a16,
            'a17' => $a17,
            'a18' => $a18,
            'a19' => $a19,
            'a20' => $a20,
            'a21' => $a21,
            'a22' => $a22,
            'a23' => $a23,
            'a24' => $a24,
            'a25' => $a25,
            'a26' => $a26,
            'a27' => $a27,
            'z1' => $z1,
            'z2' => $z2,
            'z3' => $z3,
            'z4' => $z4,
            'z5' => $z5,
            'z6' => $z6,
            'z7' => $z7,
            'z8' => $z8,
            'z9' => $z9,
            'z10' => $z10,
            'z11' => $z11,
            'z12' => $z12,
            'z13' => $z13,
            'z14' => $z14,
            'z15' => $z15,
            'z16' => $z16,
            'z17' => $z17,
            'z18' => $z18,
            'z19' => $z19,
            'z20' => $z20,
            'z21' => $z21,
            'z22' => $z22,
            'z23' => $z23,
            'z24' => $z24,
            'z25' => $z25,
            'z26' => $z26,
            'z27' => $z27,
        ];

        $this->HitungModel->save($olah_data);
        echo $total_Z;
        echo '11';
    }
    */

    public function hitung($suhu, $ppm, $ph)
    {
        echo $suhu;
        echo $ppm;
        echo $ph;
    }
}
