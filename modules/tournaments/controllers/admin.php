<?php
namespace HB\Modules\Tournaments\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Module as Controller_Module;
class Admin extends Controller_Module
{
	public function index() { return $this->view('admin', ['tournaments' => $this->model()->all()]); }
	public function add() { return $this->tournament_form(); } public function _edit($row) { return $this->tournament_form($row); }
	public function delete($row) { return $this->deletion('tournament', $row['tournament_id'], $row['title']); }
	public function _participants($row) { return $this->view('participants', ['tournament' => $row, 'participants' => $this->model()->participants($row['tournament_id'])]); }
	public function _participant_add($row) { return $this->participant_form($row); }
	public function _participant_edit($participant) { return $this->participant_form($this->model()->get($participant['tournament_id']), $participant); }
	public function _participant_delete($participant) { return $this->deletion('participant', $participant['participant_id'], $participant['name']); }
	private function tournament_form($row = [])
	{
		$id = $row['tournament_id'] ?? 0; $formats = []; foreach ($this->model()::formats() as $key => $label) { $formats[$key] = (string)$this->lang($label); } $statuses = []; foreach ($this->model()::statuses() as $key => $label) { $statuses[$key] = (string)$this->lang($label); }
		return $this->form2()->rule($this->form_text('title')->title('Name')->value($row['title'] ?? '')->required())->rule($this->form_textarea('description')->title('Description')->value($row['description'] ?? ''))->rule($this->form_select('format')->title('Format')->data($formats)->value($row['format'] ?? 'single_elimination')->search(0))->rule($this->form_select('status')->title('Status')->data($statuses)->value($row['status'] ?? 'draft')->search(0))->rule($this->form_text('starts_at')->title('Start')->value(substr($row['starts_at'] ?? '', 0, 16)))->rule($this->form_text('ends_at')->title('End')->value(substr($row['ends_at'] ?? '', 0, 16)))->rule($this->form_text('location')->title('Location')->value($row['location'] ?? ''))->rule($this->form_checkbox('published')->data(['1' => (string)$this->lang('Publish')])->value(!isset($row['published']) || $row['published'] ? ['1'] : []))->submit($id ? (string)$this->lang('Save') : (string)$this->lang('Create'))->back('admin/tournaments')->success(function($data, $form) use ($id) { try { $this->model()->save($data, $id); redirect('admin/tournaments'); } catch (\InvalidArgumentException $e) { $form->error(utf8_htmlentities($e->getMessage())); } })->panel();
	}
	private function participant_form($tournament, $row = [])
	{
		$id = $row['participant_id'] ?? 0;
		return $this->form2()->rule($this->form_select('team_id')->title('Team')->data($this->model()->team_choices())->value($row['team_id'] ?? '')->search(1))->rule($this->form_text('name')->title('Display name')->value($row['name'] ?? ''))->rule($this->form_number('seed_number')->title('Seed')->value($row['seed_number'] ?? ''))->rule($this->form_text('group_name')->title('Group')->value($row['group_name'] ?? ''))->rule($this->form_number('sort_order')->title('Order')->value($row['sort_order'] ?? 0))->submit($id ? (string)$this->lang('Save') : (string)$this->lang('Create'))->back('admin/tournaments/participants/'.$tournament['tournament_id'])->success(function($data, $form) use ($tournament, $id) { try { $this->model()->save_participant($data, $tournament['tournament_id'], $id); redirect('admin/tournaments/participants/'.$tournament['tournament_id']); } catch (\InvalidArgumentException $e) { $form->error(utf8_htmlentities($e->getMessage())); } })->panel();
	}
	private function deletion($type, $id, $title) { $this->form()->confirm_deletion((string)$this->lang('Deletion'), (string)$this->lang('Delete <b>%s</b>?', utf8_htmlentities($title))); if ($this->form()->is_valid()) { $this->model()->{'delete_'.$type}($id); return 'OK'; } return $this->form()->display(); }
}
