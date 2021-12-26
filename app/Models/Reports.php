<?php

namespace App\Models;

use CodeIgniter\Model;

class Reports extends Model
{

protected $table = 'reports';
protected $primaryKey = 'id';
protected $returnType = 'object';
protected $allowedFields = ['tod', 'reported_by', 'confirmed'];

}
