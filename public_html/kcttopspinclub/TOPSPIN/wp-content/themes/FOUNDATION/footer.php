	</section>
	<footer class="footer section">
		<div class="row">
		<?php if (has_nav_menu('footer-menu')) { ?>
			<div class="large-7 columns">

			</div>
			<?php wp_nav_menu(array('theme_location'=>'footer-menu','menu_class' =>'inline-list','container' =>'nav','container_class'=>'large-5 columns')); ?>
		<?php } ?>
		</div>
		<h4><span><i class="fa fa-phone"></i>913-269-5078&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-phone"></i>913-488-8001</span><br />Kansas City TTOPSpin Club&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;Table Tennis of Overland Park&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;9034 Metcalf, Overland Park, KS 66212</h4>
        	<p id="copyright">&copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>	
	</footer>
<?php wp_footer(); ?>
</body>
</html>