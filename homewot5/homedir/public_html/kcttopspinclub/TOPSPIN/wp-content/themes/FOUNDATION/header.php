<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
    
	<!-- Set the viewport width to device width for mobile -->
    <meta name="viewport" content="initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<?php if (is_search()) { ?>
	<meta name="robots" content="noindex, nofollow" /> 
<?php } ?>
	<title><?php bloginfo('name'); ?> | <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
    <meta name="author" content="<?php $user_info = get_userdata(1); $first_name = $user_info->first_name; $last_name = $user_info->last_name; echo "$first_name $last_name"; ?>">
	<meta name="Copyright" content="Copyright <?php echo date('Y'); ?>. All rights reserved.">    
	
    
    <!-- Dublin Core Metadata -->
	<meta name="DC.title" content="<?php bloginfo('name'); ?>">
	<meta name="DC.subject" content="<?php bloginfo('description'); ?>">
	<meta name="DC.creator" content="<?php $user_info = get_userdata(1); $first_name = $user_info->first_name; $last_name = $user_info->last_name; echo "$first_name $last_name"; ?>">
      
	<!-- Favicons -->    
    	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico">
	<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-touch-icon-114x114.png" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    
<!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
  <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
  <?php wp_head(); ?>
<script type="text/javascript">
jQuery(document).ready(function(){
	$window = jQuery(window);
   	jQuery('div[data-type="parallax"]').each(function(){
     		var $bgobj = jQuery(this); 
      		jQuery(window).scroll(function() {
			var yPos = -($window.scrollTop() / $bgobj.data('speed')); 
			var xPos = $bgobj.css('backgroundPosition').split(' ')[0]; 
			var coords = xPos + yPos + 'px';
			$bgobj.css({ backgroundPosition: coords });
		});
	});
	jQuery(window).scroll(function () {
   		jQuery('[class^="fadeout"]').each(function () { 
      			if ((jQuery(this).offset().top - jQuery(window).scrollTop()) < 200) { 
          			jQuery(this).stop().fadeTo(100, 0);
      			} else {
          			jQuery(this).stop().fadeTo('fast', 1);
      			}
  		});
	});
});
</script>

</head>
<body <?php body_class(); ?> id="wrapper">
<a href="#" data-reveal-id="login"><img id="login-square" src="<?php bloginfo( 'template_url' ); ?>/img/misc/LOGINSQUARE.gif"></a>
<section id="login" class="reveal-modal" data-reveal>
	<div class="row">
		<div class="large-8 columns">
		<?php if ( is_user_logged_in() ) { ?>
			<h2>Login</h2>
			<p>You are already logged in. <a href="<?php bloginfo( 'url' ); ?>/wp-admin">Click here</a> to access the admin section.</p>
		<?php } else { ?>
			<h2>Login</h2>
			<p>Please enter your username and password</p>
		<?php wp_login_form(); ?>
		<?php } ?>
		</div>
	</div>
</section>
    <header class="header">
	<div class="row">
		<div id="logo" class="small-5 small-centered columns">
    			<a href="#"><img src="<?php echo get_template_directory_uri(); ?>/img/LOGO.png"></a>
		</div>
	</div>
	<div class="row">
		<div id="navigation" class="small-6 small-centered columns">
         		<?php wp_nav_menu(array(
            			'theme_location' => 'main-menu-left',
              			'container' => false,
 				'walker' => new scroll_walker()
          		)); ?>
		</div>
	</div>
    </header>
    <section class="section expand">