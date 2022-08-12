<?php

namespace App\Controllers;

use App\Models\Expansions;
use App\Models\Zones;
use App\Models\Marks;
use App\Models\Reports;

class Home extends BaseController
{
  public function index()
  {
    $this->Marks = new Marks();
    $this->Expansions = new Expansions();
    $this->Zones = new Zones();
    $this->Reports = new Reports();
    $expansions = $this->Expansions->orderBy('id', 'desc')->get()->getResult();
    foreach ($expansions as $thisExpansion) {
      $thisExpansion->zones = $this->Zones->where('expansion_id', $thisExpansion->id)->orderBy('id', 'desc')->get()->getResult();
      foreach ($thisExpansion->zones as $thisZone) {
        for ($instanceId = 1; $instanceId <= $thisZone->instance_count; $instanceId++) {
          $thisZone->instances[$instanceId] = new \stdClass();
          $thisZone->instances[$instanceId]->marks = $this->Marks->join('zones z', 'marks.zone_id = z.id')->join('expansions e', 'z.expansion_id = e.id')->join('reports r', 'r.mark_id = marks.id')->where('r.instance_id', $instanceId)->select('marks.*, z.name as zone_name, e.name as expansion_name, max(r.tod) as tod')->get()->getResult();
          foreach($thisZone->instances[$instanceId]->marks as $thisMark) {
            $thisMark->instance = $instanceId;
          }
        }
      }
    }
    $data['json_marks'] = json_encode($expansions);
    $data['expansions'] = $expansions;
    //now manipulate the data to pull out by instances
    echo view('header', $data);
    echo view('index', $data);
  }

  public function killed()
  {
    $mark_id = $this->request->getVar('id');
    $instance = $this->request->getVar('instance');
    $time = intval($this->request->getVar('time'));
    $this->Marks = new Marks();
    $data = ['last_kill' => $time, 'instance_id' => $instance];
    $this->Marks->update($mark_id, $data);
    echo 'success';
  }
}
