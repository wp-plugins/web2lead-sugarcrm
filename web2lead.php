<?php
/*
Plugin Name: Web2Lead Sugar CRM
Plugin URI: http://www.2wconsulting.com
Description: Plugin to collect leads from a Wordpress contact form and save into Sugar CRM lead records. Developed using WordPress 3.2.1, SugarCRM CE 6.2.4 and PHP 5.3.8. If you like this version but need additional configuration options, see my website for Web2Lead SugarCRM Pro.
Version: 1.0.5
Author: Will Wilson
Author URI: http://www.2wconsulting.com
License: GPL2
*/

	//	INSTALL/UNINSTALL HOOKS
	register_activation_hook(__FILE__,'web2lead_install');
	register_deactivation_hook(__FILE__,'web2lead_uninstall');
	
	function web2lead_install() {
		//	create options
		add_option("web2lead_sugarcrm_url");
		add_option("web2lead_sugarcrm_user");
		add_option("web2lead_sugarcrm_pwd");
		add_option("web2lead_enable_notify_email", true);
		add_option("web2lead_notify_email",get_option("admin_email"));
		add_option("web2lead_enable_recaptcha", true);
		
		//	create form field arrays
		$fields = array(		
			array( "id"=> "txtFirstName", "type"=>"textbox", "display"=> "First Name", "size"=> 60, "required"=> 1),
			array( "id"=> "txtLastName", "type"=>"textbox", "display"=> "Last Name", "size"=> 60, "required"=> 1),
			array( "id"=> "txtCompany", "type"=>"textbox", "display"=> "Company", "size"=> 60),
			array( "id"=> "txtPhone", "type"=>"textbox", "display"=> "Phone", "size"=> 60),
			array( "id"=> "txtEmail", "type"=>"textbox", "display"=> "Email", "size"=> 60, "required"=> 1, "validate_email"=>1),
			array( "id"=> "txtComments", "type"=>"textarea", "display"=> "Comments", "size"=> 60)
		);
		add_option("web2lead_form_fields",serialize($fields));
		
		//	create mapping arrays
		$mappings = array(
			array( "type"=>"field", "value"=>"txtFirstName", "target"=>"first_name"),
			array( "type"=>"field", "value"=>"txtLastName", "target"=>"last_name"),
			array( "type"=>"value", "value"=>"New", "target"=>"status"),
			array( "type"=>"field", "value"=>"txtPhone", "target"=>"phone_work"),
			array( "type"=>"field", "value"=>"txtEmail", "target"=>"email1"),
			array( "type"=>"field", "value"=>"txtCompany", "target"=>"account_name"),
			array( "type"=>"value", "value"=>"Web Site", "target"=>"lead_source"),
			array( "type"=>"field", "value"=>"txtComments", "target"=>"description")
		);
		add_option("web2lead_mappings",serialize($mappings));
	}
	
	function web2lead_uninstall() {
		//	delete options
		delete_option("web2lead_sugarcrm_url");
		delete_option("web2lead_sugarcrm_user");
		delete_option("web2lead_sugarcrm_pwd");
		delete_option("web2lead_enable_notify_email");
		delete_option("web2lead_notify_email");
		delete_option("web2lead_enable_recaptcha");
		delete_option("web2lead_form_fields");
		delete_option("web2lead_mappings");
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
		return BuildForm();
	}
	add_shortcode('web2lead', 'web2lead_display');
?>