<?php

namespace App\Controllers;

use App\Models\Marks;

class Home extends BaseController
{
    public function index()
    {
        $this->Marks = new Marks();
        $data = ['marks' => $this->Marks->findAll()];
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
