<?php
/**
 * Template for displaying content for both single and index/archive/search results
 */
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if (is_single()) : ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
	<?php else : ?>
    	<div class="entry-content">
			<h2 class="entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(sprintf( __('Permalink to %s','foundation'),the_title_attribute('echo=0'))); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	<?php endif; // is_single() ?>
	<?php if (is_search()) : ?>
			<?php the_excerpt(); ?>
        </div>
	<?php else : ?>
		<?php the_content( __('Continue reading <span class="meta-nav">&rarr;</span>','foundation')); ?>
		<?php wp_link_pages( array('before'=>'<div class="page-links">'. __('Pages:','foundation'),'after'=>'</div>')); ?>
	<?php endif; ?>
	<div class="entry-meta">
		<?php foundation_entry_meta(); ?>
	</div>
</div>
