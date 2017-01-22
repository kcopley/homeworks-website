<?php get_header(); ?>

<article class="eight columns">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<h1><?php the_title(); ?></h1>

	<?php the_content(); ?>

        <?php wp_link_pages(array('before' => 'Pages: ', 'next_or_number' => 'number')); ?>

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