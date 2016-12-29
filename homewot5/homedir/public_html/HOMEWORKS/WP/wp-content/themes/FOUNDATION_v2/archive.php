<?php get_header(); ?>
	<article class="eight columns">
		<h1><?php single_cat_title(); ?></h1>
		<?php get_template_part('loop', 'category'); ?>
	</article>
	<aside class="four columns">
		<?php get_sidebar(); ?>
	</aside>
<?php get_footer(); ?>