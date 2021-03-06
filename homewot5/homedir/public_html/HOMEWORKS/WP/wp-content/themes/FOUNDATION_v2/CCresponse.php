<?php
/*
Template Name: CC Confirmation
*/
?>
<?php session_start(); ?>

<?php get_header(); ?>
<article class="row">
	<aside class="three columns offset-by-one">
		<?php get_sidebar(); ?>
	</aside>
	<section id="bookdetail" class="eight columns">
		<div class="row">
        	<?php $approval = $_REQUEST["Auth"];
		if ($approval != "Declined") { 
			//$emailTo = get_option('customerservice');
			$emailTo = 'jacopley45@gmail.com';
			$subject = 'Online order placed';
			$body = "An order was just placed online by a Homeworks for Books customer. Please check your invoice list for order details.";
			$headers = 'From: Homeworks for Books <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $emailTo;
			mail($emailTo, $subject, $body, $headers); ?>

			<h1>Order completed</h1>
			<h4>Thank you for choosing Home Works. We hope you enjoy your purchase!</h4>
			<p>Once your payment has been approved, we will ship your order to the address you provided. If other arrangements need to be made, please <a href="<?php bloginfo( 'url' ); ?>/contact-us" title="Contact Us">contact us</a>.</p>
			<p><strong>Your invoice number:</strong> <?php echo $_REQUEST['RefNo']; ?></p>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
		        <tr>
		            <th scope="col" id="resource">Product</th>
		            <th scope="col" id="quantity">Quantity</th>
		            <th scope="col" id="price">Price</th>
		        </tr>
					<?php foreach($_SESSION['cart'] as $product => $qty) : ?>
						<?php $row = get_post($product); ?>
						<?php $type = get_post_type($product); ?>
						<?php if ($type == 'classes') {	
							$cprice = get_post_meta($product,'_cmb_class_price', true);
							$camount = str_replace("$","",$cprice); 
							$cline_cost = $camount * $qty; 
							$ctotal = number_format($ctotal + $cline_cost,2); 
							$ccost = number_format($ctotal,2); 
						} else {
							$MSRP = get_post_meta($product,'_cmb_resource_MSRP', true);
							$pprice = get_post_meta($product,'_cmb_resource_price', true);
							$pamount = str_replace("$","",$pprice); 
							$pline_cost = $pamount * $qty; 
							$ptotal = number_format($ptotal + $pline_cost,2); 
							$taxrate = get_option('taxrate') / 100;
							$tax = number_format($taxrate*$ptotal,2);
							$shipping = get_option('flatrate');
							$pcost = number_format($ptotal+$tax+$shipping,2); 
						}; ?>
						<?php if ($type == 'classes') {	
							$maxqty = get_post_meta($product,'_cmb_class_spots', true);
							if ($maxqty) {
								if ($qty > $maxqty) {
									$qty = $maxqty;
								};
							};
						} else {
							$maxqty = get_post_meta($product,'_cmb_resource_quantity', true);
							if ($qty > $maxqty) {
								$qty = $maxqty;
							};
						};
						$total = number_format($ptotal + $ctotal,2); 
						$cost = number_format($pcost+$ccost,2); ?>
						<tr>
							<td>
								<?php echo $row->post_title; ?>
				            		</td>
							<td>
								<?php echo $qty; ?>
				           		</td>
							<td>
								<?php if ($type == 'classes') { ?>
									$<?php echo number_format($cline_cost, 2); ?>
								<?php } else { ?>
									$<?php echo number_format($pline_cost, 2); ?>
								<?php  }; ?>
							</td>
						</tr>  
						<?php $oldpurchase = $row->post_title.PHP_EOL."Quantity: ".$qty.PHP_EOL; ?>
					<?php endforeach; ?>    	
  					<tr>
    						<td>&nbsp;</td>
    						<td align="right"><strong>Subtotal:</strong>&nbsp;&nbsp;</td>
    						<td><strong>$<?php echo number_format($total, 2); ?></strong></td>
  					</tr>
  					<tr class="calculate">
    						<td>&nbsp;</td>
    						<td align="right">Tax<sup>*</sup>:&nbsp;&nbsp;</td>
    						<td>$<?php echo $tax; ?></td>
  					</tr>
  					<tr class="calculate">
    						<td>&nbsp;</td>
    						<td align="right">Shipping:&nbsp;&nbsp;</td>
    						<td>$<?php echo get_option('flatrate'); ?></td>
  					</tr>
  					<tr class="calculate">
    						<td>&nbsp;</td>
    						<td align="right"><strong>TOTAL:</strong>&nbsp;&nbsp;</td>
    						<td><strong>$<?php echo $cost; ?></strong></td>
  					</tr>
			</table>
			<?php global $post;
			$invoice = $_REQUEST['RefNo'];
			$transfirst = $_REQUEST['TransID'];
			$customer = $_SESSION['checkout']['shippingName'];
			$school = $_SESSION['checkout']['shippingSchool'];
			$customeremail = $_SESSION['checkout']['shippingEmail'];
			$address = $_SESSION['checkout']['shippingAddress'].PHP_EOL.$_SESSION['checkout']['shippingCity'].", ".$_SESSION['checkout']['shippingState']." ".$_SESSION['checkout']['shippingZIP'].PHP_EOL.PHP_EOL."Phone: ".$_SESSION['checkout']['shippingPhone'];
			foreach($_SESSION['cart'] as $product => $qty) {
				$row = get_post($product);
				$item = $row->post_title;
				$inventory = " (".$qty.")";
				$summary .= $item.$inventory.PHP_EOL;
			};
			$cost = "$".number_format(($_SESSION['checkout']['Amount'] - $_SESSION['checkout']['TaxAmount']),2);
			if ($_SESSION['checkout']['shippingState'] == "KS") { 
				$ordertax = "$".$_SESSION['checkout']['TaxAmount'];
			} else { 
				$ordertax = "$0.00";
			};
			$paytype = $_REQUEST['USER1'];
			$order = array(
			  'post_title'    => $customer,
			  'post_status'   => 'publish',
			  'post_author'   => 1,
			  'post_category' => array(1),
			  'post_type'	=> purchases
			);
			$postid = wp_insert_post( $order, $wp_error );
			update_post_meta($postid, '_cmb_order_invoice', $invoice); 
			update_post_meta($postid, '_cmb_transfirst', $transfirst); 
			update_post_meta($postid, '_cmb_customer_address', $address); 
			update_post_meta($postid, '_cmb_customer_email', $customeremail); 
			update_post_meta($postid, '_cmb_customer_organization', $school); 
			update_post_meta($postid, '_cmb_order_summary', $summary); 
			update_post_meta($postid, '_cmb_purchase_price', $cost); 
			update_post_meta($postid, '_cmb_purchase_tax', $ordertax); 
			update_post_meta($postid, '_cmb_payment_type', $paytype); 

			if ($_SESSION['checkout']['shippingSchool']) {
				$client = array(
				  'post_title'    => $school,
				  'post_status'   => 'publish',
			 	 'post_author'   => 1,
				  'post_category' => array(1),
			 	 'post_type'	=> schools
				);
				$schoolid = wp_insert_post( $client, $wp_error );
				update_post_meta($schoolid, '_cmb_school_contact', $customer); 
				update_post_meta($schoolid, '_cmb_school_email', $customeremail); 
	  		} else { 

  			};
			foreach($_SESSION['cart'] as $product => $qty) {
 				for ($i=$qty; $i>0; $i--) {
					$row = get_post($product);
					$oldstock = get_post_meta($product,'_cmb_resource_quantity', true);
					$newstock = $oldstock - 1;
					update_post_meta($product, '_cmb_resource_quantity', $newstock); 
					if ($newstock <= 0){
						update_post_meta($product, '_cmb_resource_available', 'Inactive'); 
					};
					$stat = get_post_meta($product, '_cmb_resource_sold', true);
					$sale = $stat + 1;
					update_post_meta($product, '_cmb_resource_sold', $sale); 
				}
			};
			session_destroy(); ?> 
	  	<?php } else { ?>
			<?php $cw2 = $_REQUEST["CVV2ResponseMsg"];
			$notes = $_REQUEST["Notes"];
			if ($cw2 == "M") { ?>
				<h1>Purchase Error</h1>
				<h4>There was an error processing your order. </h4>
			<?php } elseif ($cw2 == "N") { ?>
				<h1>Card Error</h1>
				<h4>Credit card information does not match.</h4>
			<?php } else { ?>
				<h1>Processing Error</h1>
				<h4>Your card holder could not be identified.</h4>
			<?php } ?>
			<p><strong><?php echo $notes; ?></strong> <br />If you have questions or comments, please <a href="<?php bloginfo( 'url' ); ?>/contact-us" title="Contact Us">contact us</a>.</p>
  		<?php } ?>
		</div>
	</section>
</article>
<?php get_footer(); ?>