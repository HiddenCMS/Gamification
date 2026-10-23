<?php

namespace HB\Modules\Awards\Models;

use HB\HiddenCMS\Loadables\Model;
use InvalidArgumentException;

class Awards extends Model
{
	public function all($public = FALSE, $team_id = 0)
	{
		$this->db->select('a.*', 't.title AS team_title', 'tr.title AS tournament_title')->from('gamification_awards a')->join('gamification_teams t', 't.team_id = a.team_id', 'LEFT')->join('gamification_tournaments tr', 'tr.tournament_id = a.tournament_id', 'LEFT')->order_by('a.awarded_at', 'DESC');
		if ($public) { $this->db->where('a.published', 1); } if ($team_id) { $this->db->where('a.team_id', (int)$team_id); }
		return $this->db->get();
	}
	public function get($id) { return $this->db->from('gamification_awards')->where('award_id', (int)$id)->row(); }
	public function team_choices() { $out = ['' => (string)$this->lang('None')]; foreach ($this->db->from('gamification_teams')->order_by('title')->get() as $row) { $out[$row['team_id']] = utf8_htmlentities($row['title']); } return $out; }
	public function tournament_choices() { $out = ['' => (string)$this->lang('None')]; foreach ($this->db->from('gamification_tournaments')->order_by('title')->get() as $row) { $out[$row['tournament_id']] = utf8_htmlentities($row['title']); } return $out; }
	public function save(array $data, $id = 0)
	{
		$title = trim(utf8_html_entity_decode($data['title'] ?? '', ENT_QUOTES)); if ($title === '') { throw new InvalidArgumentException((string)$this->lang('The award title is required.')); }
		$date = trim($data['awarded_at'] ?? ''); if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date, $parts) || !checkdate((int)$parts[2], (int)$parts[3], (int)$parts[1])) { throw new InvalidArgumentException((string)$this->lang('Invalid award date.')); }
		$now = date('Y-m-d H:i:s'); $values = ['team_id' => (int)($data['team_id'] ?? 0) ?: NULL, 'tournament_id' => (int)($data['tournament_id'] ?? 0) ?: NULL, 'title' => $title, 'awarded_at' => $date, 'ranking' => max(1, (int)($data['ranking'] ?? 1)), 'participants' => (int)($data['participants'] ?? 0) ?: NULL, 'location' => trim(utf8_html_entity_decode($data['location'] ?? '', ENT_QUOTES)), 'description' => utf8_html_entity_decode($data['description'] ?? '', ENT_QUOTES), 'published' => !empty($data['published']) ? 1 : 0, 'updated_at' => $now];
		if ($id) { $this->db->where('award_id', (int)$id)->update('gamification_awards', $values); return $id; }
		$values['created_at'] = $now; return $this->db->insert('gamification_awards', $values);
	}
	public function delete_award($id) { $this->db->where('award_id', (int)$id)->delete('gamification_awards'); }
}
