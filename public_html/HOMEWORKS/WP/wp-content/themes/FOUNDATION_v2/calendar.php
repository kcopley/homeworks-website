<?php define('WP_USE_THEMES', false);

	require_once('../../../wp-load.php');



	$month = date('n',strtotime($_REQUEST['month']));

	$year = date('Y');



	if(strtotime($year.'-'.$month)<strtotime('-1 Months')){

		$year = date('Y', strtotime('+1 year'));

	};

	





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

			$postslist = get_posts($args);

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

	echo "<h2><br />".$_REQUEST['month']." ".$year."</h2>";

	echo $calendar;

?>