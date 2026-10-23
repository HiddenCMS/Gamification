<?php
namespace HB\Widgets\Awards;
use HB\HiddenCMS\Addons\Widget;
class Awards extends Widget { protected function __info() { return ['title' => $this->lang('Awards'), 'description' => $this->lang('Display the latest awards.'), 'icon' => 'fas fa-trophy', 'author' => 'HiddenCMS', 'license' => 'GPLv3', 'version' => '0.0.2']; } }
