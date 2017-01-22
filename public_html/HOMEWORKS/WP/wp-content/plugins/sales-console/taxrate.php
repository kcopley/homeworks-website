<form method="POST" action="" name="rate">

	<table width="100%" border="0" cellspacing="0" cellpadding="0">

		<tr>

			<td align="right"><label>Tax rate:</label>&nbsp;</td>

		<?php if (isset($_REQUEST['taxrate'])) {

			$_SESSION['rate']['taxrate'] = $_REQUEST['taxrate']; ?>

			<td><input type="text" name="taxrate" value="<?php echo $_SESSION['rate']['taxrate']; ?>"></td>

		<?php } else { ?>

			<td><input type="text" name="taxrate"></td>

		<?php } ?>

		</tr>

	</table>

	<input type="submit" name="button" class="button-primary" value="Update">

</form>

