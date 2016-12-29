<?php get_header(); ?>
<article class="row">
	<aside class="three columns offset-by-one">
	<h4>Book Categories</h4>
	<?php
	$categoryList = array();
	foreach((get_the_category()) as $category) {
		$categoryList[] = $category->cat_ID;
	}?>
		<ul id="library">
		<?php wp_list_categories("exclude=1,152&title_li=&include=" . join(",", $categoryList)); ?>
		</ul>
	</aside>
	<section id="bookdetail" class="eight columns top-off">
		<h2><?php single_cat_title(); ?></h2>
		<hr />
		<ul class="block-grid three-up">
		<?php
		$ct = get_query_var('cat');
		$paged = (get_query_var('page')) ? get_query_var('page') : 1;
		$args = array( 'posts_per_page' => -1, 'paged' => $paged, 'post_type' => 'bookstore', 'cat' => $ct, 'meta_value' => Active );
		query_posts( $args );
		if (have_posts()) : while (have_posts()) : the_post(); ?>
			<li>
			<?php if ( has_post_thumbnail() ) {
				the_post_thumbnail();
			} else { ?>
				<img src="<?php bloginfo( 'template_url' ); ?>/images/noimage.gif">
			<?php } ?> 
				<h4><?php the_title(); ?></h4>
				<p><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_price', true) ?></p>
				<a class="button" href="<?php the_permalink(); ?>">Details</a>
			</li>
		<?php endwhile; ?>
		<?php endif; ?>
		</ul>

		<?php global $wp_query;
		$infinite = 999999999; // unlikely pagination
		echo paginate_links( array(
			'base' => str_replace( $infinite, '%#%', esc_url( get_pagenum_link( $infinite ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages
		));
		?>
	</section>
</article>
<?php get_footer(); ?>