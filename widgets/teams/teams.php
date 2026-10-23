<?php
namespace HB\Widgets\Teams;
use HB\HiddenCMS\Addons\Widget;
class Teams extends Widget { protected function __info() { return ['title' => $this->lang('Teams'), 'description' => $this->lang('Display teams or one roster.'), 'icon' => 'fas fa-users', 'author' => 'HiddenCMS', 'license' => 'GPLv3', 'version' => '0.0.2']; } }
