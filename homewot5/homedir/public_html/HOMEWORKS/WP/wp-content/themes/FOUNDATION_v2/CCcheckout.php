<?php
/*
Template Name: Checkout card 
*/
?>
<?php session_start(); ?>

<?php get_header(); ?>
<section id="ccv" class="reveal-modal">
	<div class="row">
		<div class="six columns">
			<h4>Locating your credit card CCV/CCV2 security code</h4>
			<p><strong>Visa/MasterCard/Discover</strong><br />
Your card security code for your Visa, MasterCard or Discover card is a three-digit number on the back of your credit card, immediately following your main card number.</p>
			<p><strong>American Express</strong><br />
The card security code for your American Express card is a four-digit number located on the front your credit card, to the right or left above your main card number.</p>
			<p><strong>Why do we ask for this?</strong><br />
We ask for this information for your security, as it verifies for us that a credit card is in the physical possession of the person attempting to use it.</p>
		</div>
		<div class="six columns">
			<img src="<?php bloginfo('template_url'); ?>/images/CCV.jpg">
		</div>
	</div>
</section>
<article class="row">
	<aside class="three columns offset-by-one">
		<?php get_sidebar(); ?>
	</aside>
	<section class="eight columns">
	<?php if (is_page(array('library','bookbag','checkout','order','response')) or is_category() or get_post_type($post) == 'bookstore')  { 
		$enabled=get_option('ecommerce');
		if ($enabled=="true") { ?>
		<h1 id="offline">Checkout</h1>
			<p><strong>Ordering online is momentarily unavailable.</strong> We are currently updating our library and/or attending a homeschooling conference right now. We apologize for any inconvenience.</p>
		<?php } else { ?>
			<h1>Checkout</h1>
		<?php } ?>
	<?php } ?>
		<div class="row">
		<?php the_content(); ?>  
		<?php if($_SESSION['cart']) : ?>    
			<fieldset class="twelve columns">    		          	
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
		        <tr>
		            <th scope="col" id="resource">Product</th>
		            <th scope="col" id="quantity" class="checkout_quantity">Quantity</th>
			    <th scope="col" id="listprice">List price</th>
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
							<td class="checkout_quantity">
								<?php echo $qty; ?>
				           		</td>
							<td>
								<?php if ($type == 'classes') { 
									echo $cprice;
								} else { ?>
									<strike><?php echo $MSRP; ?></strike>
								<?php  }; ?>
							</td>
							<td>
								<?php if ($type == 'classes') { ?>
									$<?php echo number_format($cline_cost, 2); ?>
								<?php } else { ?>
									$<?php echo number_format($pline_cost, 2); ?>
								<?php  }; ?>
							</td>
						</tr>  
					<?php endforeach; ?>    	
  					<tr>
    						<td colspan="2">&nbsp;</td>
    						<td><strong>Subtotal:</strong></td>
    						<td><strong>$<?php echo number_format($total, 2); ?></strong></td>
  					</tr>
  					<tr class="calculate">
    						<td colspan="2">&nbsp;</td>
    						<td>Tax<sup>*</sup>:</td>
    						<td>$<?php echo $tax; ?></td>
  					</tr>
  					<tr class="calculate">
    						<td colspan="2">&nbsp;</td>
    						<td>Shipping:</td>
    						<td><?php echo get_option('flatrate'); ?></td>
  					</tr>
  					<tr class="calculate">
    						<td colspan="2">&nbsp;</td>
    						<td><strong>TOTAL:</strong></td>
    						<td><strong>$<?php echo $cost; ?></strong></td>
  					</tr>
			</table>
			<small id="tax-disclaimer"><sup>*</sup><em>Sales tax only applies to Kansas residents</em></small>
			<form id="TRANSFIRST" method="POST" action="<?php bloginfo('url'); ?>/order" name="frmReturn" id="frmReturn">
				<?php $last = get_posts("post_type=purchases&numberposts=1&order=DESC&orderby=ID");
					$lastid = $last[0]->ID;
					$invoice = get_post_meta($lastid, '_cmb_order_invoice', true);
					$date = date('Y');
					$invoicenumber = preg_replace("/20.*?-/","",$invoice)+1; 
					$newinvoice = $date."-".$invoicenumber;
				?>

                           	<input type="hidden" name="RefID" value="<?php echo $newinvoice; ?>">
                           	<input type="hidden" name="Amount" value="<?php echo number_format($cost,2); ?>">
                           	<input type="hidden" name="Total" value="<?php echo number_format($total,2); ?>">
                           	<input type="hidden" name="TaxAmount" value="<?php echo $tax; ?>">
                           	<input type="hidden" name="Paytype" value="online">
				<hr />
				<h4>Shipping information:</h4>
				<label for="shippingName">Name:</label>
				<input type="text" name="shippingName" id="shippingName" value="<?php echo $_SESSION['checkout']['shippingName']; ?>"/>
				<label for="shippingSchool">School or Organization:</label>
				<input type="text" name="shippingSchool" id="shippingSchool"  value="<?php echo $_SESSION['checkout']['shippingSchool']; ?>"/>
				<label for="shippingAddress">Address:</label>
				<input type="text" name="shippingAddress" id="shippingAddress"  value="<?php echo $_SESSION['checkout']['shippingAddress']; ?>"/>
				<label for="shippingCity">City:</label>
				<input type="text" name="shippingCity" id="shippingCity"  value="<?php echo $_SESSION['checkout']['shippingCity']; ?>"/>
				<div class="row">
					<div class="four columns">
						<label for="shippingState">State:</label>
						<select name="shippingState" id="shippingState">
							<?php if($_SESSION['checkout']['shippingName']) : ?>
								<option selected value="<?php echo $_SESSION['checkout']['shippingState']; ?>"><?php echo $_SESSION['checkout']['shippingState']; ?></option>
							<?php endif; ?>
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
						</select>
					</div>
					<div class="four columns end">
						<label for="shippingZIP">ZIP:</label>
						<input type="text" name="shippingZIP" id="shippingZIP" maxlength="10"  value="<?php echo $_SESSION['checkout']['shippingZIP']; ?>"/>
					</div>
				</div>
				<label for="shippingPhone">Phone:</label>
				<input type="text" name="shippingPhone1" id="shippingPhone1" size="3" maxlength="3" value="<?php echo $_SESSION['checkout']['shippingPhone1']; ?>"/><input type="text" name="shippingPhone2" id="shippingPhone2" size="3" maxlength="3" value="<?php echo $_SESSION['checkout']['shippingPhone2']; ?>"/><input type="text" name="shippingPhone3" id="shippingPhone3" size="4" maxlength="4" value="<?php echo $_SESSION['checkout']['shippingPhone3']; ?>"/>
				<label for="shippingEmail">Email:</label>
				<input type="text" name="shippingEmail" id="shippingEmail"  value="<?php echo $_SESSION['checkout']['shippingEmail']; ?>"/><br />
				<hr />
				<h4>Billing information:</h4>
				<label for="billingName">Name on credit card:</label>
				<input type="text" name="billingName" id="billingName" />
				<label for="billingCard">Card number:</label>
				<input type="text" name="billingCard1" class="credit" maxlength="4" /><input type="text" name="billingCard2" class="credit" maxlength="4" /><input type="text" name="billingCard3" class="credit" maxlength="4" /><input type="text" name="billingCard4" class="credit" maxlength="4" />
				<div class="row" style="clear: left;">
					<div class="four columns">
						<label for="billingMonth">Month:</label>
						<select name="billingMonth" id="billingMonth">
							<option value="01">January</option>
							<option value="02">February</option>
							<option value="03">March</option>
							<option value="04">April</option>
							<option value="05">May</option>
							<option value="06">June</option>
							<option value="07">July</option>
							<option value="08">August</option>
							<option value="09">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
						</select>
					</div>
					<div class="three columns">
						<label for="billingYear">Year:</label>
						<select name="billingYear" id="billingYear">
							<option value="<?php echo substr(date('Y'),-2); ?>"><?php echo date('Y'); ?></option>
							<option value="<?php echo substr(date('Y')+1,-2); ?>"><?php echo date('Y')+1; ?></option>
							<option value="<?php echo substr(date('Y')+2,-2); ?>"><?php echo date('Y')+2; ?></option>
							<option value="<?php echo substr(date('Y')+3,-2); ?>"><?php echo date('Y')+3; ?></option>
							<option value="<?php echo substr(date('Y')+4,-2); ?>"><?php echo date('Y')+4; ?></option>
							<option value="<?php echo substr(date('Y')+5,-2); ?>"><?php echo date('Y')+5; ?></option>
							<option value="<?php echo substr(date('Y')+6,-2); ?>"><?php echo date('Y')+6; ?></option>
							<option value="<?php echo substr(date('Y')+7,-2); ?>"><?php echo date('Y')+7; ?></option>
						</select>
					</div>
					<div class="three columns end">
						<label for="billingCCV">CCV: <a href="#" data-reveal-id="ccv">What's this?</a></label>
						<input type="text" name="billingCCV" id="billingCCV" maxlength="4" />
					</div>
				</div>
				<?php if (is_page(array('library','bookbag','checkout','order','response')) or is_category() or get_post_type($post) == 'bookstore')  { 
					$enabled=get_option('ecommerce');
					if ($enabled=="true") { ?>

					<?php } else { ?>
						<input name="Purchase" type="submit" value="Confirm order"/>
					<?php } ?>
				<?php } ?>
                	</form>
			</fieldset>
		<?php else : ?>        
			<p>Order error, please try again.</p>            
		<?php endif; ?>
		</div>
	</section>
</article>
<?php get_footer(); ?>