<?php
namespace HB\Modules\Teams\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Module_Checker;
class Admin_Checker extends Module_Checker
{
	public function index() { $this->allow('manage_teams'); return []; }
	public function add() { return $this->index(); }
	public function _edit($id) { $this->allow('manage_teams'); return ($row = $this->model()->get($id)) ? [$row] : NULL; }
	public function delete($id) { $this->ajax(); return $this->_edit($id); }
	public function _members($id) { $this->allow('manage_rosters'); return ($row = $this->model()->get($id)) ? [$row] : NULL; }
	public function _member_add($id) { return $this->_members($id); }
	public function _member_edit($id) { $this->allow('manage_rosters'); return ($row = $this->model()->member($id)) ? [$row] : NULL; }
	public function _member_delete($id) { $this->ajax(); return $this->_member_edit($id); }
	private function allow($permission) { if (!$this->is_authorized($permission)) { $this->error->unauthorized(); } }
}
