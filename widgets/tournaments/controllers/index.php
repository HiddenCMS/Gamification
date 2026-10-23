<?php
namespace HB\Widgets\Tournaments\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Widget as Controller_Widget;
class Index extends Controller_Widget { public function index($settings = []) { if (!($module = $this->module('tournaments'))) { return ''; } $rows = $module->model()->all(TRUE); if (($settings['status'] ?? 'all') !== 'all') { $rows = array_values(array_filter($rows, function($row) use ($settings) { return $row['status'] === $settings['status']; })); } $this->css('gamification'); return $this->view('index', ['tournaments' => array_slice($rows, 0, $settings['limit'] ?? 6)]); } }
