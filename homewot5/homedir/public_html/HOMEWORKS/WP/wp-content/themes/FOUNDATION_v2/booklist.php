<?php
/*
Template Name: Booklist
*/
?>

<?php get_header(); ?>
<div class="row booklist">
	<article class="seven columns offset-by-one">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
	<?php endwhile; endif; ?>
	</article>
	<aside class="four columns">
		<img id="schoolyard" src="<?php bloginfo( 'template_url' ); ?>/images/STUDENT.jpg">
		<div id="help-box">
		<?php $page_id = 1767; 
		$page_data = get_page( $page_id ); 
		$content = apply_filters('the_content', $page_data->post_content); 
		$title = $page_data->post_title; 
			echo $content;
		?>
			<form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
				<input type="text" value="" name="s" id="s" placeholder="Search by keyword">
				<input type="submit" id="searchsubmit" value="L" class="button">
			</form>
		</div>
	</aside>	
</div>
<?php get_footer(); ?>