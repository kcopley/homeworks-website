<?php
/**
 * Template for displaying all default pages
 */
get_header(); ?>
	<div class="row">
		<div class="large-8 columns">
			<h1 class="page-title"><?php the_title(); ?></h1>
		</div>
	</div>
	<div id="strip">
		<div class="row">
			<article id="content" class="large-8 columns" role="main">
				<?php while (have_posts()) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			</article>
			<div class="large-3 large-offset-1 columns ldivider">
				<p>Bunch of text here</p>
			</div>
		</div>
	</div>
<?php get_footer(); ?>