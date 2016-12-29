<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
		<section class="header">
			<h1><?php the_title(); ?></h1>
			<time class="updated" datetime="<?php the_time('c'); ?>" pubdate><?php printf(__('Posted on %s at %s.', 'roots'), get_the_time('l, F jS, Y'),get_the_time())?></time>
			<p>Written by <?php the_author(); ?></p>
		</section>
		<?php the_content(); ?>
		<section id="footer">
			<?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>' )); ?>
			<p><?php the_tags(); ?></p>
		</section>
		<?php comments_template(); ?>
<?php endwhile; // End the loop ?>
