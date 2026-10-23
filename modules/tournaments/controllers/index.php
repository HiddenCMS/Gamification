<?php
namespace HB\Modules\Tournaments\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Module as Controller_Module;
class Index extends Controller_Module { public function index() { $this->css('gamification'); return $this->view('index', ['tournaments' => $this->model()->all(TRUE)]); } public function _tournament($row) { $this->css('gamification'); return $this->view('tournament', ['tournament' => $row, 'participants' => $this->model()->participants($row['tournament_id'])]); } }
