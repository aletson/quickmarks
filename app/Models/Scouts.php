<?php

namespace App\Models;

use CodeIgniter\Model;

class Scouts extends Model
{

protected $table = 'scouts';
protected $primaryKey = 'id';
protected $returnType = 'object';
protected $allowedFields = ['mark_id', 'instance_id', 'timestamp', 'x', 'y', 'reported_by'];

}
