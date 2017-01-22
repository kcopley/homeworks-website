<?php

session_start();

include_once "includes.php";
selection::GetIDS();

if ($_REQUEST[request_sales_Auth()]) {
    $successful = process_auth();
    if ($successful) {
        $_REQUEST[checkout_payment::$amount_paid] = $_SESSION['last_credit_payment'];
        $_REQUEST[checkout_payment::$payment_type] = checkout_payment::$payment_credit;
        $paid = checkout_payment::GetAmountPaid();
        $currentPaid = checkout_payment::GetTotalAmountPaid();
        $totalPaid = $currentPaid + $paid;
        checkout_payment::SetTotalAmountPaid($totalPaid);
    }
}
else {
    unset($_SESSION['last_credit_payment']);
}


switch (page_action::GetAction()){
    case action_types::$add_item_checkout:
        if (!$_SESSION[checkout_payment::$current_transaction_id] || Transaction::$props[Transaction::$complete]->GetValue($_SESSION[checkout_payment::$current_transaction_id]) == 1) {
            if (isset($_POST['add_item_button'])) {
                checkout_cart::add_book(checkout_request::GetBarcode(), checkout_request::GetISBN(), checkout_request::GetQuantity());
            } else if (isset($_POST['refund_item_button'])) {
                checkout_cart::add_refund(checkout_request::GetBarcode(), checkout_request::GetISBN(), checkout_request::GetQuantity());
            }
        }
        break;
    case action_types::$add_credit_checkout:
        $tid = $_SESSION[checkout_payment::$current_transaction_id];
        if (!$tid || Transaction::$props[Transaction::$complete]->GetValue($tid) == 1) {
            if (checkout_request::GetCreditAmount() != -1) {
                checkout_cart::add_credit(checkout_request::GetCreditName(), checkout_request::GetCreditAmount());
            }
        }
        break;
    case action_types::$remove_item_checkout:
        $tid = $_SESSION[checkout_payment::$current_transaction_id];
        if (!$tid || Transaction::$props[Transaction::$complete]->GetValue($tid) == 1) {
            checkout_cart::remove_book_from_cart(selection::GetID(Book::$source), checkout_request::GetRemoveQuantity());
            Transaction::set_books_from_cart($transaction, checkout_cart::GetCart());
        }
        break;
    case action_types::$remove_credit_checkout:
        $tid = $_SESSION[checkout_payment::$current_transaction_id];
        if (!$tid || Transaction::$props[Transaction::$complete]->GetValue($tid) == 1) {
            checkout_cart::remove_credit(checkout_request::GetCreditIndex());
            Transaction::set_credits_from_cart($transaction, checkout_cart::GetCredit());
        }
        break;
    case action_types::$clear_checkout_cart:
        $tid = $_SESSION[checkout_payment::$current_transaction_id];
        if ($tid && Transaction::$props[Transaction::$complete]->GetValue($tid) == 2){
            unset($_SESSION[checkout_payment::$current_transaction_id]);
            unset($_SESSION[checkout_payment::$total_amount_paid]);
        }
        checkout_cart::clear_all();
        break;
    case action_types::$clear_checkout_credit:
        $tid = $_SESSION[checkout_payment::$current_transaction_id];
        if (!$tid || Transaction::$props[Transaction::$complete]->GetValue($tid) == 1) {
            checkout_cart::clear_credit();
        }
        break;
    case action_types::$process_payment:
        $paid = checkout_payment::GetAmountPaid();
        $currentPaid = checkout_payment::GetTotalAmountPaid();
        $totalPaid = $currentPaid + $paid;
        checkout_payment::SetTotalAmountPaid($totalPaid);
        break;
    case action_types::$pre_card_submission:
        $_SESSION['last_credit_payment'] = checkout_payment::GetAmountPaid();
        break;
    case action_types::$clear_checkout:
        $tid = $_SESSION[checkout_payment::$current_transaction_id];
        if ($tid && Transaction::$props[Transaction::$complete]->GetValue($tid) == 1){
            wp_delete_post($tid);
        }
        checkout_cart::clear_all();
        unset($_SESSION[checkout_payment::$current_transaction_id]);
        unset($_SESSION[checkout_payment::$total_amount_paid]);
        break;
    case action_types::$clear_checkout_save:
        $tid = $_SESSION[checkout_payment::$current_transaction_id];
        if ($tid && (empty(checkout_cart::GetCart()) && empty(checkout_cart::GetCredit()) && empty(checkout_cart::GetRefundBooks()) && empty(Transaction::get_payment_types($tid)))){
            wp_delete_post($tid);
        }
        else {
            create_transaction();
        }
        checkout_cart::clear_all();
        unset($_SESSION[checkout_payment::$current_transaction_id]);
        unset($_SESSION[checkout_payment::$total_amount_paid]);
        break;
    case action_types::$import_transaction:
        $tid = $_SESSION[checkout_payment::$current_transaction_id];
        if ($tid && Transaction::$props[Transaction::$complete]->GetValue($tid) == 1){
            wp_delete_post($tid);
        }
        checkout_cart::clear_all();
        unset($_SESSION[checkout_payment::$current_transaction_id]);
        unset($_SESSION[checkout_payment::$total_amount_paid]);
        $tid = selection::GetID(Transaction::$source);
        import_transaction($tid);
}


if (!empty(checkout_cart::GetCart()) || !empty(checkout_cart::GetCredit()) || !empty(checkout_cart::GetRefundBooks()) || checkout_payment::GetTotalAmountPaid() != 0.0)
    create_transaction();
else {
    $tid = $_SESSION[checkout_payment::$current_transaction_id];
    if ($tid && Transaction::$props[Transaction::$complete]->GetValue($tid) == 1){
        wp_delete_post($tid);
    }
}

$table = new TableArr(border(0).cellpadding(0).cellspacing(0).width(100), //global page table
    new Row(
        new Column(width(80).valign('top'),
            get_books_and_credit()
        ),
        new Column(width(1).style('border-right: solid; border-width: 1px; border-color: #D0D0D0;')),
        new Column(width(1)),
        new Column(width(18).valign('top'),
            get_payments()
        )
    )
);

if ($_SESSION[checkout_payment::$current_transaction_id]) {
    Transaction::print_formatting($_SESSION[checkout_payment::$current_transaction_id]);
}

wp_enqueue_media();
$table->Render();
$tid = $_SESSION[checkout_payment::$current_transaction_id];
if (!$tid || Transaction::$props[Transaction::$complete]->GetValue($tid) == 1) {
    insert_scripts();
}

function get_paid_message() {
    $list = new RenderList();
    if (checkout_payment::GetAmountPaid() > 0) {
        $type = checkout_payment::GetPaymentType();
        if ($type == checkout_payment::$payment_cash){
            $list->add_object(new Strong(new TextRender('Cash Payment Received.')));
        }
        else if ($type == checkout_payment::$payment_credit){
            $list->add_object(new Strong(new TextRender('Payment received at TransFirst/Transaction Central.')));
        }
        else if ($type == checkout_payment::$payment_check){
            $list->add_object(new Strong(new TextRender('Check Payment Received.')));
        }
        else if ($type == checkout_payment::$payment_phone){
            $list->add_object(new Strong(new TextRender('Payment handled by phone.')));
        }
        $list->add_object(new BR());
    }
    return $list;
}

function get_paid_formatting() {
    $list = new RenderList();
    if (checkout_payment::GetTotalAmountPaid() > 0) {
        $list->add_object(new H4(style('margin: 0px;'),
            new Strong(new TextRender('Paid: $' . number_format(round(checkout_payment::GetTotalAmountPaid(), 2), 2)))));
        $list->add_object(new BR());
    }
    return $list;
}

function get_refund_formatting() {
    $list = new RenderList();
    if (round(get_total() - checkout_payment::GetTotalAmountPaid(), 2) < 0) {
        $list->add_object(new H4(style('margin: 0px;'),
            new Strong(new TextRender('Refund: $' . -number_format((float) round(get_total() - checkout_payment::GetTotalAmountPaid(), 2), 2, '.', '')))));
    }
    else {
        $list->add_object(new H4(style('margin: 0px;'),
            new Strong(new TextRender('Due: $' . number_format(round(get_total() - checkout_payment::GetTotalAmountPaid(), 2), 2)))));
    }
    $list->add_object(new BR());
    return $list;
}

function get_payments() {
    return new TableArr(border(0).cellpadding(0).cellspacing(0).width(100), //right side (totals/payment) table
        new Row(
            new Column(style('padding-top: 16px; font-size: 16px; font-weight: bold;').valign('top'),
                new TableArr(border(0).cellpadding(0).cellspacing(0).width(100),
                    new Row(
                        new Column(width(60),
                            new TableArr(border(0).cellpadding(0).cellspacing(0).width(100),
                                new Row(
                                    new Column(
                                        get_paid_message())
                                ),
                                new Row(
                                    new Column(style('padding-top: 6px;'),
                                        get_paid_formatting(),
                                        get_refund_formatting()
                                    )
                                )
                            )
                        ),
                        new Column(
                            new TableArr(border(0).cellpadding(0).cellspacing(0).width(100),
                                new Row(
                                    new Column(colspan(2).style('padding-top: 6px;').align('right'),
                                        new Form(
                                            page_action::InputAction(action_types::$clear_checkout),
                                            button('New Order')
                                        )
                                    )
                                ),
                                new Row(
                                    new Column(colspan(2).style('padding-top: 6px;').align('right'),
                                        new Form(
                                            page_action::InputAction(action_types::$clear_checkout_save),
                                            button('Save Order')
                                        )
                                    )
                                ),
                                new Row(
                                    new Column(colspan(2).style('padding-top: 6px;').align('right'),
                                        new Input(classType('button-primary') . onclick("printContent('toPrint');") . type('button') . value('Print Invoice'))
                                    )
                                )
                            )
                        )
                    )
                )
            )
        ),
        new Row(
            new Column(valign('top'),
                new Paragraph(id(checkout_payment::$payment_type),
                    new Strong(new TextRender('Payment Type:')),
                    new BR(),
                    new Input(type('radio').name(checkout_payment::$payment_type).id(checkout_payment::$payment_cash).value(checkout_payment::$payment_cash)),
                    new TextRender(' Cash'),
                    new BR(),
                    new Input(type('radio').name(checkout_payment::$payment_type).id(checkout_payment::$payment_check).value(checkout_payment::$payment_check)),
                    new TextRender(' Check'),
                    new BR(),
                    new Input(type('radio').name(checkout_payment::$payment_type).id(checkout_payment::$payment_phone).value(checkout_payment::$payment_phone)),
                    new TextRender(' Phone'),
                    new BR(),
                    new Input(type('radio').name(checkout_payment::$payment_type).id(checkout_payment::$payment_credit).value(checkout_payment::$payment_credit)),
                    new TextRender(' Credit')
                )
            )
        ),
        new Row(
            new Column(
                new Div(id(checkout_payment::$display_payment_info).classType('desc').style('display: none;'),
                    new H4(new TextRender('Payment Info:'))
                ),
                verify_customer_card_info(),
                transfirst_submission(),
                get_payment_info()
            )
        )

    );
}

function verify_customer_card_info() {
    $renderlist = new RenderList();
    if (page_action::GetAction() == action_types::$pre_card_submission) {
        $renderlist->add_object(
            new H4(new TextRender('Verify Customer Information'))
        );
        $renderlist->add_object(
            new Paragraph(
                new Strong(new TextRender('Name: ')),
                new TextRender(checkout_payment::GetName()."<br>"),
                new Strong(new TextRender('Email: ')),
                new TextRender(checkout_payment::GetEmail()."<br>"),
                new Strong(new TextRender('Billing Address: ')),
                new TextRender(checkout_payment::GetAddress()."<br>"),
                new TextRender(checkout_payment::GetCity()),
                new TextRender(get_state_comma(checkout_payment::GetState())),
                new TextRender(checkout_payment::GetState().' '),
                new TextRender(checkout_payment::GetZip())
            )
        );
    }
    return $renderlist;

}

function transfirst_submission() {
    $form = new RenderList();
    if (page_action::GetAction() == action_types::$pre_card_submission) {
        $form = new Form(id('TRANSFIRST').action("https://webservices.primerchants.com/billing/TransactionCentral/processCC.asp?").name('frmReturn').id('frmReturn'),
       // $form = new Form(id('TRANSFIRST').action(admin_url('admin.php?page=conference-sales&', 'https')).name('frmReturn').id('frmReturn'),
            //added here
           // new Input(type('hidden').name('Auth').value('Approved')),
            new Input(type('hidden').name('MerchantID').id('100846').value(get_option('merchantid'))),
            new Input(type('hidden').name('RegKey').id('5QJ6J3H3YSYZAAZA').value(get_option('regkey'))),
            new Input(type('hidden').name('CCRURL').value(admin_url('admin.php?page=conference-sales&', 'https'))),
            new Input(type('hidden').name('ConfirmPage').value('Y')),
            new Input(type('hidden').name('RefID').value(checkout_payment::GetTransactionID())),
            new Input(type('hidden').name('Amount').value(checkout_payment::GetAmountPaid())),
            new Input(type('hidden').name('TaxAmount').value(get_paid_amount_tax_total())),
            new Input(type('hidden').name('TaxIndicator').value(1)),
            new Input(type('hidden').name('NameonAccount').value(checkout_payment::GetName())),
            new Input(type('hidden').name('AccountNo').value(checkout_payment::GetCardNumber())),
            new Input(type('hidden').name('CCMonth').value(checkout_payment::GetCardExpirationMonth())),
            new Input(type('hidden').name('CCYear').value(checkout_payment::GetCardExpirationYear())),
            new Input(type('hidden').name('CVV2').value(checkout_payment::GetCardVerification())),
            new Input(type('hidden').name('AVSADDR').value(checkout_payment::GetAddress())),
            new Input(type('hidden').name('AVSZIP').value(checkout_payment::GetZip())),
            new Input(type('hidden').name('ShipToZipCode').value(checkout_payment::GetZip())),
            new Input(type('hidden').name('USER1').value('credit')),
            new Input(classType('button-primary').type('submit').value('Process Credit Payment'))
        );
    }
    return $form;
}

function get_payment_info() {
    $list = new RenderList(
        get_customer_cash_info(),
        get_customer_check_info(),
        get_customer_credit_info(),
        get_customer_phone_info()
    );
    return $list;
}

function process_auth() {
    if ($_REQUEST[request_sales_Auth()] == sales_AuthCodeDeclined()) {
        $cw2 = $_REQUEST[request_sales_CCResponse()];
        if ($cw2 == sales_purchase_error()) {
            $render = new RenderList(
                new H4(new TextRender('Purchase Error')),
                new H4(new TextRender('There was an error processing the order.'))
            );
            $render->Render();
        }
        else if ($cw2 == sales_incorrect_number()) {
            $render = new RenderList(
                new H4(new TextRender('Card Error')),
                new H4(new TextRender('Credit card information does not match.'))
            );
            $render->Render();
        }
        else {
            $render = new RenderList(
                new H4(new TextRender('Processing Error')),
                new H4(new TextRender('Your card could not be identified.'))
            );
            $render->Render();
        }
        return false;
    }
    return true;
}

function create_transaction() {
    $transaction = $_SESSION[checkout_payment::$current_transaction_id];
    if ($transaction && Transaction::$props[Transaction::$complete]->GetValue($transaction) == 2) {
        return;
    }
    if (!$transaction) {
        $complete = Transaction::$props[Transaction::$complete]->GetValue($transaction);
        if ($complete) return;
        $confname = get_option(vars::$conference_name_option);
        if (!$confname){
            $confname = '';
        }
        else {
            $confname = $confname.' ';
        }
        $newinvoice    = get_next_invoice();
        $name = array(
            'post_title' => $confname.'Conference #'.$newinvoice,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_category' => array(
                1
            ),
            'post_type' => 'transactions'
        );
        $transaction = wp_insert_post($name);
        Transaction::$props[Transaction::$id]->SetValue($transaction, $confname.'Conference #'.$newinvoice);
        Transaction::$props[Transaction::$invoiceid]->SetValue($transaction, $newinvoice);
        $_SESSION[checkout_payment::$current_transaction_id] = $transaction;
    }
    Transaction::$props[Transaction::$conference]->SetValue($transaction, 1);
    if (checkout_payment::GetName()) Transaction::$props[Transaction::$customer_name]->SetValue($transaction, checkout_payment::GetName());
    if (checkout_payment::GetEmail()) Transaction::$props[Transaction::$customer_email]->SetValue($transaction, checkout_payment::GetEmail());
    if (!Transaction::$props[Transaction::$customer_address])
        Transaction::$props[Transaction::$customer_address]->SetValue($transaction, "Conference Sale");

    Transaction::$props[Transaction::$taxrate]->SetValue($transaction, str_replace('$', '', get_option('ctaxrate') / 100));
    date_default_timezone_set('America/Chicago');
    Transaction::$props[Transaction::$date]->SetValue($transaction, date('Y-m-d'));
    Transaction::$props[Transaction::$total]->SetValue($transaction, get_total());

    $roundedPaid = checkout_payment::GetTotalAmountPaid() * 100;
    $roundedPaid = ceil($roundedPaid);
    $roundedTotal = get_total() * 100;
    $roundedTotal = floor($roundedTotal);

    if ($roundedPaid >= $roundedTotal) {
        Transaction::$props[Transaction::$complete]->SetValue($transaction, 2);
        $cart = checkout_cart::GetCart();
        if (!empty($cart)) {
            foreach ($cart as $key => $value) {
                $quantity = $value[checkout_cart::$cart_book_quantity];
                $book_id = $key;

                for ($i = 0; $i < $quantity; $i++) {
                    Book::sell_book($book_id);
                }
            }
        }

        $refunds = checkout_cart::GetRefundBooks();
        if (!empty($refunds)) {
            foreach ($refunds as $key => $value) {
                $quantity = $value[checkout_cart::$cart_book_quantity];
                $book_id = $key;

                for ($i = 0; $i < $quantity; $i++) {
                    Book::add_book($book_id, get_consigner_owner_id());
                }
            }
        }
    }
    else {
        Transaction::$props[Transaction::$complete]->SetValue($transaction, 1);
    }

    Transaction::set_books_from_cart($transaction, checkout_cart::GetCart());
    Transaction::set_credits_from_cart($transaction, checkout_cart::GetCredit());
    Transaction::set_refunds_from_cart($transaction, checkout_cart::GetRefundBooks());

    $paymentType = checkout_payment::GetPaymentType();
    $paid = checkout_payment::GetAmountPaid();
    if ($paymentType && $paid) {
        if ($paymentType != checkout_payment::$payment_credit) {
            Transaction::add_payment($transaction, $paymentType, $paid);
        } else if ($_REQUEST['USER1'] == "credit") {
            $transid = $_REQUEST['TransID'];
            Transaction::add_payment_credit($transaction, $paymentType, $paid, $transid);
        }
    }
}

function import_transaction($id) {
    if (Transaction::$props[Transaction::$complete]->GetValue($id) == 2) return;

    $_SESSION[checkout_payment::$current_transaction_id] = $id;

    $books = Transaction::get_books($id);
    foreach ($books as $book) {
        $book_id = $book[Transaction::$book_id];
        checkout_cart::add_book(Book::$props[Book::$barcode]->GetValue($book_id), Book::$props[Book::$isbn]->GetValue($book_id), $book[Transaction::$book_quantity]);
    }

    $refunds = Transaction::get_refunds($id);
    foreach ($refunds as $refund) {
        $book_id = $refund[Transaction::$book_id];
        checkout_cart::add_refund(Book::$props[Book::$barcode]->GetValue($book_id), Book::$props[Book::$isbn]->GetValue($book_id), $book[Transaction::$book_quantity]);
    }

    $credits = Transaction::get_credits($id);
    foreach ($credits as $credit) {
        checkout_cart::add_credit($credit[Transaction::$credit_name], $credit[Transaction::$credit_amount]);
    }
}

function get_customer_credit_info() {
    $leftcolwidth = 20;
    $list = new RenderList(
        new Div(id(checkout_payment::$display_credit).classType('desc').style('display: none;'),
            new Form(id('TRANSFIRST').action('').name('creditform'),
                new TableArr(id('formtable').width(100).border(0).cellspacing(0).cellpadding(0),
                    new Row(
                        new Column(width($leftcolwidth)),
                        new Column(new Input(id('swipe').type('text').value("").placeholder('Swipe card &hellip;')))
                    ),
                    new Row(
                        new Column(align('right'),
                            new Input(type('hidden').name('TrackData').id('TrackData')),
                            new Label(forAttr(checkout_payment::$name), new TextRender('Name on Card:'))
                        ),
                        new Column(
                            new Input(type('text').id(checkout_payment::$name).name(checkout_payment::$name).onclick('setFromCCS()'))
                        )
                    ),
                    new Row(
                        new Column(align('right'), new Label(forAttr(checkout_payment::$email), new TextRender('Email Address:'))),
                        new Column(checkout_payment::InputEmail())
                    ),
                    new Row(
                        new Column(align('right'), new Label(forAttr(checkout_payment::$card_number), new TextRender('Credit Card #:'))),
                        new Column(checkout_payment::InputCardNumber())
                    ),
                    new Row(
                        new Column(align('right'), new Label(forAttr(checkout_payment::$card_expiration_month), new TextRender('Expires:'))),
                        new Column(
                            new Input(type('text').name(checkout_payment::$card_expiration_month).id(checkout_payment::$card_expiration_month).maxlength(2).size(2).placeholder(date('m'))),
                            new Input(type('text').name(checkout_payment::$card_expiration_year).id(checkout_payment::$card_expiration_year).maxlength(2).size(2).placeholder(substr(date('Y'), -2)))
                        )
                    ),
                    new Row(
                        new Column(align('right'), new Label(forAttr(checkout_payment::$card_verification), new TextRender('Verification #:'))),
                        new Column(
                            new Input(type('text').name(checkout_payment::$card_verification).maxlength(4).size(4))
                        )
                    ),
                    new Row(
                        new Column(align('right'), new Label(forAttr(checkout_payment::$address), new TextRender('Address:'))),
                        new Column(checkout_payment::InputAddress())
                    ),
                    new Row(
                        new Column(align('right'), new Label(forAttr(checkout_payment::$city), new TextRender('City:'))),
                        new Column(checkout_payment::InputCity())
                    ),
                    new Row(
                        new Column(align('right'), new Label(forAttr(checkout_payment::$state), new TextRender('State:'))),
                        new Column(new TextRender(state_select()))
                    ),
                    new Row(
                        new Column(align('right'), new Label(forAttr(checkout_payment::$zip), new TextRender('Zip:'))),
                        new Column(checkout_payment::InputZip())
                    ),
                    new Row(
                        new Column(align('right'), new Label(forAttr(checkout_payment::$amount_paid), new TextRender('Amount:'))),
                        new Column(checkout_payment::InputAmountPaid())
                    ),
                    new Row(
                        new Column(),
                        new Column(align('right'),
                            page_action::InputAction(action_types::$pre_card_submission),
                            new Input(classType('button-primary').type('submit').value('Verify Credit Info'))
                        )
                    )
                )
            )
        )
    );
    return $list;
}

function get_customer_check_info() {
    $div = new Div(id(checkout_payment::$display_check).classType('desc').style('display: none;'),
        new Form(
            new TableArr(border(0).cellpadding(0).cellspacing(0).width(100),
                new Row(
                    new Column(align('right'),
                        new Label(forAttr(checkout_payment::$name), new TextRender('Name on Check:'))
                    ),
                    new Column(checkout_payment::InputName())
                ),
                new Row(
                    new Column(align('right'), new Label(forAttr(checkout_payment::$email), new TextRender('Email Address:'))),
                    new Column(
                        checkout_payment::InputEmail()
                    )
                ),
                new Row(
                    new Column(align('right'),
                        new Label(forAttr(checkout_payment::$amount_paid), new TextRender('Amount:'))
                    ),
                    new Column(checkout_payment::InputAmountPaid())
                ),
                new Row(
                    new Column(),
                    new Column(align('right').style('padding-top: 12px;'),
                        new Input(type('hidden').name(checkout_payment::$payment_type).value(checkout_payment::$payment_check)),
                        page_action::InputAction(action_types::$process_payment),
                        button('Check Payment')
                    )
                )
            )
        )
    );
    return $div;
}

function get_customer_cash_info() {
    $div = new Div(id(checkout_payment::$display_cash).classType('desc').style('display: none;'),
        new Form(
            new TableArr(border(0).cellpadding(0).cellspacing(0).width(100),
                new Row(
                    new Column(align('right'),
                        new Label(forAttr(checkout_payment::$name), new TextRender('Customer Name:'))
                    ),
                    new Column(checkout_payment::InputName())
                ),
                new Row(
                    new Column(align('right'), new Label(forAttr(checkout_payment::$email), new TextRender('Email Address:'))),
                    new Column(
                        checkout_payment::InputEmail()
                    )
                ),
                new Row(
                    new Column(align('right'),
                        new Label(forAttr(checkout_payment::$amount_paid), new TextRender('Amount:'))
                    ),
                    new Column(checkout_payment::InputAmountPaid())
                ),
                new Row(
                    new Column(),
                    new Column(align('right').style('padding-top: 12px;'),
                        new Input(type('hidden').name(checkout_payment::$payment_type).value(checkout_payment::$payment_cash)),
                        page_action::InputAction(action_types::$process_payment),
                        button('Cash Payment')
                    )
                )
            )
        )
    );
    return $div;
}

function get_customer_phone_info() {
    $div = new Div(id(checkout_payment::$display_phone).classType('desc').style('display: none;'),
        new Form(
            new TableArr(border(0).cellpadding(0).cellspacing(0).width(100),
                new Row(
                    new Column(align('right'),
                        new Label(forAttr(checkout_payment::$name), new TextRender('Customer Name:'))
                    ),
                    new Column(checkout_payment::InputName())
                ),
                new Row(
                    new Column(align('right'), new Label(forAttr(checkout_payment::$email), new TextRender('Email Address:'))),
                    new Column(
                        checkout_payment::InputEmail()
                    )
                ),
                new Row(
                    new Column(align('right'),
                        new Label(forAttr(checkout_payment::$amount_paid), new TextRender('Amount:'))
                    ),
                    new Column(checkout_payment::InputAmountPaid())
                ),
                new Row(
                    new Column(),
                    new Column(align('right').style('padding-top: 12px;'),
                        new Input(type('hidden').name(checkout_payment::$payment_type).value(checkout_payment::$payment_phone)),
                        page_action::InputAction(action_types::$process_payment),
                        button('Phone Payment')
                    )
                )
            )
        )
    );
    return $div;
}

function insert_scripts() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(
            function () {
                jQuery("#payment_credit").click(function () {
                    jQuery(".desc").hide();
                    jQuery("#display_payment_info").show();
                    jQuery("#display_credit").show();
                });
                jQuery("#payment_check").click(function () {
                    jQuery(".desc").hide();
                    jQuery("#display_payment_info").show();
                    jQuery("#display_check").show();
                });
                jQuery("#payment_cash").click(function () {
                    jQuery(".desc").hide();
                    jQuery("#display_payment_info").show();
                    jQuery("#display_cash").show();
                });
                jQuery("#payment_phone").click(function () {
                    jQuery(".desc").hide();
                    jQuery("#display_payment_info").show();
                    jQuery("#display_phone").show();
                });
            }
        );
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
        ;
    </script>
    <?php
}

function get_books_and_credit() {
    return new TableArr(border(0).cellpadding(0).cellspacing(0).width(100), //left side of page table (add item, credit, book display)
        new Row(
            new Column(
                get_add_boxes()
            )
        ),
        new Row(
            new Column(
                get_current_books()
            )
        ),
        new Row(
            new Column(
                get_totals()
            )
        )
    );
}

function get_add_boxes() {
    return new TableArr(border(0).cellpadding(0).cellspacing(0), //top of left side table, add item and credit
        new Row(
            new Column(width(30), get_add_item_box()),
            new Column(width(65)),
            new Column(width(15).align('right'), get_credit_box())
        )
    );
}

function get_add_item_box() {
    return new TableArr(style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'),
        new Form(id('cart').name('cart'),
            new Row(
                new Column(align('right'),
                    new H4(style('margin: 0px;'),new TextRender('Scan:'))),
                new Column()
            ),
            new Row(
                new Column(align('right'), new Label(new TextRender('ID:'))),
                new Column(new Input(id(checkout_request::$barcode).name(checkout_request::$barcode).type('text')))
            ),
            new Row(
                new Column(align('right'), new Label(new TextRender('ISBN:'))),
                new Column(new Input(id(checkout_request::$isbn).name(checkout_request::$isbn).type('text')))
            ),
            new Row(
                new Column(align('left'),
                    new Input(name(checkout_request::$quantity).type('hidden').value(1)),
                    page_action::InputAction(action_types::$add_item_checkout),
                    //new Button(classType('hide-button-handler').type('submit').name('add_item_button').ariahidden('true').tabindex('-1')),
                    new Input(classType('button-primary default').type('submit').name('add_item_button').value('Add Item'))
                ),
                new Column(align('right'),
                    new Input(name(checkout_request::$quantity).type('hidden').value(1)),
                    page_action::InputAction(action_types::$add_item_checkout),
                    new Input(classType('button').type('submit').name('refund_item_button').value('Add Refund'))
                )
            )
        )
    );
}

function get_current_books() {
    $table = new TableArr(width(100).border(0).cellspacing(0).cellpadding(0));

    if (checkout_cart::GetCart()) {
        $cart = checkout_cart::GetCart();

        $renderlist = new RenderList(
            new Row(
                new Column(style('padding-top: 20px; padding-bottom: 3px;').width(checkout_width_constants::$bookTitleWidth).align('left'),
                    new H4(style('margin: 0px; font-size: 14px;'), new TextRender('Books'))),
                new Column(style('padding-top: 20px; padding-bottom: 3px;').width(checkout_width_constants::$bookQuantityWith).align('center'),
                    new H4(style('margin: 0px; font-size: 14px;'), new TextRender('Quantity'))),
                new Column(style('padding-top: 20px; padding-bottom: 3px;').width(checkout_width_constants::$bookPriceWidth).align('left'),
                    new H4(style('margin: 0px; font-size: 14px;'), new TextRender('Price'))),
                new Column(width(checkout_width_constants::$bookExtraWidth))
            ),
            new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan(10).style('padding-bottom: 8px; border-top: solid; border-width: 3px; border-color: #B0B0B0;'))
            )
        );
        $table->add_object($renderlist);

        $first = true;
        if (!empty($cart)) {
            foreach ($cart as $key => $value) {
                if (!$first) {
                    $table->add_object(
                        new Row(style('border: none; padding: 0px; height: 1px;'),
                            new Column(colspan(10) . style('padding-bottom: 0px; padding-top: 3px; border-bottom: solid; border-width: 1px; border-color: #F0F0F0;'))
                        )
                    );
                    $table->add_object(
                        new Row(style('border: none; padding: 0px; height: 1px;'),
                            new Column(colspan(10) . style('padding-bottom: 2px; padding-top: 0px;'))
                        )
                    );
                }
                $table->add_object(display_cart_book($value));
                $first = false;
            }
        }
    }

    if (checkout_cart::GetRefundBooks()) {
        $refunded = checkout_cart::GetRefundBooks();

        $renderlist = new RenderList(
            new Row(
                new Column(style('padding-top: 20px; padding-bottom: 3px;').width(checkout_width_constants::$bookTitleWidth).align('left'),
                    new H4(style('margin: 0px; font-size: 14px;'), new TextRender('Refunded Books'))),
                new Column(style('padding-top: 20px; padding-bottom: 3px;').width(checkout_width_constants::$bookQuantityWith).align('center'),
                    new H4(style('margin: 0px; font-size: 14px;'), new TextRender('Quantity'))),
                new Column(style('padding-top: 20px; padding-bottom: 3px;').width(checkout_width_constants::$bookPriceWidth).align('left'),
                    new H4(style('margin: 0px; font-size: 14px;'), new TextRender('Price'))),
                new Column(style('padding-top: 20px; padding-bottom: 3px;').width(checkout_width_constants::$bookExtraWidth).align('center'),
                    new H4(style('margin: 0px;'), new TextRender(''))),
                new Column(width(30))
            ),
            new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan(10).style('padding-bottom: 4px; border-top: solid; border-width: 3px; border-color: #B0B0B0;'))
            )
        );
        $table->add_object($renderlist);

        //match categories of the books
        $first = true;
        if (!empty($refunded)) {
            foreach ($refunded as $key => $value) {
                if (!$first) {
                    $table->add_object(
                        new Row(style('border: none; padding: 0px; height: 1px;'),
                            new Column(colspan(10) . style('padding-bottom: 0px; padding-top: 3px; border-bottom: solid; border-width: 1px; border-color: #F0F0F0;'))
                        )
                    );
                    $table->add_object(
                        new Row(style('border: none; padding: 0px; height: 1px;'),
                            new Column(colspan(10) . style('padding-bottom: 2px; padding-top: 0px;'))
                        )
                    );
                }
                $table->add_object(display_refund_book($value));
                $first = false;
            }
        }
    }

    if (checkout_cart::GetCredit()) {
        $credit = checkout_cart::GetCredit();

        $renderlist = new RenderList(
            new Row(
                new Column(style('padding-top: 20px; padding-bottom: 3px;').width(checkout_width_constants::$bookTitleWidth).align('left'),
                    new H4(style('margin: 0px; font-size: 14px;'), new TextRender('Credits'))),
                new Column(style('padding-top: 20px; padding-bottom: 3px;').width(checkout_width_constants::$bookQuantityWith).align('center'),
                    new H4(style('margin: 0px; font-size: 14px;'), new TextRender(''))),
                new Column(style('padding-top: 20px; padding-bottom: 3px;').width(checkout_width_constants::$bookPriceWidth).align('left'),
                    new H4(style('margin: 0px; font-size: 14px;'), new TextRender('Amount'))),
                new Column(style('padding-top: 20px; padding-bottom: 3px;').width(checkout_width_constants::$bookExtraWidth).align('center'),
                    new H4(style('margin: 0px;'), new TextRender(''))),
                new Column(width(30))
            ),
            new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan(10).style('padding-bottom: 4px; border-top: solid; border-width: 3px; border-color: #B0B0B0;'))
            )
        );
        $table->add_object($renderlist);

        //match categories of the books
        $first = true;
        if (!empty($credit)) {
            foreach ($credit as $key => $value) {
                if (!$first) {
                    $table->add_object(
                        new Row(style('border: none; padding: 0px; height: 1px;'),
                            new Column(colspan(10) . style('padding-bottom: 0px; padding-top: 3px; border-bottom: solid; border-width: 1px; border-color: #F0F0F0;'))
                        )
                    );
                    $table->add_object(
                        new Row(style('border: none; padding: 0px; height: 1px;'),
                            new Column(colspan(10) . style('padding-bottom: 2px; padding-top: 0px;'))
                        )
                    );
                }
                $table->add_object(display_credit_value($key, $value));
                $first = false;
            }
        }
    }
    return $table;
}

function get_totals() {
    $table = new TableArr(width(100).border(0).cellspacing(0).cellpadding(0));
    if (checkout_cart::GetCart() || checkout_cart::GetCredit() || checkout_cart::GetRefundBooks()) {
        $renderlist = new RenderList(
            new Row(
                new Column(width(checkout_width_constants::$bookTitleWidth).style('padding-top: 20px;')),
                new Column(width(checkout_width_constants::$bookQuantityWith - 1).align('right').style('padding-top: 20px;')),
                new Column(width(1).style('padding-top: 20px;')),
                new Column(width(8).align('left').style('padding-top: 20px;'),
                    new H4(style('margin: 0px; font-size: 14px;'), new TextRender('Total'))),
                new Column()
            ),
            new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan(5).style('padding-bottom: 8px; border-top: solid; border-width: 3px; border-color: #B0B0B0;'))
            ),
            new Row(
                new Column(width(checkout_width_constants::$bookTitleWidth)),
                new Column(width(checkout_width_constants::$bookQuantityWith - 1).align('right').style('font-weight: bold;'),
                    new TextRender('Subtotal:')),
                new Column(width(1)),
                new Column(width(8).align('left'),
                    new TextRender('$'.number_format(get_subtotal(), 2))),
                new Column()
            ),
            new Row(
                new Column(width(checkout_width_constants::$bookTitleWidth)),
                new Column(width(checkout_width_constants::$bookQuantityWith - 1).align('right').style('font-weight: bold;'),
                    new TextRender('Refunds:')),
                new Column(width(1)),
                new Column(width(8).align('left'),
                    new TextRender('$'.number_format(get_refund_total(), 2))),
                new Column()
            ),
            new Row(
                new Column(width(checkout_width_constants::$bookTitleWidth)),
                new Column(width(checkout_width_constants::$bookQuantityWith - 1).align('right').style('font-weight: bold;'),
                    new TextRender('Credit:')),
                new Column(width(1)),
                new Column(width(8).align('left'),
                    new TextRender('$'.number_format(get_credit_total(), 2))),
                new Column()
            ),
            new Row(
                new Column(width(checkout_width_constants::$bookTitleWidth)),
                new Column(width(checkout_width_constants::$bookQuantityWith - 1).align('right').style('font-weight: bold;'),
                    new TextRender('Tax:')),
                new Column(width(1)),
                new Column(width(8).align('left'),
                    new TextRender('$'.number_format(get_tax_total(), 2))),
                new Column(align('left'),
                    new TextRender('&#x2190 '.(number_format(get_tax_percent()* 100, 3)).'% applied.'))
            ),
            new Row(
                new Column(width(checkout_width_constants::$bookTitleWidth)),
                new Column(width(checkout_width_constants::$bookQuantityWith - 1)),
                new Column(width(1).style('padding-bottom: 8px; border-top: solid; border-width: 1px; border-color: #D0D0D0;')),
                new Column(width(8).style('padding-bottom: 8px; border-top: solid; border-width: 1px; border-color: #D0D0D0;')),
                new Column(style('padding-bottom: 8px; border-top: solid; border-width: 1px; border-color: #D0D0D0;'))
            ),
            new Row(
                new Column(width(checkout_width_constants::$bookTitleWidth)),
                new Column(width(checkout_width_constants::$bookQuantityWith - 1).align('right').style('font-weight: bold;'),
                    new TextRender('Total:')),
                new Column(width(1)),
                new Column(width(8).align('left'),
                    new TextRender('$'.number_format(get_total(), 2))),
                new Column(align('left'),
                    new Form(
                        page_action::InputAction(action_types::$clear_checkout_cart),
                        new Input(classType('button').type('submit').name('button').value('Clear Cart'))
                    )
                )
            )
        );
        $table->add_object($renderlist);
    }
    return $table;
}

function display_cart_book($bookArr) {
    $book_id = $bookArr[checkout_cart::$cart_book_id];
    $book_quantity = $bookArr[checkout_cart::$cart_book_quantity];
    $row = new Row(
        new Column(style('font-weight: bold;').align('left'),
            new TextRender(Book::$props[Book::$name]->GetValue($book_id))
        ), //name
        new Column(align('center'),
            new Label(style('font-weight: bold; padding-right: 8px;'), new TextRender($book_quantity)),
            new Form(style('display: inline-block; padding-right: 3px;'),
                page_action::InputAction(action_types::$remove_item_checkout),
                selection::SetID($book_id, Book::$source),
                new Input(id(checkout_request::$remove_amount).name(checkout_request::$remove_amount).type('hidden').value(1)),
                new Input(classType('button').style('padding: 0px 6px 0px; font-size: 20px; line-height: 11px; height: 22px;
                    color: #787878;
                    border-color: #D8D8D8;
                    background: #F8F8F8;
                    box-shadow: 0 1px 0 #D8D8D8;').type('submit').name('button').value('-'))
            ),
            new Form(style('display: inline-block; padding-left: 3px;'),
                page_action::InputAction(action_types::$remove_item_checkout),
                selection::SetID($book_id, Book::$source),
                new Input(id(checkout_request::$remove_amount).name(checkout_request::$remove_amount).type('hidden').value('all')),
                new Input(classType('button').style('padding: 0px 6px 0px; font-size: 14px; line-height: 11px; height: 22px;
                    color: #787878;
                    border-color: #D8D8D8;
                    background: #F8F8F8;
                    box-shadow: 0 1px 0 #D8D8D8;').type('submit').name('button').value('X'))
            )
        ), //quantity
        new Column(align('left'),
            new TextRender('$'.number_format($book_quantity * Book::$props[Book::$price]->GetValue($book_id), 2))
        ) //price
    );
    return $row;
}

function display_refund_book($bookArr) {
    $book_id = $bookArr[checkout_cart::$cart_book_id];
    $book_quantity = $bookArr[checkout_cart::$cart_book_quantity];

    $row = new Row(
        new Column(style('font-weight: bold;').align('left'),
            new TextRender(Book::$props[Book::$name]->GetValue($book_id))
        ), //name
        new Column(align('center'),
            new Label(style('font-weight: bold; padding-right: 8px;'), new TextRender($book_quantity)),
            new Form(style('display: inline-block; padding-right: 3px;'),
                page_action::InputAction(action_types::$remove_item_refund),
                selection::SetID($book_id, Book::$source),
                new Input(id(checkout_request::$remove_amount).name(checkout_request::$remove_amount).type('hidden').value(1)),
                new Input(classType('button').style('padding: 0px 6px 0px; font-size: 20px; line-height: 11px; height: 22px;
                    color: #787878;
                    border-color: #D8D8D8;
                    background: #F8F8F8;
                    box-shadow: 0 1px 0 #D8D8D8;').type('submit').name('button').value('-'))
            ),
            new Form(style('display: inline-block; padding-left: 3px;'),
                page_action::InputAction(action_types::$remove_item_refund),
                selection::SetID($book_id, Book::$source),
                new Input(id(checkout_request::$remove_amount).name(checkout_request::$remove_amount).type('hidden').value('all')),
                new Input(classType('button').style('padding: 0px 6px 0px; font-size: 14px; line-height: 11px; height: 22px;
                    color: #787878;
                    border-color: #D8D8D8;
                    background: #F8F8F8;
                    box-shadow: 0 1px 0 #D8D8D8;').type('submit').name('button').value('X'))
            )
        ), //quantity
        new Column(align('left'),
            new TextRender('-$'.number_format($book_quantity * Book::$props[Book::$price]->GetValue($book_id), 2))
        ) //price
    );
    return $row;
}

function display_credit_value($index, $credit) {
    $credit_name = $credit[checkout_cart::$credit_name];
    $credit_amount = $credit[checkout_cart::$credit_amount];

    $row = new Row(
        new Column(style('font-weight: bold;').align('left'),
            new TextRender($credit_name)
        ), //name
        new Column(align('center'),
            new Form(
                page_action::InputAction(action_types::$remove_credit_checkout),
                new Input(id(checkout_request::$remove_credit_index).name(checkout_request::$remove_credit_index).type('hidden').value($index)),
                new Input(classType('button').style('padding: 0px 6px 0px; font-size: 14px; line-height: 11px; height: 22px;
                    color: #787878;
                    border-color: #D8D8D8;
                    background: #F8F8F8;
                    box-shadow: 0 1px 0 #D8D8D8;').type('submit').name('button').value('X'))
            )
        ),
        new Column(align('left'),
            new TextRender('-$'.number_format($credit_amount, 2))
        )
    );
    return $row;
}

function get_credit_box() {
    $form_clear = new Form(id('clear_credit').name('clear_credit'));
    $form_add = new Form(id('add_credit').name('add_credit'));
    $table = new
    RenderList(
        $form_add,
        $form_clear,
        new TableArr(style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'),
            new Form(id('credit').name('credit'),
                new Row(
                    new Column(new H4(style('margin: 0px;'), new TextRender('Discount:')))
                ),
                new Row(
                    new Column(align('right').width(20), new TextRender('Name:')),
                    new Column(new Input(form('add_credit').id(checkout_request::$credit_name).name(checkout_request::$credit_name).type('text')))
                ),
                new Row(
                    new Column(align('right').width(20), new TextRender('Amount:')),
                    new Column(new Input(form('add_credit').id(checkout_request::$credit_amount).name(checkout_request::$credit_amount).type('text')))
                ),
                new Row(
                    new Column(width(20).style('padding-top: 5px;').align('left'),
                        new Input(form('clear_credit').id(page_action::$action).name(page_action::$action).type('hidden').value(action_types::$clear_checkout_credit)),
                        new Input(form('clear_credit').classType('button').type('submit').name('button').value('Clear Credit'))
                    ),
                    new Column(align('right').style('padding-top: 5px;'),
                        new Input(form('add_credit').id(page_action::$action).name(page_action::$action).type('hidden').value(action_types::$add_credit_checkout)),
                        new Input(form('add_credit').classType('button-primary').type('submit').name('button').value('Add Credit'))
                    )
                )
            )
        )
    );
    return $table;
}

function get_total() {
    return get_subtotal() + get_tax_total() - get_credit_total() - get_refund_total();
}

function get_subtotal() {
    $total = 0;
    $cart = checkout_cart::GetCart();
    if ($cart && !empty($cart)) {
        foreach ($cart as $key => $value) {
            $price = Book::$props[Book::$price]->GetValue($value[checkout_cart::$cart_book_id]);
            $quantity = $value[checkout_cart::$cart_book_quantity];
            $total = $total + $price * $quantity;
        }
    }
    return $total;
}

function get_refund_total() {
    $total = 0;
    $cart = checkout_cart::GetRefundBooks();
    if ($cart && !empty($cart)) {
        foreach ($cart as $key => $value) {
            $price = Book::$props[Book::$price]->GetValue($value[checkout_cart::$cart_book_id]);
            $quantity = $value[checkout_cart::$cart_book_quantity];
            $total = $total + $price * $quantity;
        }
    }
    return $total;
}

function get_credit_total() {
    $total = 0;
    $credit = checkout_cart::GetCredit();
    if ($credit && !empty($credit)) {
        foreach ($credit as $key => $value) {
            $amount = $value[checkout_cart::$credit_amount];
            $total = $total + $amount;
        }
    }
    return $total;
}

function get_tax_total() {
    $taxpercent = get_tax_percent();
    $subtotal = get_subtotal();
    return $subtotal * $taxpercent - get_refund_total() * $taxpercent;
}

function get_paid_amount_tax_total() {
    $taxpercent = get_tax_percent();
    $subtotal = checkout_payment::GetAmountPaid();
    return $subtotal * $taxpercent;
}

function get_tax_percent() {
    return str_replace('$', '', get_option('ctaxrate') / 100);
}

function state_select() {
    return '
    <select name="AVSSTATE">
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
    </select>';
}

?>

<script>
    $(function(){
        $('form').each(function () {
            var thisform = $(this);
            thisform.prepend(thisform.find('button.default').clone().css({
                position: 'absolute',
                left: '-999px',
                top: '-999px',
                height: 0,
                width: 0
            }));
        });
    });
</script>
