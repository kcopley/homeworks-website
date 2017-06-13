<!DOCTYPE html>

<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->

<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->

<!--[if IE 8]>    <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->

<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->

<head>

<?php if (is_page(array('library','bookbag','checkout','order','response')) or is_category() or get_post_type($post) == 'bookstore')  { 

	$enabled=get_option('ecommerce');

	if ($enabled=="true") { 

 		echo "<meta http-equiv=\"refresh\" content=\"0;URL=https://www.homeworksforbooks.com/HOMEWORKS/WP/offline\">";

	}

} ?>

	<meta charset="<?php bloginfo('charset'); ?>" />

    

	<!-- Set the viewport width to device width for mobile -->

	<!-- <meta name="viewport" content="width=device-width" /> -->

    

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    

<?php if (is_search()) { ?>

	<meta name="robots" content="noindex, nofollow" /> 

<?php } ?>

<?php if (is_page('order')) { ?>

	<meta name="robots" content="noindex, nofollow" /> 

<?php } ?>

<?php if (is_page('response')) { ?>

	<meta name="robots" content="noindex, nofollow" /> 

<?php } ?>

	<meta name="title" content="<?php bloginfo('name'); ?>">

	

	<meta name="google-site-verification" content="Google ID here">

	<meta name="author" content="Author name">

	<meta name="Copyright" content="Copyright <?php echo date('Y'); ?>. All rights reserved.">

    

    <!-- Dublin Core Metadata -->

	<meta name="DC.title" content="<?php bloginfo('name'); ?>">

	<meta name="DC.subject" content="<?php bloginfo('description'); ?>">

	<meta name="DC.creator" content="Jason Sprenger">



	<!-- Favicons -->    

    <link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.ico">

	<link rel="apple-touch-icon" href="<?php bloginfo('template_directory'); ?>/images/apple-touch-icon.png">

	<link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo('template_directory'); ?>/images/apple-touch-icon-72x72.png" />

	<link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('template_directory'); ?>/images/apple-touch-icon-114x114.png" />



	<title><?php bloginfo('name'); ?><?php wp_title('|'); ?></title>

    

	<!-- Included CSS Files -->

	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/stylesheets/foundation.css">

    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/stylesheets/camera.css"> 

    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">



	<!--[if lt IE 9]>

    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/stylesheets/ie.css">

	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>

    <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>

	<![endif]-->



	<script src="<?php bloginfo('template_directory'); ?>/javascripts/selectivizr-min.js"></script>

	<script src="<?php bloginfo('template_directory'); ?>/javascripts/respond.min.js"></script>



	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

	<?php wp_head(); ?>

    

</head>

<body <?php body_class(); ?>>

<div id="fb-root"></div>

<script>(function(d, s, id) {

  var js, fjs = d.getElementsByTagName(s)[0];

  if (d.getElementById(id)) return;

  js = d.createElement(s); js.id = id;

  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";

  fjs.parentNode.insertBefore(js, fjs);

}(document, 'script', 'facebook-jssdk'));</script>



<a href="#" data-reveal-id="login"><img id="login-square" src="<?php bloginfo( 'template_url' ); ?>/images/misc/LOGINSQUARE.gif"></a>

<section id="login" class="reveal-modal">

	<?php if ( is_user_logged_in() ) { ?>

		<h2>Login</h2>

		<p>You are already logged in. <a href="<?php bloginfo( 'url' ); ?>/wp-admin">Click here</a> to access the admin section.</p>

	<?php } else { ?>

		<h2>Login</h2>

		<p>Please enter your username and password</p>

	<?php wp_login_form(); ?>

	<?php } ?>

</section>

<div id="bkgd">&nbsp;</div>

<header>
	<nav>
            <div class="row" style="height: 3em;">
                <a href="<?php bloginfo( 'url' ); ?>"><img id="logo" src="<?php bloginfo( 'template_url' ); ?>/images/LOGO.png"></a>
                <?php 
                    wp_nav_menu( array( 'container' => 'false', 'theme_location' => 'mainnavigation' ) ); 
                ?>
                <br>
                
            </div>
            <div>
                <div class="column" style="width: 62%"></div>
                <div class="column">
                    <?php 
                        get_search_form();
                    ?>
                </div>
                
            </div>
	</nav>
</header>

<section id="wrapper" class="row">