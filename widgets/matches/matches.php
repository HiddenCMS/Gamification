<?php
namespace HB\Widgets\Matches;
use HB\HiddenCMS\Addons\Widget;
class Matches extends Widget { protected function __info() { return ['title' => $this->lang('Matches'), 'description' => $this->lang('Display upcoming matches or recent results.'), 'icon' => 'fas fa-crosshairs', 'author' => 'HiddenCMS', 'license' => 'GPLv3', 'version' => '0.1.0']; } }
