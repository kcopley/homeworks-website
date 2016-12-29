<?php function add_query_vars($TFVars) {
	$TFVars[] = array("Status","RefID");
	return $TFVars;
}
add_filter('query_vars', 'add_query_vars'); 

$success = $_REQUEST['Status'];
if ($success=="F"){ ?>
	<h4>Processing error. <?php echo $_REQUEST['Notes']; ?> <br />Please visit <a href="https://www.oc2net.net/billing/login.asp">Transaction Central</a> or contact customer&rsquo; credit card support.</h4>
<?php } else if ($success=="S"){ ?>
	<?php if ($_REQUEST['action']=="clear"){ ?>
		<p><strong>Order removed.</strong></p>
		<?php global $wpdb;
			$vinvoice = $_REQUEST['RefID'];
			$querystr = "SELECT post_id
				FROM $wpdb->postmeta
				WHERE
				(meta_key = '_cmb_order_invoice' AND meta_value = '$vinvoice')	GROUP BY post_id;
";
			$postid = $wpdb->get_var($wpdb->prepare($querystr));
			wp_delete_post($postid); 
	} else{ ?>
		<p><strong><?php echo $_REQUEST['Notes']; ?></strong><br />
		To completely remove the purchase from Homeworks order history, reenter invoice # and submit. <a href="<?php echo admin_url('edit.php?post_type=purchases','https'); ?>">Click here</a> to simply modify the invoice details instead.</p>
		<form id="voidorder" method="POST" action="" name="voidorder">
			<input type="hidden" name="Status" value="S">
			<input type="hidden" name="action" value="clear">
			<p><label for="RefID">Invoice #:</label><br />
			<input type="text" name="RefID" value="<?php echo $_REQUEST['RefID']; ?>"></p>
			<input class="button-primary" type="submit" value="Remove"/>
		</form>
		<p>&nbsp;</p>
	<?php }; ?> 
<?php } else{ ?>
	<p><strong>To refund, enter the unique TransFirst ticket number, customer invoice number and the FULL order amount.</strong><br />
	If needed, visit <a href="<?php echo admin_url('edit.php?post_type=purchases','https'); ?>">Orders</a> for transaction ticket and/or order total.</p>
<?php } ?> 
	<form method="POST" action="https://webservices.primerchants.com/billing/TransactionCentral/voidcreditcconline.asp?" name="frmReturn" id="frmReturn">
		<table id="formtable" width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="right" width="150px">
					<input type="hidden" name="MerchantID" value="<?php echo get_option('merchantid'); ?>">
					<input type="hidden" name="RegKey" value="<?php echo get_option('regkey'); ?>">
					<label for="TransID">Transaction/ticket ID #:</label>&nbsp;
				</td>
				<td>
					<input type="text" name="TransID">
				</td>
			</tr>
			<tr>
				<td align="right"><label for="RefID">Invoice #:</label>&nbsp;</td>
				<td><input type="text" name="RefID"></td>
			</tr>
			<tr>
				<td align="right"><label for="CreditAmount">Invoice total:&nbsp;$&nbsp;</label></td>
				<td><input type="text" name="CreditAmount"></td>
			</tr>
			<tr>
				<td align="right">
					<input type="hidden" name="CCRURL" value="<?php echo admin_url('admin.php?page=sales_console_refund&Status=S','https'); ?>">
					<label for="IsSuspectedFraud">Reason:</label>&nbsp;
				</td>
				<td>
					<select name="IsSuspectedFraud" id="IsSuspectedFraud">
						<option value="N">Wrong resource purchased</option>
						<option value="N">Dissatisfied with resource</option>
						<option value="Y">Credit fraud</option>
						<option value="N">Other</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input class="button-primary" type="submit" value="Void transaction"/></td>
			</tr>
		</table>
	</form>