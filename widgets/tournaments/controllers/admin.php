<?php
namespace HB\Widgets\Tournaments\Controllers;
use HB\HiddenCMS\Loadables\Controller;
class Admin extends Controller { public function index($settings = []) { return $this->view('admin', ['settings' => $settings]); } }
