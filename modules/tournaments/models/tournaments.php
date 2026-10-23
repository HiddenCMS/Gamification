<?php

namespace HB\Modules\Tournaments\Models;

use HB\HiddenCMS\Loadables\Model;
use InvalidArgumentException;

class Tournaments extends Model
{
	public static function formats() { return ['single_elimination' => 'Single elimination', 'double_elimination' => 'Double elimination', 'round_robin' => 'Round robin', 'league' => 'League']; }
	public static function statuses() { return ['draft' => 'Draft', 'registration' => 'Registration', 'running' => 'Running', 'completed' => 'Completed', 'cancelled' => 'Cancelled']; }

	public function all($public = FALSE)
	{
		$this->db->select('t.*', 'COUNT(p.participant_id) AS participant_count')->from('gamification_tournaments t')->join('gamification_tournament_participants p', 'p.tournament_id = t.tournament_id', 'LEFT')->group_by('t.tournament_id')->order_by('t.starts_at', 'DESC');
		if ($public) { $this->db->where('t.published', 1); }
		return $this->db->get();
	}
	public function get($id) { return $this->db->from('gamification_tournaments')->where('tournament_id', (int)$id)->row(); }
	public function participant($id) { return $this->db->from('gamification_tournament_participants')->where('participant_id', (int)$id)->row(); }
	public function participants($id) { return $this->db->select('p.*', 't.title AS team_title')->from('gamification_tournament_participants p')->join('gamification_teams t', 't.team_id = p.team_id', 'LEFT')->where('p.tournament_id', (int)$id)->order_by('p.sort_order')->order_by('p.seed_number')->get(); }
	public function choices($empty = TRUE) { $out = $empty ? ['' => (string)$this->lang('None')] : []; foreach ($this->all() as $row) { $out[$row['tournament_id']] = utf8_htmlentities($row['title']); } return $out; }
	public function team_choices() { $out = ['' => (string)$this->lang('None')]; foreach ($this->db->from('gamification_teams')->order_by('title')->get() as $row) { $out[$row['team_id']] = utf8_htmlentities($row['title']); } return $out; }

	public function save(array $data, $id = 0)
	{
		$title = trim(utf8_html_entity_decode($data['title'] ?? '', ENT_QUOTES));
		if ($title === '') { throw new InvalidArgumentException((string)$this->lang('The tournament name is required.')); }
		if (!isset(self::formats()[$data['format'] ?? ''])) { throw new InvalidArgumentException((string)$this->lang('Invalid tournament format.')); }
		if (!isset(self::statuses()[$data['status'] ?? ''])) { throw new InvalidArgumentException((string)$this->lang('Invalid tournament status.')); }
		$starts = $this->date($data['starts_at'] ?? ''); $ends = $this->date($data['ends_at'] ?? '');
		if ($starts && $ends && $ends < $starts) { throw new InvalidArgumentException((string)$this->lang('The end date must be after the start date.')); }
		$now = date('Y-m-d H:i:s'); $slug = url_title($title);
		if (($existing = $this->db->from('gamification_tournaments')->where('slug', $slug)->row()) && (int)$existing['tournament_id'] !== (int)$id) { throw new InvalidArgumentException((string)$this->lang('A tournament already uses this name.')); }
		$values = ['slug' => $slug, 'title' => $title, 'description' => utf8_html_entity_decode($data['description'] ?? '', ENT_QUOTES), 'format' => $data['format'], 'status' => $data['status'], 'starts_at' => $starts, 'ends_at' => $ends, 'location' => trim(utf8_html_entity_decode($data['location'] ?? '', ENT_QUOTES)), 'published' => !empty($data['published']) ? 1 : 0, 'updated_at' => $now];
		if ($id) { $this->db->where('tournament_id', (int)$id)->update('gamification_tournaments', $values); return $id; }
		$values['created_at'] = $now; return $this->db->insert('gamification_tournaments', $values);
	}
	public function save_participant(array $data, $tournament_id, $id = 0)
	{
		if (!$this->get($tournament_id)) { throw new InvalidArgumentException((string)$this->lang('Unknown tournament.')); }
		$name = trim(utf8_html_entity_decode($data['name'] ?? '', ENT_QUOTES));
		$team_id = (int)($data['team_id'] ?? 0) ?: NULL;
		if ($team_id && !$this->db->from('gamification_teams')->where('team_id', $team_id)->row()) { throw new InvalidArgumentException((string)$this->lang('Unknown team.')); }
		if ($name === '' && $team_id) { $team = $this->db->from('gamification_teams')->where('team_id', $team_id)->row(); $name = $team['title'] ?? ''; }
		if ($name === '') { throw new InvalidArgumentException((string)$this->lang('The participant name is required.')); }
		$values = ['tournament_id' => (int)$tournament_id, 'team_id' => $team_id, 'name' => $name, 'seed_number' => (int)($data['seed_number'] ?? 0) ?: NULL, 'group_name' => trim(utf8_html_entity_decode($data['group_name'] ?? '', ENT_QUOTES)), 'sort_order' => max(0, (int)($data['sort_order'] ?? 0))];
		if ($id) { $this->db->where('participant_id', (int)$id)->update('gamification_tournament_participants', $values); return $id; }
		return $this->db->insert('gamification_tournament_participants', $values);
	}
	public function delete_tournament($id) { $this->db->where('tournament_id', (int)$id)->delete('gamification_tournaments'); }
	public function delete_participant($id) { $this->db->where('participant_id', (int)$id)->delete('gamification_tournament_participants'); }
	private function date($value) { $value = trim(str_replace('T', ' ', $value)); if ($value === '') { return NULL; } if (strlen($value) === 16) { $value .= ':00'; } $date = \DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $value); if (!$date || $date->format('Y-m-d H:i:s') !== $value) { throw new InvalidArgumentException((string)$this->lang('Invalid date and time.')); } return $value; }
}
