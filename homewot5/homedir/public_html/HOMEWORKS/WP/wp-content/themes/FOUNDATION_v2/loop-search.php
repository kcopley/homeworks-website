<?php $type = $_REQUEST["type"];
$s = $_REQUEST["s"];

if ($type == 'publisher') { ?>
	<h1>Other resources by <br /> <span><?php echo get_search_query(); ?></span></h1>				
<?php } else { ?>
	<h1>Search Results for <br /> <span>"<?php echo get_search_query(); ?>"</span></h1>				
<?php } ?> 

<?php if (!have_posts()) : ?>
	<h3>Sorry, no results were found.</H3>
	<p>Yes, it can happen. However we're sure you'll find what you are looking for with a different search word &hellip;</p>
	<?php get_search_form(); ?>	
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>
	<?php if ('bookstore' == get_post_type()) { ?>
	<div class="row">
		<?php 
		if ($search_query == 'publisher') { ?>
			<div class="three columns">
			<?php if ( has_post_thumbnail() ) {
				the_post_thumbnail();
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
		<?php } else {  ?>
			<div class="three columns">
			<?php if ( has_post_thumbnail() ) {
				the_post_thumbnail();
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
		<?php } ?> 
	</div>
	<?php } else { ?>
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<?php the_excerpt(); ?>
	<?php $tag = get_the_tags(); if (!$tag) { } else { ?><p><?php the_tags(); ?></p><?php } ?>
	<a class="button" href="<?php the_permalink(); ?>">Read more</a>
	<?php } ?>
	<hr />
<?php endwhile; // End the loop ?>