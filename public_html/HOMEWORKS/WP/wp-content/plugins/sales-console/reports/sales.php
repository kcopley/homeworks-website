<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.datepicker').datepicker({
        dateFormat : 'yy-mm-dd'
    });
});
</script>
<form method="POST" action="" name="salesrange">
	<table id="datepicker" width="60%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td><label>Start date:</label><br /><input type="text" class="datepicker" name="start"></td>
			<td><label>End date:</label><br /><input type="text" class="datepicker" name="end"></td>
			<td width="10%" align="left" valign="top" style="padding: 15px 0 0 5px;"><input type="submit" name="button" class="button-primary" value="Submit"></form></td>
			<td width="10%" align="left" valign="top" style="padding: 15px 0 0 5px;">
			<form id="clear" action="" method="post">
				<input type="hidden" name="action" value="clear" />
				<input type="submit" class="button-primary" value="Clear" />
			</form>
			</td>
			<td align="left" valign="top" style="padding: 15px 0 0 5px;">
				<input type="button" class="button-primary" onClick="printContent('toPrint');" value="Print Sales Report">
			</td>
		</tr>
	</table>

<?php if($_REQUEST['end']) : ?>
	<h4><br />Sales from: &nbsp;<?php echo $_REQUEST['start']." &nbsp;to &nbsp;".$_REQUEST['end']; ?></h4>
	<table id="results" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<th width="50%" align="left"><strong>Resource(s)</strong></th>
			<th width="15%" align="left"><strong>Sale date</strong></th>
			<th width="10%" align="left"><strong>Amount</strong></th>
			<th width="5%" align="left"><strong>Tax</strong></th>
			<th width="10%" align="center"><strong>Method</strong></th>
			<th width="10%" align="left"><strong>TOTAL</strong></th>
		</tr>
<?php
function filter_where($where = '') {
	$start = $_REQUEST['start']; 
	$end = $_REQUEST['end']; 
	$where .= " AND post_date >= '$start' AND post_date <= '$end'";
return $where;
}
add_filter('posts_where', 'filter_where');
$args = array(
	$query_string, 
	'posts_per_page' => -1, 
	'post_type' => 'purchases', 
	'meta_query' => array(		
		'relation' => 'OR',
		array(
			'key' => '_cmb_payment_type',
			'value' => 'cash',
		),
		array(
			'key' => '_cmb_payment_type',
			'value' => 'check',
		),
		array(
			'key' => '_cmb_payment_type',
			'value' => 'credit',
		),
	)
);
query_posts($args);
while (have_posts()) : the_post(); 
	global $post;
	$resources = get_post_meta($post->ID, '_cmb_order_summary', true);
	$purchases = str_replace(")",")<br />",$resources);

	$postdate = strtotime(get_the_date());
	$saledate = date('Y-m-d', $postdate);

	$invoiceamount = get_post_meta($post->ID, '_cmb_purchase_price', true);
	$amount = str_replace("$","",$invoiceamount); 
	$invoicetax = get_post_meta($post->ID,'_cmb_purchase_tax', true);
	$tax = str_replace("$","",$invoicetax); ?>
	<tr>
		<td valign="top"><?php echo $purchases; ?></td>
		<td valign="top"><?php echo $saledate; ?></td>
		<td valign="top"><?php global $post; echo get_post_meta($post->ID, '_cmb_purchase_price', true) ?></td>
		<td valign="top"><?php global $post; echo get_post_meta($post->ID, '_cmb_purchase_tax', true) ?></td>
		<td align="center" valign="top"><?php global $post; echo get_post_meta($post->ID, '_cmb_payment_type', true) ?></td>
		<td valign="top"><strong>$<?php echo number_format(($amount + $tax),2); ?></strong></td>
	</tr>  
<?php endwhile; ?>
	</table>
<?php endif; ?>