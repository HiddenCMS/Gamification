<?php
namespace HB\Widgets\Teams\Controllers;
use HB\HiddenCMS\Loadables\Controller;
class Checker extends Controller { public function index($settings = []) { return ['team_id' => max(0, (int)($settings['team_id'] ?? 0)), 'limit' => min(24, max(1, (int)($settings['limit'] ?? 6)))]; } }
