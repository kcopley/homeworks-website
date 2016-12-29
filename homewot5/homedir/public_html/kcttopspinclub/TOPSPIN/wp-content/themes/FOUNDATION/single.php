<?php
/**
 * Template for displaying all single posts (blogging only)
 */
get_header(); ?>
	<div class="row">
		<article id="content" class="large-8 columns" role="main">
			<?php while (have_posts()) : the_post(); ?>
				<?php get_template_part('content',get_post_format()); ?>
			    <?php comments_template('', true); ?>
			<?php endwhile; ?>
		</article>
		<aside class="sidebar large-4 columns">
			<?php dynamic_sidebar('right_sidebar'); ?>
		</aside>
	</div>
<?php get_footer(); ?>