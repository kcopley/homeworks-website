<?php
/**
 * Template for displaying author pages
 */
get_header(); ?>
	<div class="row">
		<article id="content" class="large-8 columns" role="main">
		<?php if (have_posts()) : ?>
			<?php the_post(); ?>
			<h1 class="archive-title"><?php printf(__( 'Author archives: %s','foundation'),'<span class="vcard"><a class="url fn n" href="'.esc_url(get_author_posts_url( get_the_author_meta("ID"))).'" title="'.esc_attr(get_the_author()).'" rel="me">'.get_the_author().'</a></span>'); ?></h1>
			<?php rewind_posts(); ?>
			<?php foundation_content_nav('nav-above'); ?>
			<?php if (get_the_author_meta('description')) : ?>
				<div class="author-info">
					<div class="author-avatar">
						<?php echo get_avatar(get_the_author_meta('user_email'), apply_filters('foundation_author_bio_avatar_size',75)); ?>
					</div>
					<div class="author-description">
						<h2><?php printf(__('About %s','foundation'),get_the_author()); ?></h2>
						<p><?php the_author_meta('description'); ?></p>
					</div>
				</div>
			<?php endif; ?>
			<?php while (have_posts()) : the_post(); ?>
				<?php get_template_part('content',get_post_format()); ?>
			<?php endwhile; ?>
			<?php foundation_content_nav( 'nav-below' ); ?>
		<?php else : ?>
			<?php get_template_part('content','none'); ?>
		<?php endif; ?>
		</article>
		<aside class="sidebar large-4 columns">
			<?php dynamic_sidebar('right_sidebar'); ?>
		</aside>
	</div>
<?php get_footer(); ?>