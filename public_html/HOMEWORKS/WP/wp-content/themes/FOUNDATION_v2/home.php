<?php

/*

Template Name: Home

*/

?>



<?php get_header(); ?>

<article class="seven columns offset-by-one">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<?php the_content(); ?>

<?php endwhile; endif; ?>

</article>

<aside class="four columns">

<?php $page_id = 32; 

$page_data = get_page( $page_id ); 

$content = apply_filters('the_content', $page_data->post_content); 

$title = $page_data->post_title; 

	echo $content;

?>

</aside>

<?php get_footer(); ?>