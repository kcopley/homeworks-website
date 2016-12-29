<?php
// ADMINISTRATION FUNCTIONS ---------------------------------------------

// Hide dashboard panels
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

// Build "Contents" admin menu
function edit_admin_menus() {  
	global $menu;  
        global $submenu;  
        $menu[20][0] = 'Site Contents'; // Menu name  
        $submenu['edit.php?post_type=page'][5][0] = 'Pages';  
    	remove_submenu_page('edit.php?post_type=page','post-new.php?post_type=page');   
}

// Enqueue CSS and scripts
if (! function_exists('load_foundation_css')) {
	function load_foundation_css() {
		global $wp_styles;
		wp_enqueue_style(
			'normalize',
			get_template_directory_uri().'/css/normalize.css',
			array(),
			'2.1.2',
			'all'
		);
		wp_enqueue_style(
			'foundation_css',
			get_template_directory_uri().'/css/foundation.min.css',
			array('normalize'),
			'5.0.2',
			'all'
		);
		wp_enqueue_style(
			'foundation_ie8_grid',
			get_template_directory_uri().'/css/ie8-grid-foundation-4.css',
			array('foundation_css'),
			'1.0',
			'all'
		);
		wp_enqueue_style(
			'video_css',
			get_template_directory_uri().'/css/video-js.min.css',
			array(),
			'1.0',
			'all'
		);
		wp_enqueue_style(
			'site_css',
			get_template_directory_uri().'/style.css',
			array(),
			'1.0',
			'all'
		);
		$wp_styles->add_data('foundation_ie8_grid','conditional','lt IE 8');
	}
}
if (! function_exists('load_foundation_scripts')) {
	function load_foundation_scripts() {
		wp_enqueue_script(
			'foundation_modernizr_js',
			get_template_directory_uri() . '/js/custom.modernizr.js',
			array(),
			'2.6.2',
			false
		);
		wp_enqueue_script(
			'foundation_selectivizr_js',
			get_template_directory_uri() . '/js/selectivizr-min.js',
			array(),
			'1.0.2',
			false
		);
		wp_enqueue_script(
			'foundation_js',
			get_template_directory_uri() . '/js/foundation.min.js',
			array('jquery'),
			'5.0.2',
			true
		);
		wp_enqueue_script(
			'foundation_init_js',
			get_template_directory_uri().'/js/foundation_init.js',
			array('foundation_js'),
			'1.0',
			true
		);
		wp_enqueue_script(
			'smooth-scroll',
			get_template_directory_uri().'/js/smooth-scroll.js',
			array('jquery'),
			'2.0',
			true
		);
	}
}

// load Foundation specific functions
require_once('inc/foundation.php');

// Register navigation menus
function foundation_menus() {
	register_nav_menus(
		array(
			'main-menu-left' => __('Main Menu (left)','foundation'),
			'main-menu-right' => __('Main Menu (right)','foundation'),
			'footer-menu' => __('Footer','foundation')
		)
	);
}

// Register sidebars
if (function_exists('register_sidebar')) {
	register_sidebar(array(
	  'name' => __('Foundation sidebar'),
	  'id' => 'foundation-sidebar',
	  'description' => __('Content loaded here will be shown in the sidebar'),
	  'before_title' => '<h5>',
	  'after_title' => '</h5>',
	  'before_widget' => '',
	  'after_widget'  => ''
	));
	register_sidebar(array(
	  'name' => __('Foundation subfooter: Left'),
	  'id' => 'foundation-left',
	  'description' => __('Content loaded here is displayed in the subfooter.'),
	  'before_title' => '<h5>',
	  'after_title' => '</h5>',
	  'before_widget' => '',
	  'after_widget'  => ''
	));
	register_sidebar(array(
	  'name' => __('Foundation subfooter: Center'),
	  'id' => 'foundation-center',
	  'description' => __('Content loaded here is displayed in the subfooter.'),
	  'before_title' => '<h5>',
	  'after_title' => '</h5>',
	  'before_widget' => '',
	  'after_widget'  => ''
	));
	register_sidebar(array(
	  'name' => __('Foundation subfooter: Right'),
	  'id' => 'foundation-right',
	  'description' => __('Content loaded here is displayed in the subfooter.'),
	  'before_title' => '<h5>',
	  'after_title' => '</h5>',
	  'before_widget' => '',
	  'after_widget'  => ''
	));
	register_sidebar(array(
	  'name' => __('Client list'),
	  'id' => 'clientlist',
	  'description' => __('Section for displaying client names and/or logos'),
	  'before_title' => '<h5>',
	  'after_title' => '</h5>',
	  'before_widget' => '',
	  'after_widget'  => ''
	));
	register_sidebar(array(
	  'name' => __('Promotional window'),
	  'id' => 'promotional-modal',
	  'description' => __('Section for promotional content in the form of a modal window'),
	  'before_title' => '<h5>',
	  'after_title' => '</h5>',
	  'before_widget' => '',
	  'after_widget'  => ''
	));
}

// Log out page redirect	
function custom_logout_home($logouturl, $redir) {
	$redir = get_bloginfo('siteurl');
	return $logouturl . '&amp;redirect_to=' . urlencode($redir);
}

// Custom post types
add_action( 'init', 'create_post_type' );
function create_post_type() {
	register_post_type( 'Event',
		array(
			'labels' => array(
				'name' => __( 'Events' ),
				'singular_name' => __( 'Event' ),
				'add_new' => _x( 'Add New', 'event' ),
				'add_new_item' => __( 'Event' ),
				'edit-item' => __( 'Edit event' ),
				'new_item' => __( 'New event' ),
				'all_items' => __( 'All events' ),
				'view_item' => __( 'View events' ),
				'search_items' => __( 'Search' ),
				'not_found' => __( 'No matches found' ),
				'not_found_in_trash' => __( 'No results' ),
				'parent_item_colon' => '',
				'menu_name' => 'Events'
			),
		'public' => true,
		'rewrite' => true,
		'has_archive' => false,
		'exclude_from_search' => true,
		'menu_position' => 13,
 		'supports' => array('title','editor','thumbnail')
		)
	);
}

// Custom metaboxes
function cmb_sample_metaboxes( array $meta_boxes ) {
	$prefix = '_cmb_';
	$meta_boxes[] = array(
		'id'         => 'event_details',
		'title'      => 'Event detail(s)',
		'pages'      => array( 'event', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => false, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Date',
				'desc' => 'Event Date',
				'id'   => $prefix . 'event_date',
				'type' => 'text_date',
			),
			array(
				'name' => 'Time',
				'desc' => 'Event time',
				'id'   => $prefix . 'event_time',
				'type' => 'text_small',
			),
			array(
				'name' => 'Registration',
				'desc' => 'Registration fee',
				'id'   => $prefix . 'registration_fee',
				'type' => 'text_small',
			),
			array(
				'name' => 'Sponsor',
				'desc' => 'Event sponsor',
				'id'   => $prefix . 'event_sponsor',
				'type' => 'text_medium',
			),
			array(
				'name' => 'Weblink',
				'desc' => 'Sponsor link',
				'id'   => $prefix . 'weblink',
				'type' => 'text_medium',
			),
		)
	);
	// Add other metaboxes as needed
	return $meta_boxes;
}
function cmb_initialize_cmb_meta_boxes() {
	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'inc/metabox/init.php';
}

// SITE FUNCTIONS -------------------------------------------------------

// Filetype support
function custom_upload_mimes($existing_mimes=array()) {
	$existing_mimes['webm'] = 'mime/type';
	return $existing_mimes;
}

// Disable WordPress version 
function remove_generators() {
	return '';
}

// Remove thumbnail dimensions
function remove_thumbnail_dimensions($html) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom callback to list comments
function custom_comments($comment, $args, $depth) {
  	$GLOBALS['comment'] = $comment;
    $GLOBALS['comment_depth'] = $depth; ?>
    <li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
        <div class="comment-author vcard"><?php commenter_link() ?></div>
        <div class="comment-meta"><?php printf(__('Posted %1$s at %2$s <span class="meta-sep">|</span> <a href="%3$s" title="Read complete comment">Read</a>','Foundation'),
        	get_comment_date(),
            get_comment_time(),
            '#comment-'.get_comment_ID() );
            edit_comment_link(__('Edit','Foundation'),' <span class="meta-sep">|</span> <span class="edit-link">','</span>'); ?>
        </div>
  		<?php if ($comment->comment_approved == '0') _e("\t\t\t\t\t<span class='unapproved'>Your comment is awaiting moderation.</span>\n",'Foundation') ?>
        	<div class="comment-content">
            	<?php comment_text() ?>
        	</div>
        <?php if($args['type'] == 'all' || get_comment_type() == 'comment') :
        	comment_reply_link(array_merge($args, array(
        		'reply_text' => __('Reply','Foundation'),
                'login_text' => __('Log in to reply.','Foundation'),
               	'depth' => $depth,
                'before' => '<div class="comment-reply-link">',
                'after' => '</div>'
            )));
        endif;
}

// Custom callback to list pings
function custom_pings($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
    <li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
    	<div class="comment-author"><?php printf(__('By %1$s on %2$s at %3$s', 'Foundation'),
        	get_comment_author_link(),
            get_comment_date(),
            get_comment_time() );
            edit_comment_link(__('Edit', 'Foundation'),' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?>
        </div>
    	<?php if ($comment->comment_approved == '0') : 
			_e('\t\t\t\t\t<span class="unapproved">Your trackback is awaiting moderation.</span>\n','Foundation') ?>
            <div class="comment-content">
                <?php comment_text() ?>
            </div>
        <?php endif;
} 

// Comment avatar 
function commenter_link() {
    $commenter = get_comment_author_link();
    if (ereg('<a[^>]* class=[^>]+>', $commenter)) {
        $commenter = ereg_replace('(<a[^>]* class=[\'"]?)','\\1url ',$commenter);
    } else {
        $commenter = ereg_replace('(<a )/','\\1class="url "',$commenter);
    }
    $avatar_email = get_comment_author_email();
    $avatar = str_replace("class='avatar","class='photo avatar",get_avatar($avatar_email, 35));
    echo $avatar.' <span class="fn n">'.$commenter.'</span>';
}


// ACTIONS, FILTERS, SUPPORT -----------------------------------------------------

// Add Support
add_theme_support('post-formats',array('aside','image','link','quote','status')); // Add post format support
add_theme_support('post-thumbnails'); // Add thumbnail support
add_theme_support('automatic-feed-links'); // Add feed support


// Add Actions
add_action('wp_dashboard_setup', 'wpc_dashboard_widgets'); // Hide select dashboard panels
add_action( 'admin_menu', 'edit_admin_menus' ); // Build custom "Pages" admin menu
add_action( 'wp_enqueue_scripts', 'load_foundation_css', 0 ); // Add CSS to site
add_action( 'wp_enqueue_scripts', 'load_foundation_scripts', 0 ); // Add javascript to site
add_action('init','foundation_menus'); // Create nav menu positions
add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 ); // Custom metaboxes


// Remove Actions


// Add Filters
add_filter('logout_url', 'custom_logout_home', 10, 2); // Log out redirect
add_filter('the_generator','remove_generators'); // Hide WordPress
add_filter( 'cmb_meta_boxes', 'cmb_sample_metaboxes' ); // Custom metaboxes
add_filter('upload_mimes', 'custom_upload_mimes'); // Filetype support
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images



// SHORTCODES -----------------------------------------------------------
include('inc/shortcodes.php');
?>