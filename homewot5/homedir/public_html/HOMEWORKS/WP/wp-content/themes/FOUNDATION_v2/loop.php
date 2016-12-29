<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if (!have_posts()) : ?>
	<section class="notice">
		<p class="bottom">Sorry, no results found.</p>
	</section>
	<?php get_search_form(); ?>	
<?php endif; ?>

<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
			<section class="header">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<time pubdate datetime="<?php the_time('c'); ?>"><?php printf( __('Posted on %s at %s.','roots'), get_the_time('l, F jS, Y'), get_the_time()) ?></time>
				<p>Written by <?php the_author(); ?></p>
			</section>
	<?php if (is_archive() || is_search()) : // Only display excerpts for archives and search ?>
			<?php the_excerpt(); ?>
	<?php else : ?>
			<?php the_content(); ?>
	<?php endif; ?>
			<section class="footer">
				<?php $tag = get_the_tags(); if (!$tag) { } else { ?><p><?php the_tags(); ?></p><?php } ?>
			</section>
		<?php comments_template('', true); ?>
<?php endwhile; // End the loop ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ($wp_query->max_num_pages > 1) : ?>
	<nav id="post-nav">
		<div class="post-previous">&larr; Older posts</div>
		<div class="post-next">Newer posts &rarr;</div>
	</nav>
<?php endif; ?>
