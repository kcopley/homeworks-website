<?phpinclude 'bookmethods.php';?><table border="0" cellpadding="0" cellspacing="0" width="100%">	<tr>		<form action="" id="library" method="post" name="library_search">			<td width="15%" align="left" valign="top" style="text-align: left;">				<table border="0" cellpadding="0" cellspacing="0" id="formtable" width="100%">					<tr>						<td align="right" width="7%"><label>ID:</label>&nbsp;</td>						<td width="93%"><input id="<?php echo request_transaction_id() ?>" name="<?php echo request_transaction_id() ?>" type="text"></td>					</tr>                    <tr>                        <?php                        date_default_timezone_set('America/Chicago');                        ?>                        <td align="right" width="7%"><label>Date From:</label>&nbsp;</td>                        <td width="93%"><input id="<?php echo request_transaction_date_from() ?>" name="<?php echo request_transaction_date_from() ?>" type="date" value="<?php echo date("Y-m-d", mktime(0, 0, 0, 1, 1, 2000)); ?>"></td>                    </tr>                    <tr>                        <?php                        date_default_timezone_set('America/Chicago');                        ?>                        <td align="right" width="7%"><label>Date From:</label>&nbsp;</td>                        <td width="93%"><input id="<?php echo request_transaction_date_to() ?>" name="<?php echo request_transaction_date_to() ?>" type="date" value="<?php echo date('Y-m-d'); ?>"></td>                    </tr>					<tr>						<td width="35%">							<input name="<?php echo request_page_action() ?>" type="hidden" value="<?php echo query_search_action() ?>" />							<input class="button-primary" name="button" type="submit" value="Search" /></td>						<td></td>					</tr>				</table>			</td>		</form>		<td width="50%"></td>	</tr>    <td colspan="5">        <hr/>    </td></table><?phpglobal $wpdb;switch ($_REQUEST[request_page_action()]) {	case query_search_action():		querySearch();		break;    case update_transaction_action():        update_transaction($_REQUEST[request_selected_transaction()]);        select_transaction($_REQUEST[request_selected_transaction()]);        break;    case transaction_select_action():        select_transaction($_REQUEST[request_selected_transaction()]);        break;};function querySearch(){	?>	<table id="formtable" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 10px 0 50px;">		<tr>			<th width="30%" align="left">Name</th>            <th width="auto" align="left">ID</th>            <th width="auto" align="left">Date</th>            <th width="auto" align="left">Total</th>		</tr>		<?php		$args = array(			'numberposts' => -1,			'posts_per_page' => -1,			'order' => 'ASC',			'orderby' => 'date',			'post_type' => 'transactions'		);		$meta_query_array = array('relation' => 'OR');		if ($_REQUEST[request_transaction_id()]) {			$meta_query_array[] = array(				'key' => '_cmb_transaction_id',				'value' => $_REQUEST[request_transaction_id()]			);		}        if ($_REQUEST[request_transaction_date_from()] || $_REQUEST[request_transaction_date_to()]) {            $meta_query_array[] =                array(                    'key' => '_cmb_transaction_date',                    'value' => array($_REQUEST[request_transaction_date_from()], $_REQUEST[request_transaction_date_to()]),                    'compare' => 'BETWEEN',                    'type' => 'DATE'                );        }		$args['meta_query'] = $meta_query_array;		$the_query = new WP_Query($args);		while ($the_query->have_posts()):			$the_query->the_post();			global $post;            $transaction_id = $post->ID;            transaction_display($transaction_id);		endwhile;		?>	</table>	<?php}function transaction_display($transaction_id) {    ?>    <tr>        <td>            <form method="POST" name="select_transaction">                <input type="hidden" name="<?php echo request_selected_transaction(); ?>" value="<?php echo $transaction_id ?>"/>                <?php store_query_info() ?>                <input name="<?php echo request_page_action(); ?>" type="hidden" value="<?php echo transaction_select_action(); ?>" />                <input style="                        background:none!important;                         border:none;                        padding:0!important;                        /*optional*/                        font-family:arial,sans-serif; /*input has OS specific font-family*/                        color:#069;                        cursor:pointer;"                       type="submit" name="button" class="button" value="<?php echo the_title(); ?>" />            </form>        </td>        <td><?php echo get_transaction_id($transaction_id); ?></td>        <td><?php echo get_transaction_customer_name($transaction_id); ?></td>        <td><?php echo get_transaction_total($transaction_id); ?></td>        <td><?php echo get_transaction_date($transaction_id); ?></td>    </tr>    <?php}function select_transaction($transaction_post_id) {    ?>    <table id="formtable" width="100%" border="0" cellspacing="0" cellpadding="0">        <tr>            <td width="49%" valign="top">                <table id="formtable" border="0" cellspacing="0" cellpadding="0" style="margin: 10px 0 10px;">                    <tr>                        <form method="POST" action="" name="update_transaction" align="center">                            <td><?php echo get_transaction_id($transaction_post_id); ?></td>                            <td><?php echo get_transaction_customer_name($transaction_post_id); ?></td>                            <td><?php echo get_transaction_total($transaction_post_id); ?></td>                            <td width="auto" align="left">                                <input style="width: 90%; padding: 4px 3px 4px" type="date"                                       id="<?php echo request_change_transaction_date(); ?>"                                       name="<?php echo request_change_transaction_date(); ?>"                                       value="<?php echo get_transaction_date($transaction_post_id); ?>"/>                            </td>                            <td width="10%" align="right">                                <input name="<?php echo request_page_action() ?>" type="hidden" value="<?php echo update_transaction_action() ?>"/>                                <?php store_transaction_id() ?>                                <?php store_query_info() ?>                                <input type="submit" name="button" class="button-primary" value="Update"/>                            </td>                        </form>                        <td width="10%" align="right">                            <form method="POST" action="" name="back_to_query" align="center">                                <input name="<?php echo request_page_action() ?>" type="hidden" value="<?php echo query_search_action() ?>"/>                                <?php store_query_info() ?>                                <input type="submit" name="button" class="button-primary" value="Back to Search Results"/>                            </form>                        </td>                    </tr>                    <tr>                        <td><h4 style="margin: 10px 0px 0px 0px;">Book</h4></td>                        <td><h4 style="margin: 10px 0px 0px 0px;">ISBN</h4></td>                        <td><h4 style="margin: 10px 0px 0px 0px;">Barcode</h4></td>                        <td><h4 style="margin: 10px 0px 0px 0px;">Quantity Sold</h4></td>                        <td><h4 style="margin: 10px 0px 0px 0px;">Price</h4></td>                        <td></td>                    </tr>                    <tr>                        <td colspan="5">                            <hr/>                        </td>                    </tr>                    <?php                    $books = get_transaction_books($transaction_post_id);                    if (!empty($books)) {                        foreach ($books as $book) {                            book_display_transaction($book);                        }                    }                    ?>                </table>            </td>        </tr>    </table>    <?php}function book_display_transaction($book) {    $product_id = $book[0];    $soldqty = $book[1];    $productsoldprice = $book[2];    ?>    <tr>        <td><?php echo get_the_title($product_id); ?></td>        <td><?php echo get_book_sku($product_id); ?></td>        <td><?php echo get_book_barcode($product_id); ?></td>        <td><?php echo $soldqty; ?></td>        <td><?php $productsoldprice; ?></td>    </tr>    <?php}function store_query_info() {    ?>    <input name="<?php echo request_transaction_id() ?>" type="hidden" value="<?php echo $_REQUEST[request_consigner_id()]; ?>" />    <?php}function store_transaction_id(){    ?>    <input name="<?php echo request_selected_transaction() ?>" type="hidden" value="<?php echo $_REQUEST[request_selected_transaction()]; ?>" />    <?php}?>