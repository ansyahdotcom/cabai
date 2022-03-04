<?php

namespace App\Models;

use CodeIgniter\Model;

class HitungModel extends Model
{
    protected $table = 'hitung_fuzzy';
    protected $primaryKey = 'id_ht';
    protected $useTimestamps = true;
    protected $createdField  = 'created_ht';
    protected $updatedField  = 'updated_ht';
    protected $allowedFields = [
        'm_shu_dngin',
        'm_shu_nrmal',
        'm_shu_pnas',
        'm_ppm_rndah',
        'm_ppm_ckup',
        'm_ppm_tnggi',
        'm_ph_asam',
        'm_ph_basa',
        'a1',
        'a2',
        'a3',
        'a4',
        'a5',
        'a6',
        'a7',
        'a8',
        'a9',
        'a10',
        'a11',
        'a12',
        'a13',
        'a14',
        'a15',
        'a16',
        'a17',
        'a18',
        'z1',
        'z2',
        'z3',
        'z4',
        'z5',
        'z6',
        'z7',
        'z8',
        'z9',
        'z10',
        'z11',
        'z12',
        'z13',
        'z14',
        'z15',
        'z16',
        'z17',
        'z18',
        'total_AiZi',
        'total_a',
        'total_Z',
        'created_ht'
    ];
}
