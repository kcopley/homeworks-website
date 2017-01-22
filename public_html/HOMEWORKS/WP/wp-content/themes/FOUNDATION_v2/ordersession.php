<?php

/*

Template Name: Session

*/

?>

<?php session_start(); 

	$_SESSION['shipping']['billingFName'] = $_REQUEST["billingFName"];

	$_SESSION['shipping']['billingLName'] = $_REQUEST["billingLName"];

	$_SESSION['shipping']['billingSchool'] = $_REQUEST["billingSchool"];

	$_SESSION['shipping']['AVSADDR'] = $_REQUEST["AVSADDR"];

	$_SESSION['shipping']['billingCity'] = $_REQUEST["billingCity"];

	$_SESSION['shipping']['billingState'] = $_REQUEST["billingState"];

	$_SESSION['shipping']['AVSZIP'] = $_REQUEST["AVSZIP"];

	$_SESSION['shipping']['billingPhone'] = $_REQUEST["billingPhone"];

	$_SESSION['shipping']['billingEmail'] = $_REQUEST["billingEmail"];

	$_SESSION['shipping']['Amount'] = $_REQUEST["Amount"];

	$_SESSION['shipping']['Tax'] = $_REQUEST["Tax"];

	$_SESSION['shipping']['Paytype'] = $_REQUEST["Paytype"];

	$_SESSION['shipping']['RefID'] = $_REQUEST["RefID"];

?>



<?php get_header(); ?>

<article class="row">

	<section class="seven columns offset-by-one">

		<h1>Thank You!</h1>

		<p><strong>Your shipping information is being securely processed at the moment.</strong> Once completed, you will be redirected to a separate billing page to complete your purchase. If you wish, you may click "Continue" to proceed …</p>

		<form id="TRANSFIRST" method="POST" action="https://webservices.primerchants.com/billing/TransactionCentral/EnterTransaction.asp?" name="frmReturn" id="frmReturn">

			<input type="hidden" name="MerchantID" value="<?php echo get_option('merchantid'); ?>">
			<input type="hidden" name="RegKey" value="<?php echo get_option('regkey'); ?>">
			<input type="hidden" name="RURL" value="<?php bloginfo('url'); ?>/response">
			<input type="hidden" name="ConfirmPage" value="Y">
			<input type="hidden" name="TransType" value="CC">
			<input type="hidden" name="RefID" value="<?php echo $_SESSION['shipping']['RefID'];?>">
			<input type="hidden" name="Amount" value="<?php echo $_SESSION['shipping']['Amount'];?>">
			<input type="hidden" name="TaxIndicator" value="1">
			<input type="hidden" name="AVSADDR" value="<?php echo $_SESSION['shipping']['AVSADDR'];?>">
			<input type="hidden" name="AVSZIP" value="<?php echo $_SESSION['shipping']['AVSZIP'];?>">
			<input name="Purchase" type="submit" value="Continue"/>

		</form>

		<script type="text/javascript">document.frmReturn.submit();</script>

	</section>

</article>

<?php get_footer(); ?>