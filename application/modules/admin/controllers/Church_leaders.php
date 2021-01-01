<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Church_leaders extends Admin_Controller {
	public function after_init() {
		$this->load->model('admin/church_leaders_model', 'church_leaders');
		$this->load->model('admin/oauth_bridges_model', 'bridges');

		$this->set_scripts_and_styles();
	}

	public function index($page = 1) {
		$this->_data['add_label']= "New Church Leader";
		$this->_data['add_url']	 = base_url() . "church-leaders/new";

		$actions = array(
			'update'
		);

		$select = array(
			'cleader_number as id',
			'cleader_number as "Church No."',
			'cleader_fname as "First Name"',
			'cleader_mname as "Middle Name"',
			'cleader_lname as "Last Name"',
			'cleader_status as "Status"',
			'cleader_date_added as "Date Added"'
		);

		$where = array();

		$inner_joints = array();

		$total_rows = $this->church_leaders->get_count(
			$where,
			array(),
			$inner_joints
		);

		$offset = $this->get_pagination_offset($page, $this->_limit, $total_rows);
	    $results = $this->church_leaders->get_data($select, $where, array(), $inner_joints, array('filter'=>'cleader_date_added', 'sort'=>'DESC'), $offset, $this->_limit);

		$this->_data['listing'] = $this->table_listing('', $results, $total_rows, $offset, $this->_limit, $actions, 2);
		$this->_data['title']  = "Church Leaders";
		$this->set_template("church_leaders/list", $this->_data);
	}

	public function new() {
		$this->_data['form_url']		= base_url() . "church-leaders/new";
		$this->_data['notification'] 	= $this->session->flashdata('notification');

		$branches = $this->get_church_branches();
		$branch_number = "";

		if ($_POST) {
			if ($this->form_validation->run('validate')) {
				$fname			= $this->input->post("first-name");
				$mname			= $this->input->post("middle-name");
				$lname			= $this->input->post("last-name");
				$branch_number	= $this->input->post("branch");

				$leader_number = $this->generate_code(
					array(
						"church_leader",
						$this->_oauth_bridge_parent_id,
						$fname,
						$mname,
						$lname,
						$this->_today
					),
					"crc32"
				);

				$bridge_id = $this->generate_code(
					array(
						"church_leader",
						$leader_number,
						$this->_today
					)
				);

				$this->church_leaders->insert(
					array(
						'cleader_number'		=> $leader_number,
						'oauth_bridge_id'		=> $bridge_id,
						'cbranch_number'		=> $branch_number,
						'cleader_fname'			=> $fname,
						'cleader_mname'			=> $mname,
						'cleader_lname'			=> $lname,
						'cleader_date_added'	=> $this->_today
					)
				);

				$this->bridges->insert(
					array(
						'oauth_bridge_id' 			=> $bridge_id,
						'oauth_bridge_parent_id'	=> $this->_oauth_bridge_parent_id,
						'oauth_bridge_date_added'	=> $this->_today
					)
				);

				// create wallet address
				$this->create_wallet_address($branch_number, $bridge_id, $this->_oauth_bridge_parent_id);

				$this->session->set_flashdata('notification', $this->generate_notification('success', 'Successfully Added!'));
				redirect($this->_data['form_url']);
			}
		}

		$this->_data['branches'] 	= $this->generate_branches($branches, $branch_number);
		$this->_data['title']  		= "New Church Leader";
		$this->set_template("church_leaders/form", $this->_data);
	}

	public function update($id) {
		$this->_data['form_url']		= base_url() . "church-leaders/update/{$id}";
		$this->_data['notification'] 	= $this->session->flashdata('notification');
		$back_url						= base_url() . "church-leaders";

		$branches = $this->get_church_branches();

		$row = $this->church_leaders->get_datum(
			'',
			array(
				'cleader_number'	=> $id
			)
		)->row();

		if ($row == "") {
			redirect($back_url);
		}
		
		$this->_data['post'] = array(
			'first-name'	=> $row->cleader_fname,
			'middle-name'	=> $row->cleader_mname,
			'last-name'		=> $row->cleader_lname,
			'status'		=> $row->cleader_status
		);

		$branch_number = $row->cbranch_number;

		if ($_POST) {
			if ($this->form_validation->run('validate')) {
				$fname			= $this->input->post("first-name");
				$mname			= $this->input->post("middle-name");
				$lname			= $this->input->post("last-name");
				$branch_number	= $this->input->post("branch");
				$status			= $this->input->post("status");

				$this->church_leaders->update(
					$id,
					array(
						'cleader_fname'			=> $fname,
						'cleader_mname'			=> $mname,
						'cleader_lname'			=> $lname,
						'cleader_status'		=> $status == 1 ? 1 : 0,
						'cleader_date_added'	=> $this->_today
					)
				);

				$this->session->set_flashdata('notification', $this->generate_notification('success', 'Successfully Updated!'));
				redirect($this->_data['form_url']);
			}
		}

		$this->_data['branches'] 	= $this->generate_branches($branches, $branch_number);
		$this->_data['is_update']	= true;
		$this->_data['title']  		= "Update Church Leader";
		$this->set_template("church_leaders/form", $this->_data);
	}

	private function generate_branches($data, $branch_number = "") {
		return $this->generate_selection(
			"branch",
			$data,
			$branch_number,
			"cbranch_number", 
			"cbranch_name"
		);
	}
}
