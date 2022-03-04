<?php

namespace App\Models;

use CodeIgniter\Model;

class CobaModel extends Model
{
    protected $table = 'dht';
    protected $primaryKey = 'id_dht';
    protected $useTimestamps = true;
    protected $createdField  = 'created_dht';
    protected $updatedField  = 'updated_dht';
    protected $allowedFields = ['suhu', 'kelembaban', 'created_dht'];
}
