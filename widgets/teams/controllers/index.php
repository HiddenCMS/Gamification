<?php
namespace HB\Widgets\Teams\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Widget as Controller_Widget;
class Index extends Controller_Widget { public function index($settings = []) { if (!($module = $this->module('teams'))) { return ''; } $teams = !empty($settings['team_id']) && ($team = $module->model()->get($settings['team_id'])) ? [$team] : $module->model()->all(TRUE); $teams = array_slice($teams, 0, $settings['limit'] ?? 6); foreach ($teams as &$team) { $team['members'] = $module->model()->members($team['team_id'], TRUE); } $this->css('gamification'); return $this->view('index', ['teams' => $teams]); } }
