<?php

/*

Template Name: Test

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

		<?php $approval = "Approved";

		if ($approval != "Declined") { ?>

			<h1>Order completed</h1>

			<h4>Thank you for choosing Home Works. We hope you enjoy your purchase!</h4>

			<p>Once your payment has been approved, we will ship your order to the address you provided. If other arrangements need to be made, please <a href="<?php bloginfo( 'url' ); ?>/contact-us" title="Contact Us">contact us</a>.</p>

			<p><strong>Your invoice number:</strong> <?php echo $_SESSION['shipping']['RefID']; ?><br />

<strong>Your transaction/ticket ID:</strong> <?php echo $_REQUEST["TransID"]; ?></p>

			<table width="100%" border="0" cellspacing="0" cellpadding="0">

		        <tr>

		            <th scope="col" id="resource">Product</th>

		            <th scope="col" id="quantity">Quantity</th>

		            <th scope="col" id="price">Price</th>

		        </tr>

					<?php foreach($_SESSION['cart'] as $product => $qty) : ?>

						<?php $row = get_post($product); ?>	

						<?php $price = get_post_meta($product,'_cmb_resource_price', true);

							$MSRP = get_post_meta($product,'_cmb_resource_MSRP', true);

							$taxrate = get_option('taxrate') / 100;

							$shipping = get_option('flatrate');

	

							$amount = str_replace("$","",$price); 

							$line_cost = $amount * $qty; 

							$total = $total + $line_cost; 

							$tax = number_format($taxrate*$total,2);

							$cost = number_format($total+$tax+$shipping,2); ?>

						<tr>

							<td>

								<?php echo $row->post_title; ?>

				            		</td>

							<td>

								<?php echo $qty; ?>

				           		</td>

							<td>

								$<?php echo number_format($line_cost, 2); ?>

							</td>

						</tr>  

                        		<?php $total += get_post_meta($product, '_cmb_resource_price', true) * $qty; ?>

    

					<?php $oldpurchase = $row->post_title.PHP_EOL."Quantity: ".$qty.PHP_EOL; ?>

					<?php endforeach; ?>    	

  					<tr>

    						<td>&nbsp;</td>

    						<td><strong>Subtotal:</strong></td>

    						<td><strong>$<?php echo number_format($total, 2); ?></strong></td>

  					</tr>

  					<tr class="calculate">

    						<td>&nbsp;</td>

    						<td>Tax<sup>*</sup>:</td>

    						<td>$<?php echo $tax; ?></td>

  					</tr>

  					<tr class="calculate">

    						<td>&nbsp;</td>

    						<td>Shipping:</td>

    						<td><?php echo get_option('flatrate'); ?></td>

  					</tr>

  					<tr class="calculate">

    						<td>&nbsp;</td>

    						<td><strong>TOTAL:</strong></td>

    						<td><strong>$<?php echo $cost; ?></strong></td>

  					</tr>

			</table>



			<?php global $post;

			$invoice = $_SESSION['shipping']['RefID'];

			$customer = $_SESSION['shipping']['billingLName'].", ".$_SESSION['shipping']['billingFName'];

			$address = $_SESSION['shipping']['AVSADDR'].PHP_EOL.$_SESSION['shipping']['billingCity'].", ".$_SESSION['shipping']['billingState']." ".$_SESSION['shipping']['AVSZIP'].PHP_EOL.PHP_EOL."Phone: ".$_SESSION['shipping']['billingPhone'].PHP_EOL."Email: ".$_SESSION['shipping']['billingEmail'];

			foreach($_SESSION['cart'] as $product => $qty) {

				$row = get_post($product);

				$item = $row->post_title;

				$inventory = " (".$qty.")";

				$summary .= $item.$inventory.PHP_EOL;

			};

			$cost = "$".$_SESSION['shipping']['Amount'];

			$order = array(

			  'post_title'    => $customer,

			  'post_status'   => 'publish',

			  'post_author'   => 1,

			  'post_category' => array(1),

			  'post_type'	=> purchases

			);

			$postid = wp_insert_post( $order, $wp_error );

			update_post_meta($postid, '_cmb_order_invoice', $invoice); 

			update_post_meta($postid, '_cmb_customer_address', $address); 

			update_post_meta($postid, '_cmb_order_summary', $summary); 

			update_post_meta($postid, '_cmb_purchase_price', $cost); 



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

			session_destroy(); ?> 

  		<?php } else { ?>

			<?php $cw2 = $_REQUEST["CW2ResponseMsg"];

			if ($cw2 == "M") { ?>

				<h1>Purchase Error</h1>

				<h4>There was an error processing your order. </h4>

			<?php } elseif ($cw2 == "N") { ?>

				<h1>Card Error</h1>

				<h4>Credit card information does not match.</h4>

			<?php } else { ?>

				<h1>Processing Error</h1>

				<h4>Your credit card could not be identified.</h4>

			<?php } ?>

			<p><?php echo $_REQUEST["Notes"]; ?> <br />If you have questions or comments, please <a href="<?php bloginfo( 'url' ); ?>/contact-us" title="Contact Us">contact us</a>.</p>

			<p><strong>Your invoice number:</strong> <?php echo $_SESSION['shipping']['RefID']; ?><br />

<strong>Your transaction/ticket ID:</strong> <?php echo $_REQUEST["TransID"]; ?></p>

  		<?php } ?>

		</div>

	</section>

</article>

<?php get_footer(); ?>