<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {
	public function after_init() {
		$this->load->model('admin/oauth_bridges_model', 'bridges');

		$this->set_scripts_and_styles();
	}

	public function index() {
		$this->_data['title']  		= "Dashboard";
		$this->set_template("dashboard/index", $this->_data);
	}
}
