<?php

/*

Template Name: Classes

*/

?>

<?php function draw_calendar($month,$year){

	/* draw table */

	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';



	/* table headings */

	$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');

	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';



	/* days and weeks vars now ... */

	$running_day = date('w',mktime(0,0,0,$month,1,$year));

	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));

	$days_in_this_week = 1;

	$day_counter = 0;

	$dates_array = array();



	/* row for week one */

	$calendar.= '<tr class="calendar-row">';



	/* print "blank" days until the first of the current week */

	for($x = 0; $x < $running_day; $x++):

		$calendar.= '<td class="calendar-day-np"> </td>';

		$days_in_this_week++;

	endfor;

	for($list_day = 1; $list_day <= $days_in_month; $list_day++):

		$calendar.= '<td class="calendar-day">';

			$calendar.= '<div class="day-number">'.$list_day.'</div>';

			global $post; 

			$classdate = date("m/d/Y", strtotime($month."/".$list_day."/".$year));

			$args = array( 

				'numberposts' => -1, 

				'order'=> 'ASC', 

				'orderby' => 'date', 

				'post_type' => 'classes'

			);

			$postslist = get_posts( $args );

			foreach ($postslist as $post) :  setup_postdata($post); 

				$classid = get_the_ID();

				$classname = get_the_title();

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

				if (in_array($classdate, $range)) {

					$calendar.= '<p><a href="#'.$classid.'" data-reveal-id="'.$classid.'">'.$classname.'</a></p>';

				};

			endforeach;

			$calendar.= str_repeat('<p> </p>',2);

		$calendar.= '</td>';

		if($running_day == 6):

			$calendar.= '</tr>';

			if(($day_counter+1) != $days_in_month):

				$calendar.= '<tr class="calendar-row">';

			endif;

			$running_day = -1;

			$days_in_this_week = 0;

		endif;

		$days_in_this_week++; $running_day++; $day_counter++;

	endfor;

	if($days_in_this_week < 8):

		for($x = 1; $x <= (8 - $days_in_this_week); $x++):

			$calendar.= '<td class="calendar-day-np"> </td>';

		endfor;

	endif;

	$calendar.= '</tr>';

	$calendar.= '</table>';

	return $calendar;

}; ?>



<?php get_header(); ?>

<script>

	function printContent(div_id){

		var DocumentContainer = document.getElementById(div_id);

		var html = '<html><head></head><body style="background:#ffffff;">'+DocumentContainer.innerHTML+'</body></html>';

	

		var WindowObject = window.open("","PrintWindow","width=800,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");

		WindowObject.document.writeln(html);

		WindowObject.document.close();

		WindowObject.focus();

		WindowObject.print();

		document.getElementById('print_link').style.display='block';

	}



	jQuery(document).ready(function(){  

		jQuery('#class-month').submit(function(e) { 

			e.preventDefault();

    			jQuery.ajax({ 

        			data: jQuery(this).serialize(),

        			type: jQuery(this).attr('method'),

        			url: "https://www.homeworksforbooks.com/HOMEWORKS/WP/wp-content/themes/FOUNDATION_v2/calendar.php",

        			success: function(response) { 

            				jQuery('#calendar').html(response);

        			}

    			});

		});

	});  

</script>  

<div id="toPrint">

	<h2 class="hidden">Upcoming Home Works classes:</h2>

	<?php 

	$start = date('m/d/Y');

	$edate = date("Y-m-01");

	$end = strtotime ('+2 month' , strtotime ($edate));



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

	$pageposts = $wpdb->get_results($querydetails, OBJECT);

	$no_duplicates = array_intersect_key( $pageposts , array_unique( array_map('serialize' , $pageposts ) ) );		if ($no_duplicates){

 		foreach ($no_duplicates as $post):setup_postdata($post); ?>

			<div id="<?php the_ID(); ?>" class="class-description reveal-modal">

				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

				<p><?php echo get_post_meta($post->ID, '_cmb_class_details', true); ?></p>

				<p><strong>Class dates:</strong><br />

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

				$class24 = get_post_meta($post->ID, '_cmb_class_date25', true);

				$class24 = get_post_meta($post->ID, '_cmb_class_date26', true);

				$class24 = get_post_meta($post->ID, '_cmb_class_date27', true);

				$class24 = get_post_meta($post->ID, '_cmb_class_date28', true);

				$class24 = get_post_meta($post->ID, '_cmb_class_date29', true);

				$class24 = get_post_meta($post->ID, '_cmb_class_date30', true);

				$class24 = get_post_meta($post->ID, '_cmb_class_date31', true);

				$class24 = get_post_meta($post->ID, '_cmb_class_date32', true);

				$class24 = get_post_meta($post->ID, '_cmb_class_date33', true);

				$class24 = get_post_meta($post->ID, '_cmb_class_date34', true);

				$class24 = get_post_meta($post->ID, '_cmb_class_date35', true);



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

    	 				<?php echo $class; ?> @<?php global $post; echo get_post_meta($post->ID, '_cmb_class_time', true) ?><br />

				<?php }; ?>

				</p>

				<a href="<?php the_permalink(); ?>" class="button">More details/register</a>

			</div>

			<hr class="hidden"/>

		<?php endforeach;

	} else { 



	}; ?>

</div>

<div class="row">

<article class="eleven columns offset-by-one">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<h1>Classes/Schedule</h1>

	<div class="row">

		<div class="ten columns">

			<?php the_content(); ?>

			<form id="class-month" name="class-month" action="<?php bloginfo('url'); ?>/classes" method="post"> 

 				<select name="month" id="month">

  					<option selected value="NULL">Choose your month &hellip;</option>

  					<option value="January">January</option>

  					<option value="February">February</option>

  					<option value="March">March</option>

  					<option value="April">April</option>

  					<option value="May">May</option>

  					<option value="June">June</option>

  					<option value="July">July</option>

  					<option value="August">August</option>

  					<option value="September">September</option>

  					<option value="October">October</option>

  					<option value="November">November</option>

  					<option value="December">December</option>

				</select>  

        			<input class="button" name="submitmsg" type="submit" id="submitmsg" value="Get calendar" />  

				&nbsp;&nbsp;<a class="button" onClick="printContent('toPrint');">Print classes</a>

			</form>

		</div>

	</div>

<?php endwhile; endif; ?>

	<?php $year = date('Y'); 

	$cmonth_display = date('n'); ?>

	<div id="calendar">

		<h3><br />This month's classes &hellip;</h3>

		<?php echo draw_calendar($cmonth_display,$year); ?>

	</div>

</article>

</div>

<?php get_footer(); ?>