<?php

/*

Template Name: Sitemap

*/

?>



<?php get_header(); ?>

<div class="row">

<article class="seven columns offset-by-one">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<h1><?php the_title(); ?></h1>

	<?php the_content(); ?>

<?php endwhile; endif; ?>

	<div class="row">

		<div class="six columns">

			<h4>Main</h4>

			<?php wp_nav_menu( array( 'container' => 'false', 'theme_location' => 'sitemap' ) ); ?>

			<hr />

			<h4>Featured Resources</h4>

			<ul>

			<?php

			$args = array( 'numberposts' => -1, 'post_type' => 'bookstore', 'cat' => 152  );

			$postslist = get_posts( $args );

			foreach ($postslist as $post) :  setup_postdata($post); ?> 

				<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>

			<?php endforeach; ?>

			</ul>

			<hr/>

			<h4>Brand-new</h4>

			<ul>

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

					),

					array(

						'key' => '_cmb_resource_online',

						'value' => 2,

					)

				)

			);

			$postslist = get_posts( $args );

			foreach ($postslist as $post) :  setup_postdata($post); ?> 

				<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>

			<?php endforeach; ?>

			</ul>

		</div>

		<div class="six columns indent">

			<ul>

			<?php $args = array(

				'orderby' => 'name',

				'hide_empty' => 1,

				'exclude' => '1,152',

				'title_li' => '<h4>' . __('Book Categories') . '</h4>',

				'depth'  => 1

			); 

			wp_list_categories($args); ?> 

			</ul>

		</div>

	</div>

</article>

<aside class="four columns">

	<?php $page_id = 57; 

	$page_data = get_page( $page_id ); 

	$content = apply_filters('the_content', $page_data->post_content); 

	$title = $page_data->post_title; 

		echo $content;

	?>

</aside>	

</div>

<?php get_footer(); ?>