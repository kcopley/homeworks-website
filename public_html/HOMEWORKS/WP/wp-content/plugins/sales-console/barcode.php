<?php $action = $_REQUEST['action']; 

$file = dirname(__FILE__) . '/sales-console.php';

$plugin_url = plugin_dir_url($file);

global $wpdb;

switch($action) { 

	case "add":
		$bar1 = $_REQUEST['bar1'];
			$bar1str = "SELECT post_id
				FROM $wpdb->postmeta
				WHERE
					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar1')
				GROUP BY post_id;
			";

			$bar1_id = explode(",",$wpdb->get_var($bar1str));
			$bar1id = $bar1_id[0];
			$_SESSION['bar1'] = $_REQUEST['bar1'];


		$bar2 = $_REQUEST['bar2'];

			$bar2str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar2')

				GROUP BY post_id;

			";

			$bar2_id = explode(",",$wpdb->get_var(($bar2str))); 

			$bar2id = $bar2_id[0];

			$_SESSION['bar2'] = $_REQUEST['bar2'];



		$bar3 = $_REQUEST['bar3'];

			$bar3str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar3')

				GROUP BY post_id;

			";

			$bar3_id = explode(",",$wpdb->get_var(($bar3str))); 

			$bar3id = $bar3_id[0];

			$_SESSION['bar3'] = $_REQUEST['bar3'];



		$bar4 = $_REQUEST['bar4'];

			$bar4str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar4')

				GROUP BY post_id;

			";

			$bar4_id = explode(",",$wpdb->get_var(($bar4str))); 

			$bar4id = $bar4_id[0];

			$_SESSION['bar4'] = $_REQUEST['bar4'];



		$bar5 = $_REQUEST['bar5'];

			$bar5str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar5')

				GROUP BY post_id;

			";

			$bar5_id = explode(",",$wpdb->get_var(($bar5str))); 

			$bar5id = $bar5_id[0];

			$_SESSION['bar5'] = $_REQUEST['bar5'];



		$bar6 = $_REQUEST['bar6'];

			$bar6str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar6')

				GROUP BY post_id;

			";

			$bar6_id = explode(",",$wpdb->get_var(($bar6str))); 

			$bar6id = $bar6_id[0];

			$_SESSION['bar6'] = $_REQUEST['bar6'];



		$bar7 = $_REQUEST['bar7'];

			$bar7str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar7')

				GROUP BY post_id;

			";

			$bar7_id = explode(",",$wpdb->get_var(($bar7str))); 

			$bar7id = $bar7_id[0];

			$_SESSION['bar7'] = $_REQUEST['bar7'];



		$bar8 = $_REQUEST['bar8'];

			$bar8str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar8')

				GROUP BY post_id;

			";

			$bar8_id = explode(",",$wpdb->get_var(($bar8str))); 

			$bar8id = $bar8_id[0];

			$_SESSION['bar8'] = $_REQUEST['bar8'];



		$bar9 = $_REQUEST['bar9'];

			$bar9str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar9')

				GROUP BY post_id;

			";

			$bar9_id = explode(",",$wpdb->get_var(($bar9str))); 

			$bar9id = $bar9_id[0];

			$_SESSION['bar9'] = $_REQUEST['bar9'];



		$bar10 = $_REQUEST['bar10'];

			$bar10str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar10')

				GROUP BY post_id;

			";

			$bar10_id = explode(",",$wpdb->get_var(($bar10str))); 

			$bar10id = $bar10_id[0];

			$_SESSION['bar10'] = $_REQUEST['bar10'];



		$bar11 = $_REQUEST['bar11'];

			$bar11str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar11')

				GROUP BY post_id;

			";

			$bar11_id = explode(",",$wpdb->get_var(($bar11str))); 

			$bar11id = $bar11_id[0];

			$_SESSION['bar11'] = $_REQUEST['bar11'];



		$bar12 = $_REQUEST['bar12'];

			$bar12str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar12')

				GROUP BY post_id;

			";

			$bar12_id = explode(",",$wpdb->get_var(($bar12str))); 

			$bar12id = $bar12_id[0];

			$_SESSION['bar12'] = $_REQUEST['bar12'];



		$bar13 = $_REQUEST['bar13'];

			$bar13str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar13')

				GROUP BY post_id;

			";

			$bar13_id = explode(",",$wpdb->get_var(($bar13str))); 

			$bar13id = $bar13_id[0];

			$_SESSION['bar13'] = $_REQUEST['bar13'];



		$bar14 = $_REQUEST['bar14'];

			$bar14str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar14')

				GROUP BY post_id;

			";

			$bar14_id = explode(",",$wpdb->get_var(($bar14str))); 

			$bar14id = $bar14_id[0];

			$_SESSION['bar14'] = $_REQUEST['bar14'];



		$bar15 = $_REQUEST['bar15'];

			$bar15str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar15')

				GROUP BY post_id;

			";

			$bar15_id = explode(",",$wpdb->get_var(($bar15str))); 

			$bar15id = $bar15_id[0];

			$_SESSION['bar15'] = $_REQUEST['bar15'];



		$bar16 = $_REQUEST['bar16'];

			$bar16str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar16')

				GROUP BY post_id;

			";

			$bar16_id = explode(",",$wpdb->get_var(($bar16str))); 

			$bar16id = $bar16_id[0];

			$_SESSION['bar16'] = $_REQUEST['bar16'];



		$bar17 = $_REQUEST['bar17'];

			$bar17str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar17')

				GROUP BY post_id;

			";

			$bar17_id = explode(",",$wpdb->get_var(($bar17str))); 

			$bar17id = $bar17_id[0];

			$_SESSION['bar17'] = $_REQUEST['bar17'];



		$bar18 = $_REQUEST['bar18'];

			$bar18str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar18')

				GROUP BY post_id;

			";

			$bar18_id = explode(",",$wpdb->get_var(($bar18str))); 

			$bar18id = $bar18_id[0];

			$_SESSION['bar18'] = $_REQUEST['bar18'];



		$bar19 = $_REQUEST['bar19'];

			$bar19str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar19')

				GROUP BY post_id;

			";

			$bar19_id = explode(",",$wpdb->get_var(($bar19str))); 

			$bar19id = $bar19_id[0];

			$_SESSION['bar19'] = $_REQUEST['bar19'];



		$bar20 = $_REQUEST['bar20'];

			$bar20str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar20')

				GROUP BY post_id;

			";

			$bar20_id = explode(",",$wpdb->get_var(($bar20str))); 

			$bar20id = $bar20_id[0];

			$_SESSION['bar20'] = $_REQUEST['bar20'];



		$bar21 = $_REQUEST['bar21'];

			$bar21str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar21')

				GROUP BY post_id;

			";

			$bar21_id = explode(",",$wpdb->get_var(($bar21str))); 

			$bar21id = $bar21_id[0];

			$_SESSION['bar21'] = $_REQUEST['bar21'];



		$bar22 = $_REQUEST['bar22'];

			$bar22str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar22')

				GROUP BY post_id;

			";

			$bar22_id = explode(",",$wpdb->get_var(($bar22str))); 

			$bar22id = $bar22_id[0];

			$_SESSION['bar22'] = $_REQUEST['bar22'];



		$bar23 = $_REQUEST['bar23'];

			$bar23str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar23')

				GROUP BY post_id;

			";

			$bar23_id = explode(",",$wpdb->get_var(($bar23str))); 

			$bar23id = $bar23_id[0];

			$_SESSION['bar23'] = $_REQUEST['bar24'];



		$bar24 = $_REQUEST['bar24'];

			$bar24str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar24')

				GROUP BY post_id;

			";

			$bar24_id = explode(",",$wpdb->get_var(($bar24str))); 

			$bar24id = $bar24_id[0];

			$_SESSION['bar24'] = $_REQUEST['bar24'];



		$bar25 = $_REQUEST['bar25'];

			$bar25str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar25')

				GROUP BY post_id;

			";

			$bar25_id = explode(",",$wpdb->get_var(($bar25str))); 

			$bar25id = $bar25_id[0];

			$_SESSION['bar25'] = $_REQUEST['bar25'];



		$bar26 = $_REQUEST['bar26'];

			$bar26str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar26')

				GROUP BY post_id;

			";

			$bar26_id = explode(",",$wpdb->get_var(($bar26str))); 

			$bar26id = $bar26_id[0];

			$_SESSION['bar26'] = $_REQUEST['bar26'];



		$bar27 = $_REQUEST['bar27'];

			$bar27str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar27')

				GROUP BY post_id;

			";

			$bar27_id = explode(",",$wpdb->get_var(($bar27str))); 

			$bar27id = $bar27_id[0];

			$_SESSION['bar27'] = $_REQUEST['bar27'];



		$bar28 = $_REQUEST['bar28'];

			$bar28str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar28')

				GROUP BY post_id;

			";

			$bar28_id = explode(",",$wpdb->get_var(($bar28str))); 

			$bar28id = $bar28_id[0];

			$_SESSION['bar28'] = $_REQUEST['bar28'];



		$bar29 = $_REQUEST['bar29'];

			$bar29str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar29')

				GROUP BY post_id;

			";

			$bar29_id = explode(",",$wpdb->get_var(($bar29str))); 

			$bar29id = $bar29_id[0];

			$_SESSION['bar29'] = $_REQUEST['bar29'];



		$bar30 = $_REQUEST['bar30'];

			$bar30str = "SELECT post_id

				FROM $wpdb->postmeta

				WHERE

					(meta_key = '_cmb_resource_barcode' AND meta_value = '$bar30')

				GROUP BY post_id;

			";

			$bar30_id = explode(",",$wpdb->get_var(($bar30str))); 

			$bar30id = $bar30_id[0];

			$_SESSION['bar30'] = $_REQUEST['bar30'];

	break;

	case "clear":

		unset($_SESSION['bar1']); 

		unset($_SESSION['bar2']); 

		unset($_SESSION['bar3']); 

		unset($_SESSION['bar4']); 

		unset($_SESSION['bar5']); 

		unset($_SESSION['bar6']); 

		unset($_SESSION['bar7']); 

		unset($_SESSION['bar8']); 

		unset($_SESSION['bar9']); 

		unset($_SESSION['bar10']); 

		unset($_SESSION['bar11']); 

		unset($_SESSION['bar12']); 

		unset($_SESSION['bar13']); 

		unset($_SESSION['bar14']); 

		unset($_SESSION['bar15']); 

		unset($_SESSION['bar16']); 

		unset($_SESSION['bar17']); 

		unset($_SESSION['bar18']); 	

		unset($_SESSION['bar19']); 

		unset($_SESSION['bar20']); 

		unset($_SESSION['bar21']); 

		unset($_SESSION['bar22']); 

		unset($_SESSION['bar23']); 

		unset($_SESSION['bar24']); 

		unset($_SESSION['bar25']); 

		unset($_SESSION['bar26']); 

		unset($_SESSION['bar27']); 

		unset($_SESSION['bar28']); 

		unset($_SESSION['bar29']); 

		unset($_SESSION['bar30']); 

	break;

}; ?>

<form method="POST" action="" name="barcodes">

	<table width="75%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 10px;">

		<tr>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar1" value="<?php echo $_SESSION['bar1']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar2" value="<?php echo $_SESSION['bar2']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="29%" valign="top"><input type="text" name="bar3" value="<?php echo $_SESSION['bar3']; ?>"></td>

		</tr>

		<tr>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar4" value="<?php echo $_SESSION['bar4']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar5" value="<?php echo $_SESSION['bar5']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="29%" valign="top"><input type="text" name="bar6" value="<?php echo $_SESSION['bar6']; ?>"></td>

		</tr>

		<tr>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar7" value="<?php echo $_SESSION['bar7']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar8" value="<?php echo $_SESSION['bar8']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="29%" valign="top"><input type="text" name="bar9" value="<?php echo $_SESSION['bar9']; ?>"></td>

		</tr>

		<tr>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar10" value="<?php echo $_SESSION['bar10']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar11" value="<?php echo $_SESSION['bar11']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="29%" valign="top"><input type="text" name="bar12" value="<?php echo $_SESSION['bar12']; ?>"></td>

		</tr>

		<tr>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar13" value="<?php echo $_SESSION['bar13']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar14" value="<?php echo $_SESSION['bar14']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="29%" valign="top"><input type="text" name="bar15" value="<?php echo $_SESSION['bar15']; ?>"></td>

		</tr>

		<tr>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar16" value="<?php echo $_SESSION['bar16']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar17" value="<?php echo $_SESSION['bar17']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="29%" valign="top"><input type="text" name="bar18" value="<?php echo $_SESSION['bar18']; ?>"></td>

		</tr>

		<tr>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar19" value="<?php echo $_SESSION['bar19']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar20" value="<?php echo $_SESSION['bar20']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="29%" valign="top"><input type="text" name="bar21" value="<?php echo $_SESSION['bar21']; ?>"></td>

		</tr>

		<tr>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar22" value="<?php echo $_SESSION['bar22']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar23" value="<?php echo $_SESSION['bar23']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="29%" valign="top"><input type="text" name="bar24" value="<?php echo $_SESSION['bar24']; ?>"></td>

		</tr>

		<tr>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar25" value="<?php echo $_SESSION['bar25']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar26" value="<?php echo $_SESSION['bar26']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="29%" valign="top"><input type="text" name="bar27" value="<?php echo $_SESSION['bar27']; ?>"></td>

		</tr>

		<tr>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar28" value="<?php echo $_SESSION['bar28']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="28%" valign="top"><input type="text" name="bar29" value="<?php echo $_SESSION['bar29']; ?>"></td>

			<td width="5%" align="right" valign="top"><label>ID:&nbsp;</label></td>

			<td width="29%" valign="top"><input type="text" name="bar30" value="<?php echo $_SESSION['bar30']; ?>"></td>

		</tr>

</table>

<table width="100%" border="0" cellspacing="0" cellpadding="5" style="margin-bottom: 10px;">

	<tr>

		<td align="left" valign="top" width="5%">

			<input type="hidden" name="action" value="add">

			<input type="submit" name="button" class="button-primary" value="Get barcodes">

			</form>

		</td>

		<td align="left" valign="top" width="5%">

			<form method="POST" action="" name="barcodes">

				<input type="hidden" name="action" value="clear">

				<input type="submit" name="button" class="button-primary" value="Clear">

			</form>

		</td>

		<td align="left" valign="top">

			<input type="button" class="button-primary" onClick="printBarcode('toPrint');" value="Print barcodes">

		</td>

	</tr>

</table>

<div id="toPrint">

	<table id="labels" width="100%" border="0" cellspacing="0" cellpadding="10">

		<tr>

			<td align="left" valign="top" width="33%" height="82px" class="ltd">

			<?php if($_REQUEST['bar1']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar1id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar1id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar1id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar1; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="34%" height="82px" class="ctd">

			<?php if($_REQUEST['bar2']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar2id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar2id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar2id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar2; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="33%" height="82px" class="rtd">

			<?php if($_REQUEST['bar3']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar3id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar3id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar3id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar3; ?>" />

				</div>

			<?php }; ?>

			</td>

		</tr>

		<tr>

			<td align="left" valign="top" width="33%" height="82px" class="ltd">

			<?php if($_REQUEST['bar4']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar4id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar4id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar4id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar4; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="34%" height="82px" class="ctd">

			<?php if($_REQUEST['bar5']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar5id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar5id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar5id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar5; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="33%" height="82px" class="rtd">

			<?php if($_REQUEST['bar6']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar6id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar6id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar6id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar6; ?>" />

				</div>

			<?php }; ?>

			</td>

		</tr>

		<tr>

			<td align="left" valign="top" width="33%" height="82px" class="ltd">

			<?php if($_REQUEST['bar7']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar7id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar7id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar7id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar7; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="34%" height="82px" class="ctd">

			<?php if($_REQUEST['bar8']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar8id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar8id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar8id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar8; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="33%" height="82px" class="rtd">

			<?php if($_REQUEST['bar9']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar9id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar9id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar9id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar9; ?>" />

				</div>

			<?php }; ?>

			</td>

		</tr>

		<tr>

			<td align="left" valign="top" width="33%" height="82px" class="ltd">

			<?php if($_REQUEST['bar10']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar10id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar10id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar10id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar10; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="34%" height="82px" class="ctd">

			<?php if($_REQUEST['bar11']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar11id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar11id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar11id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar11; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="33%" height="82px" class="rtd">

			<?php if($_REQUEST['bar12']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar12id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar12id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar12id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar12; ?>" />

				</div>

			<?php }; ?>

			</td>

		</tr>

		<tr>

			<td align="left" valign="top" width="33%" height="82px" class="ltd">

			<?php if($_REQUEST['bar13']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar13id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar13id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar13id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar13; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="34%" height="82px" class="ctd">

			<?php if($_REQUEST['bar14']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar14id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar14id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar14id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar14; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="33%" height="82px" class="rtd">

			<?php if($_REQUEST['bar15']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar15id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar15id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar15id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar15; ?>" />

				</div>

			<?php }; ?>

			</td>

		</tr>

		<tr>

			<td align="left" valign="top" width="33%" height="82px" class="ltd">

			<?php if($_REQUEST['bar16']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar16id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar16id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar16id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar16; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="34%" height="82px" class="ctd">

			<?php if($_REQUEST['bar17']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar17id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar17id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar17id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar17; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="33%" height="82px" class="rtd">

			<?php if($_REQUEST['bar18']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar18id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar18id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar18id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar18; ?>" />

				</div>

			<?php }; ?>

			</td>

		</tr>

		<tr>

			<td align="left" valign="top" width="33%" height="82px" class="ltd">

			<?php if($_REQUEST['bar19']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar19id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar19id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar19id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar19; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="34%" height="82px" class="ctd">

			<?php if($_REQUEST['bar20']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar20id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar20id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar20id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar20; ?>" />

				</div>

			<?php }; ?>

				</td>



			<td align="left" valign="top" width="33%" height="82px" class="rtd">

			<?php if($_REQUEST['bar21']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar21id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar21id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar21id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar21; ?>" />

				</div>

			<?php }; ?>

			</td>

		</tr>

		<tr>

			<td align="left" valign="top" width="33%" height="82px" class="ltd">

			<?php if($_REQUEST['bar22']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar22id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar22id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar22id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar22; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="34%" height="82px" class="ctd">

			<?php if($_REQUEST['bar23']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar23id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar23id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar23id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar23; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="33%" height="82px" class="rtd">

			<?php if($_REQUEST['bar24']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar24id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar24id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar24id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar24; ?>" />

				</div>

			<?php }; ?>

			</td>

		</tr>

		<tr>

			<td align="left" valign="top" width="33%" height="82px" class="ltd">

			<?php if($_REQUEST['bar25']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar25id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar25id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar25id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar25; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="34%" height="82px" class="ctd">

			<?php if($_REQUEST['bar26']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar26id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar26id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar26id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar26; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="33%" height="82px" class="rtd">

			<?php if($_REQUEST['bar27']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar27id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar27id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar27id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar27; ?>" />

				</div>

			<?php }; ?>

			</td>

		</tr>

		<tr>

			<td align="left" valign="top" width="33%" height="82px" class="ltd">

			<?php if($_REQUEST['bar28']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar28id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar28id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar28id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar28; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="34%" height="82px" class="ctd">

			<?php if($_REQUEST['bar29']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar29id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar29id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar29id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar29; ?>" />

				</div>

			<?php }; ?>

			</td>



			<td align="left" valign="top" width="33%" height="82px" class="rtd">

			<?php if($_REQUEST['bar30']){ ?>

				<div class="left">

					<small><strong><?php echo substr(get_the_title($bar30id),0,40); ?></strong><br /><strong>MSRP: $</strong><?php echo number_format(get_post_meta($bar30id,'_cmb_resource_MSRP', true), 2); ?><br /><strong>Price: $</strong><?php echo number_format(get_post_meta($bar30id,'_cmb_resource_price', true), 2); ?></small>

				</div>

				<div class="right">

					<img src="<?php echo $plugin_url; ?>barcode/generate.php?text=<?php echo $bar30; ?>" />

				</div>

			<?php }; ?>

			</td>

		</tr>

	</table>

</div>