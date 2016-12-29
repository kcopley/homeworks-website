<?php
/**
 * Template for displaying search results
 */
get_header(); ?>	
	<div class="row">
		<article id="content" class="large-8 columns" role="main">
			<?php if (have_posts()) : ?>
				<h1 class="page-title"><?php printf(__('Search Results for: %s','foundation'),'<span>'.get_search_query().'</span>'); ?></h1>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part('content',get_post_format()); ?>
				<?php endwhile; ?>
			<?php else : ?>
				<h1 class="entry-title">No results found</h1>
				<p>Apologies, but we could not find what you were looking for. Perhaps searching by a specific keyword will yield better results.</p>
				<?php get_search_form(); ?>
			<?php endif; ?>
		</article>
		<aside class="sidebar large-4 columns">
			<?php dynamic_sidebar('right_sidebar'); ?>
		</aside>
	</div>
<?php get_footer(); ?>