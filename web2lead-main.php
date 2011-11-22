<div style="text-align:center;background:#FFFF66">
<p>Like this plugin, but need more configuration options? Check out the Web2Lead SugarCRM Pro version <a href="http://www.2wconsulting.com/products-2/premium-plugins/web2lead-sugarcrm-pro/" target="_blank">here</a>!</p>
</div>
<h2>Web2Lead - SugarCRM Settings</h2>
<p>Welcome to the Web2Lead SugarCRM plugin. This plugin allows you to collect leads from a contact form in Wordpress and save into Sugar CRM lead records.</p>
<p>In order to save data into SugarCRM, you need to specify some basic information about your environment. Any records created by this plugin will be imported by and assigned to the user specified below:</p>
<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>
	<table class="form-table">
		<tr valign="top">
			<th>SugarCRM SOAP URL</th>
			<td><input type="text" name="web2lead_sugarcrm_url" size="80" placeholder="Example: http://mysugarcrmurl/soap.php" value="<?php echo get_option('web2lead_sugarcrm_url'); ?>" /></td>
		</tr>
		<tr valign="top">
			<th>SugarCRM Login Name</th>
			<td><input type="text" id="comp_date" name="web2lead_sugarcrm_user" size="50" value="<?php echo get_option('web2lead_sugarcrm_user'); ?>" /></td>
		</tr>
		<tr valign="top">
			<th>SugarCRM Login Password</th>
			<td><input type="password" id="comp_begin" name="web2lead_sugarcrm_pwd" size="50" value="<?php echo get_option('web2lead_sugarcrm_pwd'); ?>" /></td>
		</tr>
	</table>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="web2lead_sugarcrm_url,web2lead_sugarcrm_user,web2lead_sugarcrm_pwd" />
	<div style="text-align:center;">
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</div>
</form>
<p>Lastly, to expose the contact form on your Wordpress site, simply create a page and place the following shortcode on the page: [web2lead]</p>
<hr />
<h2>Test it out!</h2>
<p>Use the form below to test the Web2Lead plugin:</p>
<?php
	include('web2lead-form.php');
	echo BuildForm();
?>
