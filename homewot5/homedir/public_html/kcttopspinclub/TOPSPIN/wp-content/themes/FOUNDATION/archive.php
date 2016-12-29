<?php
/**
 * Template for displaying archive pages
 */
get_header(); ?>
	<div class="row">
		<article id="content" class="large-8 columns" role="main">
			<?php if (have_posts()) : ?>
				<h1 class="archive-title"><?php
				if (is_day()) :
					printf( __('Daily archives: %s','foundation'),'<span>'. get_the_date().'</span>');
					elseif (is_month()) :
						printf( __('Monthly Archives: %s','foundation'),'<span>'.get_the_date(_x('F Y','monthly archives date format','foundation')).'</span>');
					elseif (is_year()) :
						printf( __('Yearly Archives: %s','foundation'),'<span>'.get_the_date(_x('Y','yearly archives date format','foundation')).'</span>');
					else :
						_e('Archives','foundation');
				endif;
				?></h1>
				<?php while (have_posts()) : the_post(); ?>
					<?php get_template_part('content',get_post_format()); ?>
				<?php endwhile; ?>
			<?php else : ?>
					<?php get_template_part('content', 'none'); ?>
			<?php endif; ?>
		</article>
		<aside class="sidebar large-4 columns">
			<?php dynamic_sidebar('right_sidebar'); ?>
		</aside>
	</div>
<?php get_footer(); ?>