<?php
namespace HB\Widgets\Tournaments\Controllers;
use HB\HiddenCMS\Loadables\Controller;
class Checker extends Controller { public function index($settings = []) { $status = in_array($settings['status'] ?? '', ['all','registration','running','completed'], TRUE) ? $settings['status'] : 'all'; return ['status' => $status, 'limit' => min(24, max(1, (int)($settings['limit'] ?? 6)))]; } }
