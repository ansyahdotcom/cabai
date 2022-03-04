<?php

namespace App\Models;

use CodeIgniter\Model;

class PpmModel extends Model
{
    protected $table = 'ppm';
    protected $primaryKey = 'id_ppm';
    protected $useTimestamps = true;
    protected $createdField  = 'created_ppm';
    protected $updatedField  = 'updated_ppm';
    protected $allowedFields = ['id_ppm', 'umur', 'ppm_rendah', 'ppm_cukup_min', 'ppm_cukup_max', 'ppm_tinggi', 'st_ppm'];
}
