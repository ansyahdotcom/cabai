<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiAwalModel extends Model
{
    protected $table = 'nilai_awal';
    protected $primaryKey = 'id_awal';
    protected $useTimestamps = true;
    protected $createdField  = 'created_awal';
    protected $updatedField  = 'updated_awal';
    protected $allowedFields = ['ppm', 'suhu', 'ph', 'created_awal'];
}
