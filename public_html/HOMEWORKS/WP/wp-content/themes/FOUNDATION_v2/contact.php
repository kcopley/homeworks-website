<?php

/*

Template Name: Contact

*/

?>

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

		} else {

			$phone1 = trim($_POST['contactPhone1']);

		}



		if(trim($_POST['contactPhone2']) === '')  {

			$phoneError = ' ';

		} else {

			$phone2 = trim($_POST['contactPhone2']);

		}



		if(trim($_POST['contactPhone3']) === '')  {

			$phoneError = ' ';

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

				$emailSuccess = "Success";

				$emailTo = 'jacopley45@gmail.com';



				$subject = 'Contact via Homeworks for Books web site';

				$body = 'Name: '.$name.'<br />Email: '.$email.'<br />Phone: ('.$phone1.') '.$phone2.'-'.$phone3.'<br /><br />-----Message:------------------------ <br />'.$comments.' ';



				$headers  = 'MIME-Version: 1.0' . "\r\n";

				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

				$headers .= 'From: '.$name.' <'.$email.'>' . "\r\n" . 'Reply-To: ' . $email;



				mail($emailTo, $subject, $body, $headers);

				$emailSent = true;

		}

	}

} ?>

<?php get_header(); ?>

<div class="row">

	<article class="seven columns offset-by-one">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<h1><?php the_title(); ?></h1>

		<?php if($emailSuccess=='Success') 

			echo "<h3>Thank you! Your message has been received.</h3>";

		else



		?>

		<?php the_content(); ?>

	<?php endwhile; endif; ?>

		<form action="<?php the_permalink(); ?>" id="contactForm" method="post">

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

			<button class="button" type="submit">Contact</button>

			<input type="hidden" name="submitted" id="submitted" value="true" />

			<label><input type="text" class="hidden" name="leaveblank"></label>

			<label><input type="text" class="hidden" name="dontchange" value="http://" ></label>

		</form>

</article>

<aside class="four columns">

	<div id="addressbox">

	<?php $page_id = 61; 

	$page_data = get_page( $page_id ); 

	$content = apply_filters('the_content', $page_data->post_content); 

	$title = $page_data->post_title; 

		echo $content;

	?>

	</div>

</aside>	

</div>

<?php get_footer(); ?>