	<ul>
	<?php $args = array(
		'orderby'            => 'name',
		'hide_empty'         => 1,
		'exclude'            => '1,152',
		'title_li'           => '<h4>' . __('Book Categories') . '</h4>',
		'depth'              => 1
	); 
		wp_list_categories($args); ?> 
	</ul>