<?php

function BuildForm() {
	$html = "<h2>Contact Us</h2>";
	$nospam = $_POST["nospam"];
	$submit = $_POST["web2lead-submit"];
	$is_error = false;
	
	if($nospam == null && $submit == 1) {
		//	check for required fields
		$fields = unserialize(get_option("web2lead_form_fields"));

		$err_msg = "";
		foreach ($fields as $field) {
			$field_name = $field["id"];
			$field_display = $field["display"];
			$field_req = $field["required"];
			$validate_email = $field["validate_email"];
			$field_value = $_POST[$field_name];
			
			if($field_req == 1 && $field_value == null) {
				//	required field missing
				$is_error = true;
				$err_msg .= $field_display." is required.<br />";
			}
			if($field_value != null && $validate_email == 1) {
				if(ValidateEmail($field_value) == false) {
					$is_error = true;
					$err_msg .= $field_display." is invalid.<br />";
				}
			}
		}
		if($is_error) {
			//	
			$html .= "The following errors were found:<br/>";
			$html .= $err_msg;
		}
		else {
			//	import lead
			require_once('sugarlead.php');
			
			$sugar = new SugarLead();
			if($sugar->CreateLead()) {
				$html .= '<p>Thank you for your interest!</p>';
			}
		}
	}
	if($nospam || $submit != 1 || ($submit == 1 && $is_error == true)) {
		$html .= "<form method=\"post\">
	<table class=\"form-table\">";
			//	get our fields
			$fields = unserialize(get_option("web2lead_form_fields"));
			foreach ($fields as $field) {
				//	each field is stored as an array
				$field_name = $field["id"];
				$field_type = $field["type"];
				$field_display = $field["display"];
				$field_size = $field["size"];
				
				//	try to retain values if this was an incorrectly submitted form
				$value = $_POST[$field_name];
				$html .= "<tr><th>".$field_display."</th><td>";
				if($field_type=="textbox") {
					$html .= "<input type='$field_type' id='$field_name' name='$field_name' size='$field_size' value='$value' />";
				}
				elseif($field_type == "textarea") {
					$html .= "<textarea id='$field_name' name='$field_name' cols='$field_size'></textarea>";
				}
				$html .= "</td></tr>";
			}
		$html .="</table>
	<input type=\"hidden\" name=\"nospam\" />
	<input type=\"hidden\" name=\"web2lead-submit\" value=\"1\" />
	<div style=\"text-align:center;\">
		<p class=\"submit\">
			<input type=\"submit\" value=\"submit\" />
		</p>
	</div>
</form>";
}
	return $html;
}

function ValidateEmail($email) {
		// First, we check that there's one @ symbol, 
		// and that the lengths are right.
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
			// Email invalid because wrong number of characters 
			// in one section or wrong number of @ symbols.
			return false;
		}
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
			?'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
			$local_array[$i])) {
				return false;
			}
		}
		// Check if domain is IP. If not, 
		// it should be valid domain name
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if
				(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
				?([A-Za-z0-9]+))$",
				$domain_array[$i])) {
					return false;
				}
			}
		}
		return true;
	}

?>