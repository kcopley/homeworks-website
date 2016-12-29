<?php
if(isset($_POST['submitted'])) {
	if ($_POST['leaveblank'] != '' or $_POST['dontchange'] != 'http://') {

	} else {
		if(trim($_POST['contactName']) === '') {
			$nameError = '*Please enter your name';
			$hasError = true;
		} else {
			$name = trim($_POST['contactName']);
		}

		if(trim($_POST['contactEmail']) === '')  {
			$emailError = '*Valid e-mail address required';
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['contactEmail']))) {
			$emailError = 'You entered an invalid email address.';
			$hasError = true;
		} else {
			$email = trim($_POST['contactEmail']);
		}

		if(trim($_POST['contactPhone1']) === '')  {
			$phoneError = ' ';
			$hasError = true;
		} else {
			$phone1 = trim($_POST['contactPhone1']);
		}

		if(trim($_POST['contactPhone2']) === '')  {
			$phoneError = ' ';
			$hasError = true;
		} else {
			$phone2 = trim($_POST['contactPhone2']);
		}

		if(trim($_POST['contactPhone3']) === '')  {
			$phoneError = ' ';
			$hasError = true;
		} else {
			$phone3 = trim($_POST['contactPhone3']);
		}

		if(trim($_POST['studentCount']) === '') {
			$commentError = '*Student name(s)?';
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$students = stripslashes(trim($_POST['studentCount']));
				$class = trim($_POST['className']);
			} else {
				$students = trim($_POST['studentCount']);
				$class = trim($_POST['className']);
			}
		}

		if(!isset($hasError)) {
				$emailSuccess = "Success";
				$emailTo = get_option('customerservice');

				$subject = 'Enrollment via Web form from '.$name;
				$body = "Name: $name \n\nEmail: $email \nPhone: ($phone1) $phone2-$phone3 \n\nClass: $class \n\n-----Student(s):------------------------ \n$students";
				$headers = 'From: '.$name.' <'.$email.'>' . "\r\n" . 'Reply-To: ' . $email;

				mail($emailTo, $subject, $body, $headers);
				$emailSent = true;
		}
	}
} ?>
<?php get_header(); ?>
<article class="row">
	<section id="classdetail" class="eight columns">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="row">
			<h2><?php the_title(); ?></h2>
			<hr />
			<div class="row">
			<div class="six columns">
				<p><?php global $post; echo get_post_meta($post->ID, '_cmb_class_details', true) ?></p>
				<h6 class="price"><small>Enrollment fee:</small> <br /><?php global $post; echo get_post_meta($post->ID, '_cmb_class_price', true) ?> <span style="font-size: .75em; text-transform: none;">per student</span></h6>
				<?php global $post; $notes = get_post_meta($post->ID, '_cmb_class_special', true);
					if ($notes) { ?>
						<p><?php echo $notes; ?> </p>
					<?php } ?>
				<?php if ($active == 'Y') { ?>
				<form id="cart" method="POST" action="<?php bloginfo('url'); ?>/bookbag" name="cart">
                           		<input type="hidden" name="product_id" value="<?php the_ID(); ?>">
                           		<input type="hidden" name="quantity" value="1">
                           		<input type="hidden" name="action" value="add">
					<input name="Purchase" type="submit" value="Add to cart"/>
                           	</form>
				<?php } ?>

				<?php $weblink = get_post_meta($post->ID, '_cmb_weblink', true);
				if ($weblink) { ?>
					<p>More information/registration at <a href="http://<?php echo $weblink; ?>"><?php echo $weblink; ?></a></p>
				<?php }; ?>
			</div>
			<div id="classtimes" class="five columns offset-by-one">
				<h5>Class dates:</h5>
				<p>
				<?php 
				$class1 = get_post_meta($post->ID, '_cmb_class_date', true);
				$class2 = get_post_meta($post->ID, '_cmb_class_date2', true);
				$class3 = get_post_meta($post->ID, '_cmb_class_date3', true);
				$class4 = get_post_meta($post->ID, '_cmb_class_date4', true);
				$class5 = get_post_meta($post->ID, '_cmb_class_date5', true);
				$class6 = get_post_meta($post->ID, '_cmb_class_date6', true);
				$class7 = get_post_meta($post->ID, '_cmb_class_date7', true);
				$class8 = get_post_meta($post->ID, '_cmb_class_date8', true);
				$class9 = get_post_meta($post->ID, '_cmb_class_date9', true);
				$class10 = get_post_meta($post->ID, '_cmb_class_date10', true);
				$class11 = get_post_meta($post->ID, '_cmb_class_date11', true);
				$class12 = get_post_meta($post->ID, '_cmb_class_date12', true);
				$class13 = get_post_meta($post->ID, '_cmb_class_date13', true);
				$class14 = get_post_meta($post->ID, '_cmb_class_date14', true);
				$class15 = get_post_meta($post->ID, '_cmb_class_date15', true);
				$class16 = get_post_meta($post->ID, '_cmb_class_date16', true);
				$class17 = get_post_meta($post->ID, '_cmb_class_date17', true);
				$class18 = get_post_meta($post->ID, '_cmb_class_date18', true);
				$class19 = get_post_meta($post->ID, '_cmb_class_date19', true);
				$class20 = get_post_meta($post->ID, '_cmb_class_date20', true);
				$class21 = get_post_meta($post->ID, '_cmb_class_date21', true);
				$class22 = get_post_meta($post->ID, '_cmb_class_date22', true);
				$class23 = get_post_meta($post->ID, '_cmb_class_date23', true);
				$class24 = get_post_meta($post->ID, '_cmb_class_date24', true);
				$class25 = get_post_meta($post->ID, '_cmb_class_date25', true);
				$class26 = get_post_meta($post->ID, '_cmb_class_date26', true);
				$class27 = get_post_meta($post->ID, '_cmb_class_date27', true);
				$class28 = get_post_meta($post->ID, '_cmb_class_date28', true);
				$class29 = get_post_meta($post->ID, '_cmb_class_date29', true);
				$class30 = get_post_meta($post->ID, '_cmb_class_date30', true);
				$class31 = get_post_meta($post->ID, '_cmb_class_date31', true);
				$class32 = get_post_meta($post->ID, '_cmb_class_date32', true);
				$class33 = get_post_meta($post->ID, '_cmb_class_date33', true);
				$class34 = get_post_meta($post->ID, '_cmb_class_date34', true);
				$class35 = get_post_meta($post->ID, '_cmb_class_date35', true);

				$range = array();
				if ($class1){array_push($range, $class1);};
				if ($class2){array_push($range, $class2);};
				if ($class3){array_push($range, $class3);};
				if ($class4){array_push($range, $class4);};
				if ($class5){array_push($range, $class5);};
				if ($class6){array_push($range, $class6);};
				if ($class7){array_push($range, $class7);};
				if ($class8){array_push($range, $class8);};
				if ($class9){array_push($range, $class9);};
				if ($class10){array_push($range, $class10);};
				if ($class11){array_push($range, $class11);};
				if ($class12){array_push($range, $class12);}; 
				if ($class13){array_push($range, $class13);}; 
				if ($class14){array_push($range, $class14);}; 
				if ($class15){array_push($range, $class15);}; 
				if ($class16){array_push($range, $class16);}; 
				if ($class17){array_push($range, $class17);}; 
				if ($class18){array_push($range, $class18);}; 
				if ($class19){array_push($range, $class19);}; 
				if ($class20){array_push($range, $class20);}; 
				if ($class21){array_push($range, $class21);}; 
				if ($class22){array_push($range, $class22);}; 
				if ($class23){array_push($range, $class23);}; 
				if ($class24){array_push($range, $class24);}; 
				if ($class25){array_push($range, $class25);}; 
				if ($class26){array_push($range, $class26);}; 
				if ($class27){array_push($range, $class27);}; 
				if ($class28){array_push($range, $class28);}; 
				if ($class29){array_push($range, $class29);}; 
				if ($class30){array_push($range, $class30);}; 
				if ($class31){array_push($range, $class31);}; 
				if ($class32){array_push($range, $class32);}; 
				if ($class33){array_push($range, $class33);}; 
				if ($class34){array_push($range, $class34);}; 
				if ($class35){array_push($range, $class35);}; 
				
				foreach($range as $class){ ?>
    	 				<strong><?php echo $class; ?></strong> @<?php global $post; echo get_post_meta($post->ID, '_cmb_class_time', true) ?><br />
				<?php }; ?>
				</p>
				<hr />
				<h5 id="instructor">Instructor:</h5>
				<p id="instructor"><strong><?php global $post; echo get_post_meta($post->ID, '_cmb_class_instructor', true) ?></strong></p>
			</div>
		</div>
			</div>
		</div>
	<?php endwhile; endif; ?>
	</section>
	<aside class="four columns left-divider">
		<h5>UPCOMING CLASSES <br />the week of <?php echo date('F j'); ?>:</h5>
		<ul>
		<?php 
		$start = date('m/d/Y');
		$end = date('m/d/Y', strtotime('+6 day'));
		$querydetails = "SELECT wposts.*
   			FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
   			WHERE wposts.ID = wpostmeta.post_id
   			AND (wpostmeta.meta_key = '_cmb_class_date'
   			OR wpostmeta.meta_key = '_cmb_class_date2'
   			OR wpostmeta.meta_key = '_cmb_class_date3'
   			OR wpostmeta.meta_key = '_cmb_class_date4'
   			OR wpostmeta.meta_key = '_cmb_class_date5'
   			OR wpostmeta.meta_key = '_cmb_class_date6'
   			OR wpostmeta.meta_key = '_cmb_class_date7'
   			OR wpostmeta.meta_key = '_cmb_class_date8'
   			OR wpostmeta.meta_key = '_cmb_class_date9'
   			OR wpostmeta.meta_key = '_cmb_class_date10'
   			OR wpostmeta.meta_key = '_cmb_class_date11'
   			OR wpostmeta.meta_key = '_cmb_class_date12'
   			OR wpostmeta.meta_key = '_cmb_class_date13'
   			OR wpostmeta.meta_key = '_cmb_class_date14'
   			OR wpostmeta.meta_key = '_cmb_class_date15'
   			OR wpostmeta.meta_key = '_cmb_class_date16'
   			OR wpostmeta.meta_key = '_cmb_class_date17'
   			OR wpostmeta.meta_key = '_cmb_class_date18'
   			OR wpostmeta.meta_key = '_cmb_class_date19'
   			OR wpostmeta.meta_key = '_cmb_class_date20'
   			OR wpostmeta.meta_key = '_cmb_class_date21'
   			OR wpostmeta.meta_key = '_cmb_class_date22'
   			OR wpostmeta.meta_key = '_cmb_class_date23'
   			OR wpostmeta.meta_key = '_cmb_class_date24'
   			OR wpostmeta.meta_key = '_cmb_class_date25'
   			OR wpostmeta.meta_key = '_cmb_class_date26'
   			OR wpostmeta.meta_key = '_cmb_class_date27'
   			OR wpostmeta.meta_key = '_cmb_class_date28'
   			OR wpostmeta.meta_key = '_cmb_class_date29'
   			OR wpostmeta.meta_key = '_cmb_class_date30'
   			OR wpostmeta.meta_key = '_cmb_class_date31'
   			OR wpostmeta.meta_key = '_cmb_class_date32'
   			OR wpostmeta.meta_key = '_cmb_class_date33'
   			OR wpostmeta.meta_key = '_cmb_class_date34'
   			OR wpostmeta.meta_key = '_cmb_class_date35')
   			AND wpostmeta.meta_value BETWEEN '".$start."'AND '".$end."'
   			AND wposts.post_status = 'publish'
   			AND wposts.post_type = 'classes'
   			ORDER BY wposts.post_date DESC
 		";
		$pageposts = $wpdb->get_results($querydetails, OBJECT); ?>
		<?php if ($pageposts){
 			foreach ($pageposts as $post):setup_postdata($post); ?>
				<li class="row">
					<div class="ten columns end">
						<h6><?php the_title(); ?></h6>
						<p><a href="<?php the_permalink(); ?>">See details</a></p>
					</div>
				</li>
		<?php endforeach;
		} else { ?>
			<h6>No classes available</h6>
			<p><a href="<?php bloginfo('url'); ?>/classes">View calendar</a></p>
		<?php }; ?>
		</ul>
	</aside>
</article>
<?php get_footer(); ?>