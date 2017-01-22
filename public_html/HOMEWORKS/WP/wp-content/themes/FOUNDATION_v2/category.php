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

		if ( get_query_var( 'paged' ) ) { $paged = get_query_var( 'paged' ); }
		elseif ( get_query_var( 'page' ) ) { $paged = get_query_var( 'page' ); }
		else { $paged = 1; }

		$args = array(
			'posts_per_page' => 15,
			'paged' => $paged,
			'post_type' => 'bookstore',
			'order' => 'ASC',
			'orderby' => 'title',
			'cat' => $ct,
			'meta_query' => array(
				array(
					'key' => '_cmb_resource_available',
					'value' => 2,
					'compare' => '='
				),

				array(
					'key' => '_cmb_resource_online',
					'value' => 2,
					'compare' => '='
				)
			)
		);
		$query = new WP_Query($args);
		while ($query->have_posts()):
			$query->the_post();
			global $post;
			$id = $post->ID;
			?>
			<li>
				<?php
				if ( has_post_thumbnail($id) ) {
					the_post_thumbnail();
				}
				else { ?>
					<img src="<?php bloginfo( 'template_url' ); ?>/images/noimage.gif">
				<?php } ?>

				<h4><?php echo get_the_title($id); ?></h4>

				<p><?php
					global $post;
					$str = get_post_meta($id, '_cmb_resource_price', true);
					$str = floatval($str);
					if ($str) {
						echo '$' . number_format($str, 2);
					}
					else {
						echo 'No Price Listed';
					}
				?></p>

				<a class="button" href="<?php the_permalink(); ?>">Details</a>
			</li> <?php
		endwhile;
?>
		</ul>



		<?php global

		$wp_query;
		$infinite = 999999999; // unlikely pagination
		echo paginate_links( array(

			//'base' => str_replace( $infinite, '%#%', esc_url( get_pagenum_link( $infinite ) ) ),

			'format' => '?page=%#%',

			'current' => max( 1, get_query_var('page') ),

			'total' => $query->max_num_pages

		));

		?>

	</section>

</article>

<?php get_footer(); ?>