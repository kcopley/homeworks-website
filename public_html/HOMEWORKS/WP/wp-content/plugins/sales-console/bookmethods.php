<?php
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 12/22/2016
 * Time: 2:20 PM
 */

//Sales Methods
function request_sale_quantity() { return 'query_quantity'; }
function request_sale_isbn() { return 'query_isbn'; }
function request_sale_id() { return 'query_id'; }
function request_sale_credit_name() { return 'query_credit_name'; }
function request_sale_credit() { return 'query_credit'; }

function request_sale_clear_action() { return 'sale_clear_action'; }

function request_sale_remove_credit_action() { return 'remove_credit_action'; }
function request_sale_credit_indice() { return 'remove_credit_indice'; }

function request_sale_remove_amount() { return 'request_sale_remove_amount'; }
function sale_remove_all() { return 'sale_remove_all'; }
function sale_remove_one() { return 'sale_remove_one'; }

function request_sale_session_cart() { return 'request_sale_session_cart'; }
function request_sale_session_quantity() { return 'request_sale_session_quantity'; }
function request_sale_session_credit() { return 'request_sale_session_credit'; }

function request_sale_add_item_action() { return 'request_sale_add_item_action'; }
function request_sale_remove_item_action() { return 'request_sale_remove_item_action'; }
function request_sale_credit_action() { return 'request_sale_credit_action'; }
function request_processing() { return 'request_processing'; }

function sales_payment_type() { return 'sales_payment_type'; }
function sales_payment_credit() { return 'sales_payment_credit'; }
function sales_payment_cash() { return 'sales_payment_cash'; }
function sales_payment_check() { return 'sales_payment_check'; }
function sales_payment_phone() { return 'sales_payment_phone'; }

function sales_customer_name() { return 'customer_name'; }
function sales_customer_email() { return 'customer_email'; }
function sales_customer_cash_payment_amount() { return 'sales_customer_cash_payment_amount'; }

function request_sales_Auth() { return 'Auth'; }
function request_sales_CCResponse() { return 'CVV2ResponseMsg'; }
function sales_AuthCodeDeclined() { return 'Declined'; }
function sales_Notes() { return 'Notes'; }
function sales_purchase_error() { return 'M'; }
function sales_incorrect_number() { return 'N'; }

//Book Constants
function request_book_cost() { return 'query_cost'; }
function request_book_price(){ return 'query_sale_price'; }
function request_book_msrp() { return 'query_msrp'; }
function request_book_publisher() { return 'query_publisher'; }
function request_book_availability() { return 'query_availability'; }
function request_book_isbn() { return 'query_isbn'; }
function request_book_condition() { return 'query_condition'; }
function request_book_barcode() { return 'query_barcode'; }
function request_book_title() { return 'query_title'; }
function request_book_category() { return 'query_category'; }
function request_book_consigner_id() { return 'query_consigner_id'; }

function request_change_book_cost() { return 'change_book_cost'; }
function request_change_book_price(){ return 'change_book_sale_price'; }
function request_change_book_msrp() { return 'change_book_msrp'; }
function request_change_book_publisher() { return 'change_book_publisher'; }
function request_change_book_availability() { return 'change_book_availability'; }
function request_change_book_isbn() { return 'change_book_isbn'; }
function request_change_book_condition() { return 'change_book_condition'; }
function request_change_book_barcode() { return 'change_book_barcode'; }
function request_change_book_title() { return 'change_book_title'; }
function request_change_book_category() { return 'change_book_category'; }

function request_selected_book() { return 'selected_book_id'; }
function delete_book_set() { return 'delete_book_set'; }
function update_book_set() { return 'update_book_set'; }

//Consigners
function request_consigner_title() { return 'query_title'; }
function request_consigner_id(){ return 'query_id'; }
function request_consigner_date_from(){ return 'query_date_from'; }
function request_consigner_date_to(){ return 'query_date_to'; }

function request_change_consigner_title() { return 'change_consigner_title'; }
function request_change_consigner_date(){ return 'change_consigner_date'; }

function request_selected_consigner() { return 'selected_consigner_id'; }
function request_consigner_book_search() { return 'request_consigner_book_search'; }

//Transactions
function request_transaction_id(){ return 'query_id'; }
function request_transaction_date_from(){ return 'query_date_from'; }
function request_transaction_date_to(){ return 'query_date_to'; }

function request_change_transaction_date(){ return 'change_transaction_date'; }

function request_selected_transaction() { return 'selected_transaction_id'; }

//Actions
function request_page_action() { return 'page_action'; }

function query_search_action() { return 'query_select_action'; }
function book_select_action() { return 'book_select_action'; }
function add_book_action() { return 'add_book_action'; }
function change_book_action() { return 'change_book_action'; }
function add_consigner_to_book_action() { return 'add_consigner_to_book_action'; }
function add_consigner_action() { return 'add_consigner_action'; }
function consigner_select_action() { return 'consigner_select_action'; }
function search_consigner_books_action() { return 'search_consigner_books_action'; }
function add_book_to_consigner_action() { return 'add_book_to_consigner_action'; }
function remove_book_from_consigner_action() { return 'remove_book_from_consigner_action'; }
function remove_sold_book_from_consigner_action() { return 'remove_sold_book_from_consigner_action'; }
function update_consigner_action() { return 'update_consigner_action'; }

function update_transaction_action() { return 'update_transaction_action'; }
function transaction_select_action() { return 'transaction_select_action'; }

//General constants
function get_consigner_owner() { return '_cmb_consigner_owner'; }
function get_consigner_owner_id() { return get_option(get_consigner_owner()); }
function set_consigner_owner() { update_option('_cmb_consigner_owner', 66755); }


function get_book_consigners($book) {
    $consigners = get_post_meta($book, '_cmb_resource_consigners', true);
    if (empty($consigners)) {
        $consigners = array();
        set_book_consigners($book, $consigners);
    }
    return $consigners;
}

function get_consigner_count($book) {
    $consigners = get_book_consigners($book);
    return count($consigners);
}

function error_check_consigners($book) {
    $consigners = get_book_consigners($book);
    if (empty($consigners)) {
        $quantity = get_post_meta($book, '_cmb_resource_quantity', true);
        for ($i = 0; $i < $quantity; $i++){
            $consigners[] = get_option('_cmb_consigner_owner');
        }
        update_post_meta($book, '_cmb_resource_consigners', $consigners);
        return true;
    }
    else {
        $count = count($consigners);
        $quantity = get_post_meta($book, '_cmb_resource_quantity', true);
        if ($count < $quantity){
            $difference = $quantity - $count;
            for ($i = 0; $i < $difference; $i++){
                $consigners[] = get_option('_cmb_consigner_owner');
            }
            update_post_meta($book, '_cmb_resource_consigners', $consigners);
            return true;
        }
    }
    return false;
}

function set_book_consigners($book, $consigners) {
    update_post_meta($book, '_cmb_resource_consigners', $consigners);
}

function add_book_and_consigner($book_id, $consigner_id) {
    add_consigner_to_book($book_id, $consigner_id);
    add_book_to_consigner($consigner_id, $book_id);
}

function remove_book_and_consigner($book_id, $consigner_id) {
    remove_consigner_from_book($book_id, $consigner_id);
    remove_book_from_consigner($consigner_id, $book_id);
}

function add_consigner_to_book($book_id, $consigner_id) {
    $consigners = get_book_consigners($book_id);
    $consigners[] = $consigner_id;
    set_book_consigners($book_id, $consigners);
}

function remove_consigner_from_book($book_id, $consigner_id) {
    $consigners = get_book_consigners($book_id);
    if (($key = array_search($consigner_id, $consigners)) !== false) {
        unset($consigners[$key]);
    }
    $consigners = array_values($consigners);
    set_book_consigners($book_id, $consigners);
}

function add_book_to_consigner($consigner_id, $book_id) {
    $books = get_consigner_books($consigner_id);
    $books[] = $book_id;
    set_consigner_books($consigner_id, $books);
}

function remove_book_from_consigner($consigner_id, $book_id) {
    $books = get_consigner_books($consigner_id);
    if (($key = array_search($book_id, $books)) !== false) {
        unset($books[$key]);
    }
    $books = array_values($books);
    set_consigner_books($consigner_id, $books);
}

function add_book_post($booktitle, $bookcategory) {

    //Set up barcode
    $lastBarcodeExists = get_option('_cmb_resource_lastBarcode');
    $lastBarcode = 15000;
    if ($lastBarcodeExists == false){
        add_option('_cmb_resource_lastBarcode', 15000);
        $lastBarcode = get_option('_cmb_resource_lastBarcode');
    }
    $newbarcode = $lastBarcode + 1;
    update_option('_cmb_resource_lastBarcode', $newbarcode);

    $order = array(
        'post_title' => $booktitle,
        'post_status' => 'publish',
        'post_author' => 4,
        'post_category' => array(
            $bookcategory
        ),
        'post_type' => 'bookstore'
    );
    $postid = wp_insert_post($order);
    set_book_sku($postid, $newbarcode);
    return $postid;
}

function add_book($booktitle, $bookcategory, $cost, $price, $MSRP, $publisher, $condition, $available, $newISBN) {
    $postid = add_book_post($booktitle, $bookcategory);
    set_book_cost($postid, $cost);
    set_book_condition($postid, $condition);
    set_book_sku($postid, $newISBN);
    set_book_msrp($postid, $MSRP);
    set_book_publisher($postid, $publisher);
    set_book_saleprice($postid, $price);
    set_book_availablity($postid, $available);
    return $postid;
}

function delete_book($product_id) {
    $consigners = get_book_consigners($product_id);
    if (!empty($consigners)) {
        foreach ($consigners as $consigner) {
            $consigner_book_list = get_consigner_books($consigner);
            if (!empty($consigner_book_list)) {
                $temparr = array();
                foreach ($consigner_book_list as $book) {
                    if ($product_id != $book) {
                        $temparr[] = $book;
                    }
                }
                set_consigner_books($consigner, $temparr);
            }
        }
    }
    wp_delete_post($product_id);
}

function update_book($product_id) {
    //Get the input data from form
    $booktitle = $_REQUEST[request_change_book_title()];
    $cost = $_REQUEST[request_change_book_cost()];
    $price = $_REQUEST[request_change_book_price()];
    $MSRP = $_REQUEST[request_change_book_msrp()];
    $vendor = $_REQUEST[request_change_book_publisher()];
    $condition = $_REQUEST[request_change_book_condition()];
    $newISBN = $_REQUEST[request_change_book_isbn()];
    $barcode = $_REQUEST[request_change_book_barcode()];

    $titleupdate = array(
        'ID'           => $product_id,
        'post_title'   => $booktitle,
    );
    wp_update_post($titleupdate);

    set_book_cost($product_id, $cost);
    set_book_saleprice($product_id, $price);
    set_book_msrp($product_id, $MSRP);
    set_book_publisher($product_id, $vendor);
    set_book_barcode($product_id, $barcode);
    set_book_sku($product_id, $newISBN);
    set_book_condition($product_id, $condition);
}

function get_consigner_books($consigner) {
    $ret = get_post_meta($consigner, "_cmb_consigner_books", true);
    if (!$ret){
        $ret = array();
    }
    return $ret;
}

function set_consigner_books($consigner, $books) {
    update_post_meta($consigner, "_cmb_consigner_books", $books);
}

function get_consigner_sold_books($consigner) {
    $ret = get_post_meta($consigner, "_cmb_consigner_sold_books", true);
    if (!$ret){
        $ret = array();
    }
    return $ret;
}

function set_consigner_sold_books($consigner, $books) {
    update_post_meta($consigner, "_cmb_consigner_sold_books", $books);
}

function get_consigner_id($consigner) {
    return get_post_meta($consigner, "_cmb_consigner_id", true);
}

function set_consigner_id($consigner, $id) {
    update_post_meta($consigner, "_cmb_consigner_id", $id);
}

function get_consigner_date($consigner) {
    return get_post_meta($consigner, "_cmb_consigner_date", true);
}

function set_consigner_date($consigner, $date) {
    update_post_meta($consigner, "_cmb_consigner_date", $date);
}

function add_consigner() {
    //Get the input data from form
    $consignertitle = $_REQUEST[request_consigner_title()];
    $consignerdate = $_REQUEST[request_consigner_date_to()];

    $order = array(
        'post_title' => $consignertitle,
        'post_status' => 'publish',
        'post_author' => 4,
        'post_type' => 'consigners'
    );
    $postid = wp_insert_post($order);

    //Set up barcode
    $lastConsignerID = get_option('_cmb_consigner_lastID');
    if ($lastConsignerID == false){
        add_option('_cmb_consigner_lastID', -1);
        $lastConsignerID = get_option('_cmb_consigner_lastID');
        update_option('_cmb_consigner_owner', $postid);
    }
    $newConsignerID = $lastConsignerID + 1;
    update_option('_cmb_consigner_lastID', $newConsignerID);

    set_consigner_id($postid, $newConsignerID);
    set_consigner_date($postid, $consignerdate);
    return $postid;
}

function consigner_add_sold_book($consigner_id, $book_id) {
    $books = get_consigner_sold_books($consigner_id);
    $books[$book_id] = 'N';
    set_consigner_sold_books($consigner_id, $books);
}

function consigner_remove_sold_book($consigner_id, $book_id) {
    $books = get_consigner_sold_books($consigner_id);
    if (($key = array_search($book_id, $books)) !== false) {
        unset($books[$key]);
    }
    $books = array_values($books);
    set_consigner_sold_books($consigner_id, $books);
}

function update_consigner($consigner_id) {
    $title = $_REQUEST[request_change_consigner_title()];
    $date = $_REQUEST[request_change_consigner_date()];

    $titleupdate = array(
        'ID'           => $consigner_id,
        'post_title'   => $title,
    );
    wp_update_post($titleupdate);
    set_consigner_date($consigner_id, $date);
}

function get_transaction_total($transaction_id) {
    $books = get_transaction_books($transaction_id);
    $total = 0;
    if (!empty($books)) {
        foreach ($books as $book) {
            $total += $book[1] * $book[2];
        }
    }
    return $total;
}

function get_transaction_books($transaction_id) {
    return get_post_meta($transaction_id, '_cmb_transaction_books', true);
}

function set_transaction_books($transaction_id, $books) {
    update_post_meta($transaction_id, '_cmb_transaction_books', $books);
}

function get_transaction_credits($transaction_id) {
    return get_post_meta($transaction_id, '_cmb_transaction_credits', true);
}

function set_transaction_credits($transaction_id, $credits) {
    update_post_meta($transaction_id, '_cmb_transaction_credits', $credits);
}

function get_transaction_customer_name($transaction_id){
    return get_post_meta($transaction_id, '_cmb_transaction_customer_name', true);
}

function set_transaction_customer_name($transaction_id, $name){
    update_post_meta($transaction_id, '_cmb_transaction_customer_name', $name);
}

function get_transaction_customer_email($transaction_id){
    return get_post_meta($transaction_id, '_cmb_transaction_customer_email', true);
}

function set_transaction_customer_email($transaction_id, $name){
    update_post_meta($transaction_id, '_cmb_transaction_customer_email', $name);
}

function get_transaction_customer_address($transaction_id){
    return get_post_meta($transaction_id, '_cmb_transaction_customer_address', true);
}

function set_transaction_customer_address($transaction_id, $name){
    update_post_meta($transaction_id, '_cmb_transaction_customer_address', $name);
}

function get_transaction_date($transaction_id){
    return get_post_meta($transaction_id, '_cmb_transaction_date', true);
}

function set_transaction_date($transaction_id, $date){
    update_post_meta($transaction_id, '_cmb_transaction_date', $date);
}

function get_transaction_id($transaction_id){
    return get_post_meta($transaction_id, '_cmb_transaction_id', true);
}

function set_transaction_id($transaction_id, $id){
    update_post_meta($transaction_id, '_cmb_transaction_id', $id);
}

function get_transaction_transfirstid($transaction_id){
    return get_post_meta($transaction_id, '_cmb_transaction_transfirstid', true);
}

function set_transaction_transfirstid($transaction_id, $id){
    update_post_meta($transaction_id, '_cmb_transaction_transfirstid', $id);
}

function get_transaction_taxrate($transaction_id){
    return get_post_meta($transaction_id, '_cmb_transaction_taxrate', true);
}

function set_transaction_taxrate($transaction_id, $id){
    update_post_meta($transaction_id, '_cmb_transaction_taxrate', $id);
}

function get_transaction_payment_type($transaction_id){
    return get_post_meta($transaction_id, '_cmb_transaction_payment_type', true);
}

function set_transaction_payment_type($transaction_id, $type){
    update_post_meta($transaction_id, '_cmb_transaction_payment_type', $type);
}

function get_transaction_paid($transaction_id){
    return get_post_meta($transaction_id, '_cmb_transaction_paid', true);
}

function set_transaction_paid($transaction_id, $paid){
    update_post_meta($transaction_id, '_cmb_transaction_paid', $paid);
}