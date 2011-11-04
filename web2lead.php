<?php
/*
Plugin Name: Web2Lead Sugar CRM
Plugin URI: http://www.2wconsulting.com
Description: Plugin to collect leads from a Wordpress contact form and save into Sugar CRM lead records. Developed using WordPress 3.2.1, SugarCRM CE 6.2.4 and PHP 5.3.8
Version: 1.0.0
Author: Will Wilson
Author URI: http://www.2wconsulting.com
License: GPL2
*/

	//	INSTALL/UNINSTALL HOOKS
	register_activation_hook(__FILE__,'web2lead_install');
	register_deactivation_hook(__FILE__,'web2lead_uninstall');
	
	function web2lead_install() {
		/*	create options	*/
		add_option("web2lead_sugarcrm_url");
		add_option("web2lead_sugarcrm_user");
		add_option("web2lead_sugarcrm_pwd");
	}
	
	function web2lead_uninstall() {
		//	delete options
		delete_option("web2lead_sugarcrm_url");
		delete_option("web2lead_sugarcrm_user");
		delete_option("web2lead_sugarcrm_pwd");
	}
	
	/*	create menu area	*/
	function web2lead_menu() {
		add_menu_page( 'Web2Lead', 'Web2Lead', 'manage_options', 'web2lead-menu', 'web2lead_main');
	}
	add_action('admin_menu', 'web2lead_menu');


	function web2lead_main() {
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		//	display main options page
		include('web2lead-main.php');
	}
	
	/*	create shortcode tags.	*/
	// [web2lead]
	function web2lead_display() {
		//	display contact form
		include('web2lead-form.php');
	}
	add_shortcode('web2lead', 'web2lead_display');
?>