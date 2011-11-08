<h2>Contact Us</h2>
<?php
	$submit = $_POST["web2lead-submit"];
	if($submit == 1) {
		//	import lead
		require_once('sugarlead.php');
		
		$sugar = new SugarLead();
		if($sugar->CreateLead()) {
			echo '<p>Thank you for your interest!</p>';
		}
	}
	else {
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
				
				echo "<tr><th>".$field_display."</th><td>";
				if($field_type=="textbox") {
					echo "<input type='$field_type' id='$field_name' name='$field_name' size='$field_size' />";
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