<?php
namespace HB\Widgets\Matches\Controllers;
use HB\HiddenCMS\Loadables\Controller;
class Checker extends Controller { public function index($settings = []) { $mode = in_array($settings['mode'] ?? '', ['all','upcoming','results'], TRUE) ? $settings['mode'] : 'upcoming'; return ['mode' => $mode, 'team_id' => max(0, (int)($settings['team_id'] ?? 0)), 'limit' => min(24, max(1, (int)($settings['limit'] ?? 5)))]; } }
