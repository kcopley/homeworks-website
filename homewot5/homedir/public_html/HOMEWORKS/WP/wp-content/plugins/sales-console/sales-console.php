<?php
/*
   Plugin Name: Sales Console
   Plugin URI: http://fourthcup.org
   Version: 1.0
   Author: Jason Sprenger
   Description: A simple kiosk for managing bookstore sales and inventory
   Text Domain: sales-console
   License: GPL2
*/

function sales_console_scripts() {
        $src = plugins_url( 'js/invoices.js', __FILE__ );
	wp_enqueue_script( 'googlechart-js', 'https://www.google.com/jsapi', array( 'jquery' ), false, false );
        wp_enqueue_script( 'printscreen-js', plugins_url( 'js/invoices.js', __FILE__ ), array( 'jquery' ), false, false );
        wp_enqueue_script( 'barcodes-js', plugins_url( 'js/barcodes.js', __FILE__ ), array( 'jquery' ), false, false );
	wp_enqueue_script('jquery-ui-datepicker');
	wp_register_style( 'sales_console_css', plugins_url('css/stylesheet.css', __FILE__) );
	wp_enqueue_style( 'sales_console_css' );
	wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
}
add_action( 'admin_init', 'sales_console_scripts' );

function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}
add_action('init', 'myStartSession', 1);

function myEndSession() {
    session_destroy ();
}
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function sales_console_cart () { ?>
<?php function add_query_vars($TFVars) {
	$TFVars[] = array("TransID","Auth","CVV2ResponseMsg","Notes","USER1");
	return $TFVars;
}
add_filter('query_vars', 'add_query_vars'); ?>
<div class="wrap">
	<div id="icon-themes" class="icon32"><br></div>
	<h2>Checkout</h2>
	<div id="poststuff">
	<div id="post-body" class="metabox-holder">
		<div id="postbox-container-1" class="postbox-container">
				<div id="inventory_details" class="postbox ">
					<div class="handlediv"><br /></div>
					<h3 class="hndle"><span>Book Order(s)</span></h3>
					<div class="inside">
						<?php include(plugin_dir_path(__FILE__).'checkout.php'); ?>
					</div>
				</div>
		</div>
	</div>
	</div>
</div>
<?php }

function sales_console_refund () { ?>
<div class="wrap">
	<div id="icon-themes" class="icon32"><br></div>
	<h2>Refund/Void</h2>
	<div id="poststuff">
	<div id="post-body" class="metabox-holder">
		<div id="postbox-container-1" class="postbox-container">
				<div id="income_details" class="postbox ">
					<div class="handlediv"><br /></div>
					<h3 class="hndle"><span>Void Transaction</span></h3>
					<div class="inside">
						<?php include(plugin_dir_path(__FILE__).'refund.php'); ?>
					</div>
				</div>
		</div>
	</div>
	</div>
</div>
<?php }

function inventory_update () { ?>
<div class="wrap">
	<div id="icon-themes" class="icon32"><br></div>
	<h2>Add Resources</h2>
	<div id="poststuff">
	<div id="post-body" class="metabox-holder">
		<div id="postbox-container-2" class="postbox-container">
				<div id="inventory_details" class="postbox ">
					<div class="handlediv"><br /></div>
					<h3 class="hndle"><span>Add item(s)</span></h3>
					<div class="inside">
						<?php include(plugin_dir_path(__FILE__).'inventory/update.php'); ?>
					</div>
				</div>
		</div>
	</div>
	</div>
</div>
<?php }

function sales_console_barcodes () { ?>
<div class="wrap">
	<div id="icon-themes" class="icon32"><br></div>
	<h2>Barcodes/Labels</h2>
	<div id="poststuff">
	<div id="post-body" class="metabox-holder">
		<div id="postbox-container-2" class="postbox-container">
				<div id="barcode_details" class="postbox ">
					<div class="handlediv"><br /></div>
					<h3 class="hndle"><span>Generate barcode(s)</span></h3>
					<div class="inside">
						<?php include(plugin_dir_path(__FILE__).'barcode.php'); ?>
					</div>
				</div>
		</div>
	</div>
	</div>
</div>
<?php }

function sales_console_purchases () { ?>
<div class="wrap">
	<div id="icon-themes" class="icon32"><br></div>
	<h2>Conference Sales</h2>
	<div id="poststuff">
	<div id="post-body" class="metabox-holder">
		<div id ="toPrint">
		<div id="postbox-container-1" class="postbox-container">
				<div id="sales_details" class="postbox ">
					<div class="handlediv"><br /></div>
					<h3 class="hndle"><span>Conference sales</span></h3>
					<div class="inside">
						<?php include(plugin_dir_path(__FILE__).'reports/sales.php'); ?>
					</div>
				</div>
				<div id="totals_details" class="postbox ">
					<div class="handlediv"><br /></div>
					<h3 class="hndle"><span>Totals</span></h3>
					<div class="inside">
						<?php include(plugin_dir_path(__FILE__).'reports/total-sales.php'); ?>
					</div>
				</div>
		</div>
		</div>
	</div>
	</div>
</div>
<?php }

function sales_console_income () { ?>
<div class="wrap">
	<div id="icon-themes" class="icon32"><br></div>
	<h2>Total Income</h2>
	<div id="poststuff">
	<div id="post-body" class="metabox-holder columns-2">
		<div id ="toPrint">
		<div id="postbox-container-2" class="postbox-container">
				<div id="income_details" class="postbox ">
					<div class="handlediv"><br /></div>
					<h3 class="hndle"><span>Invoices</span></h3>
					<div class="inside">
						<?php include(plugin_dir_path(__FILE__).'reports/income.php'); ?>
					</div>
				</div>
		</div>
		<div id="postbox-container-1" class="postbox-container">
				<div id="totals_details" class="postbox ">
					<div class="handlediv"><br /></div>
					<h3 class="hndle"><span>Revenue</span></h3>
					<div class="inside">
						<?php include(plugin_dir_path(__FILE__).'reports/total-income.php'); ?>
					</div>
				</div>
		</div>
		</div>
	</div>
	</div>
</div>
<?php }
function sales_console_bestsellers () { ?>
<div class="wrap">
	<div id="icon-themes" class="icon32"><br></div>
	<h2>Bestselling Resources</h2>
	<div id="poststuff">
	<div id="post-body" class="metabox-holder">
		<div id="postbox-container-1" class="postbox-container">
				<div id="sales_details" class="postbox ">
					<div class="handlediv"><br /></div>
					<h3 class="hndle"><span>Bestselling resources</span></h3>
					<div class="inside">
						<?php include(plugin_dir_path(__FILE__).'bestsellers.php'); ?>
					</div>
				</div>
		</div>
	</div>
	</div>
</div>
<?php }
function sales_console_library () { ?>
<div class="wrap">
	<div id="icon-themes" class="icon32"><br></div>
	<h2>Search Library</h2>
	<div id="poststuff">
	<div id="post-body" class="metabox-holder">
		<div id="postbox-container-1" class="postbox-container">
				<div id="library_details" class="postbox ">
					<div class="handlediv"><br /></div>
					<h3 class="hndle"><span>Search Library</span></h3>
					<div class="inside">
						<?php include(plugin_dir_path(__FILE__).'library.php'); ?>
					</div>
				</div>
		</div>
	</div>
	</div>
</div>
<?php }

function sales_console_menu () {
	add_menu_page('Sales Console', 'Sales Console', 'publish_posts', 'sales-console-admin', 'sales_console_options', '', 6);
	add_submenu_page('sales-console-admin','','','publish_posts','sales-console-admin','sales_console_cart');
	add_submenu_page('sales-console-admin', 'Browse Library', 'Browse Library', 'publish_posts', 'library_breakdown', 'sales_console_library');
	add_submenu_page('sales-console-admin', 'Add Resources', 'Add Resources', 'publish_posts', 'inventory-update', 'inventory_update');
	add_submenu_page('sales-console-admin', 'Barcodes', 'Barcodes', 'publish_posts', 'inventory-barcodes', 'sales_console_barcodes');
	add_submenu_page('sales-console-admin', 'Checkout', 'Checkout', 'publish_posts', 'conference-sales', 'sales_console_cart');
	add_submenu_page('sales-console-admin', 'Refund/Void', 'Refund/Void', 'publish_posts', 'sales_console_refund', 'sales_console_refund');
	add_submenu_page('sales-console-admin', 'Conference Sales', 'Conference Sales', 'publish_posts', 'sales_console_purchases', 'sales_console_purchases');
	add_submenu_page('sales-console-admin', 'Income/Revenue', 'Income/Revenue', 'publish_posts', 'sales_console_income', 'sales_console_income');
	add_submenu_page('sales-console-admin', 'Bestsellers', 'Bestsellers', 'publish_posts', 'sales_console_bestsellers', 'sales_console_bestsellers');
}
add_action('admin_menu','sales_console_menu');
?>