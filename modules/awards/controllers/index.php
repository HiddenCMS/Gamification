<?php
namespace HB\Modules\Awards\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Module as Controller_Module;
class Index extends Controller_Module { public function index() { $this->css('gamification'); return $this->view('index', ['awards' => $this->model()->all(TRUE)]); } public function _award($row) { $this->css('gamification'); return $this->view('award', ['award' => $row]); } }
