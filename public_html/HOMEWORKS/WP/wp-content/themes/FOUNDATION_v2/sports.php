<?php

/*

Template Name: Sports

*/

?>

<?php get_header(); ?>

<div class="row">

<article class="eleven columns offset-by-one">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<h1><?php the_title(); ?></h1>

	<hr />

	<div class="row">

		<?php the_content(); ?>

	</div>

<?php endwhile; endif; ?>

</article>

</div>

<?php get_footer(); ?>