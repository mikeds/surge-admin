<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_transactions extends Admin_Controller {
	public function after_init() {
		$this->load->model('admin/oauth_bridges_model', 'bridges');
		$this->load->model('admin/transactions_model', 'transactions');

		$this->set_scripts_and_styles();
	}

	public function index($page = 1) {
		$this->_data['form_url'] = base_url() . "client-transactions";

		$where = array();
		$or_where	= array(
			'cl1.account_number !=' => '',
			'cl2.account_number !=' => '',
		);

		$inner_joints = array(
			array(
				'table_name'	=> 'transaction_types',
				'condition'		=> 'transaction_types.transaction_type_id = transactions.transaction_type_id'
			),
			array(
				'table_name'	=> 'client_accounts as cl1',
				'condition'		=> 'cl1.oauth_bridge_id = transactions.transaction_requested_by',
				'type'			=> 'left'
			),
			array(
				'table_name'	=> 'client_accounts as cl2',
				'condition'		=> 'cl2.oauth_bridge_id = transactions.transaction_requested_to',
				'type'			=> 'left'
			)
		);

		$total_rows = $this->transactions->get_count_or(
			$where,
			$or_where,
			$inner_joints
		);

		$offset = $this->get_pagination_offset($page, $this->_limit, $total_rows);

		$results = $this->transactions->get_data_or(
			array(
				'*'
			),
			$where,
			$or_where,
			$inner_joints,
			array(
				'filter'=> 'transaction_date_created', 
				'sort'	=> 'DESC'
			),
			$offset,
			$this->_limit
		);
		
		$filtered_results = $this->filter_results($results);

		$this->_data['listing'] 	= $this->table_listing('', $filtered_results, $total_rows, $offset, $this->_limit, array(), 2);
		$this->_data['title']  		= "Client Transactions";
		$this->set_template("client_transactions/list", $this->_data);
	}

	private function filter_results($results) {
		$array = array();

		foreach ($results as $row) {
			$requested_by = $row['transaction_requested_by'];
			$requested_to = $row['transaction_requested_to'];

			$requested_by_row = $this->get_oauth_info($requested_by);
			$requested_to_row = $this->get_oauth_info($requested_to);

			$array[] = array(
				'TX ID'			=> $row['transaction_id'],
				'Sender Ref ID'	=> $row['transaction_sender_ref_id'],
				'TX Type'		=> $row['transaction_type_name'],
				'Amount'		=> $row['transaction_amount'],
				'Fee'			=> $row['transaction_fee'],
				'Total Amount'	=> $row['transaction_total_amount'],
				'TX By' 		=> isset($requested_by_row['name']) ? $requested_by_row['name'] : "",
				'TX To' 		=> isset($requested_to_row['name']) ? $requested_to_row['name'] : "",
				'Date Created'	=> $row['transaction_date_created']
			);
		}

		return $array;
	}
}
