<?php get_header(); ?>

	<article class="eight columns">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<h1>Latest Posts</h1>

    	<?php get_template_part('loop', 'index'); ?>

		<?php endwhile; endif; ?>

	</article>

	<aside class="four columns">

		<?php get_sidebar(); ?>

	</aside>

<?php get_footer(); ?>