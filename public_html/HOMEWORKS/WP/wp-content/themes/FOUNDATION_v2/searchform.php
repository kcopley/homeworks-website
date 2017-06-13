<form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
	<input type="hidden" name="s" id="s" placeholder="Searching..." value="pleasesearch" />
	<input type="text" value="" name="search_name" id="search_name" placeholder="Search by Keyword" />
	<input type="text" value="" name="search_publisher" id="search_publisher" placeholder="Search by Publisher" />
	<input type="text" value="" name="search_ISBN" id="search_ISBN" placeholder="Search by ISBN" />
	<input type="submit" id="searchsubmit" value="L" class="button">
</form>