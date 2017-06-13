<?php

// ADMINISTRATION FUNCTIONS ---------------------------------------------


// Hide dashboard panels

add_action('wp_dashboard_setup', 'wpc_dashboard_widgets');

function wpc_dashboard_widgets() {

	global $wp_meta_boxes;

	unset($wp_meta_boxes['dashboard']['normal']['high']['dashboard_browser_nag']);

	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);

	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);

	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);

}



// Hide admin tabs

function remove_links_menu() {

     remove_menu_page('edit.php'); // Posts

     remove_menu_page('link-manager.php'); // Links

}

add_action( 'admin_menu', 'remove_links_menu' );

function sales_console_css() {

	if (current_user_can('cashier')) { ?>

 		<style type="text/css">

			#menu-media, #menu-posts-content, #menu-posts-slide, #menu-comments, #menu-plugins, #menu-tools, .update-nag, #wp-admin-bar-comments, #wp-admin-bar-new-content, #wp-admin-bar-wpseo-menu, #screen-options-link-wrap, #contextual-help-link-wrap { display: none; } #poststuff td, #poststuff th { font-size: .75em; line-height: 1.4em; } #poststuff .button-primary { font-size: 1em; }

		</style>

	<?php }

}

add_action( 'admin_menu', 'sales_console_css' );



// Hide SEO filters

add_filter( 'wpseo_use_page_analysis', '__return_false' );



// Navigation menus

	register_nav_menus( array(

		'mainnavigation' =>  'Main',

		'lfooternavigation' =>  'Left Footer',

		'rfooternavigation' =>  'Right Footer',

		'socialmedia' =>  'Social Media',

		'sitemap' => 'Sitemap'

	) );



// Register sidebars

if ( function_exists ('register_sidebar')) {

	register_sidebar( array(

		'name' => 'First',

		'id' => 'sidebar-first',

		'description' => __( 'Left sidebar' ),

		'before_widget' => '',

		'after_widget' => '',

		'before_title'  => '',

		'after_title'   => ''

	) );

}



// Custom post types

add_action( 'init', 'create_post_type' );

add_theme_support( 'post-thumbnails' );
add_image_size( 'book-thumbnail', 160, 160, FALSE );
add_image_size( 'book-image', 400, 400, FALSE );

function create_post_type() {

	register_post_type( 'Content',

		array(

			'labels' => array(

				'name' => __( 'Additional content' ),

				'singular_name' => __( 'Content' ),

				'add_new' => _x( 'Add New', 'content' ),

				'add_new_item' => __( 'Content' ),

				'edit-item' => __( 'Edit site contents' ),

				'new_item' => __( 'New content' ),

				'all_items' => __( 'All content' ),

				'view_item' => __( 'View content' ),

				'search_items' => __( 'Search content' ),

				'not_found' => __( 'No matches found' ),

				'not_found_in_trash' => __( 'No results' ),

				'parent_item_colon' => '',

				'menu_name' => 'Content'

			),

		'public' => true,

		'rewrite' => true,

		'has_archive' => false,

		'exclude_from_search' => true,

		'menu_position' => 15,

 		'supports' => array('title','editor')

		)

	);

	register_post_type( 'Bookstore',

		array(

			'labels' => array(

				'name' => __( 'Bookstore' ),

				'singular_name' => __( 'Resource' ),

				'add_new' => _x( 'Add New', 'resource' ),

				'add_new_item' => __( 'Resource' ),

				'edit-item' => __( 'Edit resource' ),

				'new_item' => __( 'New resource' ),

				'all_items' => __( 'All resources' ),

				'view_item' => __( 'View resource' ),

				'search_items' => __( 'Search resources' ),

				'not_found' => __( 'No matches found' ),

				'not_found_in_trash' => __( 'No results' ),

				'parent_item_colon' => '',

				'menu_name' => 'Bookstore'

			),

		'public' => true,

		'rewrite' => true,

		'has_archive' => true,

		'exclude_from_search' => false,

		'taxonomies' => array('category'),

		'menu_position' => 14,

 		'supports' => array('title','editor','thumbnail')

		)

	);

	register_post_type( 'Consigners',

		array(

			'labels' => array(

				'name' => __( 'Consigners' ),

				'singular_name' => __( 'Consigner' ),

				'add_new' => _x( 'Add New', 'consigners' ),

				'add_new_item' => __( 'Consigner' ),

				'edit-item' => __( 'Edit Consigner' ),

				'new_item' => __( 'New Consigner' ),

				'all_items' => __( 'All Consigners' ),

				'view_item' => __( 'View Consigners' ),

				'search_items' => __( 'Search Consigners' ),

				'not_found' => __( 'No matches found' ),

				'not_found_in_trash' => __( 'No results' ),

				'parent_item_colon' => '',

				'menu_name' => 'Consigners'

			),

			'public' => true,

			'rewrite' => true,

			'has_archive' => true,

			'exclude_from_search' => false,

			'menu_position' => 15,

			'supports' => array('title')

		)

	);

	register_post_type( 'Transactions',

		array(

			'labels' => array(

				'name' => __( 'Transactions' ),

				'singular_name' => __( 'Transaction' ),

				'add_new' => _x( 'Add New', 'transaction' ),

				'add_new_item' => __( 'Transaction' ),

				'edit-item' => __( 'Edit Transaction' ),

				'new_item' => __( 'New Transaction' ),

				'all_items' => __( 'All Transactions' ),

				'view_item' => __( 'View Transactions' ),

				'search_items' => __( 'Search Transactions' ),

				'not_found' => __( 'No matches found' ),

				'not_found_in_trash' => __( 'No results' ),

				'parent_item_colon' => '',

				'menu_name' => 'Transactions'

			),

			'public' => true,

			'rewrite' => true,

			'has_archive' => true,

			'exclude_from_search' => false,

			'menu_position' => 16,

			'supports' => array('title')

		)

	);

	register_post_type( 'Classes',

		array(

			'labels' => array(

				'name' => __( 'Classes' ),

				'singular_name' => __( 'Class' ),

				'add_new' => _x( 'Add New', 'class' ),

				'add_new_item' => __( 'Class' ),

				'edit-item' => __( 'Edit class' ),

				'new_item' => __( 'New class' ),

				'all_items' => __( 'All classes' ),

				'view_item' => __( 'View classes' ),

				'search_items' => __( 'Search' ),

				'not_found' => __( 'No matches found' ),

				'not_found_in_trash' => __( 'No results' ),

				'parent_item_colon' => '',

				'menu_name' => 'Classes'

			),

		'public' => true,

		'rewrite' => true,

		'has_archive' => false,

		'exclude_from_search' => true,

		'menu_position' => 13,

 		'supports' => array('title','thumbnail')

		)

	);

	register_post_type( 'Purchases',

		array(

			'labels' => array(

				'name' => __( 'Order details' ),

				'singular_name' => __( 'Order' ),

				'add_new' => _x( 'Add new', 'order' ),

				'add_new_item' => __( 'Order' ),

				'edit-item' => __( 'Edit order' ),

				'new_item' => __( 'New order' ),

				'all_items' => __( 'All orders' ),

				'view_item' => __( 'View order details' ),

				'search_items' => __( 'Search' ),

				'not_found' => __( 'No matches found' ),

				'not_found_in_trash' => __( 'No results' ),

				'parent_item_colon' => '',

				'menu_name' => 'Orders'

			),

		'public' => true,

		'rewrite' => true,

		'has_archive' => true,

		'exclude_from_search' => false,

		'menu_position' => 13,

 		'supports' => array('title')

		)

	);

	register_post_type( 'Schools',

		array(

			'labels' => array(

				'name' => __( 'Schools' ),

				'singular_name' => __( 'Schools' ),

				'add_new' => _x( 'Add new', 'school' ),

				'add_new_item' => __( 'School' ),

				'edit-item' => __( 'Edit details' ),

				'new_item' => __( 'New contact' ),

				'all_items' => __( 'All schools' ),

				'view_item' => __( 'View details' ),

				'search_items' => __( 'Search' ),

				'not_found' => __( 'No matches found' ),

				'not_found_in_trash' => __( 'No results' ),

				'parent_item_colon' => '',

				'menu_name' => 'Schools'

			),

		'public' => true,

		'rewrite' => true,

		'has_archive' => false,

		'exclude_from_search' => true,

		'menu_position' => 12,

 		'supports' => array('title')

		)

	);

}



// Custom metabox

add_filter( 'cmb_meta_boxes', 'cmb_sample_metaboxes' );

function cmb_sample_metaboxes( array $meta_boxes ) {

	$prefix = '_cmb_';

	$meta_boxes[] = array(

		'id'         => 'bookstore_details',

		'title'      => 'Details',

		'pages'      => array( 'bookstore', ), // Post type

		'context'    => 'normal',

		'priority'   => 'high',

		'show_names' => false, // Show field names on the left

		'fields' => array(

			array(

				'name' => 'Quantity',

				'desc' => 'Number available',

				'id'   => $prefix . 'resource_quantity',

				'type' => 'text_small',

			),

			array(

				'name' => 'Condition',

				'desc' => 'Enter condition of resource',

				'id'   => $prefix . 'resource_condition',

				'type' => 'text_medium',

			),

			array(

				'name' => 'Cost',

				'desc' => 'Enter retail cost',

				'id'   => $prefix . 'resource_cost',

				'type' => 'text_small',

			),

			array(

				'name' => 'MSRP',

				'desc' => 'Enter suggest retail price',

				'id'   => $prefix . 'resource_MSRP',

				'type' => 'text_small',

			),

			array(

				'name' => 'Price',

				'desc' => 'Enter sale price',

				'id'   => $prefix . 'resource_price',

				'type' => 'text_small',

			),

			array(

				'name'    => 'Availability',

				'desc'    => 'Please choose one',

				'id'      => $prefix . 'resource_available',

				'type'    => 'radio_inline',

				'options' => array(

					array( 'name' => 'Active', 'value' => 'Active', ),

					array( 'name' => 'Inactive', 'value' => 'Inactive', ),

				),

			),

			array(

				'name' => 'ISBN/SKU',

				'desc' => 'Enter ISBN number or SKU',

				'id'   => $prefix . 'resource_sku',

				'type' => 'text_medium',

			),

			array(

				'name' => 'ISBN/SKU (USED ONLY)',

				'desc' => 'Enter USED ISBN number or SKU',

				'id'   => $prefix . 'resource_u-sku',

				'type' => 'text_medium',

			),

			array(

				'name' => 'Barcode/ID Number',

				'desc' => 'Enter Homeworks barcode ID',

				'id'   => $prefix . 'resource_barcode',

				'type' => 'text_small',

			),

			array(

				'name' => 'Publisher',

				'desc' => 'Enter publisher name',

				'id'   => $prefix . 'resource_publisher',

				'type' => 'text_medium',

			),

			array(

				'name' => 'Variation',

				'desc' => 'Publisher/Book title variation',

				'id'   => $prefix . 'resource_variation',

				'type' => 'text_medium',

			),

			array(

				'name' => 'Sales',

				'desc' => 'Total number sold',

				'id'   => $prefix . 'resource_sold',

				'type' => 'text_small',

			),

		)

	);

	$meta_boxes[] = array(

		'id'         => 'order_details',

		'title'      => 'Details',

		'pages'      => array( 'purchases', ), // Post type

		'context'    => 'normal',

		'priority'   => 'high',

		'show_names' => false, // Show field names on the left

		'fields' => array(

			array(

				'name' => 'Invoice number',

				'desc' => 'Customer invoice reference number',

				'id'   => $prefix . 'order_invoice',

				'type' => 'text_small',

			),

			array(

				'name' => 'TransID',

				'desc' => 'TransFirst ticket number',

				'id'   => $prefix . 'transfirst',

				'type' => 'text_small',

			),

			array(

				'name' => 'Shipping and Contact details',

				'desc' => 'Shipping address',

				'id'   => $prefix . 'customer_address',

				'type' => 'textarea_small',

			),

			array(

				'name' => 'Email address',

				'desc' => 'Email',

				'id'   => $prefix . 'customer_email',

				'type' => 'text_medium',

			),

			array(

				'name' => 'School or Organization',

				'desc' => 'Organization',

				'id'   => $prefix . 'customer_organization',

				'type' => 'text_medium',

			),

			array(

				'name' => 'Order summary',

				'desc' => 'Order summary',

				'id'   => $prefix . 'order_summary',

				'type' => 'textarea',

			),

			array(

				'name' => 'Purchase price',

				'desc' => 'Purchase price',

				'id'   => $prefix . 'purchase_price',

				'type' => 'text_small',

			),

			array(

				'name' => 'Tax',

				'desc' => 'Tax amount',

				'id'   => $prefix . 'purchase_tax',

				'type' => 'text_small',

			),

			array(

				'name'    => 'Payment type',

				'desc'    => 'Payment type',

				'id'      => $prefix . 'payment_type',

				'type'    => 'radio_inline',

				'options' => array(

					array( 'name' => 'Online', 'value' => 'online', ),

					array( 'name' => 'Credit', 'value' => 'credit', ),

					array( 'name' => 'Check', 'value' => 'check', ),

					array( 'name' => 'Cash', 'value' => 'cash', ),

				),

			),

			array(

				'name'    => 'Payment received',

				'desc'    => 'Payment processed',

				'id'      => $prefix . 'customer_payment',

				'type'    => 'radio_inline',

				'options' => array(

					array( 'name' => 'Yes', 'value' => 'Yes', ),

					array( 'name' => 'No', 'value' => 'No', ),

				),

			),

		)

	);

	$meta_boxes[] = array(

		'id'         => 'school_details',

		'title'      => 'Details',

		'pages'      => array( 'schools', ), // Post type

		'context'    => 'normal',

		'priority'   => 'high',

		'show_names' => false, // Show field names on the left

		'fields' => array(

			array(

				'name' => 'Contact',

				'desc' => 'School contact',

				'id'   => $prefix . 'school_contact',

				'type' => 'text_medium',

			),

			array(

				'name' => 'Contact Phone',

				'desc' => 'Contact phone number',

				'id'   => $prefix . 'school_phone',

				'type' => 'text_small',

			),

			array(

				'name' => 'Contact Email',

				'desc' => 'Contact email address',

				'id'   => $prefix . 'school_email',

				'type' => 'text_medium',

			),

			array(

				'name' => 'School Address',

				'desc' => 'School address',

				'id'   => $prefix . 'school_address',

				'type' => 'textarea_small',

			),

		)

	);

	$meta_boxes[] = array(

		'id'         => 'class_details',

		'title'      => 'Details',

		'pages'      => array( 'classes', ), // Post type

		'context'    => 'normal',

		'priority'   => 'high',

		'show_names' => false, // Show field names on the left

		'fields' => array(

			array(

				'name' => 'Date',

				'desc' => 'Class date #1',

				'id'   => $prefix . 'class_date',

				'type' => 'text_date',

			),

			array(

				'name' => 'Second date',

				'desc' => 'Class date #2',

				'id'   => $prefix . 'class_date2',

				'type' => 'text_date',

			),

			array(

				'name' => 'Third date',

				'desc' => 'Class date #3',

				'id'   => $prefix . 'class_date3',

				'type' => 'text_date',

			),

			array(

				'name' => 'Fourth date',

				'desc' => 'Class date #4',

				'id'   => $prefix . 'class_date4',

				'type' => 'text_date',

			),

			array(

				'name' => 'Fifth date',

				'desc' => 'Class date #5',

				'id'   => $prefix . 'class_date5',

				'type' => 'text_date',

			),

			array(

				'name' => 'Sixth date',

				'desc' => 'Class date #6',

				'id'   => $prefix . 'class_date6',

				'type' => 'text_date',

			),

			array(

				'name' => 'Seventh date',

				'desc' => 'Class date #7',

				'id'   => $prefix . 'class_date7',

				'type' => 'text_date',

			),

			array(

				'name' => 'Eighth date',

				'desc' => 'Class date #8',

				'id'   => $prefix . 'class_date8',

				'type' => 'text_date',

			),

			array(

				'name' => 'Ninth date',

				'desc' => 'Class date #9',

				'id'   => $prefix . 'class_date9',

				'type' => 'text_date',

			),

			array(

				'name' => 'Tenth date',

				'desc' => 'Class date #10',

				'id'   => $prefix . 'class_date10',

				'type' => 'text_date',

			),

			array(

				'name' => 'Eleventh date',

				'desc' => 'Class date #11',

				'id'   => $prefix . 'class_date11',

				'type' => 'text_date',

			),

			array(

				'name' => 'Twelfth date',

				'desc' => 'Class date #12',

				'id'   => $prefix . 'class_date12',

				'type' => 'text_date',

			),

			array(

				'name' => 'Thirteenth date',

				'desc' => 'Class date #13',

				'id'   => $prefix . 'class_date13',

				'type' => 'text_date',

			),

			array(

				'name' => 'Fourteenth date',

				'desc' => 'Class date #14',

				'id'   => $prefix . 'class_date14',

				'type' => 'text_date',

			),

			array(

				'name' => 'Fifteenth date',

				'desc' => 'Class date #15',

				'id'   => $prefix . 'class_date15',

				'type' => 'text_date',

			),

			array(

				'name' => 'Sixteenth date',

				'desc' => 'Class date #16',

				'id'   => $prefix . 'class_date16',

				'type' => 'text_date',

			),

			array(

				'name' => 'Seventeenth date',

				'desc' => 'Class date #17',

				'id'   => $prefix . 'class_date17',

				'type' => 'text_date',

			),

			array(

				'name' => 'Eighteenth date',

				'desc' => 'Class date #18',

				'id'   => $prefix . 'class_date18',

				'type' => 'text_date',

			),

			array(

				'name' => 'Nineteenth date',

				'desc' => 'Class date #19',

				'id'   => $prefix . 'class_date19',

				'type' => 'text_date',

			),

			array(

				'name' => 'Twentieth date',

				'desc' => 'Class date #20',

				'id'   => $prefix . 'class_date20',

				'type' => 'text_date',

			),

			array(

				'name' => 'Twentyfirst date',

				'desc' => 'Class date #21',

				'id'   => $prefix . 'class_date21',

				'type' => 'text_date',

			),

			array(

				'name' => 'Twentysecond date',

				'desc' => 'Class date #22',

				'id'   => $prefix . 'class_date22',

				'type' => 'text_date',

			),

			array(

				'name' => 'Twentythird date',

				'desc' => 'Class date #23',

				'id'   => $prefix . 'class_date23',

				'type' => 'text_date',

			),

			array(

				'name' => 'Twentyfourth date',

				'desc' => 'Class date #24',

				'id'   => $prefix . 'class_date24',

				'type' => 'text_date',

			),

			array(

				'name' => 'Twentyfifth date',

				'desc' => 'Class date #25',

				'id'   => $prefix . 'class_date25',

				'type' => 'text_date',

			),

			array(

				'name' => 'Twentysixth date',

				'desc' => 'Class date #26',

				'id'   => $prefix . 'class_date26',

				'type' => 'text_date',

			),

			array(

				'name' => 'Twentyseventh date',

				'desc' => 'Class date #27',

				'id'   => $prefix . 'class_date27',

				'type' => 'text_date',

			),

			array(

				'name' => 'Twentyeighth date',

				'desc' => 'Class date #28',

				'id'   => $prefix . 'class_date28',

				'type' => 'text_date',

			),

			array(

				'name' => 'Twentyninth date',

				'desc' => 'Class date #29',

				'id'   => $prefix . 'class_date29',

				'type' => 'text_date',

			),

			array(

				'name' => 'Thirtieth date',

				'desc' => 'Class date #30',

				'id'   => $prefix . 'class_date30',

				'type' => 'text_date',

			),

			array(

				'name' => 'Thirtyfirst date',

				'desc' => 'Class date #31',

				'id'   => $prefix . 'class_date31',

				'type' => 'text_date',

			),

			array(

				'name' => 'Thirtysecond date',

				'desc' => 'Class date #32',

				'id'   => $prefix . 'class_date32',

				'type' => 'text_date',

			),

			array(

				'name' => 'Thirtythird date',

				'desc' => 'Class date #33',

				'id'   => $prefix . 'class_date33',

				'type' => 'text_date',

			),

			array(

				'name' => 'Thirtyfourth date',

				'desc' => 'Class date #34',

				'id'   => $prefix . 'class_date34',

				'type' => 'text_date',

			),

			array(

				'name' => 'Thirtyfifth date',

				'desc' => 'Class date #35',

				'id'   => $prefix . 'class_date35',

				'type' => 'text_date',

			),

			array(

				'name' => 'Time',

				'desc' => 'Class time',

				'id'   => $prefix . 'class_time',

				'type' => 'text_small',

			),

			array(

				'name' => 'Price',

				'desc' => 'Class costs',

				'id'   => $prefix . 'class_price',

				'type' => 'text_small',

			),

			array(

				'name' => 'Additional costs/notes',

				'desc' => 'Additional class costs and/or special information',

				'id'   => $prefix . 'class_special',

				'type' => 'text_medium',

			),

			array(

				'name' => 'Spots',

				'desc' => 'Maximum number of students',

				'id'   => $prefix . 'class_spots',

				'type' => 'text_small',

			),

			array(

				'name' => 'Instructor',

				'desc' => 'Class instructor',

				'id'   => $prefix . 'class_instructor',

				'type' => 'text_medium',

			),

			array(

				'name' => 'Weblink',

				'desc' => 'Website link',

				'id'   => $prefix . 'weblink',

				'type' => 'text_medium',

			),

			array(

				'name' => 'Details',

				'desc' => 'Class details',

				'id'   => $prefix . 'class_details',

				'type' => 'textarea',

			),

			array(

				'name' => 'Students',

				'desc' => 'Students',

				'id'   => $prefix . 'class_students',

				'type' => 'textarea',

			),

		)

	);

	// Add other metaboxes as needed

	return $meta_boxes;

}



add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );

function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )

		require_once 'metabox/init.php';

}



// Add additional columns to custom post types

function bookstore_columns($cols) {

  $cols = array(

    'cb'       => '<input type="checkbox" />',

    'resource'      => __( 'Resource Title', 'trans' ),

    'ISBN' => __( 'ISBN', 'trans' ),

    'barcode'     => __( 'Barcode #', 'trans' ),

    'quantity'     => __( 'Quantity', 'trans' ),

    'categories'     => __( 'Categories', 'trans' ),

  );

  return $cols;

}

function bookstore_columns_content($column, $post_id) {

  switch ($column) {

    case "resource":

      $url = get_edit_post_link($post_id);

      $resource = get_the_title($post_id);

      echo '<strong><a href="' . $url . '">' . $resource. '</a></strong>';

      break;

    case "ISBN":

      $sku = get_post_meta($post_id, '_cmb_resource_sku', true);

      $usku = get_post_meta($post_id, '_cmb_resource_u-sku', true);

      echo $sku.$usku;

      break;

    case "barcode":

      $url = "https://www.homeworksforbooks.com/HOMEWORKS/WP/wp-content/plugins/sales-console/barcode/generate.php?text=";

      $label = get_post_meta($post_id, '_cmb_resource_barcode', true);

      echo '<img src="'.$url.$label.'" />';

      break;

    case "quantity":

      echo get_post_meta($post_id, '_cmb_resource_quantity', true);

      break;

    case "categories":

      echo "category here";

      break;

  }

}



function classes_columns($cols) {

  $cols = array(

    'cb'       => '<input type="checkbox" />',

    'class'      => __( 'Class Title', 'trans' ),

    'instructor'     => __( 'Instructor', 'trans' ),

    'date' => __( 'Date', 'trans' ),

    'empty' => __( ' ', 'trans' ),

  );

  return $cols;

}

function classes_columns_content($column, $post_id) {

  switch ($column) {

    case "class":

      $url = get_edit_post_link($post_id);

      $classname = get_the_title($post_id);

      echo '<strong><a href="' . $url . '">' . $classname. '</a></strong>';

      break;

    case "instructor":

      echo get_post_meta($post_id, '_cmb_class_instructor', true);

      break;

    case "date":

      echo get_post_meta($post_id, '_cmb_class_date', true);

      break;

    case "empty":

      echo " ";

      break;

  }

}



function order_columns($cols) {

  $cols = array(

    'cb'       => '<input type="checkbox" />',

    'customer'      => __( 'Customer', 'trans' ),

    'invoice' => __( 'Invoice #', 'trans' ),

    'transid'     => __( 'TransID', 'trans' ),

    'amount'     => __( 'Amount', 'trans' ),

    'paytype'     => __( 'Type', 'trans' ),

    'orderdate'     => __( 'Date', 'trans' ),

  );

  return $cols;

}

function order_columns_content($column, $post_id) {

  switch ($column) {

    case "customer":

      $url = get_edit_post_link($post_id);

      $customername = get_the_title($post_id);

      echo '<strong><a href="' . $url . '">' . $customername. '</a></strong>';

      break;

    case "invoice":

      $url = get_edit_post_link($post_id);

      $invoiceno = get_post_meta($post_id, '_cmb_order_invoice', true);

      echo '<a href="' . $url . '">' . $invoiceno. '</a>';

      break;

    case "transid":

      echo get_post_meta($post_id, '_cmb_transfirst', true);

      break;

    case "amount":

      $colprice = substr(get_post_meta($post_id, '_cmb_purchase_price', true), 1);

      $coltax = substr(get_post_meta($post_id, '_cmb_purchase_tax', true), 1);

      echo "$".number_format($colprice+$coltax,2);

      break;

    case "paytype":

      echo get_post_meta($post_id, '_cmb_payment_type', true);

      break;

    case "orderdate":

      echo get_the_time('F j, Y @g:i a', $post_id);

      break;

  }

}

add_filter( "manage_bookstore_posts_columns", "bookstore_columns" );

add_action( "manage_posts_custom_column", "bookstore_columns_content", 10, 2 );

add_filter( "manage_classes_posts_columns", "classes_columns" );

add_action( "manage_posts_custom_column", "classes_columns_content", 10, 2 );

add_filter( "manage_purchases_posts_columns", "order_columns" );

add_action( "manage_posts_custom_column", "order_columns_content", 10, 2 );



// Filter by payment method







// Log out page redirect

function custom_logout_home($logouturl, $redir)

{

$redir = get_site_option('home');

return $logouturl . '&amp;redirect_to=' . urlencode($redir);

}

add_filter('logout_url', 'custom_logout_home', 10, 2);



// Bookstore options page setup

add_action('admin_menu', 'register_bookstore_submenu');

function register_bookstore_submenu() {

	add_submenu_page( 'edit.php?post_type=bookstore', 'Bookstore options', 'Settings', 'manage_options', 'bookstore_submenu', 'bookstore_submenu_callback' );

}

function bookstore_submenu_callback() { ?>

	<div class="wrap">

		<div id="icon-themes" class="icon32"><br></div>

		<h2>Settings</h2>

        	<form method="post" action="options.php">

            	<?php wp_nonce_field('update-options') ?>

		<div class="widgets-holder-wrap">

		<div class="postbox-container">

				<div class="postbox">

					<div class="sidebar-name">

						<h3>eCommerce</h3>

					</div>

					<div class="inside">

						<p><strong>Disable online purchases:</strong><br />

						<?php $checked = get_option('ecommerce'); ?>

						&nbsp;<input type="radio" name="ecommerce" <?php if($checked == 'true') echo 'checked="checked"'; ?> value="true" />&nbsp;&nbsp;Yes<br />

						&nbsp;<input type="radio" name="ecommerce" <?php if($checked == 'false') echo 'checked="checked"'; ?> value="false" />&nbsp;&nbsp;No</p>

            					<input type="submit" class="button-primary" name="Submit" value="Update" />

					</div>

				</div>

				<div class="postbox">

					<div class="sidebar-name">

						<h3>Messages</h3>

					</div>

					<div class="inside">

						<p><strong>E-mail address:</strong><br />

						<input type="text" name="customerservice" size="15" value="<?php echo get_option('customerservice'); ?>" /></p>

            					<input type="submit" class="button-primary" name="Submit" value="Update" />

					</div>

				</div>

				<div class="postbox">

					<div class="sidebar-name">

						<h3>TransFirst</h3>

					</div>

					<div class="inside">

						<p><strong>Merchant ID:</strong><br />

						<input type="text" name="merchantid" size="15" value="<?php echo get_option('merchantid'); ?>" /></p>

						<p><strong>Reg Key:</strong><br />

						<input type="text" name="regkey" size="45" value="<?php echo get_option('regkey'); ?>" /></p>

            					<input type="submit" class="button-primary" name="Submit" value="Update" />

					</div>

				</div>

				<div class="postbox">

					<div class="sidebar-name">

						<h3>Checkout</h3>

					</div>

					<div class="inside">

						<p><strong>CONFERENCE Tax rate:</strong><br />

						<input type="text" name="ctaxrate" size="15" value="<?php echo get_option('ctaxrate'); ?>" /></p>

						<p><strong>Tax rate:</strong><br />

						<input type="text" name="taxrate" size="15" value="<?php echo get_option('taxrate'); ?>" /></p>

						<p><strong>Shipping (flat rate):</strong><br />

						<input type="text" name="flatrate" size="15" value="<?php echo get_option('flatrate'); ?>" /></p>

            					<input type="submit" class="button-primary" name="Submit" value="Update" />

					</div>

				</div>

				<div class="postbox">

					<div class="sidebar-name">

						<h3>Invoice details/promotional</h3>

					</div>

					<div class="inside">

						<p><strong>Phone number:</strong><br />

						<input type="text" name="invoicephone" size="15" value="<?php echo get_option('invoicephone'); ?>" /></p>

						<p><strong>Web URL:</strong><br />

						<input type="text" name="invoiceURL" size="15" value="<?php echo get_option('invoiceURL'); ?>" /></p>

						<p><strong>Address:</strong><br />

						<input type="text" name="invoiceaddress" size="15" value="<?php echo get_option('invoiceaddress'); ?>" /></p>

						<p><strong>State/ZIP:</strong><br />

						<input type="text" name="invoiceZIP" size="15" value="<?php echo get_option('invoiceZIP'); ?>" /></p>

						<p><strong>Invoice promotion text:</strong><br />

						<input type="text" name="invoicepromo" size="15" value="<?php echo get_option('invoicepromo'); ?>" /></p>

            					<input type="submit" class="button-primary" name="Submit" value="Update" />

					</div>

				</div>

		</div>

            		<input type="hidden" name="action" value="update" />

            		<input type="hidden" name="page_options" value="customerservice,merchantid,regkey,ctaxrate,taxrate,flatrate,ecommerce,invoicephone,invoiceURL,invoicepromo,invoiceaddress,invoiceZIP" />

		</div>

        	</form>

    	</div>

<?php }



// Custom search queries for resources

add_filter('posts_join', 'bookstore_search_metajoin' );

function bookstore_search_metajoin ($join){

    global $pagenow, $wpdb;

    if ( is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='bookstore' && $_GET['s'] != '') {

        $join .='LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';

    }

    return $join;

}

add_filter( 'posts_where', 'bookstore_search_metawhere' );

function bookstore_search_metawhere($where){

    global $pagenow, $wpdb;

    if ( is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='bookstore' && $_GET['s'] != '') {

        $where = preg_replace(

       "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",

       "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );

    }

    return $where;

}



// SITE FUNCTIONS ---------------------------------------------



// Exclude page from search results

function hide_from_search($qry) {

  if (is_search()) $qry->query_vars['post__not_in'][] = 3366;

  return $qry;

}

add_filter('pre_get_posts','hide_from_search');



// Remove WordPress auto paragraph

remove_filter( 'the_content', 'wpautop' );



// Load jQuery

if ( !function_exists(core_mods) ) {

	function core_mods() {

	if ( !is_admin() ) {

		wp_deregister_script('jquery');

		wp_register_script('jquery', ("https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"), false);

		wp_enqueue_script('jquery'); }

}

core_mods();

}



// Remove WordPress references <head>

function removeHeadLinks() {

    remove_action('wp_head', 'rsd_link');

    remove_action('wp_head', 'wlwmanifest_link');

}

add_action('init', 'removeHeadLinks');

remove_action('wp_head', 'wp_generator');



// Add page title to <body>

function pagetitle_class($classes) {

    if( is_singular() ) {

	global $post;

	array_push($classes, "{$post->post_name}"); }

    return $classes;

}

add_filter( 'body_class', 'pagetitle_class' );



// Create breadcrumbs

if (!function_exists('seobreadcrumbs')) :

	function seobreadcrumbs() {

		$separator = ' :: ';

		$home = 'Home';

		echo '<div xmlns:v="http://rdf.data-vocabulary.org/#" class="seobreadcrumbs">';

		global $post;

		echo '<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="#">Home</a></span>';

		$category = get_the_category();

		if ($category) {

			foreach($category as $category) {

			echo $separator . "<span typeof=\"v:Breadcrumb\"><a rel=\"v:url\" property=\"v:title\" href=\"".get_category_link($category->term_id)."\" >$category->name</a></span>". $separator;

			echo the_title();

		}

	}

echo '</div>';

}

endif;



// Fix pagination for custom-post type

function custom_query_posts(array $query = array()) {

	global $wp_query;

	wp_reset_query();

	$paged = get_query_var('paged') ? get_query_var('paged') : 1;

	$defaults = array('paged' => $paged, 'posts_per_page' => PER_PAGE_DEFAULT);

	$query += $defaults;



	$wp_query = new WP_Query($query);

}



// SHORTCODES ---------------------------------------------













?>