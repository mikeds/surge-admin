<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Church_branches extends Admin_Controller {
	public function after_init() {
		$this->load->model('admin/church_branches_model', 'branches');
		$this->load->model('admin/oauth_bridges_model', 'bridges');

		$this->set_scripts_and_styles();
	}

	public function index($page = 1) {
		$this->_data['add_label']= "New Church Branch";
		$this->_data['add_url']	 = base_url() . "church-branches/new";

		$actions = array(
			'update'
		);

		$select = array(
			'cbranch_number as id',
			'cbranch_number as "Branch No."',
			'cbranch_name as "Name"',
			'cbranch_email_address as "Email Address"',
			'cbranch_mobile_no as "Mobile No."',
			'cbranch_status as "Status"',
			'cbranch_date_added as "Date Added"'
		);

		$where = array();

		$inner_joints = array();

		$total_rows = $this->branches->get_count(
			$where,
			array(),
			$inner_joints
		);

		$offset = $this->get_pagination_offset($page, $this->_limit, $total_rows);
	    $results = $this->branches->get_data($select, $where, array(), $inner_joints, array('filter'=>'cbranch_name', 'sort'=>'ASC'), $offset, $this->_limit);

		$this->_data['listing'] = $this->table_listing('', $results, $total_rows, $offset, $this->_limit, $actions, 2);
		$this->_data['title']  = "Church Branches";
		$this->set_template("branches/list", $this->_data);
	}

	public function new() {
		$this->_data['form_url']		= base_url() . "church-branches/new";
		$this->_data['notification'] 	= $this->session->flashdata('notification');

		if ($_POST) {
			if ($this->form_validation->run('validate')) {
				$branch_name	= $this->input->post("branch-name");
				$mobile_no		= $this->input->post("mobile-no");
				$email_address	= $this->input->post("email-address");

				$branch_number = $this->generate_code(
					array(
						"branch",
						$this->_oauth_bridge_parent_id,
						$branch_name,
						$email_address,
						$this->_today
					),
					"crc32"
				);

				$bridge_id = $this->generate_code(
					array(
						"branch",
						$branch_number,
						$this->_today
					)
				);

				$this->branches->insert(
					array(
						'oauth_bridge_id'		=> $bridge_id,
						'cbranch_number'		=> $branch_number,
						'cbranch_name'			=> $branch_name,
						'cbranch_mobile_no'		=> $mobile_no,
						'cbranch_email_address'	=> $email_address,
						'cbranch_date_added'	=> $this->_today
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

				// create token auth for api (for entity with api login only)
				$this->create_token_auth($branch_number, $bridge_id);

				$this->session->set_flashdata('notification', $this->generate_notification('success', 'Successfully Added!'));
				redirect($this->_data['form_url']);
			}
		}

		$this->_data['title']  = "New Church Branch";
		$this->set_template("branches/form", $this->_data);
	}

	public function update($id) {
		$this->_data['form_url']		= base_url() . "church-branches/update/{$id}";
		$this->_data['notification'] 	= $this->session->flashdata('notification');
		$back_url = base_url() . "church-branches";

		$row = $this->branches->get_datum(
			'',
			array(
				'cbranch_number'	=> $id
			)
		)->row();

		if ($row == "") {
			redirect($back_url);
		}

		$this->_data['post'] = array(
			'branch-name'	=> $row->cbranch_name,
			'mobile-no'		=> $row->cbranch_mobile_no,
			'email-address'	=> $row->cbranch_email_address,
			'status'		=> $row->cbranch_status
		);

		if ($_POST) {
			if ($this->form_validation->run('validate')) {
				$branch_name	= $this->input->post("branch-name");
				$mobile_no		= $this->input->post("mobile-no");
				$email_address	= $this->input->post("email-address");
				$status			= $this->input->post("status");
				
				$this->branches->update(
					$id,
					array(
						'cbranch_name'			=> $branch_name,
						'cbranch_mobile_no'		=> $mobile_no,
						'cbranch_email_address'	=> $email_address,
						'cbranch_status'		=> $status == 1 ? 1 : 0
					)
				);

				$this->session->set_flashdata('notification', $this->generate_notification('success', 'Successfully Updated!'));
				redirect($this->_data['form_url']);
			}
		}

		$this->_data['is_update'] 	= true;
		$this->_data['title']  		= "Update Church Branch";
		$this->set_template("branches/form", $this->_data);
	}
}
