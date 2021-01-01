<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Form validation rules by on controller
 *
 */
$default_rules 		= "trim|alpha_numeric_spaces|xss_clean";
$required_rules 	= "trim|required|alpha_numeric_spaces|xss_clean";

$default_numeric_rules 	= "trim|numeric|xss_clean";
$required_numeric_rules = "trim|required|numeric|xss_clean";

$default_alpha_rules 	= "trim|alpha|xss_clean";
$required_alpha_rules 	= "trim|required|alpha|xss_clean";

$default_alphanumeric_rules 	= "trim|alpha_numeric|xss_clean";
$required_alphanumeric_rules 	= "trim|required|alpha_numeric|xss_clean";

$required_email_rules 	= "trim|required|valid_email|xss_clean";

switch( strtolower(get_controller()) ) {
	case 'login' : 
		$config = array(
			'login' => array(
				array( 	
					'field' => 'username',
					'label' => 'Username',
					'rules'	=> 'trim|required|xss_clean'
				),
				array( 	
					'field' => 'password',
					'label' => 'Password',
					'rules'	=> 'trim|required|min_length[6]|xss_clean'
				)
			),
		);
	break;

	case 'church_leaders' : 
		$config = array(
			'validate' => array(
				array( 	
					'field' => 'first-name',
					'label' => 'First Name',
					'rules'	=> $required_rules
				),
				array( 	
					'field' => 'middle-name',
					'label' => 'Middle Name',
					'rules'	=> $default_rules
				),
				array( 	
					'field' => 'last-name',
					'label' => 'Last Name',
					'rules'	=> $required_rules
				),
				array( 	
					'field' => 'branch',
					'label' => 'Branch',
					'rules'	=> 'trim|xss_clean|required'
				),
				array( 	
					'field' => 'status',
					'label' => 'Status',
					'rules'	=> $default_numeric_rules
				)
			),
		);
	break;

	case 'church_branches' : 
		$config = array(
			'validate' => array(
				array( 	
					'field' => 'branch-name',
					'label' => 'Branch Name',
					'rules'	=> 'trim|xss_clean|required'
				),
				array( 	
					'field' => 'email-address',
					'label' => 'Email Address',
					'rules'	=> 'trim|xss_clean|required'
				),
				array( 	
					'field' => 'mobile-no',
					'label' => 'Mobile No.',
					'rules'	=> 'trim|xss_clean'
				)
			),
		);
	break;

	default : $config = array();
}


// pre( $config );

/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */