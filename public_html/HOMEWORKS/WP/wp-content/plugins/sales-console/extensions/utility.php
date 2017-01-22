<?php
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 12/29/2016
 * Time: 2:23 PM
 */

//Sales Methods
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

class vars {
    public static $var_name = 0;
    public static $var_type = 1;
    public static $var_db = 2;
    public static $var_search = 3;
    public static $var_edit = 4;
    public static $var_add = 5;
    public static $var_format = 6;

    public static $current_page;

    public static $search_prefix = 'search_';
    public static $edit_prefix = 'edit_';
    public static $add_prefix = 'add_';

    public static $went_back = 'went_back';
    public static $new_page = 'current_page';
    public static $last_page = 'last_page';
    public static $last2_page = 'last_page';
    public static $last2_action = 'last2_action';
    public static $last_action = 'last_action';
    public static $new_action = 'new_action';
    public static $consigner_page = 'admin.php?page=sales_console_consigners';
    public static $library_page = 'admin.php?page=library_breakdown';
    public static $transaction_page = 'admin.php?page=sales_console_transactions';
    public static $checkout_page = 'admin.php?page=sales-console-admin';

    public static function GetPage($source) {
        if ($source == Book::$source) {
            return vars::$library_page;
        }
        else if ($source == Consigner::$source) {
            return vars::$consigner_page;
        }
        else if ($source == Transaction::$source) {
            return vars::$transaction_page;
       }
        return '';
    }

    public static $conference_name_option = 'conference_name_option';
    public static $shipping_margin_option = 'shipping_margin_option';
    public static $allow_multiple_categories_option = 'allow_multiple_categories';
}

function get_next_invoice() {
    $lastID = get_option('_cmb_transaction_lastID');
    if (!$lastID){
        add_option('_cmb_transaction_lastID', 2000);
        $lastID = get_option('_cmb_transaction_lastID');
    }
    $newbarcode = $lastID + 1;
    update_option('_cmb_transaction_lastID', $newbarcode);
    return $newbarcode;
}

function get_consigner_wp_id($id) {
    $args = array(
        'numberposts' => -1,
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'date',
        'post_type' => Consigner::$post_type,
    );

    $meta_query_array = array('relation' => 'AND');
    $args['meta_query'] = $meta_query_array;

    $args['meta_query'][] = array(
        'key' => Consigner::$props[Consigner::$id]->db_value,
        'value' => $id
    );

    $query = new WP_Query($args);

    while ($query->have_posts()):
        $query->the_post();
        global $post;
        $id = $post->ID;
        return $id;
    endwhile;
    return get_consigner_owner_id();
}

function get_book_by_title($title) {
    $args = array(
        'numberposts' => -1,
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'date',
        'post_type' => Book::$post_type,
        'sentence' => 1
    );

    $meta_query_array = array('relation' => 'AND');
    $args['meta_query'] = $meta_query_array;
    $args['s'] = $title;

    $query = new WP_Query($args);

    while ($query->have_posts()):
        $query->the_post();
        global $post;
        $id = $post->ID;
        return $id;
    endwhile;
    return false;
}

function cast_decimal_precision( $array ) {
    $array['where'] = str_replace('DECIMAL','DECIMAL(10,3)',$array['where']);
    return $array;
}

function dump($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

function align($type) {
    return ' align="'.$type.'" ';
}

function width($percent) {
    return ' width="'.$percent.'%" ';
}

function value($str) {
    return ' value="'.$str.'" ';
}

function action($str) {
    return ' action="'.$str.'" ';
}

function id($str) {
    return ' id="'.$str.'" ';
}

function method($str) {
    return ' method="'.$str.'" ';
}

function name($str) {
    return ' name="'.$str.'" ';
}

function colspan($str) {
    return ' colspan="'.$str.'" ';
}

function valign($str) {
    return ' valign="'.$str.'" ';
}

function border($str) {
    return ' border="'.$str.'" ';
}

function cellpadding($str) {
    return ' cellpadding="'.$str.'" ';
}

function cellspacing($str) {
    return ' cellspacing="'.$str.'" ';
}

function type($str) {
    return ' type="'.$str.'" ';
}

function classType($str) {
    return ' class="'.$str.'" ';
}

function style($str){
    return ' style="'.$str.'" ';
}

function size($str){
    return ' size="'.$str.'" ';
}

function form($str){
    return ' form="'.$str.'" ';
}

function checkedAttr($str){
    return ' checked="'.$str.'" ';
}

function maxlength($str){
    return ' maxlength="'.$str.'" ';
}

function placeholder($str){
    return ' placeholder="'.$str.'" ';
}

function onclick($str){
    return ' onclick="'.$str.'" ';
}

function forAttr($str){
    return ' for="'.$str.'" ';
}

function ariahidden($str){
    return ' aria-hidden="'.$str.'" ';
}

function tabindex($str){
    return ' tabindex="'.$str.'" ';
}

function step($str){
    return ' step="'.$str.'" ';
}

function href($str){
    return ' href="'.$str.'" ';
}

function add_image($id) {
    set_post_thumbnail($id, absint( $_POST[Book::$image_set]));
}

function button($value) {
    return new Input(classType('button-primary').type('submit').name('button').value($value));
}

function get_user_name() {
    $user = wp_get_current_user();
    return $user->first_name;
}

function get_state_comma($state) {
    if ($state != ""){
        return ', ';
    }
    else return '';
}