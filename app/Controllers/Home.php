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
        $expansions = $this->Expansions->orderBy('id', 'desc')->get()->getResult();
        foreach ($expansions as $thisExpansion) {
          $thisExpansion->instances = [];
          for ($instanceId = 1; $instanceId <= $thisExpansion->instance_count; $instanceId++) {
            $thisExpansion->instances[$instanceId] = new \stdClass();
            $thisExpansion->instances[$instanceId]->marks = $this->Marks->join('zones z', 'marks.zone_id = z.id')->join('reports r', 'r.mark_id = marks.id')->where('z.expansion_id', $thisExpansion->id)->where('r.instance_id', $instanceId)->select('marks.*, z.name as zone_name, max(r.tod) as tod')->get()->getResult();
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
