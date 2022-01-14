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
        $expansions = $this->Expansions->orderBy('id', 'desc')->findAll();
        foreach ($expansions as $thisExpansion) {
          for ($instanceId = 1; $instanceId <= $thisExpansion->instances; $instanceId++) {
            $thisExpansion[$instanceId]['zones'] = $this->Zones->findAll();
            foreach ($thisExpansion[$instanceId]['zones'] as $thisZone) {
              $thisZone['marks'] = $this->Marks->getMarksWithKillTimes($thisZone->id, $instanceId);
            }
          }
        }
        $data['json_marks'] = json_encode($expansions);
        //now manipulate the data to pull out by instances
        echo view('header', $data);
        echo view('index', $data);
    }
    
    public function killed() {
      $mark_id = $this->request->getVar('id');
      $time = intval($this->request->getVar('time'));
      $this->Marks = new Marks();
      $data = [ 'last_kill' => $time ];
      $this->Marks->update($mark_id, $data);
      echo 'success';
    }
}
