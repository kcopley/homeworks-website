<?php
/*
Template Name: Cart
*/
?>
<?php session_start();
if($_REQUEST['product_id']) :
	$product_id = $_REQUEST['product_id']; 
	$qty = $_REQUEST['quantity']; 
	$action = $_REQUEST['action']; 
		switch($action) { 
		    case "add":
		        $_SESSION['cart'][$product_id]++; 
		    break;
		    case "remove":
		        $_SESSION['cart'][$product_id]--;
		        if($_SESSION['cart'][$product_id] == 0) 
				unset($_SESSION['cart'][$product_id]);
		    break;
		    case "empty":
		        unset($_SESSION['cart']); 
		    break;
		}
endif;
?>

<?php get_header(); ?>
<article class="row">
	<aside class="three columns offset-by-one">
		<?php get_sidebar(); ?>
	</aside>
	<section class="eight columns">
		<h1>My Bookbag</h1>
		<div class="row">
			<?php the_content(); ?>        
			<?php if($_SESSION['cart']) : ?>        		          	
			<table width="90%">
		        <thead>
		            <th width="50%">Product</th>
		            <th width="15%">Quantity</th>
		            <th width="35%">Price</th>
		        </thead>
					<?php foreach($_SESSION['cart'] as $product => $qty) : ?>
						<?php $row = get_post($product); ?>	
						<?php $type = get_post_type($product); ?>
						<?php if ($type == 'classes') {
								$price = get_post_meta($product,'_cmb_class_price', true);
								$maxqty = get_post_meta($product,'_cmb_class_spots', true);
							} else {
								$price = get_post_meta($product,'_cmb_resource_price', true);
								$maxqty = get_post_meta($product,'_cmb_resource_quantity', true);
								if ($qty > $maxqty) {
									$qty = $maxqty;
									$qtymessage = "<br /><span class='notice'>Only ".$maxqty." available</span>";
								};
							};	
							$amount = str_replace("$","",$price); 
							$line_cost = $amount * $qty; 
							$total = $total + $line_cost; ?>
						<tr>
							<td>
								<?php $permalink = get_permalink( $product ); ?>
								<a href="<?php echo $permalink; ?>"><?php echo $row->post_title; ?></a>
								<?php echo $qtymessage; ?>
				           		</td>
							<td>
                                				<form class="remove" action="" method="post">
									<input type="hidden" name="product_id" value="<?php echo $product; ?>" />
									<input type="hidden" name="action" value="remove" />
									<input type="submit" value="-" />
								</form>
								<span class="quantity"><?php echo $qty; ?></span>
                                				<form class="add" action="" method="post">
									<input type="hidden" name="product_id" value="<?php echo $product; ?>" />
									<input type="hidden" name="action" value="add" />
									<input type="submit" value="+" />
								</form>
				           		</td>
							<td>
								$<?php echo number_format($line_cost, 2); ?>
							</td>
						</tr>  
                        		<?php $total += get_post_meta($product, '_cmb_resource_price', true) * $qty; ?>
					<?php endforeach; ?>    	
					<tr>
						<td colspan="2">&nbsp;</td>
						<td><strong>Total:</strong> $<?php echo number_format($total, 2); ?></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
						<td>
                        				<form id="empty" action="" method="post">
                                				<input type="hidden" name="product_id" value="null" />
                                				<input type="hidden" name="action" value="empty" />
                                				<input type="submit" value="Empty bookbag" />
                            				</form>
                        				<form id="checkout" action="<?php bloginfo('url'); ?>/checkout" method="post">
                                				<input type="hidden" name="total" value="<?php echo number_format($total, 2); ?>" />
                                				<input type="submit" value="Proceed to checkout" />&rarr;
                            				</form>
						</td>
					</tr>
			</table>		
		<?php else : ?>        
			<p>Your bookbag is empty.</p>    
			<a href="<?php bloginfo('url'); ?>/library" class="button">Shop for resources</a>        
		<?php endif; ?>
		</div>
	</section>
</article>
<?php get_footer(); ?>