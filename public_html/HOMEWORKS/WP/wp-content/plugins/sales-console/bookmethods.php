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

//General constants
function get_consigner_owner() { return '_cmb_consigner_owner'; }
function get_consigner_owner_id() { return get_option(get_consigner_owner()); }
function set_consigner_owner() { update_option('_cmb_consigner_owner', 66755); }



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
    $booktitle = book_editing::GetTitle();
    $cost = book_editing::GetCost();
    $price = book_editing::GetPrice();
    $MSRP = book_editing::GetMSRP();
    $vendor = book_editing::GetPublisher();
    $condition = book_editing::GetCondition();
    $newISBN = book_editing::GetISBN();
    $barcode = book_editing::GetBarcode();

    $titleupdate = array(
        'ID'           => $product_id,
        'post_title'   => $booktitle,
    );
    wp_update_post($titleupdate);

    book_properties::set_book_cost($product_id, $cost);
    book_properties::set_book_saleprice($product_id, $price);
    book_properties::set_book_msrp($product_id, $MSRP);
    book_properties::set_book_publisher($product_id, $vendor);
    book_properties::set_book_barcode($product_id, $barcode);
    book_properties::set_book_sku($product_id, $newISBN);
    book_properties::set_book_condition($product_id, $condition);
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