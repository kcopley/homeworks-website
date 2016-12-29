<form method="POST" action="" name="discount">
	<table width="100%">
		<tr>
			<td align="right"><label>Amount:</label>&nbsp;$</td>
		<?php if (isset($_POST['credit'])) {
			$_SESSION['discount']['credit'] = $_POST['credit']; ?>
			<td><input type="text" name="credit" value="<?php echo $_SESSION['discount']['credit']; ?>"></td>
		<?php } else { ?>
			<td><input type="text" name="credit"></td>
		<?php } ?>
		</tr>
	</table>
	<input class="button-primary" name="discount" type="submit" value="Update"/>
</form>