
<?php get_header();
$path = $_SERVER['DOCUMENT_ROOT'];
if (get_option('offline-mode') == 1) {
    include($path.'/homeworks/public_html/HOMEWORKS/WP/wp-content/plugins/sales-console/includes.php');
}
else {
    include($path.'/HOMEWORKS/WP/wp-content/plugins/sales-console/includes.php');
}
?>

<?php

global $post;

$active= get_post_meta($post->ID, '_cmb_resource_online', true);

if ($active==1) { ?>

<article class="row">
	<aside class="three columns offset-by-one">
		<?php get_sidebar(); ?>
	</aside>

	<section id="bookdetail" class="eight columns top-off">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="row">

			<?php seobreadcrumbs(); ?>

			<div class="four columns">

			<?php if ( has_post_thumbnail() ) {

				the_post_thumbnail('book-image');

			} else { ?>

				<img src="<?php bloginfo( 'template_url' ); ?>/images/noimage.gif">

			<?php } ?> 

			</div>

			<div class="seven columns end">

				<h2><?php the_title(); ?></h2>

				<h4>is unavailable</h4>

				<hr />

				<p><strong>Want to try searching for a similar book?</strong><br />

				At Home Works we have over 10,000+ resources available in our library. Try typing a keyword in the search field below and see what comes up—we're sure you'll find something similar to <em><?php the_title(); ?></em>.</p>

				<form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
					<input type="text" value="" name="s" id="s" placeholder="Search by keyword">
					<input type="submit" id="searchsubmit" value="L" class="button">
				</form>
			</div>
		</div>

	<?php endwhile; endif; ?>
	</section>

</article>

<?php } else { ?>

<article class="row">

	<aside class="three columns offset-by-one">

		<?php get_sidebar(); ?>

	</aside>

	<section id="bookdetail" class="eight columns top-off">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="row">

			<?php seobreadcrumbs(); ?>

			<div class="four columns">

			<?php if ( has_post_thumbnail() ) {
				the_post_thumbnail('book-image');
			} else { ?>
				<img src="<?php bloginfo( 'template_url' ); ?>/images/noimage.gif">
			<?php 
			} 
			?> 
			</div>

			<div class="seven columns end">
				<h2><?php the_title(); ?></h2>
				<p>
				<?php
					global $post;
					$publisher = get_post_meta($post->ID, '_cmb_resource_publisher', true);
					if ($publisher != '') {
						echo '<strong>Publisher:</strong> ';
						echo $publisher;
					}
				?>
				<?php
					global $post;
					$author = get_post_meta($post->ID, '_cmb_resource_author', true);
					if ($author != '') {
						echo '<br /><strong>Author:</strong> ';
						echo $author;
					}
				?>
				<?php
					global $post;
					$condition = get_post_meta($post->ID, '_cmb_resource_condition', true);
					if ($condition != '') {
						echo '<br /><strong>Condition:</strong> ';
						if ($condition == 1) {
							echo 'Used';
						}
						if ($condition == 2) {
							echo 'New';
						}
					}
				?>
				<br /><strong>ISBN:</strong> 
				
				<?php 
					global $post;
					$skuid = get_post_meta($post->ID, '_cmb_resource_isbn', true);
					//if ($skuid == '') $skuid = rtrim(get_post_meta($post->ID, '_cmb_resource_u-sku', true), "U");
					if ($skuid == '') $skuid = "No ISBN listed for this book.";
					echo  $skuid; 
				?></p>

				<div class="row">

					<div class="six columns">

						<?php 

						global $post;

						$count = get_post_meta($post->ID, '_cmb_resource_available', true);

						if ($count == 2) { ?>

							<p>Retail price: <strike><?php global $post; echo '$'.number_format(get_post_meta($post->ID, '_cmb_resource_MSRP', true), 2) ?></strike><br />

							<strong>Price: <span class="price"><?php global $post; echo '$'.number_format(get_post_meta($post->ID, '_cmb_resource_price', true), 2) ?></span></strong></p>

							<p><strong><?php global $post; echo Book::get_consigner_count($post->ID) ?> IN STOCK</strong></p>

							<div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="false" data-width="200" data-show-faces="false" data-font="lucida grande"></div>

					</div>

					<div class="six columns">

						<form id="cart" method="POST" action="<?php bloginfo('url'); ?>/bookbag" name="cart">

                           				<input type="hidden" name="product_id" value="<?php the_ID(); ?>">

                           				<input type="hidden" name="quantity" value="1">

                           				<input type="hidden" name="action" value="add">

							<input name="Purchase" type="submit" value="Add to cart"/>

                           			</form>

					</div>

						<?php } else { ?>

							<p>List price: <strike><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_MSRP', true) ?></strike><br />

							<strong>Price: <span class="price"><?php global $post; echo get_post_meta($post->ID, '_cmb_resource_price', true) ?></span></strong></p>

							<p><strong>UNAVAILABLE</strong></p>

					</div>

						<?php } ?> 

				</div>

			<?php if( empty( $post->post_content) ) { ?>



			<?php } else { ?>

				<h5>Details</h5>

				<?php the_content(); ?>	

			<?php } ?>

			</div>

		</div>

	<?php endwhile; endif; ?>

		<hr />

		<h5>FEATURED RESOURCES</h5>

		<ul class="block-grid four-up">

		<?php

		$args = array( 'numberposts' => -1, 'post_type' => 'bookstore', 'cat' => 152  );

		$postslist = get_posts( $args );

		foreach ($postslist as $post) :  setup_postdata($post); ?> 

			<li>

			<?php if ( has_post_thumbnail() ) {

				the_post_thumbnail('book-image');

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

<?php } ?>

<?php get_footer(); ?>