<?php
namespace HB\Modules\Awards\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Module as Controller_Module;
class Admin extends Controller_Module
{
	public function index() { return $this->view('admin', ['awards' => $this->model()->all()]); } public function add() { return $this->award_form(); } public function _edit($row) { return $this->award_form($row); }
	public function delete($row) { $this->form()->confirm_deletion((string)$this->lang('Deletion'), (string)$this->lang('Delete <b>%s</b>?', utf8_htmlentities($row['title']))); if ($this->form()->is_valid()) { $this->model()->delete_award($row['award_id']); return 'OK'; } return $this->form()->display(); }
	private function award_form($row = [])
	{
		$id = $row['award_id'] ?? 0;
		return $this->form2()->rule($this->form_text('title')->title('Title')->value($row['title'] ?? '')->required())->rule($this->form_select('team_id')->title('Team')->data($this->model()->team_choices())->value($row['team_id'] ?? '')->search(1))->rule($this->form_select('tournament_id')->title('Tournament')->data($this->model()->tournament_choices())->value($row['tournament_id'] ?? '')->search(1))->rule($this->form_text('awarded_at')->title('Date')->value($row['awarded_at'] ?? date('Y-m-d'))->required())->rule($this->form_number('ranking')->title('Ranking')->value($row['ranking'] ?? 1))->rule($this->form_number('participants')->title('Participants')->value($row['participants'] ?? ''))->rule($this->form_text('location')->title('Location')->value($row['location'] ?? ''))->rule($this->form_textarea('description')->title('Description')->value($row['description'] ?? ''))->rule($this->form_checkbox('published')->data(['1' => (string)$this->lang('Publish')])->value(!isset($row['published']) || $row['published'] ? ['1'] : []))->submit($id ? (string)$this->lang('Save') : (string)$this->lang('Create'))->back('admin/awards')->success(function($data, $form) use ($id) { try { $this->model()->save($data, $id); redirect('admin/awards'); } catch (\InvalidArgumentException $e) { $form->error(utf8_htmlentities($e->getMessage())); } })->panel();
	}
}
