<?php

namespace App\Models;

use CodeIgniter\Model;

class Marks extends Model
{

protected $table = 'marks';
protected $primaryKey = 'id';
protected $returnType = 'object';
protected $allowedFields = ['last_kill'];

}
