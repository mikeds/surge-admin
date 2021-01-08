<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Church_transactions extends Admin_Controller {
	public function after_init() {
		$this->load->model('admin/oauth_bridges_model', 'bridges');
		$this->load->model('admin/transactions_model', 'transactions');

		$this->set_scripts_and_styles();
	}

	public function index($page = 1) {
		$this->_data['form_url'] = base_url() . "church-transactions";
		$branch_no = "";

		$actions = array();

		$account_oauth_bridge_id = $this->_oauth_bridge_id;

        $select = array(
			'*'
		);
		$select = ARRtoSTR($select);
		
		$query = "cb1.cbranch_number != '' OR cb2.cbranch_number != ''";

		if ($_POST) {
			$redirect_url = $this->_data['form_url'];
			
			$branch_no = $this->input->post('branch');

			if ($branch_no != "") {
				$redirect_url .= "?branch_no={$branch_no}";
			}

			redirect($redirect_url);
		}

		if (isset($_GET['branch_no'])) {
			$branch_no = $_GET['branch_no']; 
			$query = "(cb1.cbranch_number = '{$branch_no}') OR (cb2.cbranch_number = '{$branch_no}')";
		}

$sql = <<<SQL
SELECT count(*) as count FROM `transactions` as tx 
inner join transaction_types
on transaction_types.transaction_type_id = tx.transaction_type_id
left join church_branches as cb1 
on tx.transaction_requested_by = cb1.oauth_bridge_id
left join church_branches as cb2
on tx.transaction_requested_to = cb2.oauth_bridge_id
where
$query
LIMIT 1
SQL;

		$query_count 	= $this->db->query($sql);
		$count_result 	= $query_count->row();
		$total_rows 	= isset($count_result->count) ? $count_result->count : 0;
		$offset 		= $this->get_pagination_offset($page, $this->_limit, $total_rows);

		$query_limit = $offset == "" || $offset == 0 ? "LIMIT {$this->_limit}" : "LIMIT {$offset}, {$this->_limit}";

$sql = <<<SQL
SELECT $select FROM `transactions` as tx 
inner join transaction_types
on transaction_types.transaction_type_id = tx.transaction_type_id
left join church_branches as cb1 
on tx.transaction_requested_by = cb1.oauth_bridge_id
left join church_branches as cb2
on tx.transaction_requested_to = cb2.oauth_bridge_id
where
$query
$query_limit
SQL;

		$query = $this->db->query($sql);
		$results = $query->result_array();
		$filtered_results = $this->filter_results($results);

		$this->_data['branches'] =  $this->generate_selection(
			'branch',
			$this->get_branches(),
			$branch_no,
			'cbranch_number',
			'cbranch_name'
		);

		$this->_data['listing'] 	= $this->table_listing('', $filtered_results, $total_rows, $offset, $this->_limit, $actions, 2);
		$this->_data['title']  		= "Church Transactions";
		$this->set_template("church_transactions/list", $this->_data);
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
