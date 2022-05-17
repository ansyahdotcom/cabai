<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_adm';
    protected $useTimestamps = true;
    protected $createdField  = 'created_adm';
    protected $updatedField  = 'updated_adm';
    protected $allowedFields = ['username', 'password', 'created_adm'];
}
