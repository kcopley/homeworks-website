<?php
$path = $_SERVER['DOCUMENT_ROOT'];
if (get_option('offline-mode') == 1) {
    include($path.'/homeworks/public_html/HOMEWORKS/WP/wp-content/plugins/sales-console/includes.php');
}
else {
    include($path.'/HOMEWORKS/WP/wp-content/plugins/sales-console/includes.php');
}
/*

Template Name: Billing

*/

?>

<?php session_start(); 

	$_SESSION['checkout']['RefID'] = $_REQUEST["RefID"];
	$_SESSION['checkout']['Amount'] = $_REQUEST["Amount"];
	$_SESSION['checkout']['TaxAmount'] = $_REQUEST["TaxAmount"];
	$_SESSION['checkout']['Total'] = $_REQUEST["Total"];

	$_SESSION['checkout']['shippingName'] = $_REQUEST["shippingName"];
	$_SESSION['checkout']['shippingSchool'] = $_REQUEST["shippingSchool"];
	$_SESSION['checkout']['shippingAddress'] = $_REQUEST["shippingAddress"];
	$_SESSION['checkout']['shippingCity'] = $_REQUEST["shippingCity"];
	$_SESSION['checkout']['shippingState'] = $_REQUEST["shippingState"];
	$_SESSION['checkout']['shippingZIP'] = $_REQUEST["shippingZIP"];
	$_SESSION['checkout']['shippingPhone1'] = $_REQUEST["shippingPhone1"];
	$_SESSION['checkout']['shippingPhone2'] = $_REQUEST["shippingPhone2"];
	$_SESSION['checkout']['shippingPhone3'] = $_REQUEST["shippingPhone3"];
	$_SESSION['checkout']['shippingPhone'] = $_REQUEST["shippingPhone1"]."-".$_REQUEST["shippingPhone2"]."-".$_REQUEST["shippingPhone3"];
	$_SESSION['checkout']['shippingEmail'] = $_REQUEST["shippingEmail"];

	$_SESSION['checkout']['billingName'] = $_REQUEST["billingName"];
	$_SESSION['checkout']['billingMonth'] = $_REQUEST["billingMonth"];
	$_SESSION['checkout']['billingYear'] = $_REQUEST["billingYear"];
	$_SESSION['checkout']['billingCCV'] = $_REQUEST["billingCCV"];
?>



<?php get_header(); ?>

<div class="row">

<article class="seven columns offset-by-one">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<?php the_content(); ?>

		<h5>

		<?php if ($_SESSION['checkout']['shippingState'] == "KS") { 

			echo "Order total: $".(number_format($_SESSION['checkout']['Amount'],2));

		} else { 

			echo "Order total: $".number_format($_SESSION['checkout']['Total'],2);

		}; ?>

		</h5>

		<fieldset>

			<legend>Shipping information</legend>

			<p><strong><?php echo $_SESSION['checkout']['shippingName']; ?></strong><br />

			<?php echo $_SESSION['checkout']['shippingAddress']; ?><br />

			<?php echo $_SESSION['checkout']['shippingCity']; ?> <?php echo $_SESSION['checkout']['shippingState']; ?>, <?php echo $_SESSION['checkout']['shippingZIP']; ?><br />

			<strong>Phone:</strong> <?php echo $_SESSION['checkout']['shippingPhone']; ?><br />

			<strong>Email:</strong> <?php echo $_SESSION['checkout']['shippingEmail']; ?></p>

		</fieldset>

		<fieldset>

			<legend>Billing information</legend>

			<p><strong>Purchaser:</strong> <?php echo $_SESSION['checkout']['billingName']; ?><br />

			<?php $card = $_REQUEST['billingCard1'].$_REQUEST['billingCard2'].$_REQUEST['billingCard3'].$_REQUEST['billingCard4'];

				$creditcard = "XXXX-XXXX-XXXX-".substr($card,-4,4);

				$cardnum = trim($_REQUEST['billingCard1']).trim($_REQUEST['billingCard2']).trim($_REQUEST['billingCard3']).trim($_REQUEST['billingCard4']);
			?>

			<strong>Card number:</strong> <?php echo $creditcard; ?><br />

			<strong>Expires:</strong> <?php echo $_SESSION['checkout']['billingMonth']; ?>/<?php echo $_SESSION['checkout']['billingYear']; ?></p>

		</fieldset>

		<form id="TRANSFIRST" method="POST" action="https://webservices.primerchants.com/billing/TransactionCentral/processCC.asp?" name="frmReturn" id="frmReturn">
			<input type="hidden" name="MerchantID" value="<?php echo get_option('merchantid'); ?>">
			<input type="hidden" name="RegKey" value="<?php echo get_option('regkey'); ?>">
			<input type="hidden" name="CCRURL" value="<?php bloginfo('url'); ?>/response">

			<input type="hidden" name="RefID" value="<?php echo $_SESSION['checkout']['RefID'];?>">

			<?php if ($_SESSION['checkout']['shippingState'] == "KS") { ?>
				<input type="hidden" name="Amount" value="<?php echo $_SESSION['checkout']['Amount'];?>">
				<input type="hidden" name="TaxAmount" value="<?php echo $_SESSION['checkout']['TaxAmount'];?>">
			<?php } else { ?>
				<input type="hidden" name="Amount" value="<?php echo $_SESSION['checkout']['Total'];?>">
				<input type="hidden" name="TaxAmount" value="0.00">
			<?php }; ?>

                        <input type="hidden" name="TaxIndicator" value="1">
                        <input type="hidden" name="AccountNo" value="<?php echo $cardnum; ?>">
                        <input type="hidden" name="NameonAccount" value="<?php echo $_SESSION['checkout']['billingName'];?>">
                        <input type="hidden" name="CCMonth" value="<?php echo $_SESSION['checkout']['billingMonth'];?>">
                        <input type="hidden" name="CCYear" value="<?php echo $_SESSION['checkout']['billingYear'];?>">

                        <input type="hidden" name="CVV2" value="<?php echo $_SESSION['checkout']['billingCCV'];?>">

			<input type="hidden" name="AVSADDR" value="<?php echo $_SESSION['checkout']['shippingAddress'];?>">

			<input type="hidden" name="AVSZIP" value="<?php echo $_SESSION['checkout']['shippingZIP'];?>">

			<input type="hidden" name="ShipToZipCode" value="<?php echo $_SESSION['checkout']['shippingZIP'];?>">

                        <input type="hidden" name="USER1" value="online">

			<p><a class="button" href="<?php bloginfo('url'); ?>/checkout">Back</a> <input name="Purchase" type="submit" value="Place order"/></p>

			

                </form>

	<?php endwhile; endif; ?>

</article>

<aside class="four columns">

<?php $page_id = 32; 

$page_data = get_page( $page_id ); 

$content = apply_filters('the_content', $page_data->post_content); 

$title = $page_data->post_title; 

	echo $content;

?>

</aside>

</div>

<?php get_footer(); ?>