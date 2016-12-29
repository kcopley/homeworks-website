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

		if(trim($_POST['email']) === '')  {
			$emailError = '*Valid e-mail address required';
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$emailError = 'You entered an invalid email address.';
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
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

		if(trim($_POST['comments']) === '') {
			$commentError = '*Please complete your message';
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(trim($_POST['comments']));
			} else {
				$comments = trim($_POST['comments']);
			}
		}

		if(!isset($hasError)) {
				$emailSuccess = "Thank you for your message, we will be contacting you soon.";
				$emailTo = "homeworksforbooks@sbcglobal.net";

				$subject = 'Contact via Web form from '.$name;
				$body = "Name: $name \n\nEmail: $email \nPhone: ($phone1) $phone2-$phone3 \n\n-----Message:------------------------ \n$comments";
				$headers = 'From: '.$name.' <'.$email.'>' . "\r\n" . 'Reply-To: ' . $email;

				mail($emailTo, $subject, $body, $headers);
				$emailSent = true;
		}
	}
} ?>
<?php
/**
 * The template for displaying the home page
 */
get_header(); ?>
	<div id="Home" class="parallax" data-type="parallax" data-speed="10">
		<div class="row">
			<div id="content" class="small-7 small-offset-1 columns transparent" role="main">
			<?php while (have_posts()) : the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile; ?>
			</div>
			<div class="small-4 columns">

			</div>
		</div>
	</div>
	<div id="Schedule" class="parallax" data-type="parallax" data-speed="10">
		<div class="row">
			<div id="content" class="small-8 small-offset-2 columns transparent" role="main">
			<?php $page_id = 7; 
				$page_data = get_page( $page_id ); 
				$content = apply_filters('the_content', $page_data->post_content); 
				$title = $page_data->post_title; 
				echo $content;
			?>
			<hr />
			<h2>Upcoming tournaments & events</h2>
			<table>
				<thead>
				<tr>
				<th>Event</th>
				<th>Date</th>
				<th>Cost</th>
				<th>Sponsor</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$args = array( 'numberposts' => -1, 'post_type' => 'event');
				$postslist = get_posts( $args );
				foreach ($postslist as $post) :  setup_postdata($post); ?> 
					<tr>
					<td><strong><?php the_title(); ?></strong></td>
					<?php $time = get_post_meta($post->ID, '_cmb_event_time', true); ?>
					<td><?php global $post; echo get_post_meta($post->ID, '_cmb_event_date', true) ?> 
 					<?php if ($time) { ?>
						@<?php global $post; echo get_post_meta($post->ID, '_cmb_event_time', true) ?>
					<?php } ?> 
					</td>
					<td class="accent">
					<?php $fee = get_post_meta($post->ID, '_cmb_registration_fee', true); ?>
 					<?php if ($fee) { ?>
						$<?php global $post; echo get_post_meta($post->ID, '_cmb_registration_fee', true) ?>
					<?php } ?> 
					</td>
					<td><a href="<?php global $post; echo get_post_meta($post->ID, '_cmb_weblink', true) ?>" target="_blank"><?php global $post; echo get_post_meta($post->ID, '_cmb_event_sponsor', true) ?></a></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
	<div id="Location" class="parallax" data-type="parallax" data-speed="15">
		<div class="row">
			<div id="content" class="small-10 small-offset-1 columns transparent" role="main">
				<div class="row">
					<div class="small-6 columns">
					<?php $page_id = 5; 
						$page_data = get_page( $page_id ); 
						$content = apply_filters('the_content', $page_data->post_content); 
						$title = $page_data->post_title; 
						echo $content;
					?>
					</div>
					<div class="small-6 columns">
						<?php $upload_dir = wp_upload_dir(); ?>
						<img src="<?php echo $upload_dir['baseurl']; ?>/location.gif" />
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="Contact" class="parallax" data-type="parallax" data-speed="20">
		<div class="row">
			<div id="content" class="small-10 small-offset-1 columns transparent" role="main">
			<?php if($emailSuccess != '') { ?>
				<h1>Contact us</h1>
				<h3 class="accent"><?php echo $emailSuccess; ?></h3>
			<?php } else { ?>
			<?php $page_id = 9; 
				$page_data = get_page( $page_id ); 
				$content = apply_filters('the_content', $page_data->post_content); 
				$title = $page_data->post_title; 
				echo $content;
			?>
			<?php } ?>
			<form action="<?php bloginfo('url'); ?>/#Contact" id="contactForm" method="post">
				<label for="contactName">Name:</label>
				<?php if($nameError != '') { ?>
					<em class="error"><?=$nameError;?></em>
				<?php } ?>
				<input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="required requiredField" />

				<label for="email">Email:</label>
				<?php if($emailError != '') { ?>
					<em class="error"><?=$emailError;?></em>
				<?php } ?>
				<input type="text" name="email" id="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="required requiredField email" />

				<label for="contactPhone">Phone #:</label>
				<?php if($phoneError != '') { ?>
					<em class="error"><?=$phoneError;?></em>
				<?php } ?>
				<input type="text" name="contactPhone1" id="contactPhone1" size="3" maxlength="3" value="<?php if(isset($_POST['contactPhone1'])) echo $_POST['contactPhone1'];?>"/><input type="text" name="contactPhone2" id="contactPhone2" size="3" maxlength="3" value="<?php if(isset($_POST['contactPhone2'])) echo $_POST['contactPhone2'];?>"/><input type="text" name="contactPhone3" id="contactPhone3" size="4" maxlength="4" value="<?php if(isset($_POST['contactPhone3'])) echo $_POST['contactPhone3'];?>"/>

				<label for="commentsText">Message:</label>
				<textarea name="comments" id="commentsText" rows="10" class="required requiredField"></textarea>
				<button class="button" type="submit">Submit message</button>
				<input type="hidden" name="submitted" id="submitted" value="true" />
				<label><input type="text" class="hidden" name="leaveblank"></label>
				<label><input type="text" class="hidden" name="dontchange" value="http://" ></label>
			</form>
			</div>
		</div>
	</div>
<?php get_footer(); ?>