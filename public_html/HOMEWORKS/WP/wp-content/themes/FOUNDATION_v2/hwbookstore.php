<?php

/*

Template Name: Library

*/

?>



<?php get_header(); ?>

<article id="Bookstore" class="row">

	<aside class="three columns offset-by-one">

		<?php get_sidebar(); ?>

	</aside>

	<section class="eight columns">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<h1><?php the_title(); ?></h1>

	<hr />

	<div class="row">

		<div id="NEW-BOOKS" class="six columns">

			<h2>Brand-new in <?php echo date("F"); ?></h2>
			<script>
				jQuery(function(){
					jQuery('#slideshow').camera({
						fx: 'simpleFade',
						height: '350px',
						playPause: false,
						pagination: false,
						navigation: false,
						time: 15000,
						loader: 'none',
						imagePath: 'images/'
					});
				});
			</script>

			<div id="slideshow">

			<?php

			$args = array(
				'numberposts' => 5,
				'order'=> 'ASC',
				'orderby' => 'date',
				'post_type' => 'bookstore',
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
			$postslist = get_posts( $args );

			foreach ($postslist as $post) :  setup_postdata($post); ?>
				<div data-thumb="<?php bloginfo('template_url'); ?>/images/TRANSPARENT.png" data-src="<?php bloginfo('template_url'); ?>/images/TRANSPARENT.png">
                			<div id="<?php the_ID(); ?>" class="fadeIn row">
						<div class="WN-SLIDE eleven columns">
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p class="price"><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_price', true) ?></p>
							<a href="<?php the_permalink(); ?>" class="button">View details</a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>

			</div>

		<?php the_content(); ?>

		</div>

		<div class="six columns">

			<div id="conferences">

			<?php $page_id = 32; 

			$page_data = get_page( $page_id ); 

			$content = apply_filters('the_content', $page_data->post_content); 

			$title = $page_data->post_title; 

				echo $content;

			?>

			</div>

		</div>

	</div>

	<?php endwhile; endif; ?>

		<hr />

		<h5>FEATURED RESOURCES</h5>

		<ul class="block-grid four-up">

		<?php

		$args = array(
			'numberposts' => -1,
			'post_type' =>
			'bookstore',
			'cat' => 152,
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


		$postslist = get_posts( $args );

		foreach ($postslist as $post) :  setup_postdata($post); ?> 

			<li>

			<?php if ( has_post_thumbnail() ) {

				the_post_thumbnail('book-thumbnail');

			} else { ?>

				<img src="<?php bloginfo( 'template_url' ); ?>/images/noimage.gif">

			<?php } ?> 

				<h6><?php the_title(); ?></h6>

				<p><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_price', true) ?><br />

				<a href="<?php the_permalink(); ?>">See details</a></p>

			</li>

		<?php endforeach; ?>

		</ul>

	</section>

</article>

<?php get_footer(); ?>