<?php
/**
 * Template Name: Foundation: Full-width
 */
get_header(); ?>
	<div class="row">
		<article id="content" class="large-12 columns" role="main">
			<?php while (have_posts()) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php the_content(); ?>
				</div>
				<?php comments_template('',true); ?>
			<?php endwhile; ?>
		</article>
	</div>
<?php get_footer(); ?>