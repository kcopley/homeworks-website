<?php get_header(); ?>
<article class="row">
	<h1 class="offset-by-one">Search Results for <br /> <span>"<?php echo get_search_query(); ?>"</span></h1>
	<div class="seven columns">
	<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$largs = array(
		's' => get_search_query(), 
		'posts_per_page' => -1, 
		'order'=> 'ASC', 
		'orderby' => 'title', 
		'post_type' => 'bookstore',
		'paged' => $paged,
		'meta_query' => array(
			array(
			'key' => '_cmb_resource_available',
			'value' => "Active",
			'compare' => '='
			),
		)
	);
	$lpostslist = new WP_Query( $largs );
	$count = $lpostslist->post_count;
	if ( $lpostslist->have_posts() ):
		echo "<h4 class='offset-by-one'>(".$count,") resources</h4>";
   		while ( $lpostslist->have_posts() ) :
        	$lpostslist->the_post(); ?>
			<div class="row">
				<div class="three columns">
				<?php if ( has_post_thumbnail() ) {
					the_post_thumbnail();
				} else { ?>
					<img src="<?php bloginfo( 'template_url' ); ?>/images/noimage.gif">
				<?php } ?> 
				</div>
				<div class="nine columns">
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p>Publisher: <?php global $post; echo get_post_meta($post->ID, '_cmb_resource_publisher', true) ?><br />
					Retail price: <strike><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_MSRP', true) ?></strike><br />
					<strong>Price: <span class="price"><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_price', true) ?></span></strong></p>
					<p><?php $tag = get_the_tags(); if (!$tag) { } else { ?><p><?php the_tags(); ?></p><?php } ?></p>
					<a class="button" href="<?php the_permalink(); ?>">See details</a>
				</div>
			</div>
    			<?php endwhile;
			else: ?>
			<div class="eleven columns offset-by-one">
				<h3>Sorry, no results/additional resources were found.</H3>
				<p>Yes, it can happen. However we're sure you'll find what you are looking for with a different search word &hellip;</p>
				<p><br /><?php get_search_form(); ?></p>
			</div>	
			<?php endif;
			wp_reset_query(); ?>
	</div>
	<div id="publisher-list" class="four columns">
		<h2>Books by "<?php echo get_search_query(); ?>"</h2>
	<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$pargs = array(
		'posts_per_page' => -1, 
		'order'=> 'ASC', 
		'orderby' => 'title', 
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
			'value' => "Active",
			'compare' => '='
			),
		)
	);
	$ppostslist = new WP_Query( $pargs );
   		while ( $ppostslist->have_posts() ) :
        	$ppostslist->the_post(); ?>
		<div class="row">
			<div class="eleven columns">
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<p>Publisher: <?php global $post; echo get_post_meta($post->ID, '_cmb_resource_publisher', true) ?><br />
				Retail price: <strike><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_MSRP', true) ?></strike><br />
				<strong>Price: <span class="price"><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_price', true) ?></span></strong></p>
			</div>
		</div>
    		<?php endwhile;
		wp_reset_query(); ?>
	<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$pargs = array(
		'posts_per_page' => -1, 
		'order'=> 'ASC', 
		'orderby' => 'title', 
		'post_type' => 'bookstore',
		'paged' => $paged,
		'meta_query' => array('relation' => 'AND',
			array(
			'key' => '_cmb_resource_variation',
			'value' => get_search_query(),
			'compare' => 'LIKE'
			),
			array(
			'key' => '_cmb_resource_available',
			'value' => "Active",
			'compare' => '='
			),
		)
	);
	$ppostslist = new WP_Query( $pargs );
   		while ( $ppostslist->have_posts() ) :
        	$ppostslist->the_post(); ?>
		<div class="row">
			<div class="eleven columns">
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<p>Publisher: <?php global $post; echo get_post_meta($post->ID, '_cmb_resource_publisher', true) ?><br />
				Retail price: <strike><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_MSRP', true) ?></strike><br />
				<strong>Price: <span class="price"><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_price', true) ?></span></strong></p>
			</div>
		</div>
    		<?php endwhile;
		wp_reset_query(); ?>
	</div>
</article>
<?php get_footer(); ?>