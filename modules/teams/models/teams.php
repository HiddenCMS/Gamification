<?php

namespace HB\Modules\Teams\Models;

use HB\HiddenCMS\Loadables\Model;
use InvalidArgumentException;

class Teams extends Model
{
	public function all($public = FALSE)
	{
		$this->db->select('t.*', 'COUNT(m.member_id) AS member_count')->from('gamification_teams t')
			->join('gamification_team_members m', 'm.team_id = t.team_id', 'LEFT')->group_by('t.team_id')->order_by('t.title');
		if ($public) { $this->db->where('t.published', 1); }
		return $this->db->get();
	}

	public function get($id) { return $this->db->from('gamification_teams')->where('team_id', (int)$id)->row(); }
	public function member($id) { return $this->db->from('gamification_team_members')->where('member_id', (int)$id)->row(); }

	public function members($team_id, $public = FALSE)
	{
		$this->db->from('gamification_team_members')->where('team_id', (int)$team_id)->order_by('sort_order')->order_by('display_name');
		if ($public) { $this->db->where('published', 1); }
		return $this->db->get();
	}

	public function choices($empty = TRUE)
	{
		$choices = $empty ? ['' => (string)$this->lang('None')] : [];
		foreach ($this->all() as $team) { $choices[$team['team_id']] = utf8_htmlentities($team['title']); }
		return $choices;
	}

	public function save(array $data, $id = 0)
	{
		$title = trim(utf8_html_entity_decode($data['title'] ?? '', ENT_QUOTES));
		if ($title === '') { throw new InvalidArgumentException((string)$this->lang('The team name is required.')); }
		$color = trim($data['color'] ?? '#126b58');
		if (!preg_match('/^#[a-f0-9]{6}$/i', $color)) { throw new InvalidArgumentException((string)$this->lang('Invalid hexadecimal color.')); }
		$now = date('Y-m-d H:i:s'); $slug = url_title($title);
		if (($existing = $this->db->from('gamification_teams')->where('slug', $slug)->row()) && (int)$existing['team_id'] !== (int)$id) { throw new InvalidArgumentException((string)$this->lang('A team already uses this name.')); }
		$values = ['slug' => $slug, 'title' => $title, 'description' => utf8_html_entity_decode($data['description'] ?? '', ENT_QUOTES), 'color' => $color, 'published' => !empty($data['published']) ? 1 : 0, 'updated_at' => $now];
		if ($id) { $this->db->where('team_id', (int)$id)->update('gamification_teams', $values); return $id; }
		$values['created_at'] = $now;
		return $this->db->insert('gamification_teams', $values);
	}

	public function save_member(array $data, $team_id, $id = 0)
	{
		if (!$this->get($team_id)) { throw new InvalidArgumentException((string)$this->lang('Unknown team.')); }
		$name = trim(utf8_html_entity_decode($data['display_name'] ?? '', ENT_QUOTES));
		if ($name === '') { throw new InvalidArgumentException((string)$this->lang('The member name is required.')); }
		$steam = trim($data['steam_id'] ?? '');
		$values = ['team_id' => (int)$team_id, 'display_name' => $name, 'role' => trim(utf8_html_entity_decode($data['role'] ?? '', ENT_QUOTES)), 'steam_id' => $steam !== '' ? $steam : NULL, 'captain' => !empty($data['captain']) ? 1 : 0, 'sort_order' => max(0, (int)($data['sort_order'] ?? 0)), 'published' => !empty($data['published']) ? 1 : 0];
		if ($id) { $this->db->where('member_id', (int)$id)->update('gamification_team_members', $values); return $id; }
		return $this->db->insert('gamification_team_members', $values);
	}

	public function delete_team($id) { $this->db->where('team_id', (int)$id)->delete('gamification_teams'); }
	public function delete_member($id) { $this->db->where('member_id', (int)$id)->delete('gamification_team_members'); }
}
