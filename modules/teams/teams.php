<?php

namespace HB\Modules\Teams;

use HB\HiddenCMS\Addons\Module;

class Teams extends Module
{
	protected function __info()
	{
		return [
			'title' => $this->lang('Teams'),
			'description' => $this->lang('Manage teams and their rosters.'),
			'icon' => 'fas fa-users',
			'author' => 'HiddenCMS',
			'license' => 'GPLv3',
			'version' => '0.0.2',
			'admin' => 'gaming',
			'front' => TRUE,
			'reserved_route' => 'teams',
			'routes' => [
				'admin/add' => 'add',
				'admin/edit/{id}' => '_edit',
				'admin/delete/{id}' => 'delete',
				'admin/members/{id}' => '_members',
				'admin/members/{id}/add' => '_member_add',
				'admin/members/edit/{id}' => '_member_edit',
				'admin/members/delete/{id}' => '_member_delete',
				'admin' => 'index',
				'{id}/{url_title}' => '_team',
				'' => 'index'
			]
		];
	}

	public function permissions()
	{
		return ['default' => ['access' => [[
			'title' => (string)$this->lang('Teams'),
			'icon' => 'fas fa-users',
			'access' => [
				'manage_teams' => ['title' => (string)$this->lang('Manage teams'), 'icon' => 'fas fa-edit', 'admin' => TRUE],
				'manage_rosters' => ['title' => (string)$this->lang('Manage team rosters'), 'icon' => 'fas fa-user-plus', 'admin' => TRUE]
			]
		]]]];
	}

	public function menu_links()
	{
		return ['teams' => (string)$this->lang('Teams')];
	}
}
