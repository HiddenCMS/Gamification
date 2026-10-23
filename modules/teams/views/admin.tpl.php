<section class="teams-admin">
	<header class="teams-admin-toolbar">
		<div><h2><?php echo $this->lang('Teams') ?></h2><p><?php echo $this->lang('Manage teams and their rosters.') ?></p></div>
		<?php echo $this->button_create('admin/teams/add', (string)$this->lang('Create team')) ?>
	</header>
	<?php if ($teams): ?>
	<div class="teams-table-wrap"><table class="teams-table">
		<thead><tr><th><?php echo $this->lang('Team') ?></th><th><?php echo $this->lang('Members') ?></th><th><?php echo $this->lang('Status') ?></th><th class="teams-actions-heading"><?php echo $this->lang('Actions') ?></th></tr></thead>
		<tbody><?php foreach ($teams as $team): ?><tr style="--team-color:<?php echo utf8_htmlentities($team['color']) ?>">
			<td><a class="teams-identity" href="<?php echo url('admin/teams/edit/'.$team['team_id']) ?>"><span class="teams-logo"><?php if ($team['logo_path']): ?><img src="<?php echo utf8_htmlentities(url($team['logo_path'])) ?>" alt="" loading="lazy" /><?php else: ?><?php echo icon('fas fa-users') ?><?php endif ?></span><span><strong><?php echo utf8_htmlentities($team['title']) ?></strong><?php if ($team['description']): ?><small><?php echo utf8_htmlentities(mb_strimwidth(strip_tags($team['description']), 0, 90, '...')) ?></small><?php endif ?></span></a></td>
			<td data-label="<?php echo utf8_htmlentities((string)$this->lang('Members')) ?>"><span class="teams-count"><?php echo icon('fas fa-user') ?> <?php echo (int)$team['member_count'] ?></span></td>
			<td data-label="<?php echo utf8_htmlentities((string)$this->lang('Status')) ?>"><span class="teams-status <?php echo $team['published'] ? 'is-published' : 'is-draft' ?>"><?php echo $team['published'] ? $this->lang('Published') : $this->lang('Draft') ?></span></td>
			<td class="teams-actions"><a class="btn btn-secondary" href="<?php echo url('admin/teams/members/'.$team['team_id']) ?>"><?php echo icon('fas fa-users') ?> <span><?php echo $this->lang('Roster') ?></span></a><?php echo $this->button_update('admin/teams/edit/'.$team['team_id']) ?><?php echo $this->button_delete('admin/teams/delete/'.$team['team_id']) ?></td>
		</tr><?php endforeach ?></tbody>
	</table></div>
	<?php else: ?><div class="teams-empty"><?php echo icon('fas fa-users') ?><h3><?php echo $this->lang('No teams yet') ?></h3><p><?php echo $this->lang('Create your first team to start building a roster.') ?></p></div><?php endif ?>
</section>
