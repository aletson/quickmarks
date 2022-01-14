<?php

namespace App\Models;

use CodeIgniter\Model;

class Marks extends Model
{

protected $table = 'marks';
protected $primaryKey = 'id';
protected $returnType = 'object';

public function getMarksWithKillTimes($zoneID, $instanceID) {
    $result = $this->builder->where('zone_id', $zoneID)->join('reports r', 'r.id = marks.id')->where('r.instance_id', $instanceID)->select('marks.*, max(r.timestamp)')->get()->getResultArray();
    return $result;
}
}
