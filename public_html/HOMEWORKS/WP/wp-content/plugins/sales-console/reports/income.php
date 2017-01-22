<script type="text/javascript">

jQuery(document).ready(function() {

    jQuery('.datepicker').datepicker({

        dateFormat : 'yy-mm-dd'

    });

    jQuery("#conference-button").click(function(){

        jQuery(".online, #online, #income").hide();

        jQuery(".cash, .check, .credit, #conference").show();

    });

    jQuery("#online-button").click(function(){

        jQuery(".cash, .check, .credit, #conference, #income").hide();

        jQuery(".online, #online").show();

    });

});

</script>

<form method="POST" action="" name="salesrange">

	<table id="datepicker" width="60%" border="0" cellspacing="0" cellpadding="0">

		<tr>

			<td><label>Start date:</label><br /><input type="text" class="datepicker" name="start"></td>

			<td><label>End date:</label><br /><input type="text" class="datepicker" name="end"></td>

			<td width="10%" valign="top" align="left"  style="padding: 15px 0 0 5px;"><input type="submit" name="button" class="button-primary" value="Submit"></form></td>

			<td width="10%" valign="top" align="left" style="padding: 15px 0 0 5px;">

			<form id="clear" action="" method="post">

				<input type="hidden" name="action" value="clear" />

				<input type="submit" class="button-primary" value="Clear" />

			</form>

			</td>

			<td align="left" valign="top" style="padding: 15px 0 0 5px;">

				<input type="button" class="button-primary" onClick="printContent('toPrint');" value="Print Report">

			</td>

			<td align="left" valign="top" style="padding: 15px 0 0 25px;">&nbsp;</td>

			<td align="left" valign="top" style="padding: 15px 0 0 5px;">

				<input id="conference-button" type="button" class="button-primary" value="Conference">

			</td>

			<td align="left" valign="top" style="padding: 15px 0 0 5px;">

				<input id="online-button" type="button" class="button-primary" value="Online">

			</td>

		</tr>

	</table>



<?php if($_REQUEST['end']) : ?>

	<h4><br />Income from: &nbsp;<?php echo $_REQUEST['start']." &nbsp;to &nbsp;".$_REQUEST['end']; ?></h4>

	<table id="results" width="100%" border="0" cellspacing="0" cellpadding="0">

		<tr>

			<th width="10%" align="left"><strong>Invoice #</strong></th>

			<th width="45%" align="left"><strong>Name</strong></th>

			<th width="25%" align="left"><strong>Order date</strong></th>

			<th width="10%" align="left"><strong>Payment</strong></th>

			<th width="10%" align="left"><strong>Amount</strong></th>

		</tr>

<?php

function filter_where($where = '') {

	$start = $_REQUEST['start']; 

	$end = $_REQUEST['end']; 

	$where .= " AND post_date >= '$start' AND post_date <= '$end'";

return $where;

}

add_filter('posts_where', 'filter_where');

$args = array($query_string, 'posts_per_page' => -1, 'post_type' => 'purchases');

query_posts($args);

while (have_posts()) : the_post(); 

	global $post;

	$postdate = strtotime(get_the_date());

	$orderdate = date('Y-m-d', $postdate); ?>

	<tr class="<?php global $post; echo get_post_meta($post->ID, '_cmb_payment_type', true) ?>">

		<td valign="top"><?php global $post; echo get_post_meta($post->ID, '_cmb_order_invoice', true) ?></td>

		<td valign="top"><?php the_title(); ?></td>

		<td valign="top"><?php echo $orderdate; ?></td>

		<td valign="top"><?php global $post; echo get_post_meta($post->ID, '_cmb_payment_type', true) ?></td>

		<td valign="top"><strong><?php global $post; echo get_post_meta($post->ID, '_cmb_purchase_price', true) ?></strong></td>

	</tr>  

<?php endwhile; ?>

	</table>

<?php endif; ?>