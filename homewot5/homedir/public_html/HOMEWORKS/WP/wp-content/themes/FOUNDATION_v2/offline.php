<?php
/*
Template Name: Offline
*/
?>

<?php get_header(); ?>
<div class="row">
<article class="seven columns offset-by-one">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<h1><?php the_title(); ?></h1>
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
</div>
<hr />
<div class="row">
	<div class="three columns offset-by-two">
		<?php $upload_dir = wp_upload_dir(); ?>
		<img src="<?php echo $upload_dir['baseurl']; ?>/HOMEWORKS1.jpg" />
	</div>
	<div class="three columns">
		<?php $upload_dir = wp_upload_dir(); ?>
		<img src="<?php echo $upload_dir['baseurl']; ?>/HOMEWORKS3.jpg" />
	</div>
	<div class="three columns end">
		<?php $upload_dir = wp_upload_dir(); ?>
		<img src="<?php echo $upload_dir['baseurl']; ?>/HOMEWORKS2.jpg" />
	</div>
</div>
<?php get_footer(); ?>