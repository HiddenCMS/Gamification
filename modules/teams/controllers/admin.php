<?php
namespace HB\Modules\Teams\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Module as Controller_Module;
class Admin extends Controller_Module
{
	public function index() { $this->css('admin'); return $this->view('admin', ['teams' => $this->model()->all()]); }
	public function add() { return $this->team_form(); }
	public function _edit($team) { return $this->team_form($team); }
	public function delete($team) { return $this->deletion('team', $team['team_id'], $team['title']); }
	public function _members($team) { return $this->view('members', ['team' => $team, 'members' => $this->model()->members($team['team_id'])]); }
	public function _member_add($team) { return $this->member_form($team); }
	public function _member_edit($member) { return $this->member_form($this->model()->get($member['team_id']), $member); }
	public function _member_delete($member) { return $this->deletion('member', $member['member_id'], $member['display_name']); }
	private function team_form($team = [])
	{
		$this->css('admin')->css('file_picker')->js('file_picker');
		$id = $team['team_id'] ?? 0;
		return $this->form2()
			->rule($this->form_text('title')->title('Name')->value($team['title'] ?? '')->required())
			->rule($this->form_textarea('description')->title('Description')->value($team['description'] ?? ''))
			->info($this->module('files')->picker_field('logo_id', $team['logo_id'] ?? 0, $this->lang('Team logo'), 'image', $this->lang('No image selected')))
			->info($this->module('files')->picker_field('banner_id', $team['banner_id'] ?? 0, $this->lang('Banner'), 'image', $this->lang('No image selected')))
			->rule($this->form_colorpicker('color')->title('Color')->value($team['color'] ?? '#126b58')->required())
			->rule($this->form_checkbox('published')->size('hb-switch-field')->data(['1' => (string)$this->lang('Publish')])->value(!isset($team['published']) || $team['published'] ? ['1'] : []))
			->submit($id ? (string)$this->lang('Save') : (string)$this->lang('Create'))->back('admin/teams')
			->success(function($data, $form) use ($id) {
				try { $data['logo_id'] = post('logo_id'); $data['banner_id'] = post('banner_id'); $this->model()->save($data, $id); notify((string)$this->lang('Team saved')); redirect('admin/teams'); }
				catch (\InvalidArgumentException $e) { $form->error(utf8_htmlentities($e->getMessage())); }
			})->panel();
	}
	private function member_form($team, $member = [])
	{
		$id = $member['member_id'] ?? 0;
		return $this->form2()->rule($this->form_text('display_name')->title('Name')->value($member['display_name'] ?? '')->required())->rule($this->form_text('role')->title('Role')->value($member['role'] ?? ''))->rule($this->form_text('steam_id')->title('Steam ID')->value($member['steam_id'] ?? ''))->rule($this->form_number('sort_order')->title('Order')->value($member['sort_order'] ?? 0))->rule($this->form_checkbox('captain')->data(['1' => (string)$this->lang('Captain')])->value(!empty($member['captain']) ? ['1'] : []))->rule($this->form_checkbox('published')->data(['1' => (string)$this->lang('Publish')])->value(!isset($member['published']) || $member['published'] ? ['1'] : []))->submit($id ? (string)$this->lang('Save') : (string)$this->lang('Create'))->back('admin/teams/members/'.$team['team_id'])->success(function($data, $form) use ($team, $id) { try { $this->model()->save_member($data, $team['team_id'], $id); redirect('admin/teams/members/'.$team['team_id']); } catch (\InvalidArgumentException $e) { $form->error(utf8_htmlentities($e->getMessage())); } })->panel();
	}
	private function deletion($type, $id, $title) { $this->form()->confirm_deletion((string)$this->lang('Deletion'), (string)$this->lang('Delete <b>%s</b>?', utf8_htmlentities($title))); if ($this->form()->is_valid()) { $this->model()->{'delete_'.$type}($id); return 'OK'; } return $this->form()->display(); }
}
