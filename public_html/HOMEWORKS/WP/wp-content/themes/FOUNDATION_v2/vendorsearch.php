<?php

/*

Template Name: Publishers

*/

?>



<?php get_header(); ?>

<div class="row">

	<article class="seven columns offset-by-one">

	<?php $publisher = $_REQUEST['p']; ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<h1>Other resources by <br /> <span><?php echo $publisher; ?></span></h1>			

		<?php the_content(); ?>

	<?php endwhile; endif; ?>

<?php $args = array(

	'numberposts' => -1, 

	'order'=> 'ASC', 

	'orderby' => 'date', 

	'post_type' => 'bookstore',

	'paged' => $paged,

	'meta_query' => array('relation' => 'AND',

		array(

		'key' => '_cmb_resource_publisher',

		'value' => get_search_query(),

		'compare' => 'LIKE'

		),

		array(

		'key' => '_cmb_resource_available',

		'value' => 2,

		'compare' => '='

		),

		array(

			'key' => '_cmb_resource_online',

			'value' => 2,

			'compare' => '='

		),

	)

);

	$postslist = get_posts( $args );

	foreach ($postslist as $post) : setup_postdata($post); ?> 

		<div class="row">

			<div class="three columns">

			<?php if ( has_post_thumbnail() ) {

				the_post_thumbnail('book-thumbnail');

			} else { ?>

				<img src="<?php bloginfo( 'template_url' ); ?>/images/noimage.gif">

			<?php } ?> 

			</div>

			<div class="nine columns">

				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

				<p>Retail price: <strike><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_MSRP', true) ?></strike><br />

				<strong>Price: <span class="price"><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_price', true) ?></span></strong></p>

				<a class="button" href="<?php the_permalink(); ?>">See details</a>

			</div>

		</div>

	<?php endforeach; ?>



	<?php global $wp_query;

	$infinite = 999999999; // unlikely pagination

	echo paginate_links( array(

		'base' => str_replace( $infinite, '%#%', esc_url( get_pagenum_link( $infinite ) ) ),

		'format' => '?paged=%#%',

		'current' => max( 1, get_query_var('paged') ),

		'total' => $wp_query->max_num_pages

	));

	?>

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

<div class="row">

	<div class="three columns offset-by-two">



	</div>

	<div class="three columns">



	</div>

	<div class="three columns end">



	</div>

</div>

<?php get_footer(); ?>