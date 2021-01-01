<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pastors extends Admin_Controller {
	public function after_init() {

		$this->load->model('admin/pastor_accounts_model', 'accounts');
		$this->set_scripts_and_styles();
	}

	public function index($page = 1) {

		// $this->_data['add_label']= "New Pastor Account";
		// $this->_data['add_url']	 = base_url() . "pastor-accounts/new";

		$actions = array(
			// 'update'
		);

		$select = array(
			'account_number as id',
			'account_number as "Account No."',
			'account_fname as "First Name"',
			'account_mname as "Middle Name"',
			'account_lname as "Last Name"',
			'account_email_address as "Email Address"',
			'account_mobile_no as "Mobile No."',
			'account_date_added as "Date Added"',
			'account_status as "Status"',
		);

		$where = array(
			// 'oauth_bridge_parent_id' => $admin_oauth_bridge_id
		);

		$inner_joints = array(
			array(
				'table_name' 	=> 'oauth_bridges',
				'condition'		=> 'oauth_bridges.oauth_bridge_id = pastor_accounts.oauth_bridge_id'
			),
		);

		$total_rows = $this->accounts->get_count(
			$where,
			array(),
			$inner_joints
		);

		$page = 1;

		$offset = $this->get_pagination_offset($page, $this->_limit, $total_rows);
	    $results = $this->accounts->get_data($select, $where, array(), $inner_joints, array('filter'=>'account_fname', 'sort'=>'ASC'), $offset, $this->_limit);

		$this->_data['listing'] = $this->table_listing('', $results, $total_rows, $offset, $this->_limit, $actions, 2);
		$this->_data['title']  = "Pastors";
		$this->set_template("pastors/list", $this->_data);
	}
}
