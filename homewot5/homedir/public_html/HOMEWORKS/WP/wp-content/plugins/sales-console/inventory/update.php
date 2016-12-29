<table id="formtable" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left" valign="top" width="50%">
			<form method="POST" action="" name="search">
				<table id="formtable" width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="7%" align="right" valign="top"><label>Title:</label>&nbsp;</td>
						<td width="93%" valign="top"><input type="text" id="search_title" name="search_title"><br />&nbsp;&nbsp;&nbsp;or</td>
					</tr>
					<tr>
						<td width="7%" align="right" valign="top"><label>Publisher:</label>&nbsp;</td>
						<td width="93%" valign="top"><input type="text" id="search_publisher" name="search_publisher"><br />&nbsp;&nbsp;&nbsp;or</td>
					</tr>
					<tr>
						<td width="7%" align="right" valign="top"><label>ISBN:</label>&nbsp;</td>
						<td width="93%" valign="top"><input type="text" id="search_ISBN" name="search_ISBN"></td>
					</tr>
					<tr>
						<td align="left" valign="top">
							<input type="hidden" name="action" value="match">
							<input type="submit" name="button" class="button-primary" value="Search">
						</td>
						<td align="left" valign="top">
							&nbsp;&nbsp;<a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=inventory-update" class="button-primary">Refresh</a>
						</td>
					</tr>
				</table>
			</form>
		</td>
		<td align="left" style="padding-left: 25px;">
			<?php if($_REQUEST['message']){ 
				$booktitle = $_REQUEST['booktitle'];
				$vendor = $_REQUEST['vendor'];
				$MSRP = $_REQUEST['MSRP'];
				$cost = $_REQUEST['cost'];
				$price = $_REQUEST['price'];
				$newISBN = $_REQUEST['newISBN']; 

				$last = get_posts("post_type=bookstore&numberposts=1");
				$lastid = $last[0]->ID;
				$lastbarcode = get_post_meta($lastid, '_cmb_resource_barcode', true);
				$barcode = $lastbarcode + 1;

				$bookid = $_REQUEST['product_id'];
				$oldbarcode = get_post_meta($bookid, '_cmb_resource_barcode', true);

				if ($_REQUEST['message'] == "new"){ ?>
					<h4 style="font-size: 14px; margin: 5px 0 0;">New book(s) added:</h4>
					<p><strong><?php echo $booktitle; ?></strong><br />
					<?php if ($vendor != "" or $vendor != " "){ ?>
						<strong>Publisher:</strong> <?php echo $vendor; ?><br />
					<?php }; ?>
					<?php if ($cost != "" or $cost != " "){ ?>
						<strong>Cost:</strong> $<?php echo $cost; ?><br />
					<?php }; ?>
					<?php if ($MSRP != "" or $MSRP != " "){ ?>
						<strong>MSRP:</strong> $<?php echo $MSRP; ?><br />
					<?php }; ?>
					<?php if ($price != "" or $price != " "){ ?>
						<strong>Price:</strong> $<?php echo $price; ?><br />
					<?php }; ?>
					<strong>ISBN:</strong> <?php echo $newISBN; ?></p>
					<p><strong>Barcode:</strong> <?php echo $barcode; ?></p>  

 				<?php } else if ($_REQUEST['message'] == "update"){ ?>
					<h4 style="font-size: 14px; margin: 5px 0 10px;">Book quantity updated.</h4>
					<p><strong>Barcode:</strong> <?php echo $oldbarcode; ?></p>  
					<?php 
					?>
 				<?php } else {

				}; 
			}; ?>
		</td>
	</tr>
</table>

<?php 
global $wpdb;
$library_title = $_REQUEST['search_title']; 
$search_publisher = $_REQUEST['search_publisher']; 
$search_ISBN = $_REQUEST['search_ISBN']; 
$action = $_REQUEST['action']; 
switch($action) { 
	case "match":
		if($_REQUEST['search_title']){ 
			$args = array(
				'post_type' => 'bookstore',
				'posts_per_page' => -1,
				's' => $library_title,
			);
		} elseif($_REQUEST['search_publisher'])  { 
			$args = array(
				'post_type' => 'bookstore',
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
						'key' => '_cmb_resource_publisher',
						'value' => $search_publisher,),
					)
				);
		} else {
			$args = array(
				'post_type' => 'bookstore',
				'posts_per_page' => -1,
				'meta_query' => array('relation' => 'OR',
					array(
						'key' => '_cmb_resource_u-sku',
						'value' => $search_ISBN,
						'compare' => 'LIKE',),
					array(
						'key' => '_cmb_resource_sku',
						'value' => $search_ISBN,
						'compare' => 'LIKE',),
				)
			);
		};
		$the_query = new WP_Query( $args );
		if ($the_query->have_posts()) { ?>
			<hr />
			<table id="formtable" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 10px 0 50px;">
				<tr>
					<td valign="top" align="left" width="25%">
			<h4 style="font-size: 14px; margin: 10px 0;">Add resource:</h4>
			<form method="POST" action="" name="inventory">
				<table id="formtable" width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="5%" align="right"><label>Title:</label></td>
						<td><input type="text" name="booktitle" value="<?php echo stripslashes($_REQUEST['search_title']); ?>"></td>
					</tr>
					<tr>
						<td align="right"><label>Quantity:</label></td>
						<td><input type="text" name="newquantity"></td>
					</tr>
					<tr>
						<td align="right"><label>Department:</label></td>
						<td><?php wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'bookcategory', 'hierarchical' => true, 'show_option_all' => 'Choose one')); ?></td>
					</tr>
					<tr>
						<td align="right"><label>Availability:</label></td>
						<td>&nbsp;&nbsp;<input type="radio" name="available" value="Active"> Active&nbsp;&nbsp;&nbsp;<input type="radio" name="available" value="Inactive"> Inactive</td>
					</tr>
					<tr>
						<td align="right"><label>Cost: $</label> </td>
						<td><input type="text" name="cost"></td>
					</tr>
					<tr>
						<td align="right"><label>Price: $</label> </td>
						<td><input type="text" name="price"></td>
					</tr>
					<tr>
						<td align="right"><label>MSRP: $</label> </td>
						<td><input type="text" name="MSRP"></td>
					</tr>
					<tr>
						<td align="right"><label>Vendor:</label></td>
						<td><input type="text" name="vendor" value="<?php echo stripslashes($_REQUEST['search_publisher']); ?>"></td>
					</tr>
					<tr>
						<td align="right"><label>Condition:</label></td>
						<td>&nbsp;&nbsp;<input type="radio" name="condition" value="New"> New&nbsp;&nbsp;&nbsp;<input type="radio" name="condition" value="Used"> Used</td>
					</tr>
					<tr>
						<td align="right"><label>ISBN:</label></td>
						<td><input type="text" name="newISBN" value="<?php echo $_REQUEST['search_ISBN']; ?>"></td>
					</tr>
				</table>
				<input type="hidden" name="action" value="add" />
				<input type="hidden" name="message" value="new" />
				<input type="submit" name="button" class="button-primary" value="Add to library">
			</form>
					</td>
					<td valign="top" align="left">
			<h4 style="font-size: 14px; margin: 10px 0 0;">Search results:</h4>
			<table id="searchtable" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 10px 0 50px;">
				<tr>
					<th align="left">&nbsp;&nbsp;&nbsp;Title</th>
					<th align="left">ISBN</th>
					<th align="left">Publisher</th>
					<th align="center">Cost</th>
					<th align="center">MSRP</th>
					<th align="center">Sale price</th>
					<th align="center">Condition</th>
					<th align="center">Qty</th>
					<th align="left">&nbsp;</th>
				</tr>
				<tr><td colspan="9"><hr /></td></tr>
			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<tr>
					<td width="30%" valign="top"><?php the_title(); ?></td>
					<td width="15%" valign="top" bgcolor="#f2f2f2"><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_sku', true); ?><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_u-sku', true); ?></td>
					<td width="15%" valign="top"><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_publisher', true); ?></td>
					<td width="5%" align="center" valign="top" bgcolor="#f2f2f2"><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_cost', true); ?></td>
					<td width="5%" align="center" valign="top"><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_MSRP', true); ?></td>
					<td width="10%" align="center" valign="top" bgcolor="#f2f2f2"><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_price', true); ?></td>
					<td width="5%" align="center" valign="top"><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_condition', true); ?></td>
					<td width="5%" align="center" valign="top" bgcolor="#f2f2f2"><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_quantity', true); ?></td>
					<td width="10%" valign="top" class="update">
					<form id="addlibrary" action="" method="post">
						<input type="hidden" name="product_id" value="<?php the_ID(); ?>" />
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="message" value="update" />
						<input type="submit" class="button-primary" value="+1" />
					</form>
					</td>
				</tr>
			<?php endwhile; ?>
			</table>
					</td>
				</tr>
			</table>
		<?php } else { ?>
			<p>No results were found. Complete the fields below to add a new entry into the library.</p>
			<form method="POST" action="" name="inventory">
				<table id="formtable" width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="5%" align="right"><label>Title:</label></td>
						<td><input type="text" name="booktitle" value="<?php echo stripslashes($_REQUEST['search_title']); ?>"></td>
					</tr>
					<tr>
						<td align="right"><label>Quantity:</label></td>
						<td><input type="text" name="newquantity"></td>
					</tr>
					<tr>
						<td align="right"><label>Department:</label></td>
						<td><?php wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'bookcategory', 'hierarchical' => true, 'show_option_all' => 'Choose one')); ?></td>
					</tr>
					<tr>
						<td align="right"><label>Availability:</label></td>
						<td>&nbsp;&nbsp;<input type="radio" name="available" value="Active"> Active&nbsp;&nbsp;&nbsp;<input type="radio" name="available" value="Inactive"> Inactive</td>
					</tr>
					<tr>
						<td align="right"><label>Cost: $</label> </td>
						<td><input type="text" name="cost"></td>
					</tr>
					<tr>
						<td align="right"><label>Price: $</label> </td>
						<td><input type="text" name="price"></td>
					</tr>
					<tr>
						<td align="right"><label>MSRP: $</label> </td>
						<td><input type="text" name="MSRP"></td>
					</tr>
					<tr>
						<td align="right"><label>Vendor:</label></td>
						<td><input type="text" name="vendor" value="<?php echo stripslashes($_REQUEST['search_publisher']); ?>"></td>
					</tr>
					<tr>
						<td align="right"><label>Condition:</label></td>
						<td>&nbsp;&nbsp;<input type="radio" name="condition" value="New"> New&nbsp;&nbsp;&nbsp;<input type="radio" name="condition" value="Used"> Used</td>
					</tr>
					<tr>
						<td align="right"><label>ISBN:</label></td>
						<td><input type="text" name="newISBN" value="<?php echo $_REQUEST['search_ISBN']; ?>"></td>
					</tr>
				</table>
				<input type="hidden" name="action" value="add" />
				<input type="hidden" name="message" value="new" />
				<input type="submit" name="button" class="button-primary" value="Add to library">
			</form>
		<?php } 
	break;
	case "update":
		global $post;
		$product_id = $_REQUEST['product_id']; 
		$qty = 1;
		$oldstock = get_post_meta($product_id,'_cmb_resource_quantity', true);
		$newstock = $oldstock + $qty;
		update_post_meta($product_id, '_cmb_resource_quantity', $newstock); 

		$newbarcode = get_post_meta($product_id, '_cmb_resource_barcode', true);
		$_SESSION['newbarcode'] = $newbarcode;

		$status = get_post_meta($product_id, '_cmb_resource_available', true);
 		if ($status == "Inactive"){
			update_post_meta($product_id, '_cmb_resource_available', 'Active'); 
  		} else {

 		};
	break;
	case "add":
		global $wpdb;
		$_SESSION['booktitle'] = $_REQUEST['booktitle']; 
		$_SESSION['newquantity'] = $_REQUEST['newquantity'];
		$_SESSION['bookcategory'] = $_REQUEST['bookcategory']; 
		$_SESSION['cost'] = $_REQUEST['cost']; 
		$_SESSION['price'] = $_REQUEST['price']; 
		$_SESSION['MSRP'] = $_REQUEST['MSRP']; 
		$_SESSION['vendor'] = $_REQUEST['vendor']; 
		$_SESSION['condition'] = $_REQUEST['condition']; 
		$_SESSION['available'] = $_REQUEST['available']; 
		$_SESSION['newISBN'] = $_REQUEST['newISBN']; 

		$booktitle = $_REQUEST['booktitle']; 
		$newquantity = $_REQUEST['newquantity'];
		$bookcategory = $_REQUEST['bookcategory']; 
		$cost = "$".$_REQUEST['cost']; 
		$price = "$".$_REQUEST['price']; 
		$MSRP = "$".$_REQUEST['MSRP']; 
		$vendor = $_REQUEST['vendor']; 
		$condition = $_REQUEST['condition']; 
		$available = $_REQUEST['available']; 
		$newISBN = $_REQUEST['newISBN']; 

		$last = get_posts("post_type=bookstore&numberposts=1");
		$lastid = $last[0]->ID;
		$lastbarcode = get_post_meta($lastid, '_cmb_resource_barcode', true);
		$newbarcode = $lastbarcode + 1;

		global $post;
		$order = array(
			'post_title'    => $booktitle,
			'post_status'   => 'publish',
			'post_author'   => 4,
			'post_category' => array($bookcategory),
			'post_type'	=> bookstore
		);
		$postid = wp_insert_post( $order, $wp_error );
		update_post_meta($postid, '_cmb_resource_quantity', $newquantity); 
		update_post_meta($postid, '_cmb_resource_cost', $cost);
		update_post_meta($postid, '_cmb_resource_price', $price);
		update_post_meta($postid, '_cmb_resource_MSRP', $MSRP);
		update_post_meta($postid, '_cmb_resource_publisher', $vendor);
		update_post_meta($postid, '_cmb_resource_sold', 0);
		update_post_meta($postid, '_cmb_resource_available', $available);
 		if ($condition == "New"){
			update_post_meta($postid, '_cmb_resource_sku', $newISBN); 
  		} else {
			update_post_meta($postid, '_cmb_resource_u-sku', $newISBN); 
 		};
		update_post_meta($postid, '_cmb_resource_condition', $condition);
		update_post_meta($postid, '_cmb_resource_barcode', $newbarcode); 
	break;
}; ?>