<?php if($_REQUEST['end']) : 

	$args = array(

		$query_string, 

		'posts_per_page' => -1, 

		'post_type' => 'purchases',

		'meta_query' => array(

			'relation' => 'OR',

			array(

				'key' => '_cmb_payment_type',

				'value' => 'online',

				'compare' => 'LIKE'

			),

			array(

				'key' => '_cmb_payment_type',

				'value' => 'cash',

				'compare' => 'LIKE'

			),

			array(

				'key' => '_cmb_payment_type',

				'value' => 'check',

				'compare' => 'LIKE'

			),

			array(

				'key' => '_cmb_payment_type',

				'value' => 'credit',

				'compare' => 'LIKE'

			)

		)

	);

	query_posts($args);

	while (have_posts()) : the_post();

		global $post;

		$invoiceamount = get_post_meta($post->ID, '_cmb_purchase_price', true);

		$amount = str_replace("$","",$invoiceamount); 

		$amounttotal = $amounttotal + $amount;

	

		$invoicetax = get_post_meta($post->ID,'_cmb_purchase_tax', true);

		$tax = str_replace("$","",$invoicetax); 

		$taxtotal = $taxtotal + $tax; ?>

	<?php endwhile; ?>

	<div id="income">

		<p><strong>Sales total:</strong> $<?php echo number_format($amounttotal,2); ?></p>

		<p><strong>Tax collected:</strong> $<?php echo number_format($taxtotal,2); ?></p>

		<p><strong>GRAND TOTAL:</strong> $<?php echo number_format(($amounttotal + $taxtotal),2); ?></p>

	</div>



	<?php $cargs = array(

		$query_string, 

		'posts_per_page' => -1, 

		'post_type' => 'purchases',

		'meta_query' => array(

			'relation' => 'OR',

			array(

				'key' => '_cmb_payment_type',

				'value' => 'cash',

				'compare' => 'LIKE'

			),

			array(

				'key' => '_cmb_payment_type',

				'value' => 'check',

				'compare' => 'LIKE'

			),

			array(

				'key' => '_cmb_payment_type',

				'value' => 'credit',

				'compare' => 'LIKE'

			)

		)

	);

	query_posts($cargs);

	while (have_posts()) : the_post();

		global $post;

		$cinvoiceamount = get_post_meta($post->ID, '_cmb_purchase_price', true);

		$camount = str_replace("$","",$cinvoiceamount); 

		$camounttotal = $camounttotal + $camount;

	

		$cinvoicetax = get_post_meta($post->ID,'_cmb_purchase_tax', true);

		$ctax = str_replace("$","",$cinvoicetax); 

		$ctaxtotal = $ctaxtotal + $ctax; ?>

	<?php endwhile; ?>

	<div id="conference">

		<p><strong>Conference sales total:</strong> $<?php echo number_format($camounttotal,2); ?></p>

		<p><strong>Tax collected:</strong> $<?php echo number_format($ctaxtotal,2); ?></p>

		<p><strong>GRAND TOTAL:</strong> $<?php echo number_format(($camounttotal + $ctaxtotal),2); ?></p>

	</div>



	<?php $oargs = array(

		$query_string, 

		'posts_per_page' => -1, 

		'post_type' => 'purchases',

		'meta_query' => array(

			array(

				'key' => '_cmb_payment_type',

				'value' => 'online',

				'compare' => '='

			)

		)

	);

	query_posts($oargs);

	while (have_posts()) : the_post();

		global $post;

		$oinvoiceamount = get_post_meta($post->ID, '_cmb_purchase_price', true);

		$oamount = str_replace("$","",$oinvoiceamount); 

		$oamounttotal = $oamounttotal + $oamount;

	

		$oinvoicetax = get_post_meta($post->ID,'_cmb_purchase_tax', true);

		$otax = str_replace("$","",$oinvoicetax); 

		$otaxtotal = $otaxtotal + $otax; ?>

	<?php endwhile; ?>

	<div id="online">

		<p><strong>Online sales total:</strong> $<?php echo number_format($oamounttotal,2); ?></p>

		<p><strong>Tax collected:</strong> $<?php echo number_format($otaxtotal,2); ?></p>

		<p><strong>GRAND TOTAL:</strong> $<?php echo number_format(($oamounttotal + $otaxtotal),2); ?></p>

	</div>

<?php else : ?>   

	<p><strong>Sales total:</strong></p>

	<p><strong>Tax collected:</strong></p>

	<p><strong>GRAND TOTAL:</strong></p>

<?php endif; ?>

<?php remove_filter('posts_where', 'filter_where'); ?> 