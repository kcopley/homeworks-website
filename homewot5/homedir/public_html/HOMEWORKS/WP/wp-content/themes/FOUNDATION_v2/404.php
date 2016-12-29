<?php get_header(); ?>
	<article class="eight columns">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>			
		<h1><?php _e('File Not Found', 'roots'); ?></h1>
		<p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable. Please try the following:</p>
		<ul> 
			<li>Check your spelling</li>
			<li>Return to the <a href="<?php bloginfo('url'); ?>">home page</a></li>
			<li>Click the <a href="javascript:history.back()">Back</a> button</li>
		</ul>
	</article>
	<aside class="four columns">
		<?php get_sidebar(); ?>
	</aside>
<?php get_footer(); ?>

