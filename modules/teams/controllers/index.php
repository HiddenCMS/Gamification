<?php
namespace HB\Modules\Teams\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Module as Controller_Module;
class Index extends Controller_Module { public function index() { $this->css('gamification'); return $this->view('index', ['teams' => $this->model()->all(TRUE)]); } public function _team($team) { $this->css('gamification'); return $this->view('team', ['team' => $team, 'members' => $this->model()->members($team['team_id'], TRUE)]); } }
