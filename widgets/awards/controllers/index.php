<?php
namespace HB\Widgets\Awards\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Widget as Controller_Widget;
class Index extends Controller_Widget { public function index($settings = []) { if (!($module = $this->module('awards'))) { return ''; } $this->css('gamification'); return $this->view('index', ['awards' => array_slice($module->model()->all(TRUE, $settings['team_id'] ?? 0), 0, $settings['limit'] ?? 6)]); } }
