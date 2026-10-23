<?php

namespace HB\Modules\Matches\Models;

use HB\HiddenCMS\Loadables\Model;
use InvalidArgumentException;

class Matches extends Model
{
	public static function statuses() { return ['scheduled' => 'Scheduled', 'live' => 'Live', 'completed' => 'Completed', 'cancelled' => 'Cancelled']; }
	public function all($public = FALSE, $mode = 'all', $team_id = 0)
	{
		$this->db->select('m.*', 'ht.title AS home_team_title', 'at.title AS away_team_title', 't.title AS tournament_title')->from('gamification_matches m')->join('gamification_teams ht', 'ht.team_id = m.home_team_id', 'LEFT')->join('gamification_teams at', 'at.team_id = m.away_team_id', 'LEFT')->join('gamification_tournaments t', 't.tournament_id = m.tournament_id', 'LEFT')->order_by('m.starts_at '.($mode === 'results' ? 'DESC' : 'ASC'));
		if ($public) { $this->db->where('m.published', 1); }
		if ($mode === 'upcoming') { $this->db->where('m.status !=', 'completed')->where('m.status !=', 'cancelled'); }
		if ($mode === 'results') { $this->db->where('m.status', 'completed'); }
		if ($team_id) { $this->db->where('(m.home_team_id = '.(int)$team_id.' OR m.away_team_id = '.(int)$team_id.')'); }
		return $this->db->get();
	}
	public function get($id) { return $this->db->from('gamification_matches')->where('match_id', (int)$id)->row(); }
	public function team_choices() { $out = ['' => (string)$this->lang('None')]; foreach ($this->db->from('gamification_teams')->order_by('title')->get() as $row) { $out[$row['team_id']] = utf8_htmlentities($row['title']); } return $out; }
	public function tournament_choices() { $out = ['' => (string)$this->lang('None')]; foreach ($this->db->from('gamification_tournaments')->order_by('title')->get() as $row) { $out[$row['tournament_id']] = utf8_htmlentities($row['title']); } return $out; }
	public function save(array $data, $id = 0)
	{
		if (!isset(self::statuses()[$data['status'] ?? ''])) { throw new InvalidArgumentException((string)$this->lang('Invalid match status.')); }
		$home_id = (int)($data['home_team_id'] ?? 0) ?: NULL; $away_id = (int)($data['away_team_id'] ?? 0) ?: NULL; $tournament_id = (int)($data['tournament_id'] ?? 0) ?: NULL;
		$home = trim(utf8_html_entity_decode($data['home_name'] ?? '', ENT_QUOTES)); $away = trim(utf8_html_entity_decode($data['away_name'] ?? '', ENT_QUOTES));
		if ($home_id) { $row = $this->db->from('gamification_teams')->where('team_id', $home_id)->row(); if (!$row) { throw new InvalidArgumentException((string)$this->lang('Unknown team.')); } $home = $row['title']; }
		if ($away_id) { $row = $this->db->from('gamification_teams')->where('team_id', $away_id)->row(); if (!$row) { throw new InvalidArgumentException((string)$this->lang('Unknown team.')); } $away = $row['title']; }
		if ($tournament_id && !$this->db->from('gamification_tournaments')->where('tournament_id', $tournament_id)->row()) { throw new InvalidArgumentException((string)$this->lang('Unknown tournament.')); }
		if ($home === '' || $away === '') { throw new InvalidArgumentException((string)$this->lang('Both opponents are required.')); }
		if ($home_id && $away_id && $home_id === $away_id) { throw new InvalidArgumentException((string)$this->lang('A team cannot play against itself.')); }
		$score_home = trim((string)($data['score_home'] ?? '')); $score_away = trim((string)($data['score_away'] ?? '')); $now = date('Y-m-d H:i:s'); $stream_url = trim($data['stream_url'] ?? '');
		if ($stream_url !== '' && (!filter_var($stream_url, FILTER_VALIDATE_URL) || !in_array(strtolower(parse_url($stream_url, PHP_URL_SCHEME)), ['http', 'https'], TRUE))) { throw new InvalidArgumentException((string)$this->lang('Invalid stream URL.')); }
		$values = ['tournament_id' => $tournament_id, 'title' => trim(utf8_html_entity_decode($data['title'] ?? '', ENT_QUOTES)) ?: $home.' - '.$away, 'home_team_id' => $home_id, 'home_name' => $home, 'away_team_id' => $away_id, 'away_name' => $away, 'starts_at' => $this->date($data['starts_at'] ?? ''), 'status' => $data['status'], 'best_of' => max(1, (int)($data['best_of'] ?? 1)), 'score_home' => $score_home === '' ? NULL : max(0, (int)$score_home), 'score_away' => $score_away === '' ? NULL : max(0, (int)$score_away), 'stage' => trim(utf8_html_entity_decode($data['stage'] ?? '', ENT_QUOTES)), 'round_number' => (int)($data['round_number'] ?? 0) ?: NULL, 'location' => trim(utf8_html_entity_decode($data['location'] ?? '', ENT_QUOTES)), 'stream_url' => $stream_url, 'description' => utf8_html_entity_decode($data['description'] ?? '', ENT_QUOTES), 'published' => !empty($data['published']) ? 1 : 0, 'updated_at' => $now];
		if ($id) { $this->db->where('match_id', (int)$id)->update('gamification_matches', $values); return $id; }
		$values['created_at'] = $now; return $this->db->insert('gamification_matches', $values);
	}
	public function delete_match($id) { $this->db->where('match_id', (int)$id)->delete('gamification_matches'); }
	private function date($value) { $value = trim(str_replace('T', ' ', $value)); if ($value === '') { return NULL; } if (strlen($value) === 16) { $value .= ':00'; } $date = \DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $value); if (!$date || $date->format('Y-m-d H:i:s') !== $value) { throw new InvalidArgumentException((string)$this->lang('Invalid date and time.')); } return $value; }
}
