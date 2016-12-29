<?php
/**
 * Main template file (required)
 */
get_header(); ?>
	<div class="row">
		<article id="content" class="large-8 columns" role="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php get_template_part('content',get_post_format()); ?>
			<?php endwhile; ?>
			<?php else : ?>
				<h1 class="entry-title">No results</h1>
				<p>Apologies, but we could not find what you were looking for. Perhaps searching by a specific keyword will yield better results.</p>
				<?php get_search_form(); ?>
			<?php endif; ?>
			<?php // Pagination
				if (function_exists("foundation_paginate")) {
			    foundation_paginate();
			} ?>
		</article>
		<aside class="sidebar large-4 columns">
			<?php dynamic_sidebar('right_sidebar'); ?>
		</aside>
	</div>
<?php get_footer(); ?>