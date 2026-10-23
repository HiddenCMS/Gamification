<?php
namespace HB\Widgets\Matches\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Widget as Controller_Widget;
class Index extends Controller_Widget { public function index($settings = []) { if (!($module = $this->module('matches'))) { return ''; } $this->css('gamification'); return $this->view('index', ['matches' => array_slice($module->model()->all(TRUE, $settings['mode'] ?? 'upcoming', $settings['team_id'] ?? 0), 0, $settings['limit'] ?? 5)]); } }
