<?php

/*

Template Name: Checkout

*/

?>

<?php session_start(); ?>



<?php get_header(); ?>

<article class="row">

	<aside class="three columns offset-by-one">

		<?php get_sidebar(); ?>

	</aside>

	<section class="eight columns">

		<h1>Checkout</h1>

		<div class="row">

		<?php the_content(); ?>        

		<?php if($_SESSION['cart']) : ?>    

			<fieldset class="ten columns">    		          	

			<table width="100%" border="0" cellspacing="0" cellpadding="0">

		        <tr>

		            <th scope="col" id="resource">Product</th>

		            <th scope="col" id="quantity">Quantity</th>

			    <th scope="col" id="listprice">List price</th>

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

							$tax = $taxrate * $total;

							$cost = number_format($total+$tax+$shipping,2); ?>

						<tr>

							<td>

								<?php echo $row->post_title; ?>

				            		</td>

							<td>

								<?php echo $qty; ?>

				           		</td>

							<td>

								<strike><?php echo $MSRP; ?></strike>

							</td>

							<td>

								$<?php echo number_format($line_cost, 2); ?>

							</td>

						</tr>  

                        		<?php
						//$total += get_post_meta($product, '_cmb_resource_price', true) * $qty;
						?>

					<?php endforeach; ?>    	

  					<tr>

    						<td colspan="2">&nbsp;</td>

    						<td><strong>Subtotal:</strong></td>

    						<td><strong>$<?php echo number_format($total, 2); ?></strong></td>

  					</tr>

  					<tr class="calculate">

    						<td colspan="2">&nbsp;</td>

    						<td>Tax<sup>*</sup>:</td>

    						<td>$<?php echo number_format($tax, 2); ?></td>

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

				<?php $last = get_posts("post_type=purchases&numberposts=1");

					$lastid = $last[0]->ID;

					$invoice = get_post_meta($lastid, '_cmb_order_invoice', true);

					$date = date('Y');

					$invoicenumber = preg_replace("/20.*?-/","",$invoice)+1; 

					$newinvoice = $date."-".$invoicenumber;

				?>

                           	<input type="hidden" name="RefID" value="<?php echo $newinvoice; ?>">

                           	<input type="hidden" name="Amount" value="<?php echo $cost; ?>">

                           	<input type="hidden" name="Tax" value="<?php echo $tax; ?>">

                           	<input type="hidden" name="Paytype" value="online">

				<hr />

				<h4>Shipping information:</h4>

				<label for="billingFName">First Name:</label>

				<input type="text" name="billingFName" id="billingFName" />

				<label for="billingLName">Last Name:</label>

				<input type="text" name="billingLName" id="billingLName" />

				<label for="billingSchool">School or Organization:</label>

				<input type="text" name="billingSchool" id="billingSchool" />

				<label for="AVSADDR">Address:</label>

				<input type="text" name="AVSADDR" id="AVSADDR" />

				<label for="billingCity">City:</label>

				<input type="text" name="billingCity" id="billingCity" />

				<div class="row">

					<div class="four columns">

						<label for="billingState">State:</label>

						<select name="billingState" id="billingState">

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

						<label for="AVSZIP">ZIP:</label>

						<input type="text" name="AVSZIP" id="AVSZIP" />

					</div>

				</div>

				<label for="billingPhone">Phone:</label>

				<input type="text" name="billingPhone" id="billingPhone" />

				<label for="billingEmail">Email:</label>

				<input type="text" name="billingEmail" id="billingEmail" /><br />

				<input name="Purchase" type="submit" value="Place Order"/>

                           </form>

			</fieldset>

		<?php else : ?>        

			<p>Order error, please try again.</p>            

		<?php endif; ?>

		</div>

	</section>

</article>

<?php get_footer(); ?>