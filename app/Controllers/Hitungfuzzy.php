<?php

namespace App\Controllers;

use App\Models\NilaiAwalModel;
use App\Models\HitungModel;
use App\Models\PpmModel;

class Hitungfuzzy extends BaseController
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
            'hitung' => $this->HitungModel->orderBy('created_ht DESC')->findAll()
        ];
        return view('v_perhitungan', $data);
    }

    public function hitung($suhu, $ppm, $ph)
    {
        // http://localhost:8080/hitungfuzzy/hitung/24/1230/6
        // $data_awal = [
        //     'id_awal' => 26,
        //     'suhu' => $suhu,
        //     'ppm' => $ppm,
        //     'ph' => $ph
        // ];
        // $this->NilaiAwalModel->save($data_awal);

        // AMBIL DATA RENTANG UMUR
        $get_ppm = $this->PpmModel->where('st_ppm', '1')->first();
        $ppm_rendah = $get_ppm['ppm_rendah'];
        $ppm_cukup_min = $get_ppm['ppm_cukup_min'];
        $ppm_cukup_max = $get_ppm['ppm_cukup_max'];
        $ppm_tinggi = $get_ppm['ppm_tinggi'];

        // ATURAN
        // SUHU x <= 32 NORMAL, x >= 35 PANAS
        // PPM x <= 1250 RENDAH, 1200 < x < 1400 CUKUP, x >= 1350 TINGGI
        // PH x <= 6.7, 6.5 < x < 7.5 NETRAL, >= 7.3 BASA

        // jika suhu normal && PPM rendah && PH asam, maka kondisi kurang bagus (tambahkan nutrisi dan notif terlalu asam)
        // jika suhu normal && PPM rendah && PH netral, maka kondisi OK (tambahkan nutrisi)
        // jika suhu normal && PPM rendah && PH basa, maka kondisi kurang bagus (tambahkan nutrisi dan notif terlalu basa)
        // jika suhu normal && PPM cukup && PH asam, maka kondisi OK (notif terlalu asam)
        // jika suhu normal && PPM cukup && PH netral, maka kondisi OK
        // jika suhu normal && PPM cukup && PH basa, maka kondisi OK (notif terlalu basa)
        // jika suhu normal && PPM tinggi && PH asam, maka kondisi kurang bagus (tambahkan air dan notif terlalu asam)
        // jika suhu normal && PPM tinggi && PH netral, maka kondisi OK (tambahkan air)
        // jika suhu normal && PPM tinggi && PH basa, maka kondisi kurang bagus (tambahkan air dan notif terlalu basa)
        // jika suhu panas && PPM rendah && PH asam, maka kondisi kurang bagus (tambahkan nutrisi dan hidupkan pompa dan notif terlalu asam)
        // jika suhu panas && PPM rendah && PH netral, maka kondisi kurang bagus (tambahkan nutrisi dan hidupkan pompa)
        // jika suhu panas && PPM rendah && PH basa, maka kondisi kurang bagus (tambahkan nutrisi dan hidupkan pompa dan notif terlalu basa)
        // jika suhu panas && PPM cukup && PH asam, maka kondisi kurang bagus (hidupkan pompa dan notif terlalu asam)
        // jika suhu panas && PPM cukup && PH netral, maka kondisi OK (hidupkan pompa)
        // jika suhu panas && PPM cukup && PH basa, maka kondisi kurang bagus (hidupkan pompa dan notif terlalu basa)
        // jika suhu panas && PPM tinggi && PH asam, maka kondisi kurang bagus (tambahkan air dan hidupkan pompa dan notif terlalu asam)
        // jika suhu panas && PPM tinggi && PH netral, maka kondisi kurang bagus (tambahkan air dan hidupkan pompa)
        // jika suhu panas && PPM tinggi && PH basa, maka kondisi kurang bagus (tambahkan air dan hidupkan pompa dan notif terlalu basa)


        // FUZZIFIKASI
        // SUHU
        // miu suhu nrmal
        if ($suhu >= 35) {
            $m_shu_nrmal = 0;
        } elseif ($suhu > 32 && $suhu < 35) {
            $m_shu_nrmal = (35 - $suhu) / (35 - 32);
        } elseif ($suhu <= 32) {
            $m_shu_nrmal = 1;
        }
        // miu suhu pnas
        if ($suhu <= 32) {
            $m_shu_pnas = 0;
        } elseif ($suhu > 32 && $suhu < 35) {
            $m_shu_pnas = ($suhu - 32) / (35 - 32);
        } elseif ($suhu >= 35) {
            $m_shu_pnas = 1;
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
        } elseif (6.7 < $ph && $ph < 7.3) {
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

        // $z1 = 60 - ($a1 * (60 - 50));
        // $z2 = 60 - ($a2 * (60 - 50));
        $a1 = min($m_shu_nrmal, $m_ppm_rndah, $m_ph_asam); // kondisi kurang bagus
        $z1 = 50;
        $a2 = min($m_shu_nrmal, $m_ppm_rndah, $m_ph_ntral); // kondisi OK
        $z2 = 100;
        $a3 = min($m_shu_nrmal, $m_ppm_rndah, $m_ph_basa); // kondisi kurang bagus
        $z3 = 50;
        $a4 = min($m_shu_nrmal, $m_ppm_ckup, $m_ph_asam); // kondisi OK
        $z4 = 100;
        $a5 = min($m_shu_nrmal, $m_ppm_ckup, $m_ph_ntral); // kondisi OK
        $z5 = 100;
        $a6 = min($m_shu_nrmal, $m_ppm_ckup, $m_ph_basa); // kondisi OK
        $z6 = 100;
        $a7 = min($m_shu_nrmal, $m_ppm_tnggi, $m_ph_asam); // kondisi kurang bagus
        $z7 = 50;
        $a8 = min($m_shu_nrmal, $m_ppm_tnggi, $m_ph_ntral); // kondisi OK
        $z8 = 100;
        $a9 = min($m_shu_nrmal, $m_ppm_tnggi, $m_ph_basa); // kondisi kurang bagus
        $z9 = 50;
        $a10 = min($m_shu_pnas, $m_ppm_rndah, $m_ph_asam); // kondisi kurang bagus
        $z10 = 50;
        $a11 = min($m_shu_pnas, $m_ppm_rndah, $m_ph_ntral); // kondisi kurang bagus
        $z11 = 50;
        $a12 = min($m_shu_pnas, $m_ppm_rndah, $m_ph_basa); // kondisi kurang bagus
        $z12 = 50;
        $a13 = min($m_shu_pnas, $m_ppm_ckup, $m_ph_asam); // kondisi kurang bagus
        $z13 = 50;
        $a14 = min($m_shu_pnas, $m_ppm_ckup, $m_ph_ntral); // kondisi OK
        $z14 = 100;
        $a15 = min($m_shu_pnas, $m_ppm_ckup, $m_ph_basa); // kondisi kurang bagus
        $z15 = 50;
        $a16 = min($m_shu_pnas, $m_ppm_tnggi, $m_ph_asam); // kondisi kurang bagus
        $z16 = 50;
        $a17 = min($m_shu_pnas, $m_ppm_tnggi, $m_ph_ntral); // kondisi kurang bagus
        $z17 = 50;
        $a18 = min($m_shu_pnas, $m_ppm_tnggi, $m_ph_basa); // kondisi kurang bagus
        $z18 = 50;


        // DEFUZZYFIKASI
        $total_AiZi = ($a1 * $z1) + ($a2 * $z2) + ($a3 * $z3) + ($a4 * $z4) + ($a5 * $z5) + ($a6 * $z6)
            + ($a7 * $z7) + ($a8 * $z8) + ($a9 * $z9) + ($a10 * $z10) + ($a11 * $z11) + ($a12 * $z12)
            + ($a13 * $z13) + ($a14 * $z14) + ($a15 * $z15) + ($a16 * $z16) + ($a17 * $z17) + ($a18 * $z18);
        $total_a = $a1 + $a2 + $a3 + $a4 + $a5 + $a6 + $a7 + $a8 + $a9 + $a10 + $a11 + $a12 + $a13 + $a14 + $a15 + $a16 + $a17 + $a18;
        $total_Z = $total_AiZi / $total_a;
        
        $olah_data = [
            'id_ht' => 50,
            'm_shu_nrmal' => $m_shu_nrmal,
            'm_shu_pnas' => $m_shu_pnas,
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
            'total_AiZi' => $total_AiZi,
            'total_a' => $total_a,
            'total_Z' => $total_Z
        ];

        dd($olah_data);

        $this->HitungModel->save($olah_data);
        // KONDISI PERINTAH
        return redirect()->to('');
    }

    public function ppm($ppm)
    {
        // http://localhost:8080/hitungfuzzy/ppm/1222
        // AMBIL DATA RENTANG UMUR
        $get_ppm = $this->PpmModel->where('st_ppm', '1')->first();
        $ppm_rendah = $get_ppm['ppm_rendah'];
        $ppm_cukup_min = $get_ppm['ppm_cukup_min'];
        $ppm_cukup_max = $get_ppm['ppm_cukup_max'];
        $ppm_tinggi = $get_ppm['ppm_tinggi'];
        // FUZZIFIKASI
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

        $z1 = 0;
        $z2 = 0;
        $z3 = 0;

        $a1 = $m_ppm_rndah;
        $z1 = 50;
        $a2 = $m_ppm_ckup;
        $z2 = 100;
        $a3 = $m_ppm_tnggi;
        $z3 = 150;

        // DEFUZZYFIKASI
        $total_AiZi = ($a1 * $z1) + ($a2 * $z2) + ($a3 * $z3);
        $total_a = $a1 + $a2 + $a3;
        $total_Z = $total_AiZi / $total_a;

        // PENENTUAN KONDISI
        if ($total_Z <= 50) {
            $kondisi = 'tambahkan nutrisi';
        } elseif ($total_Z > 50 && $total_Z < 150) {
            $kondisi = 'kondisi ok';
        } elseif ($total_Z > 150) {
            $kondisi = 'tambahkan air';
        }

        $data = [
            'm_ppm_rndah' => $m_ppm_rndah,
            'm_ppm_ckup' => $m_ppm_ckup,
            'm_ppm_tnggi' => $m_ppm_tnggi,
            'a1' => $a1,
            'a2' => $a2,
            'a3' => $a3,
            'z1' => $z1,
            'z2' => $z2,
            'z3' => $z3,
            'total_AiZi' => $total_AiZi,
            'total_a' => $total_a,
            'total_Z' => $total_Z,
            'kondisi' => $kondisi
        ];

        dd($data);
    }
}
