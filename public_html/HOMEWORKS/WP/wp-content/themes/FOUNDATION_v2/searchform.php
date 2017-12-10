<form style="max-width: 500px; display: inline-block;" role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
    <table style="background-color: rgba(0, 0, 0, 0); width: 100%;" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <input type="hidden" name="s" id="s" placeholder="Searching..." value="pleasesearch" />
            </td>
        </tr>
        <tr>
            <td>
                <input style="opacity: .65; width: 95%;" type="text" value="" name="search_name" id="search_name" placeholder="Search by Keyword" />
            </td>
        </tr>
        <tr>
            <td>
	            <input style="opacity: .65; width: 95%;" type="text" value="" name="search_publisher" id="search_publisher" placeholder="Search by Publisher" />
            </td>
        </tr>
         <tr>
            <td>
	            <input style="opacity: .65; width: 95%;" type="text" value="" name="search_author" id="search_author" placeholder="Search by Author" />
            </td>
        </tr>
        <tr>
            <td style="width: 90%;">
                <input style="opacity: .65; width: 95%;" type="text" value="" name="search_ISBN" id="search_ISBN" placeholder="Search by ISBN" />
            </td>
            <td style="width: 10%;">
                <input style="padding: 0 0 0;" type="submit" id="searchsubmit" value="L" class="button">
            </td>
        </tr>
    </table>
</form>