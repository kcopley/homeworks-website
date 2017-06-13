</section>

<footer>	

	<section class="row">

		<div class="five columns offset-by-one">

			<div class="row">

				<nav class="four columns">

				<?php wp_nav_menu( array( 'container' => 'false', 'theme_location' => 'lfooternavigation' ) ); ?>

				</nav>

				<nav class="eight columns end">

				<?php wp_nav_menu( array( 'container' => 'false', 'theme_location' => 'rfooternavigation' ) ); ?>

				</nav>

			</div>

		</div>

		<div class="six columns">

		<?php $page_id = 34; 

		$page_data = get_page( $page_id ); 

		$content = apply_filters('the_content', $page_data->post_content); 

		$title = $page_data->post_title; 

			echo "<h6>".$title."</h6>";

			echo $content;

		?>

		<p id="copyright"><a href="https://www.facebook.com/homeworksforbooks" target="_blank"><span class="socialmedia">f</span> Find us on Facebook</a> &copy;<?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>

		</div>

	</section>

</footer>



<script src="<?php bloginfo('template_directory'); ?>/javascripts/foundation.js"></script>

<script src='<?php bloginfo('template_directory'); ?>/javascripts/jquery.mobile.customized.min.js'></script>

<script src='<?php bloginfo('template_directory'); ?>/javascripts/jquery.easing.1.3.js'></script> 

<script src='<?php bloginfo('template_directory'); ?>/javascripts/camera.js'></script> 

<script src="<?php bloginfo('template_directory'); ?>/javascripts/app.js"></script>



<?php wp_footer(); ?>

</body>

</html>