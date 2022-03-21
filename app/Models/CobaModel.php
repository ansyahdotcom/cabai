<?php

namespace App\Models;

use CodeIgniter\Model;

class CobaModel extends Model
{
    protected $table = 'dht';
    protected $primaryKey = 'id_st';
    protected $useTimestamps = true;
    protected $createdField  = 'created_dht';
    protected $updatedField  = 'updated_dht';
    protected $allowedFields = ['st_suhu', 'st_ppm', 'st_ph', 'created_dht'];
}
