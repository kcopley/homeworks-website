<?php
?>

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="left" valign="top">
                <form action="" id="cart" method="post" name="cart">
                    <table border="0" cellpadding="0" cellspacing="0" id="formtable" width="100%">
                        <tr>
                            <td align="right" width="7%"><label>ID:</label>&nbsp;</td>
                            <td width="93%"><input id="<?php request_sale_id() ?>" name="<?php request_sale_id() ?>" type="text"></td>
                        </tr>
                        <tr>
                            <td align="right"><label>ISBN:</label>&nbsp;</td>
                            <td><input id="<?php request_sale_isbn() ?>" name="<?php request_sale_isbn() ?>" type="text"></td>
                        </tr>
                    </table>
                    <input name="<?php request_sale_quantity() ?>" type="hidden" value="1">
                    <input name="<?php request_page_action() ?>" type="hidden" value="<?php request_sale_add_item_action() ?>">
                    <input class="button-primary" name="button" type="submit" value="Add item">
                </form>
                <?php

                switch ($_REQUEST[request_page_action()]) {
                    case request_sale_add_item_action():
                        add_item();
                        break;
                    case request_sale_remove_item_action():
                        remove_item();
                        break;
                    case $_REQUEST[request_sale_credit_action()]:
                        add_credit();
                        break;
                    case $_REQUEST[request_sale_remove_credit_action()]:
                        remove_credit();
                        break;
                    case $_REQUEST[request_sale_clear_action()]:
                        clear_cart();
                        break;
                };

                $cart = $_SESSION[request_sale_session_cart()];
                if (!$cart) $_SESSION[request_sale_session_cart()] = array();

                if (!empty($cart)) {
                    ?>
                    <p>&nbsp;</p>
                    <table border="0" cellpadding="0" cellspacing="0" id="bookbag" width="100%">
                    <thead>
                    <tr>
                        <th align="left" width="55%">Item</th>
                        <th width="5%">Quantity</th>
                        <th width="10%">&nbsp;</th>
                        <th align="left" class="price" width="10%">Price</th>
                        <th width="20%">&nbsp;</th>
                    </tr>
                    </thead>
                    <?php
                    list_books();
                    ?>
                    <tr>
                        <td colspan="5">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td align="right"><strong>Subtotal:</strong>&nbsp;&nbsp;</td>
                        <td>
                            <strong>
                                <?php
                                echo '$'.number_format(get_subtotal(), 2);
                                ?>
                            </strong>
                        </td>
                        <td></td>
                    </tr>
                    <tr class="calculate">
                        <td colspan="2">&nbsp;</td>
                        <td align="right">Credit:&nbsp;&nbsp;</td><?php
                        if ($credit == "0.00" or $credit == "0" or $credit == " ") {
                            ?>
                            <td>$0.00</td>
                            <td>&nbsp;</td><?php
                        } else {
                            ?>
                            <td>$<?php
                                echo number_format($credit, 2);
                                ?></td>
                            <td>&larr; Discount applied</td><?php
                        }
                        ?>
                    </tr>
                    <tr class="calculate">
                        <td colspan="2">&nbsp;</td>
                        <td align="right">Tax:&nbsp;&nbsp;</td>
                        <td>$<?php
                            echo $tax;
                            ?></td><?php
                        if ($tax == "0.00") {
                            ?>
                            <td>&larr; Tax rate not set</td><?php
                        } else {
                            ?>
                            <td>&larr; <?php
                            echo get_option('ctaxrate');
                            ?>% applied</td><?php
                        }
                        ?>
                    </tr>
                    <tr class="calculate">
                        <td colspan="2">&nbsp;</td>
                        <td colspan="3">
                            <hr>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="calculate">
                        <td colspan="2">&nbsp;</td>
                        <td align="right"><strong>TOTAL:</strong>&nbsp;&nbsp;</td>
                        <td><strong>$<?php
                                echo $cost;
                                ?></strong></td>
                        <td>
                            <form action="" id="empty" method="post" name="empty">
                                <input name="product_id" type="hidden" value="null"> <input name="action" type="hidden"
                                                                                            value="empty"> <input
                                    class="button-primary" type="submit" value="Empty bookbag">
                            </form>
                        </td>
                    </tr>
                    </table><?php
                }
                ?>
            </td>
            <td align="left" id="payment-column" valign="top">
                <div id="credit_details">
                    <h4>Discount</h4>
                    <form action="" id="discount" method="post" name="discount">
                        <table width="100%">
                            <tr>
                                <td align="right"><label>Amount:</label>&nbsp;$</td>
                                <td><input name="credit" type="text"></td>
                            </tr>
                        </table><input name="action" type="hidden" value="credit"> <input class="button-primary" name="discount" type="submit" value="Update">
                    </form>
                </div><?php
                $last          = get_posts("post_type=purchases&numberposts=1");
                $lastid        = $last[0]->ID;
                $invoice       = get_post_meta($lastid, '_cmb_order_invoice', true);
                $date          = date('Y');
                $invoicenumber = preg_replace("/20.*?-/", "", $invoice) + 1;
                $newinvoice    = $date . "-" . $invoicenumber;
                $refund        = number_format(($_REQUEST['payment'] - ($_REQUEST['cost'] + $_REQUEST['tax'])), 2);
                if (($_REQUEST['action'] == "process") OR ($_REQUEST['Auth'])) {
                if ($_REQUEST['Auth'] == "Declined"):
                    $cw2   = $_REQUEST["CVV2ResponseMsg"];
                    $notes = $_REQUEST["Notes"];
                if ($cw2 == "M") {
                    ?>
                    <h1>Purchase Error</h1>
                    <h4>There was an error processing the order.</h4><?php
                } else if ($cw2 == "N") {
                    ?>
                    <h1>Card Error</h1>
                    <h4>Credit card information does not match.</h4><?php
                } else {
                    ?>
                    <h1>Processing Error</h1>
                    <h4>Your card could not be identified.</h4><?php
                }
                else:
                    global $post;
                    $invoice  = $_REQUEST['RefNo'];
                    $transid  = $_REQUEST['TransID'];
                    $customer = $_REQUEST['customer'] . $_SESSION['customer'];
                    $email    = $_REQUEST['email'] . $_SESSION['email'];
                    $address  = "No shipping address available (conference sale)";
                    foreach ($_SESSION['cart'] as $product => $qty) {
                        $row       = get_post($product);
                        $item      = $row->post_title;
                        $inventory = " (" . $qty . ")";
                        $summary .= $item . $inventory . PHP_EOL;
                    };
                    $purchaseprice = "$" . $order;
                    $ordertax      = "$" . $tax;
                    $order         = array(
                        'post_title' => $customer,
                        'post_status' => 'publish',
                        'post_author' => 1,
                        'post_category' => array(
                            1
                        ),
                        'post_type' => purchases
                    );
                    $postid        = wp_insert_post($order, $wp_error);
                    update_post_meta($postid, '_cmb_order_invoice', $invoice);
                    update_post_meta($postid, '_cmb_transfirst', $transid);
                    update_post_meta($postid, '_cmb_customer_address', $address);
                    update_post_meta($postid, '_cmb_customer_email', $email);
                    update_post_meta($postid, '_cmb_customer_organization', $school);
                    update_post_meta($postid, '_cmb_order_summary', $summary);
                    update_post_meta($postid, '_cmb_purchase_price', $purchaseprice);
                    update_post_meta($postid, '_cmb_purchase_tax', $ordertax);
                    foreach ($_SESSION['cart'] as $product => $qty) {
                        for ($i = $qty; $i > 0; $i--) {
                            $row      = get_post($product);
                            $consigners = get_post_meta('_cmb_resource_consigners');
                            $consigner = array_shift($consigners);

                            $consigner_book_list = get_post_meta($consigner, '_cmb_consigner_books', true);
                            if (!empty($consigner_book_list)) {
                                $key = array_search($product_id, $consigner_book_list);
                                if (!empty($key)) {
                                    array_splice($consigner_book_list, $key, 1);
                                }
                            }
                            //add to consigner sold list next

                            $oldstock = get_post_meta($product, '_cmb_resource_quantity', true);
                            $newstock = $oldstock - 1;
                            if ($newstock <= 0) {
                                update_post_meta($product, '_cmb_resource_available', 'Inactive');
                            }
                            ;
                            update_post_meta($product, '_cmb_resource_quantity', $newstock);
                            $stat = get_post_meta($product, '_cmb_resource_sold', true);
                            $sale = $stat + 1;
                            update_post_meta($product, '_cmb_resource_sold', $sale);
                        }
                    };
                if ($_REQUEST['paytype'] == "cash") {
                    update_post_meta($postid, '_cmb_payment_type', 'cash');
                    update_post_meta($postid, '_cmb_customer_payment', 'Yes');
                    ?>
                <h4>Refund: $<?php
                    echo $refund;
                    ?></h4><?php
                } else if ($_REQUEST['paytype'] == "check") {
                    update_post_meta($postid, '_cmb_payment_type', 'check');
                    update_post_meta($postid, '_cmb_customer_payment', 'Yes');
                    ?>
                    <h4>Check payment received.</h4><?php
                } else if ($_REQUEST['USER1'] == "credit") {
                    update_post_meta($postid, '_cmb_payment_type', 'credit');
                    ?>
                    <h4>Payment received at TransFirst/Transaction Central</h4><?php
                } else {
                    ?><?php
                };
                    ?>
                    <div style="display: none;">
                        <div id="toPrint">
                            <p style="text-align: center;"><strong>Home Works for Books</strong><br>
                                <em>Your homeschool connection for discounted new and used homeschool materials!</em></p>
                            <p style="text-align: center;"><?php
                                echo get_option('invoiceaddress');
                                ?><br>
                                <strong>Phone:</strong> <?php
                                echo get_option('invoicephone');
                                ?><br>
                                Come visit us online at <?php
                                echo get_option('invoiceURL');
                                ?></p>
                            <p>&nbsp;</p>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="left" colspan="3" valign="top"><?php
                                        echo date("Y/m/d H:i:s");
                                        ?></td>
                                </tr>
                                <tr>
                                    <td align="left" colspan="3" valign="top"><?php
                                        global $current_user;
                                        get_currentuserinfo();
                                        ?>Cashier: <?php
                                        echo $current_user->user_firstname;
                                        ?></td>
                                </tr>
                                <tr>
                                    <td align="left" colspan="3" valign="middle">
                                        <hr>
                                    </td>
                                </tr><?php
                                foreach ($_SESSION['cart'] as $product => $qty):
                                    $row       = get_post($product);
                                    $price     = get_post_meta($product, '_cmb_resource_price', true);
                                    $amount    = str_replace("$", "", $price);
                                    $line_cost = $amount * $qty;
                                    $total += get_post_meta($product, '_cmb_resource_price', true) * $qty;
                                    $taxpercent = get_option('ctaxrate') / 100;
                                    $tax        = number_format($taxpercent * $total, 2);
                                    $credit     = number_format($_SESSION['credit'], 2);
                                    $order      = number_format($total - $credit, 2);
                                    $cost       = number_format($total + $tax - $credit, 2);
                                    ?>
                                    <tr>
                                    <td align="left" colspan="2" valign="top">(<?php
                                        echo $qty;
                                        ?> ) <?php
                                        echo $row->post_title;
                                        ?></td>
                                    <td valign="top" width="15%">$<?php
                                        echo number_format($line_cost, 2);
                                        ?></td>
                                    </tr><?php
                                endforeach;
                                ?>
                                <tr>
                                    <td align="left" colspan="3" valign="middle">
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td align="right" valign="top" width="10%"><strong>Subtotal:</strong>&nbsp;&nbsp;</td>
                                    <td valign="top"><strong>$<?php
                                            echo number_format($total, 2);
                                            ?></strong></td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td align="right" valign="top" width="10%">Credit:&nbsp;&nbsp;</td>
                                    <td valign="top">$<?php
                                        echo number_format($credit, 2);
                                        ?></td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td align="right" valign="top" width="10%">Tax:&nbsp;&nbsp;</td>
                                    <td valign="top">$<?php
                                        echo $tax;
                                        ?></td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td align="right" valign="top" width="10%">
                                        <hr>
                                    </td>
                                    <td valign="top">
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td align="right" valign="top" width="10%"><strong>TOTAL:</strong>&nbsp;&nbsp;</td>
                                    <td valign="top"><strong>$<?php
                                            echo $cost;
                                            ?></strong></td>
                                </tr><?php
                                $paytype = $_REQUEST['paytype'];
                                if ($paytype == "credit" or $_REQUEST['USER1'] == "credit") {
                                } else {
                                    ?>
                                    <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td align="right" valign="top" width="10%"><span style="text-transform: uppercase;"><?php
                                            echo $paytype;
                                            ?>:</span>&nbsp;&nbsp;</td>
                                    <td valign="top">$<?php
                                        echo $_REQUEST['payment'];
                                        ?></td>
                                    </tr><?php
                                }
                                ?>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td align="right" valign="top" width="10%">Change:&nbsp;&nbsp;</td>
                                    <td valign="top">$<?php
                                        echo $refund;
                                        ?></td>
                                </tr>
                            </table>
                            <p style="text-align: center;"><?php
                                echo get_option('invoicepromo');
                                ?></p>
                            <p style="text-align: center;"><strong>Invoice:</strong> #<?php
                                echo $invoice;
                                ?><br>
                                Customer Copy</p>
                        </div>
                    </div>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td align="left" valign="top"><input class="button-primary" onclick="printContent('toPrint');" type="button" value="Print invoice">&nbsp;&nbsp;&nbsp;</td>
                            <td align="left" valign="top">
                                <form action="%3C?php%20echo%20admin_url('admin.php?page=conference-sales',%20'https');%20?%3E" id="clear" method="post" name="clear">
                                    <input name="product_id" type="hidden" value="null"> <input name="action" type="hidden" value="empty"> <input class="button-primary" type="submit" value="New order">
                                </form>
                            </td>
                        </tr>
                    </table><?php
                endif;
                ?><?php
                } else {
                ?>
                    <script type="text/javascript">

                        jQuery(document).ready(function () {

                            jQuery("input[value$='credit']").click(function () {

                                jQuery(".desc").hide();

                                jQuery("#credit").show();

                            });

                            jQuery("input[value$='check']").click(function () {

                                jQuery(".desc").hide();

                                jQuery("#check").show();

                            });

                            jQuery("input[value$='cash']").click(function () {

                                jQuery(".desc").hide();

                                jQuery("#cash").show();

                            });

                        });

                    </script>
                    <script type="text/javascript">

                        function setFromCCS() {

                            document.getElementById("swipe").blur(function (e) {
                                e.preventDefault();
                            });

                            var ccs = document.getElementById("swipe").value;

                            var index1 = ccs.indexOf("%B") + 2;

                            var index2 = ccs.indexOf("^") + 1;

                            var index3 = ccs.indexOf("^", index2 + 1) + 1;


                            var cardNumber = ccs.substring(index1, index2 - 1);

                            var expMonth = ccs.substr(index3, 2);

                            var expYear = ccs.substr(index3 + 2, 2);

                            var holderName = ccs.substring(index2, index3 - 1);

                            var index4 = holderName.indexOf("/");

                            var temp1 = holderName.substring(0, index4);

                            var temp2 = holderName.substring(index4 + 1);

                            holderName = temp2 + ' ' + temp1;


                            document.getElementById("swipe").style.display = "none";

                            document.getElementById("ch").value = holderName;

                            document.getElementById("cn").value = cardNumber;

                            document.getElementById("cm").value = expMonth;

                            document.getElementById("cy").value = expYear;

                        }


                    </script>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td valign="top">
                                <?php
                                if ($_REQUEST['cn']):
                                    $_SESSION['email']    = $_REQUEST["email"];
                                    $_SESSION['customer'] = $_REQUEST["ch"];
                                    ?>
                                    <h4 style="font-size: 14px;">Verify customer information</h4>
                                    <p><strong>Name:</strong> <?php
                                        echo $_REQUEST['ch'];
                                        ?><br>
                                        <strong>Contact:</strong> <?php
                                        echo $_REQUEST['email'];
                                        ?><br>
                                        <strong>Billing address:</strong> <?php
                                        echo $_REQUEST['AVSADDR'];
                                        ?><br>
                                        <?php
                                        echo $_REQUEST['AVSCITY'];
                                        ?><?php
                                        if ($_REQUEST['AVSSTATE'] != "") {
                                            ?>, <?php
                                        };
                                        ?><?php
                                        echo $_REQUEST['AVSSTATE'];
                                        ?> <?php
                                        echo $_REQUEST['AVSZIP'];
                                        ?></p>
                                    <form action="https://webservices.primerchants.com/billing/TransactionCentral/processCC.asp?" id="frmReturn" method="post" name="frmReturn">
                                    <input id="100846" name="MerchantID" type="hidden" value="<?php echo get_option('merchantid'); ?>"> <input id="5QJ6J3H3YSYZAAZA" name="RegKey" type="hidden" value="<?php echo get_option('regkey'); ?>"> <input name="CCRURL" type="hidden" value="<?php echo admin_url('admin.php?page=conference-sales&', 'https'); ?>"> <input name="ConfirmPage" type="hidden" value="Y"> <input name="RefID" type="hidden" value="<?php echo $newinvoice; ?>"> <input name="Amount" type="hidden" value="<?php echo $cost; ?>"> <input name="TaxAmount" type="hidden" value="<?php echo $tax; ?>"> <input name="TaxIndicator" type="hidden" value="1"> <input name="NameonAccount" type="hidden" value="<?php echo $_REQUEST['ch']; ?>"> <input name="AccountNo" type="hidden" value="<?php echo $_REQUEST['cn']; ?>"> <input name="CCMonth" type="hidden" value="<?php echo $_REQUEST['cy']; ?>"> <input name="CCYear" type="hidden" value="<?php echo $_REQUEST['cm']; ?>"> <input name="CVV2" type="hidden" value="<?php echo $_REQUEST['CVV2']; ?>"> <input name="AVSADDR" type="hidden" value="<?php echo $_REQUEST['AVSADDR']; ?>"> <input name="AVSZIP" type="hidden" value="<?php echo $_REQUEST['AVSZIP']; ?>"> <input name="ShipToZipCode" type="hidden" value="<?php echo $_REQUEST['AVSZIP']; ?>"> <input name="USER1" type="hidden" value="credit"> <input class="button-primary" type="submit" value="Process Credit Payment">
                                    </form><?php
                                else:
                                    ?>
                                    <div class="desc" id="cash" style="display: none;">
                                        <form action="" id="cashform" method="post" name="cashform">
                                            <table border="0" cellpadding="0" cellspacing="0" id="formtable" width="100%">
                                                <tr>
                                                    <td align="right"><input name="product_id" type="hidden" value="null"> <input name="paytype" type="hidden" value="cash"> <label for="customer">Customer name:</label>&nbsp;</td>
                                                    <td><input name="customer" type="text"></td>
                                                </tr>
                                                <tr>
                                                    <td align="right"><label for="email">Email address:</label>&nbsp;</td>
                                                    <td><input name="email" type="text"></td>
                                                </tr>
                                                <tr>
                                                    <td align="right"><input name="action" type="hidden" value="process"> <input name="cost" type="hidden" value="<?php echo $order; ?>"> <input name="tax" type="hidden" value="<?php echo $tax; ?>"> <input name="RefNo" type="hidden" value="<?php echo $newinvoice; ?>"> Amount:&nbsp;$&nbsp;</td>
                                                    <td><input name="payment" type="text"></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td><input class="button-primary" type="submit" value="Cash Payment"></td>
                                                </tr>
                                            </table>
                                        </form>
                                    </div>
                                    <div class="desc" id="check" style="display: none;">
                                        <form action="" id="checkform" method="post" name="checkform">
                                            <table border="0" cellpadding="0" cellspacing="0" id="formtable" width="100%">
                                                <tr>
                                                    <td align="right"><input name="product_id" type="hidden" value="null"> <input name="paytype" type="hidden" value="check"> <label for="customer">Name on check:</label>&nbsp;</td>
                                                    <td><input name="customer" type="text"></td>
                                                </tr>
                                                <tr>
                                                    <td align="right"><label for="email">Email address:</label>&nbsp;</td>
                                                    <td><input name="email" type="text"> <input name="action" type="hidden" value="process"> <input name="cost" type="hidden" value="<?php echo $order; ?>"> <input name="tax" type="hidden" value="<?php echo $tax; ?>"> <input name="payment" type="hidden" value="<?php echo $cost; ?>"> <input name="RefNo" type="hidden" value="<?php echo $newinvoice; ?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td><input class="button-primary" type="submit" value="Check Payment"></td>
                                                </tr>
                                            </table>
                                        </form>
                                    </div>
                                    <div class="desc" id="credit" style="display: none; margin-left: 25px;">
                                    <table border="0" cellpadding="0" cellspacing="0" id="formtable" width="100%">
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td><input id="swipe" placeholder="Swipe card &hellip;" type="text" value=""></td>
                                        </tr>
                                        <tr>
                                            <td align="right">
                                                <form action="" id="TRANSFIRST" method="post" name="creditform">
                                                    <input id="TrackData" name="TrackData" type="hidden"> <label for="ch">Name on card:</label>&nbsp;
                                                </form>
                                            </td>
                                            <td><input id="ch" name="ch" onclick="setFromCCS()" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td align="right"><label for="email">Email address:</label>&nbsp;</td>
                                            <td><input name="email" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td align="right"><label for="cn">Credit card #:</label>&nbsp;</td>
                                            <td><input id="cn" name="cn" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td align="right"><label for="cm">Expires:</label>&nbsp;</td>
                                            <td><input id="cy" maxlength="2" name="cy" placeholder="<?php echo date('m'); ?>" size="2" type="text">/<input id="cm" maxlength="2" name="cm" placeholder="<?php echo substr(date('Y'), -2); ?>" size="2" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td align="right"><label for="CVV2">Verification #:</label>&nbsp;</td>
                                            <td><input maxlength="4" name="CVV2" size="4" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td align="right"><label for="AVSADDR">Address:</label>&nbsp;&nbsp;</td>
                                            <td><input name="AVSADDR" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td align="right"><label for="AVSCITY">City:</label>&nbsp;</td>
                                            <td><input name="AVSCITY" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td align="right"><label for="AVSSTATE">State:</label>&nbsp;</td>
                                            <td><select name="AVSSTATE">
                                                    <option selected="selected" value="">
                                                    </option>
                                                    <option value="AL">
                                                        AL
                                                    </option>
                                                    <option value="AK">
                                                        AK
                                                    </option>
                                                    <option value="AZ">
                                                        AZ
                                                    </option>
                                                    <option value="AR">
                                                        AR
                                                    </option>
                                                    <option value="CA">
                                                        CA
                                                    </option>
                                                    <option value="CO">
                                                        CO
                                                    </option>
                                                    <option value="CT">
                                                        CT
                                                    </option>
                                                    <option value="DE">
                                                        DE
                                                    </option>
                                                    <option value="DC">
                                                        DC
                                                    </option>
                                                    <option value="FL">
                                                        FL
                                                    </option>
                                                    <option value="GA">
                                                        GA
                                                    </option>
                                                    <option value="HI">
                                                        HI
                                                    </option>
                                                    <option value="ID">
                                                        ID
                                                    </option>
                                                    <option value="IL">
                                                        IL
                                                    </option>
                                                    <option value="IN">
                                                        IN
                                                    </option>
                                                    <option value="IA">
                                                        IA
                                                    </option>
                                                    <option value="KS">
                                                        KS
                                                    </option>
                                                    <option value="KY">
                                                        KY
                                                    </option>
                                                    <option value="LA">
                                                        LA
                                                    </option>
                                                    <option value="ME">
                                                        ME
                                                    </option>
                                                    <option value="MD">
                                                        MD
                                                    </option>
                                                    <option value="MA">
                                                        MA
                                                    </option>
                                                    <option value="MI">
                                                        MI
                                                    </option>
                                                    <option value="MN">
                                                        MN
                                                    </option>
                                                    <option value="MS">
                                                        MS
                                                    </option>
                                                    <option value="MO">
                                                        MO
                                                    </option>
                                                    <option value="MT">
                                                        MT
                                                    </option>
                                                    <option value="NE">
                                                        NE
                                                    </option>
                                                    <option value="NV">
                                                        NV
                                                    </option>
                                                    <option value="NH">
                                                        NH
                                                    </option>
                                                    <option value="NJ">
                                                        NJ
                                                    </option>
                                                    <option value="NM">
                                                        NM
                                                    </option>
                                                    <option value="NY">
                                                        NY
                                                    </option>
                                                    <option value="NC">
                                                        NC
                                                    </option>
                                                    <option value="ND">
                                                        ND
                                                    </option>
                                                    <option value="OH">
                                                        OH
                                                    </option>
                                                    <option value="OK">
                                                        OK
                                                    </option>
                                                    <option value="OR">
                                                        OR
                                                    </option>
                                                    <option value="PA">
                                                        PA
                                                    </option>
                                                    <option value="RI">
                                                        RI
                                                    </option>
                                                    <option value="SC">
                                                        SC
                                                    </option>
                                                    <option value="SD">
                                                        SD
                                                    </option>
                                                    <option value="TN">
                                                        TN
                                                    </option>
                                                    <option value="TX">
                                                        TX
                                                    </option>
                                                    <option value="UT">
                                                        UT
                                                    </option>
                                                    <option value="VT">
                                                        VT
                                                    </option>
                                                    <option value="VA">
                                                        VA
                                                    </option>
                                                    <option value="WA">
                                                        WA
                                                    </option>
                                                    <option value="WV">
                                                        WV
                                                    </option>
                                                    <option value="WI">
                                                        WI
                                                    </option>
                                                    <option value="WY">
                                                        WY
                                                    </option>
                                                </select></td>
                                        </tr>
                                        <tr>
                                            <td align="right"><label for="AVSZIP">ZIP:</label>&nbsp;</td>
                                            <td><input name="AVSZIP" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td><input class="button-primary" type="submit" value="Continue"></td>
                                        </tr>
                                    </table>
                                    </div><?php
                                endif;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td height="35px" valign="top">
                                <h4><strong>Due: $<?php
                                        echo $cost;
                                        ?></strong></h4>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <p id="paytype"><strong>Payment type:</strong><br>
                                    <input name="paytype" type="radio" value="cash"> Cash<br>
                                    <input name="paytype" type="radio" value="check"> Check<br>
                                    <input name="paytype" type="radio" value="credit"> Credit</p>
                            </td>
                        </tr>
                    </table><?php
                }
                ?>
            </td>
        </tr>
    </table>

<?php
function add_item()
{
    $barcode_id = $_REQUEST[request_sale_id()];
    $ISBN = $_REQUEST[request_sale_isbn()];
    $quantity = $_REQUEST[request_sale_quantity()];
    $book = null;

    if ($_REQUEST[request_sale_id()]) {
        $query = new WP_Query(
            array(
                'post_type' => 'bookstore',
                'meta_key' => '_cmb_resource_barcode',
                'meta_value' => $barcode_id,
                'numberposts' => 1,
                'exact' => 1,
            )
        );
        $books = $query->get_posts();
        if (!empty($books)) {
            $book = $books[0];
        }
    }
    else if ($_REQUEST['ISBN']) {
        $query = new WP_Query(
            array(
                'post_type' => 'bookstore',
                'meta_key' => '_cmb_resource_isbn',
                'meta_value' => $ISBN,
                'numberposts' => 1,
                'exact' => 1,
            )
        );
        $books = $query->get_posts();
        if (!empty($books)) {
            $book = $books[0];
        }
    }
    if ($book != null) {
        $cart = $_SESSION[request_sale_session_cart()];
        if (!$cart) $_SESSION[request_sale_session_cart()] = array();
        if (array_key_exists($book, $cart)) {
            $cartbook = $cart[$book];
            $cartbook[1] += $quantity;
            $cart[$book] = $cartbook;
        } else {
            $cartbook = array($book, $quantity);
            $cart[$book] = $cartbook;
        }
    }
}

function list_books() {
    $cart = $_SESSION[request_sale_session_cart()];
    if (!$cart) $_SESSION[request_sale_session_cart()] = array();
    foreach ($cart as $cartObj) {
        $book = $cartObj[0];
        $quantity = $cartObj[1];

        $price = str_replace("$", "", get_book_saleprice($book)); //just in case any slip by
        $line_cost = $price * $quantity;

        ?>
        <tr>
        <td>
            <?php
            echo get_the_title($book);
            ?>
        </td>
        <td align="center">
            <span class="qty">
                <?php
                echo $quantity;
                ?>
            </span>
            <form action="" class="remove" method="post">
                <input name="<?php echo request_selected_book() ?>" type="hidden" value="<?php echo $book; ?>"/>
                <input name="<?php echo request_page_action() ?>" type="hidden"
                       value="<?php echo request_sale_remove_item_action() ?>"/>
                <input name="<?php echo request_sale_remove_amount() ?>" type="hidden"
                       value="<?php echo sale_remove_one() ?>"/>
                <input type="submit" value="-"/>
            </form>
            <form action="" class="remove" method="post">
                <input name="<?php echo request_selected_book() ?>" type="hidden" value="<?php echo $book; ?>"/>
                <input name="<?php echo request_page_action() ?>" type="hidden"
                       value="<?php echo request_sale_remove_item_action() ?>"/>
                <input name="<?php echo request_sale_remove_amount() ?>" type="hidden"
                       value="<?php echo sale_remove_all() ?>"/>
                <input type="submit" value="X"/>
            </form>
        </td>
        <td>&nbsp;</td>
        <td class="price">
            <?php echo '$' . number_format($line_cost, 2); ?></td>
        <td>&nbsp;</td>
        </tr><?php
    }
}

function get_total() {
    $taxpercent = get_option('ctaxrate') / 100;
    $subtotal = get_subtotal();
    return $subtotal + $subtotal * $taxpercent;
}

function get_subtotal() {
    $cart = $_SESSION[request_sale_session_cart()];
    if (!$cart) $_SESSION[request_sale_session_cart()] = array();
    $total = 0;
    foreach ($cart as $cartObj){
        $book = $cartObj[0];
        $quantity = $cartObj[1];
        $price = get_book_saleprice($book);
        $total += $quantity * $price;
    }
    return $total;
}

function remove_item() {
    $product_removed = $_REQUEST[request_selected_book()];
    $removeQuantity = $_REQUEST[request_sale_remove_amount()];

    $cart = $_SESSION[request_sale_session_cart()];
    if (!$cart) $_SESSION[request_sale_session_cart()] = array();
    if (array_key_exists($product_removed, $cart)) {
        if ($removeQuantity == sale_remove_all()){
            unset($cart[$product_removed]);
            $cart = array_values($cart);
            $_SESSION[request_sale_session_cart()] = $cart;
        }
        else {
            $cartbook = $cart[$product_removed];
            $cartbook[1] -= 1;
            if ($cartbook[1] > 0) {
                $cart[$product_removed] = $cartbook;
            }
            else {
                unset($cart[$product_removed]);
                $cart = array_values($cart);
                $_SESSION[request_sale_session_cart()] = $cart;
            }
        }
    }
}

function clear_cart()
{
    unset($_SESSION[request_sale_session_cart()]);
    $_SESSION[request_sale_session_cart()] = array();
    unset($_SESSION[request_sale_session_credit()]);
    $_SESSION[request_sale_session_credit()] = array();
}

function add_credit()
{
    $credit = $_REQUEST[request_sale_credit()];
    $creditname = $_REQUEST[request_sale_credit_name()];

    if ($credit) {
        $sessionCredit = $_SESSION[request_sale_session_credit()];
        if (!$sessionCredit) $_SESSION[request_sale_session_credit()] = array();
        $sessionCredit[] = array($creditname, $credit);
        $_SESSION[request_sale_session_credit()] = $sessionCredit;
    }
}

function remove_credit()
{
    $credit_indice = $_REQUEST[request_sale_credit_indice()];

    if ($credit_indice) {
        $sessionCredit = $_SESSION[request_sale_session_credit()];
        if (!$sessionCredit) $_SESSION[request_sale_session_credit()] = array();
        unset($sessionCredit[$credit_indice]);
        $sessionCredit = array_values($sessionCredit);
        $_SESSION[request_sale_session_credit()] = $sessionCredit;
    }
}
?>