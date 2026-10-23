<?php
namespace HB\Modules\Matches\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Module as Controller_Module;
class Index extends Controller_Module { public function index() { $this->css('gamification'); return $this->view('index', ['matches' => $this->model()->all(TRUE)]); } public function _match($match) { $this->css('gamification'); return $this->view('match', ['match' => $match]); } }
