<?php

$last = get_posts("post_type=purchases&numberposts=1");

$lastid = $last[0]->ID;

$invoice = get_post_meta($lastid, '_cmb_order_invoice', true);

$date = date('Y');

$invoicenumber = preg_replace("/20.*?-/","",$invoice)+1; 

$newinvoice = $date."-".$invoicenumber;



$refund = number_format(($_REQUEST['payment'] - ($_REQUEST['cost'] + $_REQUEST['tax'])),2); 



if (($_REQUEST['action'] == "process") OR ($_REQUEST['Auth'])) {

	if ($_REQUEST['Auth'] == "Declined") : 

		$cw2 = $_REQUEST["CVV2ResponseMsg"];

		$notes = $_REQUEST["Notes"];

		if ($cw2 == "M") { ?>

			<h1>Purchase Error</h1>

			<h4>There was an error processing the order. </h4>

		<?php } else if ($cw2 == "N") { ?>

			<h1>Card Error</h1>

			<h4>Credit card information does not match.</h4>

		<?php } else { ?>

			<h1>Processing Error</h1>

			<h4>Your card could not be identified.</h4>

		<?php }

	else :  

   		global $post;

		$invoice = $_REQUEST['RefNo'];

		$transid = $_REQUEST['TransID'];

		$customer = $_REQUEST['customer'].$_SESSION['customer'];

		if ($customer == "") { 

			$customer = "Name unavailable";		

		};

		$email = $_REQUEST['email'].$_SESSION['email'];

		$address = "No shipping address available (conference sale)";

	

		foreach($_SESSION['cart'] as $product => $qty) {

			$row = get_post($product);

			$item = $row->post_title;

			$inventory = " (".$qty.")";

			$summary .= $item.$inventory.PHP_EOL;

		};

		$purchaseprice = "$".$order;

		$ordertax = "$".$tax;

		$order = array(

			'post_title'    => $customer,

			'post_status'   => 'publish',

			'post_author'   => 1,

			'post_category' => array(1),

			'post_type'	=> purchases

		);

		$postid = wp_insert_post( $order, $wp_error );

		update_post_meta($postid, '_cmb_order_invoice', $invoice); 

		update_post_meta($postid, '_cmb_transfirst', $transid); 

		update_post_meta($postid, '_cmb_customer_address', $address); 

		update_post_meta($postid, '_cmb_customer_email', $email); 

		update_post_meta($postid, '_cmb_customer_organization', $school);

		update_post_meta($postid, '_cmb_order_summary', $summary); 

		update_post_meta($postid, '_cmb_purchase_price', $purchaseprice); 

		update_post_meta($postid, '_cmb_purchase_tax', $ordertax); 



		foreach($_SESSION['cart'] as $product => $qty) {

		 	for ($i=$qty; $i>0; $i--) {

				$row = get_post($product);

				$oldstock = get_post_meta($product,'_cmb_resource_quantity', true);

				$newstock = $oldstock - 1;

				update_post_meta($product, '_cmb_resource_quantity', $newstock); 

	

				$stat = get_post_meta($product, '_cmb_resource_sold', true);

				$sale = $stat + 1;

				update_post_meta($product, '_cmb_resource_sold', $sale); 

			}

		};



		if ($_REQUEST['paytype'] == "cash") { 

			update_post_meta($postid, '_cmb_payment_type', 'cash');

			update_post_meta($postid, '_cmb_customer_payment', 'Yes'); ?>

			<h4>Refund: $<?php echo $refund; ?></h4>

		<?php };

		if ($_REQUEST['paytype'] == "check") { 

			update_post_meta($postid, '_cmb_payment_type', 'check');

			update_post_meta($postid, '_cmb_customer_payment', 'Yes'); ?>

			<h4>Check payment received.</h4>

		<?php };

		if ($_REQUEST['USER1'] == "credit") { 

			update_post_meta($postid, '_cmb_payment_type', 'credit'); ?>

			<h4>Payment received at TransFirst/Transaction Central</h4>

		<?php }; ?>

		<div style="display: none;">

		<div id="toPrint">

			<p style="text-align: center;"><strong>Home Works for Books</strong><br />

			<em>Your homeschool connection for discounted new and used homeschool materials!</em></p>

			<p style="text-align: center;"><strong>Phone:</strong> <?php echo get_option('invoicephone'); ?><br /><?php echo get_option('invoiceaddress'); ?><br /><?php echo get_option('invoiceURL'); ?><br />Come visit us online at <?php echo get_option('invoiceURL'); ?></p>

			<p>&nbsp;</p>

			<table width="100%" border="0" cellspacing="0" cellpadding="0">

				<tr>

					<td colspan="3" align="left" valign="top"><?php echo date("Y/m/d H:i:s"); ?></td>

				</tr>

				<tr>

					<td colspan="3" align="left" valign="top">

						<?php global $current_user;

						get_currentuserinfo(); ?>

						Cashier: <?php echo $current_user->user_firstname; ?>

					</td>

				</tr>

				<tr>

					<td colspan="3" align="left" valign="middle"><hr /></td>

				</tr>

				<?php foreach($_SESSION['cart'] as $product => $qty) : 

					$row = get_post($product); 	

					$price = get_post_meta($product,'_cmb_resource_price', true);	

					$amount = str_replace("$","",$price); 

					$line_cost = $amount * $qty; 

                			$total += get_post_meta($product, '_cmb_resource_price', true) * $qty;

					$taxpercent = $_SESSION['rate']['taxrate'] / 100;

					$tax = number_format($taxpercent*$total,2);

					$credit = number_format($_SESSION['discount']['credit'],2);

					$order = number_format($total-$credit,2);

					$cost = number_format($total+$tax-$credit,2); ?>

					<tr>

						<td colspan="2" align="left" valign="top">(<?php echo $qty; ?>) <?php echo $row->post_title; ?></td>

						<td width="15%" valign="top">$<?php echo number_format($line_cost, 2); ?></td>

					</tr>  

				<?php endforeach; ?>  

				<tr>

					<td colspan="3" align="left" valign="middle"><hr /></td>

				</tr> 

	  			<tr>

					<td valign="top">&nbsp;</td>

	    				<td width="10%" align="right" valign="top"><strong>Subtotal:</strong>&nbsp;&nbsp;</td>

	    				<td valign="top"><strong>$<?php echo number_format($total, 2); ?></strong></td>

	  			</tr>

	  			<tr>

					<td valign="top">&nbsp;</td>

	    				<td width="10%" align="right" valign="top">Credit:&nbsp;&nbsp;</td>

	    				<td valign="top">$<?php echo number_format($credit, 2); ?></td>

	  			</tr>

	  			<tr>

					<td valign="top">&nbsp;</td>

	    				<td width="10%" align="right" valign="top">Tax:&nbsp;&nbsp;</td>

	    				<td valign="top">$<?php echo $tax; ?></td>

	  			</tr>

	  			<tr>

					<td valign="top">&nbsp;</td>

	    				<td width="10%" align="right" valign="top"><hr /></td>

	    				<td valign="top"><hr /></td>

				</tr>

	  			<tr>

					<td valign="top">&nbsp;</td>

	    				<td width="10%" align="right" valign="top"><strong>TOTAL:</strong>&nbsp;&nbsp;</td>

	    				<td valign="top"><strong>$<?php echo $cost; ?></strong></td>

				</tr>

					<?php $paytype = $_REQUEST['paytype'];

						if ($paytype=="credit") {

  					} else { ?>

	  			<tr>

					<td valign="top">&nbsp;</td>

	    				<td width="10%" align="right" valign="top"><span style="text-transform: uppercase;"><?php echo $paytype; ?>:</span>&nbsp;&nbsp;</td>

	    				<td valign="top">$<?php echo $_REQUEST['payment']; ?></td>

				</tr>

					<?php } ?> 

	  			<tr>

					<td valign="top">&nbsp;</td>

	    				<td width="10%" align="right" valign="top">Change:&nbsp;&nbsp;</td>

	    				<td valign="top">$<?php echo $refund; ?></td>

				</tr>

			</table>

			<p style="text-align: center;"><?php echo get_option('invoicepromo'); ?></p>

			<p style="text-align: center;"><strong>Invoice:</strong> #<?php echo $invoice; ?><br />Customer Copy</p>

		</div>

		</div>

		<table width="100%" border="0" cellspacing="0" cellpadding="0">

			<tr>

				<td align="left" valign="top"><input type="button" class="button-primary" onClick="printContent('toPrint');" value="Print invoice">&nbsp;&nbsp;&nbsp;</td>

				<td align="left" valign="top">

					<form id="clear" action="" method="post">

	                		   	<input type="hidden" name="product_id" value="null" />

	                			<input type="hidden" name="action" value="empty" />

	                    			<input type="submit" class="button-primary" value="New order" />

	              	    		</form>

				</td>

			</tr>

		</table>

	<?php endif; ?>

<?php } else { ?>

	<script type="text/javascript">

	jQuery(document).ready(function() {

	    jQuery("input[value$='credit']").click(function() {

	        jQuery(".desc").hide();

	        jQuery("#credit").show();

	    });

	    jQuery("input[value$='check']").click(function() {

	        jQuery(".desc").hide();

	        jQuery("#check").show();

	    });

	    jQuery("input[value$='cash']").click(function() {

	        jQuery(".desc").hide();

	        jQuery("#cash").show();

	    });

	});

	</script>

	<script type="text/javascript">

        	function setFromCCS() {

    			document.getElementById("swipe").blur(function (e) {e.preventDefault();});

			var ccs = document.getElementById("swipe").value;

                	var index1 = ccs.indexOf("%B") + 2;

                	var index2 = ccs.indexOf("^") + 1;

                	var index3 = ccs.indexOf("^", index2 + 1) + 1;

                

                	var cardNumber = ccs.substring(index1, index2 - 1);

                	var expMonth = ccs.substr(index3, 2);

                	var expYear = ccs.substr(index3 + 2, 2);

                	var holderName = ccs.substring(index2, index3 - 1);

                	var index4=holderName.indexOf("/");

			var temp1=holderName.substring(0,index4); 

			var temp2=holderName.substring(index4+1);

			holderName=temp2+' '+temp1;



			document.getElementById("swipe").style.display="none";

			document.getElementById("ch").value=holderName;

			document.getElementById("cn").value=cardNumber;

			document.getElementById("cm").value=expMonth;

			document.getElementById("cy").value=expYear;

        	};

	</script> 



	<table width="100%" border="0" cellspacing="0" cellpadding="0">

		<tr>

			<td valign="top">

			<?php if($_REQUEST['cn']) : 

				$_SESSION['email'] = $_REQUEST["email"];

				$_SESSION['customer'] = $_REQUEST["ch"]; ?>

				<h4 style="font-size: 14px;">Verify customer information</h4>

				<p><strong>Name:</strong> <?php echo $_REQUEST['ch']; ?><br />

				<strong>Contact:</strong> <?php echo $_REQUEST['email']; ?><br />

				<strong>Billing address:</strong> <?php echo $_REQUEST['AVSADDR']; ?><br />

				<?php echo $_REQUEST['AVSCITY']; ?>, <?php echo $_REQUEST['AVSSTATE']; ?> <?php echo $_REQUEST['AVSZIP']; ?></p>

				<form id="TRANSFIRST" method="POST" action="https://webservices.primerchants.com/billing/TransactionCentral/processCC.asp?" name="frmReturn" id="frmReturn">

                			<input type="hidden" name="MerchantID" id="<?php echo get_option('merchantid'); ?>" value="100846">

					<input type="hidden" name="RegKey" id="<?php echo get_option('regkey'); ?>" value="5QJ6J3H3YSYZAAZA">

            				<input type="hidden" name="CCRURL" value="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=conference-sales&">

                        		<input type="hidden" name="ConfirmPage" value="Y">

                       		 	<input type="hidden" name="RefID" value="<?php echo $newinvoice; ?>">

                        		<input type="hidden" name="Amount" value="<?php echo $cost; ?>">

                        		<input type="hidden" name="TaxAmount" value="<?php echo $tax; ?>">

                        		<input type="hidden" name="TaxIndicator" value="1">

                        		<input type="hidden" name="NameonAccount" value="<?php echo $_REQUEST['ch']; ?>">

                        		<input type="hidden" name="AccountNo" value="<?php echo $_REQUEST['cn']; ?>">

                        		<input type="hidden" name="CCMonth" value="<?php echo $_REQUEST['cy']; ?>">

                        		<input type="hidden" name="CCYear" value="<?php echo $_REQUEST['cm']; ?>">

                        		<input type="hidden" name="CVV2" value="<?php echo $_REQUEST['CVV2']; ?>">

					<input type="hidden" name="AVSADDR" value="<?php echo $_REQUEST['AVSADDR']; ?>">

					<input type="hidden" name="AVSZIP" value="<?php echo $_REQUEST['AVSZIP']; ?>">

					<input type="hidden" name="ShipToZipCode" value="<?php echo $_REQUEST['AVSZIP']; ?>">

                        		<input type="hidden" name="USER1" value="credit">

					<input class="button-primary" type="submit" value="Process Credit Payment"/>

				</form>

			<?php else : ?>        

				<div id="cash" class="desc" style="display: none;">

					<form method="POST" action="" name="cashform">

					<table id="formtable" width="100%" border="0" cellspacing="0" cellpadding="0">

						<tr>

							<td align="right">

								<input type="hidden" name="product_id" value="null" />

								<input type="hidden" name="paytype" value="cash" />

								<label for="customer">Customer name:</label>&nbsp;

							</td>

                        				<td><input type="text" name="customer"></td>

						</tr>

						<tr>

							<td align="right"><label for="email">Email address:</label>&nbsp;</td>

                        				<td><input type="text" name="email"></td>

						</tr>

						<tr>

							<td align="right">

								<input type="hidden" name="action" value="process" />

								<input type="hidden" name="cost" value="<?php echo $order; ?>" />

								<input type="hidden" name="tax" value="<?php echo $tax; ?>" />

								<input type="hidden" name="RefNo" value="<?php echo $newinvoice; ?>" />

								Amount:&nbsp;$&nbsp;

							</td>

							<td><input type="text" name="payment" /></td>

						</tr>

						<tr>

							<td>&nbsp;</td>

							<td><input class="button-primary" type="submit" value="Cash Payment"/></td>

						</tr>

					</table>

					</form>

				</div>

				<div id="check" class="desc" style="display: none;">

					<form method="POST" action="" name="checkform">

					<table id="formtable" width="100%" border="0" cellspacing="0" cellpadding="0">

						<tr>

							<td align="right">

								<input type="hidden" name="product_id" value="null" />

								<input type="hidden" name="paytype" value="check" />

								<label for="customer">Name on check:</label>&nbsp;

							</td>

                        				<td><input type="text" name="customer"></td>

						</tr>

						<tr>

							<td align="right"><label for="email">Email address:</label>&nbsp;</td>

                        				<td>

								<input type="text" name="email">

								<input type="hidden" name="action" value="process" />

								<input type="hidden" name="cost" value="<?php echo $order; ?>" />

								<input type="hidden" name="tax" value="<?php echo $tax; ?>" />

								<input type="hidden" name="payment" value="<?php echo $cost; ?>" />

								<input type="hidden" name="RefNo" value="<?php echo $newinvoice; ?>" />

							</td>

						</tr>

						<tr>

							<td>&nbsp;</td>

        						<td><input class="button-primary" type="submit" value="Check Payment"/>

							</td>

						</tr>

					</table>

					</form>

				</div>

				<div id="credit" class="desc" style="display: none; margin-left: 25px;">

					<table id="formtable" width="100%" border="0" cellspacing="0" cellpadding="0">

						<tr>

							<td>&nbsp;</td>

							<td><input id="swipe" type="text" value="" placeholder="Swipe card &hellip;"/></td>

						<tr>

							<td align="right">

								<form id="TRANSFIRST" method="POST" action="" name="creditform">

								<input type="hidden" name="TrackData" id="TrackData"/>

								<label for="ch">Name on card:</label>&nbsp;

							</td>

                        				<td><input type="text" name="ch" id="ch" onclick="setFromCCS()"></td>

						</tr>

						<tr>

							<td align="right"><label for="email">Email address:</label>&nbsp;</td>

                        				<td><input type="text" name="email"></td>

						</tr>

						<tr>

							<td align="right"><label for="cn">Credit card #:</label>&nbsp;</td>

                        				<td><input type="text" name="cn" id="cn"></td>

						</tr>

						<tr>

							<td align="right"><label for="cm">Expires:</label>&nbsp;</td>

                        				<td><input type="text" name="cy" id="cy" maxlength="2" size="2"  placeholder="<?php echo date('m'); ?>">/<input type="text" name="cm" id="cm" maxlength="2" size="2" placeholder="<?php echo substr(date('Y'),-2); ?>"></td>

						</tr>

						<tr>

							<td align="right"><label for="CVV2">Verification #:</label>&nbsp;</td>

                        				<td><input type="text" name="CVV2" maxlength="4" size="4"></td>

						</tr>

						<tr>

							<td align="right"><label for="AVSADDR">Address:</label>&nbsp;&nbsp;</td>

							<td><input type="text" name="AVSADDR"></td>

						</tr>

						<tr>

							<td align="right"><label for="AVSCITY">City:</label>&nbsp;</td>

							<td><input type="text" name="AVSCITY"></td>

						</tr>

						<tr>

							<td align="right"><label for="AVSSTATE">State:</label>&nbsp;</td>

							<td><select name="AVSSTATE">

								<option value="" selected="selected"></option>

								<option value="AL">AL</option>

								<option value="AK">AK</option>

								<option value="AZ">AZ</option>

								<option value="AR">AR</option>

								<option value="CA">CA</option>

								<option value="CO">CO</option>

								<option value="CT">CT</option>

								<option value="DE">DE</option>

								<option value="DC">DC</option>

								<option value="FL">FL</option>

								<option value="GA">GA</option>

								<option value="HI">HI</option>

								<option value="ID">ID</option>

								<option value="IL">IL</option>

								<option value="IN">IN</option>

								<option value="IA">IA</option>

								<option value="KS">KS</option>

								<option value="KY">KY</option>

								<option value="LA">LA</option>

								<option value="ME">ME</option>

								<option value="MD">MD</option>

								<option value="MA">MA</option>

								<option value="MI">MI</option>

								<option value="MN">MN</option>

								<option value="MS">MS</option>

								<option value="MO">MO</option>

								<option value="MT">MT</option>

								<option value="NE">NE</option>

								<option value="NV">NV</option>

								<option value="NH">NH</option>

								<option value="NJ">NJ</option>

								<option value="NM">NM</option>

								<option value="NY">NY</option>

								<option value="NC">NC</option>

								<option value="ND">ND</option>

								<option value="OH">OH</option>

								<option value="OK">OK</option>

								<option value="OR">OR</option>

								<option value="PA">PA</option>

								<option value="RI">RI</option>

								<option value="SC">SC</option>

								<option value="SD">SD</option>

								<option value="TN">TN</option>

								<option value="TX">TX</option>

								<option value="UT">UT</option>

								<option value="VT">VT</option>

								<option value="VA">VA</option>

								<option value="WA">WA</option>

								<option value="WV">WV</option>

								<option value="WI">WI</option>

								<option value="WY">WY</option>

							</select></td>

						</tr>

						<tr>

							<td align="right"><label for="AVSZIP">ZIP:</label>&nbsp;</td>

							<td><input type="text" name="AVSZIP"></td>

						</tr>

						<tr>

							<td>&nbsp;</td><td><input class="button-primary" type="submit" value="Continue"/></td>

						</tr>

					</table>

					</form>

				</div>

			<?php endif; ?>  

			</td>

		</tr>

		<tr>

			<td height="35px" valign="top">

				<h4><strong>Due: $<?php echo $cost; ?></strong></h4>

			</td>

		</tr>

		<tr>

			<td valign="top">

				<p id="paytype"><strong>Payment type:</strong><br />

				<input type="radio" name="paytype" value="cash"> Cash<br>

				<input type="radio" name="paytype" value="check"> Check<br>

				<input type="radio" name="paytype" value="credit"> Credit</p>

			</td>

		</tr>

	</table>

<?php } ?> 