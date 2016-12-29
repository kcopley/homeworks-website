<?php
/**
 * Template for displaying category pages
 */
get_header(); ?>
	<div class="row">
		<article id="content" class="large-8 columns" role="main">
			<?php if (have_posts()) : ?>
					<h1 class="archive-title"><?php printf(__('Category: %s','foundation'),'<span>'.single_cat_title('',false).'</span>'); ?></h1>
				<?php if (category_description()) : // Show an optional category description ?>
					<div class="archive-meta"><?php echo category_description(); ?></div>
				<?php endif; ?>
			<?php while (have_posts()) : the_post(); ?>
				<?php get_template_part('content',get_post_format()); ?>
			<?php endwhile;
			if (function_exists("emm_paginate")) {
			    emm_paginate();
			} ?>
			<?php else : ?>
				<?php get_template_part('content','none'); ?>
			<?php endif; ?>
		</article>
		<aside class="sidebar large-4 columns">
			<?php dynamic_sidebar('right_sidebar'); ?>
		</aside>
	</div>
<?php get_footer(); ?>