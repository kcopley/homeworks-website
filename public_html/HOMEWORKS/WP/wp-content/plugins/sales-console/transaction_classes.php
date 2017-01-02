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

    public static $payment_type = 'payment_type';
    public static $payment_amount = 'payment_amount';

    public static $removeable = 'transaction_removeable';
    public static $selectable = 'transaction_selectable';

    public static function create_book_transaction($book, $quantity) {
        return array(
            self::$book_id => $book,
            self::$book_quantity => $quantity,
            self::$book_price => book_properties::get_book_saleprice($book),
            self::$book_refunded_quantity => 0,
        );
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

    public static function get_books($id) {
        return get_post_meta($id, '_cmb_transaction_books', true);
    }

    public static function set_books($id, $books) {
        update_post_meta($id, '_cmb_transaction_books', $books);
    }

    public static function get_taxrate($id){
        return get_post_meta($id, '_cmb_transaction_taxrate', true);
    }

    public static function set_taxrate($id, $tax){
        update_post_meta($id, '_cmb_transaction_taxrate', $tax);
    }

    public static function create_payment_type($type, $amount) {
        return array(
            self::$payment_type => $type,
            self::$payment_amount = $amount
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
        $payments[] = self::create_payment_type($type, $amount);
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
        self::set_stored_total($id, self::get_transaction_total($id));
    }

    public static function refund_book($id, $book, $quantity) {
        $books = self::get_books($id);
        if (array_key_exists($book, $books)) {
            $existing_quantity = $books[$book][self::$book_quantity];
            $refunded_quantity = $books[$book][self::$book_refunded_quantity];
            if ($quantity > $existing_quantity) {
                $quantity = $existing_quantity;
            }
            $books[$book][self::$book_quantity] = $existing_quantity - $quantity;
            $books[$book][self::$book_refunded_quantity] = $refunded_quantity + $quantity;
        }
        self::set_books($id, $books);
    }

    function get_transaction_total($transaction_id) {
        $books = self::get_books($transaction_id);
        $total = 0;
        if (!empty($books)) {
            foreach ($books as $book => $trans) {
                $total += $trans[self::$book_quantity] * $trans[self::$book_price];
            }
        }
        return $total;
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

    public static function get_transaction_customer_name($transaction_id){
        return get_post_meta($transaction_id, '_cmb_transaction_customer_name', true);
    }

    public static function set_transaction_customer_name($transaction_id, $name){
        update_post_meta($transaction_id, '_cmb_transaction_customer_name', $name);
    }

    public static function get_transaction_customer_email($transaction_id){
        return get_post_meta($transaction_id, '_cmb_transaction_customer_email', true);
    }

    public static function set_transaction_customer_email($transaction_id, $name){
        update_post_meta($transaction_id, '_cmb_transaction_customer_email', $name);
    }

    public static function get_transaction_customer_address($transaction_id){
        return get_post_meta($transaction_id, '_cmb_transaction_customer_address', true);
    }

    public static function set_transaction_customer_address($transaction_id, $name){
        update_post_meta($transaction_id, '_cmb_transaction_customer_address', $name);
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
            self::InputID(),
            self::InputCustomerAddress(),
            self::InputCustomerEmail(),
            self::InputCustomerName(),
            self::InputDateFrom(),
            self::InputDateTo(),
            self::InputTaxRate(),
            self::InputTransFirstID(),
            self::InputTotalTo(),
            self::InputTotalFrom()
        );
        return $renderlist;
    }
}