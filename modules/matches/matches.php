<?php

namespace HB\Modules\Matches;

use HB\HiddenCMS\Addons\Module;

class Matches extends Module
{
	protected function __info()
	{
		return [
			'title' => $this->lang('Matches'),
			'description' => $this->lang('Schedule matches and publish results.'),
			'icon' => 'fas fa-crosshairs',
			'author' => 'HiddenCMS',
			'license' => 'GPLv3',
			'version' => '0.0.1',
			'admin' => 'gaming',
			'front' => TRUE,
			'reserved_route' => 'matches',
			'routes' => [
				'admin/add' => 'add',
				'admin/edit/{id}' => '_edit',
				'admin/delete/{id}' => 'delete',
				'admin' => 'index',
				'{id}/{url_title}' => '_match',
				'' => 'index'
			]
		];
	}

	public function permissions()
	{
		return ['default' => ['access' => [[
			'title' => (string)$this->lang('Matches'),
			'icon' => 'fas fa-crosshairs',
			'access' => ['manage_matches' => ['title' => (string)$this->lang('Manage matches'), 'icon' => 'fas fa-edit', 'admin' => TRUE]]
		]]]];
	}

	public function menu_links()
	{
		return ['matches' => (string)$this->lang('Matches')];
	}
}
