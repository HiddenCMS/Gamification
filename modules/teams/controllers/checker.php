<?php
namespace HB\Modules\Teams\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Module_Checker;
class Checker extends Module_Checker { public function index() { return []; } public function _team($id, $slug) { return ($team = $this->model()->get($id)) && $team['published'] ? [$team] : NULL; } }
