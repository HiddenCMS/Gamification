<?php

namespace HB\Modules\Tournaments;

use HB\HiddenCMS\Addons\Module;

class Tournaments extends Module
{
	protected function __info()
	{
		return [
			'title' => $this->lang('Tournaments'),
			'description' => $this->lang('Organize tournaments and their participants.'),
			'icon' => 'fas fa-sitemap',
			'author' => 'HiddenCMS',
			'license' => 'GPLv3',
			'version' => '0.0.2',
			'admin' => 'gaming',
			'front' => TRUE,
			'reserved_route' => 'tournaments',
			'routes' => [
				'admin/add' => 'add',
				'admin/edit/{id}' => '_edit',
				'admin/delete/{id}' => 'delete',
				'admin/participants/{id}' => '_participants',
				'admin/participants/{id}/add' => '_participant_add',
				'admin/participants/edit/{id}' => '_participant_edit',
				'admin/participants/delete/{id}' => '_participant_delete',
				'admin' => 'index',
				'{id}/{url_title}' => '_tournament',
				'' => 'index'
			]
		];
	}

	public function permissions()
	{
		return ['default' => ['access' => [[
			'title' => (string)$this->lang('Tournaments'),
			'icon' => 'fas fa-sitemap',
			'access' => ['manage_tournaments' => ['title' => (string)$this->lang('Manage tournaments'), 'icon' => 'fas fa-edit', 'admin' => TRUE]]
		]]]];
	}

	public function menu_links()
	{
		return ['tournaments' => (string)$this->lang('Tournaments')];
	}
}
