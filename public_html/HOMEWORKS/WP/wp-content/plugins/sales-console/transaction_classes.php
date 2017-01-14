<?php
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 1/1/2017
 * Time: 7:43 PM
 */

function QueryTransaction() {
    $args = array(
        'numberposts' => -1,
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'date',
        'post_type' => 'transactions'
    );

    $meta_query_array = array('relation' => 'AND');

    if (transaction_request::GetID()) {
        $args['s'] = transaction_request::GetID();
    }
    if (transaction_request::GetDateFrom() || transaction_request::GetDateTo()) {
        $meta_query_array[] =
            array(
                'key' => '_cmb_transaction_date',
                'value' => array(transaction_request::GetDateFrom(), transaction_request::GetDateTo()),
                'compare' => 'BETWEEN',
                'type' => 'DATE'
            );
    }
    if (transaction_request::GetCustomerName()) {
        $meta_query_array[] = array(
            'key' => '_cmb_transaction_customer_name',
            'value' => transaction_request::GetCustomerName()
        );
    }
    if (transaction_request::GetCustomerAddress()) {
        $meta_query_array[] = array(
            'key' => '_cmb_transaction_customer_address',
            'value' => transaction_request::GetCustomerAddress()
        );
    }
    if (transaction_request::GetCustomerEmail()) {
        $meta_query_array[] = array(
            'key' => '_cmb_transaction_customer_email',
            'value' => transaction_request::GetCustomerEmail()
        );
    }
    if (transaction_request::GetTaxRate()) {
        $meta_query_array[] = array(
            'key' => '_cmb_transaction_taxrate',
            'value' => transaction_request::GetTaxRate()
        );
    }
    if (transaction_request::GetTransFirstID()) {
        $meta_query_array[] = array(
            'key' => '_cmb_transaction_transfirstid',
            'value' => transaction_request::GetTransFirstID()
        );
    }
    if (transaction_request::GetTotalFrom() || transaction_request::GetTotalTo()) {
        $meta_query_array[] =
            array(
                'key' => '_cmb_transaction_total',
                'value' => array(transaction_request::GetTotalFrom(), transaction_request::GetTotalTo()),
                'compare' => 'BETWEEN',
                'type' => 'DOUBLE'
            );
    }

    if (transaction_request::GetCompleted() && transaction_request::GetCompleted() != 'all') {
        $meta_query_array[] = array(
            'key' => '_cmb_transaction_completed',
            'value' => transaction_request::GetCompleted()
        );
    }

    $args['meta_query'] = $meta_query_array;
    return new WP_Query($args);
}

class transaction_properties {
    public static $id = 'transaction_id';
    public static $date = 'transaction_date';
    public static $customer_name = 'transaction_cust_name';
    public static $customer_email = 'transaction_cust_email';
    public static $customer_address = 'transaction_cust_address';
    public static $transfirstid = 'transaction_transfirst';
    public static $taxrate = 'transaction_taxrate';
    public static $total = 'transaction_total';

    public static $book_id = 'transaction_book';
    public static $book_quantity = 'transaction_book_quantity';
    public static $book_price = 'transaction_price';
    public static $book_refunded_quantity = 'transaction_refunded_book_quantity';
    public static $credit_name = 'transaction_credit_name';
    public static $credit_amount = 'transaction_credit_amount';

    public static $payment_type = 'payment_type';
    public static $payment_amount = 'payment_amount';

    public static $removeable = 'transaction_removeable';
    public static $selectable = 'transaction_selectable';
    public static $printable = 'transaction_printable';
    public static $completed = 'transaction_completed';

    public static function create_book_transaction($book, $quantity) {
        return array(
            self::$book_id => $book,
            self::$book_quantity => $quantity,
            self::$book_price => book_properties::get_book_saleprice($book),
            self::$book_refunded_quantity => 0,
        );
    }

    public static function create_refund_transaction($book, $quantity) {
        return array(
            self::$book_id => $book,
            self::$book_quantity => $quantity,
            self::$book_price => book_properties::get_book_saleprice($book)
        );
    }

    public static function create_credit_transaction($name, $amount) {
        return array(
            self::$credit_name => $name,
            self::$credit_amount => $amount
        );
    }

    public static function get_refund_book_printing($id) {
        $books = self::get_refunds($id);
        $list = new RenderList();
        if (!empty($books)) {
            $list->add_object(
                new Row(
                    new Column(colspan(2), new H4(style('margin: 0px; padding-top: 4px; padding-bottom: 4px;'), new TextRender('Refunds:')))
                )
            );
            $list->add_object(new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan(10).style('padding-bottom: 0px;'),
                    new HR(style('margin: 0px;')))));
            foreach ($books as $book) {
                $book_id = $book[self::$book_id];
                $title = book_properties::get_book_title($book_id);
                $price = $book[self::$book_price];
                $quantity = $book[self::$book_quantity];
                $lineTotal = $price * $quantity;
                $list->add_object(
                    new Row(
                        new Column(align('left') . colspan(2) . valign('top'),
                            new TextRender(
                                '(' . $quantity . ') ' . $title
                            )
                        ),
                        new Column(valign('top') . width(15),
                            new TextRender('-$' . number_format($lineTotal, 2))
                        )
                    )
                );
            }
        }
        return $list;
    }

    public static function get_book_printing($id) {
        $books = self::get_books($id);
        $list = new RenderList();
        if (!empty($books)) {
            $list->add_object(
                new Row(
                    new Column(colspan(2), new H4(style('margin: 0px; padding-top: 4px; padding-bottom: 4px;'), new TextRender('Books:')))
                )
            );
            $list->add_object(new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan(10).style('padding-bottom: 0px;'),
                    new HR(style('margin: 0px;')))));
            foreach ($books as $book) {
                $book_id = $book[self::$book_id];
                $title = book_properties::get_book_title($book_id);
                $price = $book[self::$book_price];
                $quantity = $book[self::$book_quantity];
                $lineTotal = $price * $quantity;
                $list->add_object(
                    new Row(
                        new Column(align('left') . colspan(2) . valign('top'),
                            new TextRender(
                                '(' . $quantity . ') ' . $title
                            )
                        ),
                        new Column(valign('top') . width(15),
                            new TextRender('$' . number_format($lineTotal, 2))
                        )
                    )
                );
            }
        }
        return $list;
    }

    public static function get_credit_printing($id) {
        $credits = self::get_credits($id);
        $list = new RenderList();

        if (!empty($credits)) {
            $list->add_object(
                new Row(
                    new Column(colspan(2), new H4(style('margin: 0px; padding-top: 4px; padding-bottom: 4px;'), new TextRender('Credits:')))
                )
            );
            $list->add_object(new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan(10).style('padding-bottom: 0px;'),
                    new HR(style('margin: 0px;')))));
            foreach ($credits as $credit) {
                $name = $credit[self::$credit_name];
                $amount = $credit[self::$credit_amount];
                $list->add_object(
                    new Row(
                        new Column(align('left') . colspan(2) . valign('top'),
                            new TextRender(
                                $name
                            )
                        ),
                        new Column(valign('top') . width(15),
                            new TextRender('-$' . number_format($amount, 2))
                        )
                    )
                );
            }
        }
        return $list;
    }

    public static function get_payment_text($type) {
        if ($type == checkout_payment::$payment_cash) {
            return 'Cash';
        }
        if ($type == checkout_payment::$payment_credit) {
            return 'Credit';
        }
        if ($type == checkout_payment::$payment_check) {
            return 'Check';
        }
        if ($type == checkout_payment::$payment_phone) {
            return 'Phone';
        }
    }

    public static function get_payment_printing($id) {
        $payments = self::get_payment_types($id);
        $list = new RenderList();
        if (!empty($payments)) {
            foreach ($payments as $payment) {
                $type = $payment[self::$payment_type];
                $amount = $payment[self::$payment_amount];
                $list->add_object(
                    new Row(
                        new Column(align('left') . colspan(2) . valign('top'),
                            new TextRender(
                                self::get_payment_text($type)
                            )
                        ),
                        new Column(valign('top') . width(15),
                            new TextRender('-$' . number_format($amount, 2))
                        )
                    )
                );
            }
        }
        return $list;
    }

    public static function get_refund_printing($id) {
        $list = new RenderList();
        if (self::get_refund_amount($id) > 0) {
            $list->add_object(
                new Row(
                    new Column(valign('top')),
                    new Column(align('right').valign('top').width(10),
                        new Strong(new TextRender('Refund:'))),
                    new Column(style('padding-left: 4px;'),
                        new Strong(new TextRender(' $'.number_format(transaction_properties::get_refund_amount($id), 2)))
                    )
                )
            );
        }
        else {
            $list->add_object(
                new Row(
                    new Column(valign('top')),
                    new Column(align('right').valign('top').width(10),
                        new Strong(new TextRender('Due:'))),
                    new Column(style('padding-left: 4px;'),
                        new Strong(new TextRender(' $'.number_format(0, 2)))
                    )
                )
            );
        }
        return $list;
    }

    public static function get_refund_amount($id) {
        $amountPaid = ceil(self::get_total_paid($id) * 100);
        $total = ceil(self::get_total($id) * 100);
        if ($amountPaid > $total) {
            return self::get_total_paid($id) - self::get_total($id);
        }
        else return 0;
    }

    public static function get_total_paid($id) {
        $payments = self::get_payment_types($id);
        $total = 0;
        if (!empty($payments)) {
            foreach ($payments as $payment) {
                $amount = $payment[self::$payment_amount];
                $total = $total + $amount;
            }
        }
        return $total;
    }

    public static function get_id($postid){
        return get_post_meta($postid, '_cmb_transaction_id', true);
    }

    public static function set_id($postid, $id){
        $titleupdate = array(
            'ID'           => $postid,
            'post_title'   => $id,
        );
        wp_update_post($titleupdate);
        update_post_meta($postid, '_cmb_transaction_id', $id);
    }

    public static function get_date($postid){
        return get_post_meta($postid, '_cmb_transaction_date', true);
    }

    public static function set_date($postid, $date){
        update_post_meta($postid, '_cmb_transaction_date', $date);
    }

    public static function get_transfirstid($id){
        return get_post_meta($id, '_cmb_transaction_transfirstid', true);
    }

    public static function set_transfirstid($id, $transfirstid){
        update_post_meta($id, '_cmb_transaction_transfirstid', $transfirstid);
    }

    public static function get_completed($id){
        return get_post_meta($id, '_cmb_transaction_completed', true);
    }

    public static function set_completed($id, $completed){
        update_post_meta($id, '_cmb_transaction_completed', $completed);
    }

    public static function get_books($id) {
        return get_post_meta($id, '_cmb_transaction_books', true);
    }

    public static function get_refunds($id) {
        return get_post_meta($id, '_cmb_transaction_refunds', true);
    }

    public static function set_books_from_cart($id, $cart) {
        $books = array();
        foreach ($cart as $key => $value) {
            $book = $key;
            $quantity = $value[checkout_cart::$cart_book_quantity];
            $books[] = self::create_book_transaction($book, $quantity);
        }
        self::set_books($id, $books);
    }

    public static function set_refunds_from_cart($id, $cart) {
        $books = array();
        foreach ($cart as $key => $value) {
            $book = $key;
            $quantity = $value[checkout_cart::$cart_book_quantity];
            $books[] = self::create_book_transaction($book, $quantity);
        }
        self::set_refunds($id, $books);
    }

    public static function set_credits_from_cart($id, $credits) {
        $creditarr = array();
        foreach ($credits as $key => $value) {
            $name = $value[checkout_cart::$credit_name];
            $amount = $value[checkout_cart::$credit_amount];
            $creditarr[] = self::create_credit_transaction($name, $amount);
        }
        self::set_credits($id, $creditarr);
    }

    public static function set_books($id, $books) {
        update_post_meta($id, '_cmb_transaction_books', $books);
    }

    public static function set_refunds($id, $books) {
        update_post_meta($id, '_cmb_transaction_refunds', $books);
    }

    public static function get_taxrate($id){
        return get_post_meta($id, '_cmb_transaction_taxrate', true);
    }

    public static function set_taxrate($id, $tax){
        update_post_meta($id, '_cmb_transaction_taxrate', $tax);
    }

    public static function create_payment_type($type, $amount, $transfirst) {
        return array(
            self::$payment_type => $type,
            self::$payment_amount => $amount,
            self::$transfirstid => $transfirst
        );
    }

    public static function get_payment_types($id){
        return get_post_meta($id, '_cmb_transaction_payment_types', true);
    }

    public static function set_payment_types($id, $types){
        update_post_meta($id, '_cmb_transaction_payment_types', $types);
    }

    public static function add_payment($id, $type, $amount) {
        $payments = self::get_payment_types($id);
        if (!$payments) {
            $payments = array();
        }
        $payments[] = self::create_payment_type($type, $amount, -1);
        self::set_payment_types($id, $payments);
    }

    public static function add_payment_credit($id, $type, $amount, $transfirst) {
        $payments = self::get_payment_types($id);
        if (!$payments) {
            $payments = array();
        }
        $payments[] = self::create_payment_type($type, $amount, $transfirst);
        self::set_payment_types($id, $payments);
    }

    public static function remove_payment($id, $index) {
        $payments = self::get_payment_types($id);
        if (!$payments) return;
        if ($index > (count($payments) - 1)) return;
        unset($payments[$index]);
        $payments = array_values($payments);
        self::set_payment_types($id, $payments);
    }

    public static function add_book($id, $book, $quantity) {
        $books = self::get_books($id);
        if (!$books){
            $books = array();
        }
        $books[$book] = self::create_book_transaction($book, $quantity);
        self::set_stored_total($id, self::get_total($id));
    }

    public static function refund_book($id, $book, $quantity) {
        $books = self::get_refunds($id);
        if (array_key_exists($book, $books)) {
            $existing_quantity = $books[$book][self::$book_quantity];
            $refunded_quantity = $books[$book][self::$book_refunded_quantity];
            if ($quantity > $existing_quantity) {
                $quantity = $existing_quantity;
            }
            $books[$book][self::$book_quantity] = $existing_quantity - $quantity;
            $books[$book][self::$book_refunded_quantity] = $refunded_quantity + $quantity;
        }
        self::set_refunds($id, $books);
    }

    public static function get_subtotal($id) {
        $books = self::get_books($id);
        $total = 0;
        if (!empty($books)) {
            foreach ($books as $book) {
                $price = $book[self::$book_price];
                $quantity = $book[self::$book_quantity];
                $lineTotal = $price * $quantity;
                $total = $total + $lineTotal;
            }
            return $total;
        }
    }

    public static function get_credits($id) {
        return get_post_meta($id, '_cmb_transaction_credits', true);
    }

    public static function set_credits($id, $credits) {
        update_post_meta($id, '_cmb_transaction_credits', $credits);
    }

    public static function get_stored_total($id) {
        return get_post_meta($id, '_cmb_transaction_total', true);
    }

    public static function set_stored_total($id, $total) {
        update_post_meta($id, '_cmb_transaction_total', $total);
    }

    public static function get_customer_name($transaction_id){
        return get_post_meta($transaction_id, '_cmb_transaction_customer_name', true);
    }

    public static function set_customer_name($transaction_id, $name){
        update_post_meta($transaction_id, '_cmb_transaction_customer_name', $name);
    }

    public static function get_customer_email($transaction_id){
        return get_post_meta($transaction_id, '_cmb_transaction_customer_email', true);
    }

    public static function set_customer_email($transaction_id, $name){
        update_post_meta($transaction_id, '_cmb_transaction_customer_email', $name);
    }

    public static function get_customer_address($transaction_id){
        return get_post_meta($transaction_id, '_cmb_transaction_customer_address', true);
    }

    public static function set_customer_address($transaction_id, $name){
        update_post_meta($transaction_id, '_cmb_transaction_customer_address', $name);
    }

    public static function remove_transaction($post) {
        wp_delete_post($post);
    }

    public static function get_total($id) {
        return self::get_subtotal($id) + self::get_tax_total($id) - self::get_credit_total($id) - self::get_refund_total($id);
    }

    public static function get_refund_total($id) {
        $refunds = self::get_refunds($id);
        $total = 0;
        if (!empty($refunds)) {
            foreach ($refunds as $refund) {
                $amount = $refund[self::$book_price];
                $total = $total + $amount;
            }
        }
        return $total;
    }

    public static function get_credit_total($id) {
        $credits = self::get_credits($id);
        $total = 0;
        if (!empty($credits)) {
            foreach ($credits as $credit) {
                $amount = $credit[self::$credit_amount];
                $total = $total + $amount;
            }
        }
        return $total;
    }

    public static function get_tax_total($id) {
        $taxpercent = transaction_properties::get_taxrate($id);
        $subtotal = self::get_subtotal($id);
        return $subtotal * $taxpercent - self::get_refund_total($id) * $taxpercent;
    }

    public static function print_formatting($id) {
        $print =
            new Div(style('display: none;'),
                new Div(id('toPrint'),
                    new Paragraph(style('text-align: center;'),
                        new Strong(new TextRender('Home Works for Books')),
                        new BR(),
                        new EM(new TextRender('Your homeschool connection for discounted new and used homeschool materials!'))
                    ),
                    new Paragraph(style('text-align: center;'),
                        new TextRender(get_option('invoiceaddress')),
                        new BR(),
                        new Strong(new TextRender('Phone: ')),
                        new TextRender(get_option('invoicephone')),
                        new BR(),
                        new TextRender('Come visit us online at '),
                        new TextRender(get_option('invoiceURL')),
                        new BR(),
                        new BR(),
                        new TextRender(date("Y/m/d H:i:s")),
                        new BR(),
                        new TextRender('Cashier: '.get_user_name())
                    ),
                    new Paragraph(), //blank
                    new TableArr(border(0).cellpadding(0).cellspacing(0).width(100),
                        transaction_properties::get_book_printing($id),
                        transaction_properties::get_refund_book_printing($id),
                        transaction_properties::get_credit_printing($id),
                        new Row(
                            new Column(),
                            new Column(align('left').colspan(2).valign('middle'),
                                new HR()
                            )
                        ),
                        new Row(
                            new Column(valign('top')),
                            new Column(align('right').valign('top').width(10),
                                new Strong(new TextRender('Subtotal:'))),
                            new Column(style('padding-left: 4px;'),
                                new Strong(new TextRender(' $'.number_format(transaction_properties::get_subtotal($id), 2)))
                            )
                        ),
                        new Row(
                            new Column(valign('top')),
                            new Column(align('right').valign('top').width(10),
                                new Strong(new TextRender('Refunds:'))),
                            new Column(style('padding-left: 4px;'),
                                new Strong(new TextRender(' -$'.number_format(transaction_properties::get_refund_total($id), 2)))
                            )
                        ),
                        new Row(
                            new Column(valign('top')),
                            new Column(align('right').valign('top').width(10),
                                new Strong(new TextRender('Credits:'))),
                            new Column(style('padding-left: 4px;'),
                                new Strong(new TextRender(' $'.number_format(transaction_properties::get_credit_total($id), 2)))
                            )
                        ),
                        new Row(
                            new Column(valign('top')),
                            new Column(align('right').valign('top').width(10),
                                new Strong(new TextRender('Tax:'))),
                            new Column(style('padding-left: 4px;'),
                                new Strong(new TextRender(' $'.number_format(transaction_properties::get_tax_total($id), 2)))
                            )
                        ),
                        new Row(
                            new Column(),
                            new Column(align('left').colspan(2).valign('middle'),
                                new HR()
                            )
                        ),
                        new Row(
                            new Column(valign('top')),
                            new Column(align('right').valign('top').width(10),
                                new Strong(new TextRender('Total:'))),
                            new Column(style('padding-left: 4px;'),
                                new Strong(new TextRender(' $'.number_format(transaction_properties::get_total($id), 2)))
                            )
                        ),
                        new Row(
                            new Column(colspan(2), new H4(style('margin: 0px; padding-top: 4px; padding-bottom: 4px;'), new TextRender('Payments:')))
                        ),
                        new Row(style('border: none; padding: 0px; height: 1px;'),
                            new Column(colspan(10).style('padding-bottom: 0px;'),
                                new HR(style('margin: 0px;')))),
                        transaction_properties::get_payment_printing($id),
                        transaction_properties::get_refund_printing($id)
                    ),
                    new Paragraph(style('text-align: center;'),
                        new TextRender(get_option('invoicepromo'))
                    ),
                    new Paragraph(style('text-align: center;'),
                        new Strong(new TextRender('Invoice: #')),
                        new TextRender(transaction_properties::get_id($id)),
                        new BR(),
                        new TextRender('Customer Copy')
                    )
                )
            );
        $print->Render();
    }
}

class transaction_request {
    public static $id = 'req_transaction_id';
    public static $datefrom = 'req_transaction_date_from';
    public static $dateto = 'req_transaction_date_to';
    public static $customer_name = 'req_transaction_cust_name';
    public static $customer_email = 'req_transaction_cust_email';
    public static $customer_address = 'req_transaction_cust_address';
    public static $transfirstid = 'req_transaction_transfirst';
    public static $taxrate = 'req_transaction_taxrate';
    public static $totalto = 'req_transaction_total_to';
    public static $totalfrom = 'req_transaction_total_from';
    public static $completed = 'req_transaction_completed';

    public static function GetCompleted() {
        return $_REQUEST[self::$completed];
    }

    public static function GetID() {
        return $_REQUEST[self::$id];
    }

    public static function InputID() {
        return new Input(id(self::$id).name(self::$id).type('text'));
    }

    public static function GetDateFrom() {
        return $_REQUEST[self::$datefrom];
    }

    public static function InputDateFrom() {
        date_default_timezone_set('America/Chicago');
        return new Input(id(self::$datefrom).name(self::$datefrom).type('date').value(date("Y-m-d", mktime(0, 0, 0, 1, 1, 2014))));
    }

    public static function GetDateTo() {
        return $_REQUEST[self::$dateto];
    }

    public static function InputDateTo() {
        date_default_timezone_set('America/Chicago');
        return new Input(id(self::$dateto).name(self::$dateto).type('date').value(date('Y-m-d')));
    }

    public static function GetCustomerName() {
        return $_REQUEST[self::$customer_name];
    }

    public static function InputCustomerName() {
        return new Input(id(self::$customer_name).name(self::$customer_name).type('text'));
    }

    public static function GetCustomerAddress() {
        return $_REQUEST[self::$customer_address];
    }

    public static function InputCustomerAddress() {
        return new Input(id(self::$customer_address).name(self::$customer_address).type('text'));
    }

    public static function GetCustomerEmail() {
        return $_REQUEST[self::$customer_email];
    }

    public static function InputCustomerEmail() {
        return new Input(id(self::$customer_email).name(self::$customer_email).type('text'));
    }

    public static function GetTransFirstID() {
        return $_REQUEST[self::$transfirstid];
    }

    public static function InputTransFirstID() {
        return new Input(id(self::$transfirstid).name(self::$transfirstid).type('text'));
    }

    public static function GetTaxRate() {
        return $_REQUEST[self::$taxrate];
    }

    public static function InputTaxRate() {
        return new Input(id(self::$taxrate).name(self::$taxrate).type('text'));
    }

    public static function GetTotalFrom() {
        return $_REQUEST[self::$totalfrom];
    }

    public static function GetTotalTo() {
        return $_REQUEST[self::$totalto];
    }

    public static function InputTotalFrom() {
        return new Input(id(self::$totalfrom).name(self::$totalfrom).type('double'));
    }

    public static function InputTotalTo() {
        return new Input(id(self::$totalto).name(self::$totalto).type('double'));
    }

    public static function Store() {
        $renderlist = new RenderList(
            new Input(id(self::$id).name(self::$id).type('hidden').value(self::GetID())),
            new Input(id(self::$customer_address).name(self::$customer_address).type('hidden').value(self::GetCustomerAddress())),
            new Input(id(self::$customer_email).name(self::$customer_email).type('hidden').value(self::GetCustomerEmail())),
            new Input(id(self::$customer_name).name(self::$customer_name).type('hidden').value(self::GetCustomerName())),
            new Input(id(self::$datefrom).name(self::$datefrom).type('hidden').value(self::GetDateFrom())),
            new Input(id(self::$dateto).name(self::$dateto).type('hidden').value(self::GetDateTo())),
            new Input(id(self::$taxrate).name(self::$taxrate).type('hidden').value(self::GetTaxRate())),
            new Input(id(self::$transfirstid).name(self::$transfirstid).type('hidden').value(self::GetTransFirstID())),
            new Input(id(self::$totalto).name(self::$totalto).type('hidden').value(self::GetTotalTo())),
            new Input(id(self::$totalfrom).name(self::$totalfrom).type('hidden').value(self::GetTotalFrom())),
            new Input(id(self::$completed).name(self::$completed).type('hidden').value(self::GetCompleted()))
        );
        return $renderlist;
    }
}