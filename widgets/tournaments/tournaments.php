<?php
namespace HB\Widgets\Tournaments;
use HB\HiddenCMS\Addons\Widget;
class Tournaments extends Widget { protected function __info() { return ['title' => $this->lang('Tournaments'), 'description' => $this->lang('Display tournaments by status.'), 'icon' => 'fas fa-sitemap', 'author' => 'HiddenCMS', 'license' => 'GPLv3', 'version' => '0.1.0']; } }
