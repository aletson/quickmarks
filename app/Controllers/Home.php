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
      $thisExpansion->zones = $this->Zones->where('expansion_id', $thisExpansion->id)->orderBy('id', 'asc')->get()->getResult();
      foreach ($thisExpansion->zones as $thisZone) {
        for ($instanceId = 1; $instanceId <= $thisZone->instance_count; $instanceId++) {
          $thisZone->instances[$instanceId] = new \stdClass();
          $thisZone->instances[$instanceId]->marks = $this->Marks
            ->join('zones z', 'marks.zone_id = z.id')
            ->where('z.id', $thisZone->id)
            ->select('marks.*')->get()->getResult();
          if ($thisZone->instances[$instanceId]->marks) {
            foreach ($thisZone->instances[$instanceId]->marks as $thisMark) {
              $thisMark->last_kill = intval($this->Reports->select('max(tod) as tod')->where('mark_id = ' . $thisMark->id . ' and instance_id = ' . $instanceId)->get()->getRow()->tod);
              $thisMark->instance = $instanceId;
            }
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
    $this->Reports = new Reports();
    $data = ['mark_id' => $mark_id, 'tod' => $time, 'instance_id' => $instance, 'valid' => 1];
    $this->Reports->insert($data);
    echo 'success';
  }

  public function scout()
  {
    $mark_id = $this->request->getVar('id');
    $instance = $this->request->getVar('instance');
    $time = intval($this->request->getVar('time'));
    $this->Scouts = new Scouts();
    $data = ['mark_id' => $mark_id, 'timestamp' => $time, 'instance_id' => $instance, 'x' => $x, 'y' => $y];
    $this->Scouts->insert($data);
    echo 'success';
  }

  public function scout_text()
  {
    $time = intval($this->request->getVar('time'));
    // returns an array from the javascript side, let's parse clientside
  }
}
  
