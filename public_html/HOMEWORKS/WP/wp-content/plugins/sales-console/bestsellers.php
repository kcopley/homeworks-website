<script type="text/javascript">

	google.load('visualization', '1.0', {'packages':['corechart']});

	google.setOnLoadCallback(drawChart);

	function drawChart() {

		var data = new google.visualization.DataTable();

		data.addColumn('string', 'Books');

		data.addColumn('number', 'Sales');

		data.addRows([

 		<?php $args = array(

			'post_type' => 'bookstore',

			'posts_per_page=12',

			'meta_key' => '_cmb_resource_sold',

			'orderby' => 'meta_value_num',

			'order' => 'DESC',

			'meta_query' => array(array(

				'key' => '_cmb_resource_sold',

				'value' => 1,'compare' => '>',

				'type' => 'NUMERIC')

			)

		);

		$chartdata = get_posts( $args );

		global $post;

		foreach( $chartdata as $post ) : setup_postdata($post); ?>

			['<?php the_title(); ?>', <?php echo get_post_meta($post->ID,'_cmb_resource_sold',true); ?>],

		<?php endforeach; ?>

			['Other', 0]

		]);

		var options = {

			'width':500,

			'height':500,

			'backgroundColor': 'transparent',

			is3D: true,

			legend: {position: 'none'},

		};

		var chart = new google.visualization.PieChart(document.getElementById('chart_div'));

		chart.draw(data, options);

	}

</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">

	<tr>

		<td width="500px">

			<div id="chart_div" style="margin-top:-100px;"></div>

		</td>

		<td valign="top" align="left">

			<table id="bestsellers" width="90%" border="0" cellspacing="0" cellpadding="0">

				<tr>

					<th align='left' width='65%'>&nbsp&nbsp;<strong>Resource Title</strong></th>

					<th align='center' width='15%'><strong>Sold</strong></th>

					<th align='left' width='20%'><strong>Sales</strong></th>

				</tr>

				<?php

 				$args = array(

					'post_type' => 'bookstore',

					'posts_per_page=15',

					'meta_key' => '_cmb_resource_sold',

					'orderby' => 'meta_value_num',

					'order' => 'DESC',

					'meta_query' => array(array(

						'key' => '_cmb_resource_sold',

						'value' => 1,

						'compare' => '>',

						'type' => 'NUMERIC')

					)

				);

				$the_query = new WP_Query( $args );

				while ( $the_query->have_posts() ) :

					$the_query->the_post();

					global $post;

					$sold = get_post_meta($post->ID, '_cmb_resource_sold', true);

					$price = get_post_meta($post->ID, '_cmb_resource_price', true);

					$amount = str_replace("$","",$price); 

					$fmt = '$%i';

					$profit = money_format($fmt,$sold*$amount); ?>

				<tr>

					<td><a href="<?php bloginfo('wpurl');?>/wp-admin/post.php?post=<?php the_ID(); ?>&action=edit"><?php the_title(); ?></a></td>

					<td align='center'><?php echo get_post_meta($post->ID,'_cmb_resource_sold',true); ?></td>

					<td><?php echo $profit; ?></td>

				</tr> 

			<?php endwhile; ?>

			</table>

		</td>

	</tr>

</table>







