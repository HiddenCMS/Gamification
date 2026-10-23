<?php

namespace HB\Modules\Awards;

use HB\HiddenCMS\Addons\Module;

class Awards extends Module
{
	protected function __info()
	{
		return [
			'title' => $this->lang('Awards'),
			'description' => $this->lang('Publish rankings and achievements.'),
			'icon' => 'fas fa-trophy',
			'author' => 'HiddenCMS',
			'license' => 'GPLv3',
			'version' => '0.0.2',
			'admin' => 'gaming',
			'front' => TRUE,
			'reserved_route' => 'awards',
			'routes' => [
				'admin/add' => 'add',
				'admin/edit/{id}' => '_edit',
				'admin/delete/{id}' => 'delete',
				'admin' => 'index',
				'{id}/{url_title}' => '_award',
				'' => 'index'
			]
		];
	}

	public function permissions()
	{
		return ['default' => ['access' => [[
			'title' => (string)$this->lang('Awards'),
			'icon' => 'fas fa-trophy',
			'access' => ['manage_awards' => ['title' => (string)$this->lang('Manage awards'), 'icon' => 'fas fa-edit', 'admin' => TRUE]]
		]]]];
	}

	public function menu_links()
	{
		return ['awards' => (string)$this->lang('Awards')];
	}
}
