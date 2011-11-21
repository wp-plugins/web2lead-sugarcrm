<h2>Contact Us</h2>
<?php
	$submit = $_POST["web2lead-submit"];
	$is_missing = false;
	if($submit == 1) {
		//	check for required fields
		$fields = unserialize(get_option("web2lead_form_fields"));

		$missing_msg = "";
		foreach ($fields as $field) {
			$field_name = $field["id"];
			$field_display = $field["display"];
			$field_req = $field["required"];
			
			if($field_req == 1 && $_POST[$field_name] == null) {
				//	required field missing
				$is_missing = true;
				$missing_msg .= $field_display."<br />";
			}
		}
		if($is_missing) {
			//	
			echo "The following field(s) are required:<br/>";
			echo $missing_msg;
		}
		else {
			//	import lead
			require_once('sugarlead.php');
			
			$sugar = new SugarLead();
			if($sugar->CreateLead()) {
				echo '<p>Thank you for your interest!</p>';
			}
		}
	}
	if($submit != 1 || ($submit == 1 && $is_missing == true)) {
?>
<form method="post">
	<table class="form-table">
		<?php
			//	get our fields
			$fields = unserialize(get_option("web2lead_form_fields"));
			foreach ($fields as $field) {
				//	each feld is stored as an array
				$field_name = $field["id"];
				$field_type = $field["type"];
				$field_display = $field["display"];
				$field_size = $field["size"];
				
				//	try to retain values if this was an incorrectly submitted form
				$value = $_POST[$field_name];
				echo "<tr><th>".$field_display."</th><td>";
				if($field_type=="textbox") {
					echo "<input type='$field_type' id='$field_name' name='$field_name' size='$field_size' value='$value' />";
				}
				elseif($field_type == "textarea") {
					echo "<textarea id='$field_name' name='$field_name' cols='$field_size'></textarea>";
				}
				echo "</td></tr>";
			}
		?>
	</table>
	<input type="hidden" name="web2lead-submit" value="1" />
	<div style="text-align:center;">
		<p class="submit">
			<input type="submit" value="submit" />
		</p>
	</div>
</form>
<?php }
?>