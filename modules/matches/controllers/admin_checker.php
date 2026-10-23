<?php
namespace HB\Modules\Matches\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Module_Checker;
class Admin_Checker extends Module_Checker { public function index() { $this->allow(); return []; } public function add() { return $this->index(); } public function _edit($id) { $this->allow(); return ($row = $this->model()->get($id)) ? [$row] : NULL; } public function delete($id) { $this->ajax(); return $this->_edit($id); } private function allow() { if (!$this->is_authorized('manage_matches')) { $this->error->unauthorized(); } } }
