<?php 
	global $wpdb;
	$barcode_id = $_REQUEST['library_id']; 
	$library_title = $_REQUEST['library_title']; 
	$ISBN = $_REQUEST['library_ISBN']; 
	$library_publisher = $_REQUEST['library_publisher']; 
	$library_price = "$".$_REQUEST['library_price']; 

	$product_id = $_REQUEST['product_id']; 
	$qty = $_REQUEST['quantity']; 
	$action = $_REQUEST['action']; 

	switch($action) { 
		case "add":
			$_SESSION['breakdown'][$product_id]++; 
		break;
		case "empty":
			unset($_SESSION['breakdown']); 
		break;
		case "query": ?>
	<table id="formtable" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 10px 0 50px;">
		<tr>
			<th align="left">&nbsp;&nbsp;&nbsp;Title</th>
			<th align="left">ISBN</th>
			<th align="left">Publisher</th>
			<th align="left">Retail price</th>
			<th align="left">Sale price</th>
			<th align="left">Condition</th>
			<th align="left">&nbsp;</th>
		</tr>
		<tr><td colspan="7"><hr /></td></tr>
	<?php 
		if ($_REQUEST['library_title']) { 
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				's' => $library_title, 
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
			);
		};
		if ($_REQUEST['library_publisher']) { 
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_publisher',
						'value' => $library_publisher,),
				)
			);
		};
		if ($_REQUEST['library_price']) { 
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_price',
						'value' => $library_price,),
				)
			);
		};
		if ($_REQUEST['library_id']) { 
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_barcode',
						'value' => $barcode_id,),
				)
			);
		};
		if ($_REQUEST['library_ISBN']) { 
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_u-sku',
						'value' => $ISBN,), 
					array(
						'key' => '_cmb_resource_sku',
						'value' => $ISBN,),
				)
			);
		};
		if ($_REQUEST['library_publisher'] && $_REQUEST['library_price']) {
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_publisher',
						'value' => $library_publisher,),
					array(
						'key' => '_cmb_resource_price',
						'value' => $library_price,),
				)
			);
		};
		if ($_REQUEST['library_publisher'] && $_REQUEST['library_id']) {
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_publisher',
						'value' => $library_publisher,),
					array(
						'key' => '_cmb_resource_barcode',
						'value' => $barcode_id,),
				)
			);
		};
		if ($_REQUEST['library_publisher'] && $_REQUEST['library_ISBN']) {
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_publisher',
						'value' => $library_publisher,),
					array(
						'key' => '_cmb_resource_u-sku',
						'value' => $ISBN,), 
					array(
						'key' => '_cmb_resource_sku',
						'value' => $ISBN,),
				)
			);
		};
		if ($_REQUEST['library_price'] && $_REQUEST['library_id']) {
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_price',
						'value' => $library_price,),
					array(
						'key' => '_cmb_resource_barcode',
						'value' => $barcode_id,),
				)
			);
		};
		if ($_REQUEST['library_price'] && $_REQUEST['library_ISBN']) {
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_price',
						'value' => $library_price,),
					array(
						'key' => '_cmb_resource_u-sku',
						'value' => $ISBN,), 
					array(
						'key' => '_cmb_resource_sku',
						'value' => $ISBN,),
				)
			);
		};
		if ($_REQUEST['library_id'] && $_REQUEST['library_ISBN']) {
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_barcode',
						'value' => $barcode_id,),
					array(
						'key' => '_cmb_resource_u-sku',
						'value' => $ISBN,), 
					array(
						'key' => '_cmb_resource_sku',
						'value' => $ISBN,),
				)
			);
		};
		if ($_REQUEST['library_ISBN'] && $_REQUEST['library_publisher'] && $_REQUEST['library_id']) {
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_publisher',
						'value' => $library_publisher,),
					array(
						'key' => '_cmb_resource_barcode',
						'value' => $barcode_id,),
					array(
						'key' => '_cmb_resource_u-sku',
						'value' => $ISBN,), 
					array(
						'key' => '_cmb_resource_sku',
						'value' => $ISBN,),
				)
			);
		};
		if ($_REQUEST['library_publisher'] && $_REQUEST['library_price'] && $_REQUEST['library_id']) {
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_publisher',
						'value' => $library_publisher,),
					array(
						'key' => '_cmb_resource_price',
						'value' => $library_price,),
					array(
						'key' => '_cmb_resource_barcode',
						'value' => $barcode_id,),
				)
			);
		};
		if ($_REQUEST['library_publisher'] && $_REQUEST['library_id'] && $_REQUEST['library_ISBN']) {
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_publisher',
						'value' => $library_publisher,),
					array(
						'key' => '_cmb_resource_barcode',
						'value' => $barcode_id,),
					array(
						'key' => '_cmb_resource_u-sku',
						'value' => $ISBN,), 
					array(
						'key' => '_cmb_resource_sku',
						'value' => $ISBN,),
				)
			);
		};
		if ($_REQUEST['library_publisher'] && $_REQUEST['library_price'] && $_REQUEST['library_ISBN']) {
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_publisher',
						'value' => $library_publisher,),
					array(
						'key' => '_cmb_resource_price',
						'value' => $library_price,),
					array(
						'key' => '_cmb_resource_u-sku',
						'value' => $ISBN,), 
					array(
						'key' => '_cmb_resource_sku',
						'value' => $ISBN,),
				)
			);
		};
		if ($_REQUEST['library_price'] && $_REQUEST['library_id'] && $_REQUEST['library_ISBN']) {
			$args = array(
				'numberposts' => -1, 
				'posts_per_page'=>-1,
				'order'=> 'ASC', 
				'orderby' => 'date', 
				'post_type' => 'bookstore',
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_price',
						'value' => $library_price,),
					array(
						'key' => '_cmb_resource_barcode',
						'value' => $barcode_id,),
					array(
						'key' => '_cmb_resource_u-sku',
						'value' => $ISBN,), 
					array(
						'key' => '_cmb_resource_sku',
						'value' => $ISBN,),
				)
			);
		};
		$the_query = new WP_Query($args);
		while ($the_query->have_posts()) : $the_query->the_post(); ?>
		<tr>
			<td>&nbsp;&nbsp;&nbsp;<a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></td>
			<td><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_u-sku', true) ?><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_sku', true) ?></td>
			<td><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_publisher', true) ?></td>
			<td><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_MSRP', true) ?></td>
			<td><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_price', true) ?></td>
			<td><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_condition', true) ?></td>
			<td>
				<form method="POST" action="" name="breakdown">
					<input type="hidden" name="action" value="add">
					<input type="hidden" name="product_id" value="<?php the_ID(); ?>">
					<input type="submit" name="button" class="button-primary" value="Add">
				</form>
			</td>
		</tr>
	<?php endwhile; ?>
	</table>
		<?php break;
	}; 
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="25%" valign="top">
			<form method="POST" action="" name="library">
				<table id="formtable" width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="7%" align="right"><label>Title:</label>&nbsp;</td>
						<td width="93%"><input type="text" id="library_title" name="library_title"></td>
					</tr>
					<tr>
						<td width="7%">&nbsp;</td>
						<td width="93%">&nbsp;&nbsp;&nbsp;or</td>
					</tr>
					<tr>
						<td width="7%" align="right"><label>ID:</label>&nbsp;</td>
						<td width="93%"><input type="text" id="library_id" name="library_id"></td>
					</tr>
					<tr>
						<td width="7%" align="right"><label>ISBN:</label>&nbsp;</td>
						<td width="93%"><input type="text" id="library_ISBN" name="library_ISBN"></td>
					</tr>
					<tr>
						<td width="7%" align="right"><label>Publisher:</label>&nbsp;</td>
						<td width="93%"><input type="text" id="library_publisher" name="library_publisher"></td>
					</tr>
					<tr>
						<td width="7%" align="right"><label>Price:</label>&nbsp;$&nbsp;</td>
						<td width="93%"><input type="text" id="library_price" name="library_price"></td>
					</tr>
					<tr>
						<td width="35%">
							<input type="hidden" name="action" value="query">
							<input type="submit" name="button" class="button-primary" value="Search">
						</td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</form>
		</td>
		<td width="75%" valign="top" style="padding-left: 15px;">
			<div id="toPrint">
			<table id="results" width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<th align="left">Title</th>
					<th align="left">Publisher</th>
					<th align="left">Retail price</th>
					<th align="left">Sale price</th>
					<th align="left">Condition</th>
					<th align="left">&nbsp;</th>
				</tr>
				<?php foreach($_SESSION['breakdown'] as $product => $qty) : 
				$row = get_post($product); ?>
				<tr>
					<td><?php echo $row->post_title; ?></td>
					<td><?php echo get_post_meta($product,'_cmb_resource_publisher', true); ?></td>
					<td><?php echo get_post_meta($product,'_cmb_resource_MSRP', true); ?></td>
					<td><?php echo get_post_meta($product,'_cmb_resource_price', true); ?></td>
					<td><?php echo get_post_meta($product,'_cmb_resource_condition', true); ?></td>
				</tr>  
			<?php endforeach; ?>   
			</table>
			</div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;">
				<tr>
					<td width="10%" align="left" valign="top"><input type="button" class="button-primary" onClick="printContent('toPrint');" value="Print Breakdown"></td>
					<td align="left" valign="top" style="padding-left: 5px;">
						<form id="empty" action="" method="post">
							<input type="hidden" name="product_id" value="null" />
							<input type="hidden" name="action" value="empty" />
							<input type="submit" class="button-primary" value="Clear" />
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>