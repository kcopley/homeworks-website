<?php if($_REQUEST['end']) : 

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

		$purchaseamount = get_post_meta($post->ID, '_cmb_purchase_price', true);

		$amount = str_replace("$","",$purchaseamount); 

		$amounttotal = $amounttotal + $amount;

	

		$purchasetax = get_post_meta($post->ID,'_cmb_purchase_tax', true);

		$tax = str_replace("$","",$purchasetax); 

		$taxtotal = $taxtotal + $tax; ?>

	<?php endwhile; ?>

		<p><strong>Sales total:</strong> $<?php echo number_format($amounttotal,2); ?></p>

		<p><strong>Tax collected:</strong> $<?php echo number_format($taxtotal,2); ?></p>

		<p><strong>GRAND TOTAL:</strong> $<?php echo number_format(($amounttotal + $taxtotal),2); ?></p>

<?php else : ?>   

	<p><strong>Sales total:</strong></p>

	<p><strong>Tax collected:</strong></p>

	<p><strong>GRAND TOTAL:</strong></p>

<?php endif; ?>

<?php remove_filter('posts_where', 'filter_where'); ?> 