<?php
/**
 * Template for displaying post status
 */
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<h1><?php the_author(); ?></h1>
	<h2><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(sprintf(__('Permalink to %s','foundation'), the_title_attribute('echo=0'))); ?>" rel="bookmark"><?php echo get_the_date(); ?></a></h2>
	<?php echo get_avatar(get_the_author_meta('ID'), apply_filters('foundation_status_avatar','48')); ?>
	<div class="entry-content">
		<?php the_content(__('Continue reading <span class="meta-nav">&rarr;</span>','foundation')); ?>
	</div>
	<div class="entry-meta">
	<?php if (comments_open()) : ?>
		<div class="comments-link">
			<?php comments_popup_link('<span class="leave-reply">'.__('Leave a reply','foundation').'</span>',__('1 Reply','foundation'),__('% Replies','foundation')); ?>
		</div>
	<?php endif; ?>
</div>