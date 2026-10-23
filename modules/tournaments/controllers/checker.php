<?php
namespace HB\Modules\Tournaments\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Module_Checker;
class Checker extends Module_Checker { public function index() { return []; } public function _tournament($id, $slug) { return ($row = $this->model()->get($id)) && $row['published'] ? [$row] : NULL; } }
