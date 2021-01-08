<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CMS_Controller class
 * Base controller ?
 *
 * @author Marknel Pineda
 */
class Admin_Controller extends Admin_Core_Controller {
	private 
		$_stylesheets = array(),
		$_scripts = array();

	protected
		$_account = null;

	protected
		$_oauth_bridge_id = null,
		$_oauth_bridge_parent_id = null;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize all configs, helpers, libraries from parent
		parent::__construct();
		date_default_timezone_set("Asia/Manila");
		$this->_today = date("Y-m-d H:i:s");

		$this->validate_login();
		$this->setup_nav_sidebar_menu();
		$this->after_init();
	}

	public function validate_login() {
		$login_url = base_url() . "login";

        $controller = strtolower(get_controller());
		if(empty($this->session->userdata("{$this->_base_session}")) && $controller != 'login' ) {
            $this->session->unset_userdata("{$this->_base_session}");
			$this->session->sess_destroy();
            redirect($login_url);
        } else if(!empty($this->session->userdata("{$this->_base_session}"))) {
			$this->_account = $this->session->userdata("{$this->_base_session}");

			$this->_oauth_bridge_id 		= $this->_account['oauth_bridge_id'];
			$this->_oauth_bridge_parent_id 	= $this->_account['oauth_bridge_parent_id'];
		}
	}

	/**
	 * Generate Menu UI
	 *
	 */
	private function setup_nav_sidebar_menu() {
		$this->_data['logout_url'] = base_url() . "logout";

		$menu_items = array();

		$menu_items[] = array(
			'menu_id'			=> 'dashboard',
			'menu_title'		=> 'Dashboard',
			'menu_url'			=> 	base_url(),
			'menu_controller'	=> 'dashboard',
			'menu_icon'			=> 'view-dashboard',
			// 'menu_sub_items'	=> array(
			// 	array(
			// 		'menu_title'		=> 'Sub Menu Sample',
			// 		'menu_url'			=> 	base_url(),
			// 		'menu_controller'	=> 'dashboard',
			// 	)
			// )
		);

		$menu_items[] = array(
			'menu_id'			=> 'pastors',
			'menu_title'		=> 'Pastors',
			'menu_url'			=> 	base_url() . "pastors",
			'menu_controller'	=> 'pastors',
			'menu_icon'			=> 'view-dashboard',
		);

		$menu_items[] = array(
			'menu_id'			=> 'church-branches',
			'menu_title'		=> 'Church Branches',
			'menu_url'			=> 	base_url() . "church-branches",
			'menu_controller'	=> 'church_branches',
			'menu_icon'			=> 'view-dashboard',
		);

		$menu_items[] = array(
			'menu_id'			=> 'church-leaders',
			'menu_title'		=> 'Church Leaders',
			'menu_url'			=> 	base_url() . "church-leaders",
			'menu_controller'	=> 'church_leaders',
			'menu_icon'			=> 'view-dashboard',
		);

		$menu_items[] = array(
			'menu_id'			=> 'church-transactions',
			'menu_title'		=> 'Church Transactions',
			'menu_url'			=> 	base_url() . "church-transactions",
			'menu_controller'	=> 'church_transactions',
			'menu_icon'			=> 'view-dashboard',
		);

		$menu_items[] = array(
			'menu_id'			=> 'pastor-transactions',
			'menu_title'		=> 'Pastor Transactions',
			'menu_url'			=> 	base_url() . "pastor-transactions",
			'menu_controller'	=> 'pastor_transactions',
			'menu_icon'			=> 'view-dashboard',
		);

		$menu_items[] = array(
			'menu_id'			=> 'client-transactions',
			'menu_title'		=> 'Client Transactions',
			'menu_url'			=> 	base_url() . "client-transactions",
			'menu_controller'	=> 'client_transactions',
			'menu_icon'			=> 'view-dashboard',
		);

		$this->_data['nav_sidebar_menu'] = $this->generate_sidebar_items($menu_items);
	}

	public function get_oauth_info($oauth_bridge_id) {
		$this->load->model("admin/client_accounts_model", "clients");
		$this->load->model("admin/pastor_accounts_model", "pastors");
		$this->load->model("admin/church_branches_model", "branches");
		$this->load->model("admin/admins_model", "admins");

		$client_row = $this->clients->get_datum(
			'',
			array(
				'oauth_bridge_id' => $oauth_bridge_id
			)
		)->row();

		if ($client_row != "") {
			return array(
				'name' => "{$client_row->account_fname} {$client_row->account_mname} {$client_row->account_lname}"
			);
		}

		$pastor_row = $this->pastors->get_datum(
			'',
			array(
				'oauth_bridge_id' => $oauth_bridge_id
			)
		)->row();

		if ($pastor_row != "") {
			return array(
				'name' => "{$pastor_row->account_fname} {$pastor_row->account_mname} {$pastor_row->account_lname}"
			);
		}

		$branch_row = $this->branches->get_datum(
			'',
			array(
				'oauth_bridge_id' => $oauth_bridge_id
			)
		)->row();

		if ($branch_row != "") {
			return array(
				'name' => $branch_row->cbranch_name
			);
		}

		$admin_row = $this->admins->get_datum(
			'',
			array(
				'oauth_bridge_id' => $oauth_bridge_id
			)
		)->row();

		if ($admin_row != "") {
			return array(
				'name' => $admin_row->admin_name
			);
		}

		return false;
	}

	public function get_branches() {
		$this->load->model("admin/church_branches_model", "branches");

		$results = $this->branches->get_data(
			array(
				'cbranch_number',
				'cbranch_name'
			),
			array(
				'cbranch_status' => 1
			),
			array(),
			array(),
			array('filter'=> 'cbranch_name', 'sort'=>'ASC')
		);

		return $results;
	}
}
