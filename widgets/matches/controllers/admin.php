<?php
namespace HB\Widgets\Matches\Controllers;
use HB\HiddenCMS\Loadables\Controller;
class Admin extends Controller { public function index($settings = []) { $module = $this->module('teams'); return $this->view('admin', ['settings' => $settings, 'teams' => $module ? $module->model()->all() : []]); } }
