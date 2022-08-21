<?php

namespace App\Models;

use CodeIgniter\Model;

class Reports extends Model
{

protected $table = 'reports';
protected $primaryKey = 'id';
protected $returnType = 'object';
protected $allowedFields = ['mark_id', 'instance_id', 'tod', 'reported_by', 'confirmed'];

}
