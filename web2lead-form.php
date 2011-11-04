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
		<tr>
			<th scope="row">Name</th>
			<td><input type="text" id="txtName" name="txtName" size="60" value="" /></td>
		</tr>
		<tr>
			<th scope="row">Company</th>
			<td><input type="text" id="txtCompany" name="txtCompany" size="60"/></td>
		</tr>
		<tr>
			<th scope="row">Phone</th>
			<td><input type="text" id="txtPhone" name="txtPhone" size="60"/></td>
		</tr>
		<tr>
			<th scope="row">Email</th>
			<td><input type="text" id="txtEmail" name="txtEmail" size="60"/></td>
		</tr>
		<tr>
			<th scope="row">Comments</th>
			<td><textarea id="txtComments" name="txtComments"></textarea></td>
		</tr>
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