<?php
/**
 * Template for displaying 404 pages
 */
get_header(); ?>
	<div class="row">
		<article id="content" class="large-8 columns" role="main">
			<h1 class="entry-title">404 Error</h1>
            <h5>This is somewhat embarrassing, isn&rsquo;t it?</h5>
			<p>Apologies, but the page you are looking for might have been removed or is temporarily unavailable. Please try any of the following:</p>
			<ul class="disc"> 
				<li>Check the spelling of the web address</li>
				<li>Perhaps searching by keyword will help?</li>
				<li>Return to the <a href="<?php bloginfo('url'); ?>">home page</a></li>
				<li><a href="javascript:history.back()">Go back</a> to the previous page</li>
			</ul>
            <p>Or perhaps searching by a specific keyword will yield better results.</p>
			<?php get_search_form(); ?>
		</article>
		<aside class="sidebar large-4 columns">
			<?php dynamic_sidebar('right_sidebar'); ?>
		</aside>
	</div>
<?php get_footer(); ?>