<?php
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 12/29/2016
 * Time: 3:06 PM
 */
class action_types {
    public static $search = 'search_action';
    public static $add_book = 'add_book_action';
    public static $select_book = 'select_book_action';
    public static $edit_book = 'edit_book_action';
    public static $delete_book = 'delete_book_action';
}

class selection {
    public static $book = 'selected_book';
    public static $consigner = 'selected_consigner';

    public static function GetBook() {
        return $_REQUEST[selection::$book];
    }

    public static function GetConsigner() {
        return $_REQUEST[selection::$consigner];
    }
}

function QueryBook() {
    $args = array(
        'numberposts' => -1,
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'date',
        'post_type' => 'bookstore'
    );

    $meta_query_array = array('relation' => 'AND');

    if (book_request::GetTitle()) {
        $args['s'] = book_request::GetTitle();
    }
    if (book_request::GetDepartment()) {
        $args['cat'] = book_request::GetDepartment();
    }
    if (book_request::GetPublisher()) {
        $meta_query_array[] = array(
            'key' => '_cmb_resource_publisher',
            'value' => book_request::GetPublisher()
        );
    }
    if (book_request::GetPrice()) {
        $meta_query_array[] = array(
            'key' => '_cmb_resource_price',
            'value' => book_request::GetPrice()
        );
    };
    if (book_request::GetBarcode()) {
        $meta_query_array[] = array(
            'key' => '_cmb_resource_barcode',
            'value' => book_request::GetBarcode()
        );
    };
    if (book_request::GetISBN()) {
        $meta_query_array[] = array(
            'key' => '_cmb_resource_u-sku',
            'value' => book_request::GetISBN()
        );
        $meta_query_array[] = array(
            'key' => '_cmb_resource_sku',
            'value' => book_request::GetISBN()
        );
    }
    if (book_request::GetCondition()) {
        $meta_query_array[] = array(
            'key' => '_cmb_resource_condition',
            'value' => book_request::GetCondition()
        );
    };
    if (book_request::GetAvailability()) {
        $meta_query_array[] = array(
            'key' => '_cmb_resource_available',
            'value' => book_request::GetAvailability()
        );
    };

    $args['meta_query'] = $meta_query_array;
    return new WP_Query($args);
}

class book_request {
    public static $title = 'query_title';
    public static $barcode = 'query_barcode';
    public static $isbn = 'query_isbn';
    public static $publisher = 'query_publisher';
    public static $price = 'query_price';
    public static $department = 'query_department';
    public static $availability = 'query_availability';
    public static $condition = 'query_condition';

    public static function InputTitle() {
        return new Input(id(book_request::$title).name(book_request::$title).type('text'));
    }

    public static function GetTitle() {
        return $_REQUEST[book_request::$title];
    }

    public static function InputBarcode() {
        return new Input(id(book_request::$barcode).name(book_request::$barcode).type('text'));
    }

    public static function GetBarcode() {
        return $_REQUEST[book_request::$barcode];
    }

    public static function InputISBN() {
        return new Input(id(book_request::$isbn).name(book_request::$isbn).type('text'));
    }

    public static function GetISBN() {
        return $_REQUEST[book_request::$isbn];
    }

    public static function InputPublisher() {
        return new Input(id(book_request::$publisher).name(book_request::$publisher).type('text'));
    }

    public static function GetPublisher() {
        return $_REQUEST[book_request::$publisher];
    }

    public static function InputPrice() {
        return new Input(id(book_request::$price).name(book_request::$price).type('text'));
    }

    public static function GetPrice() {
        return $_REQUEST[book_request::$price];
    }

    public static function GetDepartment() {
        return $_REQUEST[book_request::$department];
    }

    public static function GetAvailability() {
        return $_REQUEST[book_request::$availability];
    }

    public static function GetCondition(){
        return $_REQUEST[book_request::$condition];
    }

    public static function Store() {
        $list = new RenderList(
            new Input(id(book_request::$department).name(book_request::$department).type('hidden')),
            new Input(id(book_request::$condition).name(book_request::$condition).type('hidden')),
            new Input(id(book_request::$price).name(book_request::$price).type('hidden')),
            new Input(id(book_request::$availability).name(book_request::$availability).type('hidden')),
            new Input(id(book_request::$publisher).name(book_request::$publisher).type('hidden')),
            new Input(id(book_request::$title).name(book_request::$title).type('hidden')),
            new Input(id(book_request::$barcode).name(book_request::$barcode).type('hidden')),
            new Input(id(book_request::$isbn).name(book_request::$isbn).type('hidden'))
        );
        return $list;
    }
}

class book_properties {
    public static $title = 'title';
    public static $cost = 'cost';
    public static $price = 'price';
    public static $MSRP = 'MSRP';
    public static $publisher = 'publisher';
    public static $isbn = 'isbn';
    public static $condition = 'condition';
    public static $availability = 'availability';
    public static $barcode = 'barcode';
    public static $hasimage = 'hasimage';
    public static $selectable = 'selectable';
    public static $quantity = 'quantity';

    public static function get_book_title($post_id) {
        return get_the_title($post_id);
    }

    public static function get_book_cost($post_id) {
        return get_post_meta($post_id, '_cmb_resource_cost', true);
    }

    public static function set_book_cost($post_id, $cost) {
        update_post_meta($post_id, '_cmb_resource_cost', $cost);
    }

    public static function get_book_msrp($post_id) {
        return get_post_meta($post_id, '_cmb_resource_MSRP', true);
    }

    public static function set_book_msrp($post_id, $msrp) {
        update_post_meta($post_id, '_cmb_resource_MSRP', $msrp);
    }

    public static function get_book_saleprice($post_id) {
        return str_replace('$', '', get_post_meta($post_id, '_cmb_resource_price', true));
    }

    public static function set_book_saleprice($post_id, $saleprice) {
        $saleprice = str_replace('$', '', $saleprice);
        update_post_meta($post_id, '_cmb_resource_price', $saleprice);
    }

    public static function get_book_publisher($post_id) {
        return get_post_meta($post_id, '_cmb_resource_publisher', true);
    }

    public static function set_book_publisher($post_id, $publisher) {
        update_post_meta($post_id, '_cmb_resource_publisher', $publisher);
    }

    public static function get_book_availablity($post_id) {
        return get_post_meta($post_id, '_cmb_resource_available', true);
    }

    public static function set_book_availablity($post_id, $availability) {
        update_post_meta($post_id, '_cmb_resource_available', $availability);
    }

    public static function get_book_isbn($post_id) {
        $sku = get_post_meta($post_id, '_cmb_resource_sku', true);
        if (empty($sku)) {
            $sku = get_post_meta($post_id, '_cmb_resource_u-sku', true);
            update_post_meta($post_id, '_cmb_resource_sku', $sku);
        }
        return $sku;
    }

    public static function set_book_sku($post_id, $sku) {
        update_post_meta($post_id, '_cmb_resource_sku', $sku);
    }

    public static function get_book_condition($post_id) {
        return get_post_meta($post_id, '_cmb_resource_condition', true);
    }

    public static function set_book_condition($post_id, $condition) {
        update_post_meta($post_id, '_cmb_resource_condition', $condition);
    }

    public static function get_book_barcode($post_id) {
        return get_post_meta($post_id, '_cmb_resource_barcode', true);
    }

    public static function set_book_barcode($post_id, $barcode) {
        update_post_meta($post_id, '_cmb_resource_barcode', $barcode);
    }

    public static function get_book_numsold($post_id) {
        return get_post_meta($post_id, '_cmb_resource_numsold', true);
    }

    public static function add_book_numsold($post_id, $num) {
        $curNum = get_book_numsold($post_id);
        set_book_numsold($post_id, $curNum + $num);
    }

    public static function set_book_numsold($post_id, $num) {
        update_post_meta($post_id, '_cmb_resource_numsold', $num);
    }

    public static function get_book_image($post_id) {
        return has_post_thumbnail($post_id);
    }
}

class book_addition {
    public static $title = 'add_title';
    public static $barcode = 'add_barcode';
    public static $isbn = 'add_isbn';
    public static $publisher = 'add_publisher';
    public static $price = 'add_price';
    public static $cost = 'add_cost';
    public static $msrp = 'add_msrp';
    public static $department = 'add_department';
    public static $availability = 'add_availability';
    public static $condition = 'add_condition';

    public static function InputTitle() {
        return new Input(id(book_addition::$title).name(book_addition::$title).type('text'));
    }

    public static function GetTitle() {
        return $_REQUEST[book_addition::$title];
    }

    public static function InputBarcode() {
        return new Input(id(book_addition::$barcode).name(book_addition::$barcode).type('text'));
    }

    public static function GetBarcode() {
        return $_REQUEST[book_addition::$barcode];
    }

    public static function InputISBN() {
        return new Input(id(book_addition::$isbn).name(book_addition::$isbn).type('text'));
    }

    public static function GetISBN() {
        return $_REQUEST[book_addition::$isbn];
    }

    public static function InputPublisher() {
        return new Input(id(book_addition::$publisher).name(book_addition::$publisher).type('text').value(book_request::GetPublisher()));
    }

    public static function GetPublisher() {
        return $_REQUEST[book_addition::$publisher];
    }

    public static function InputPrice() {
        return new Input(id(book_addition::$price).name(book_addition::$price).type('text'));
    }

    public static function GetPrice() {
        return $_REQUEST[book_addition::$price];
    }

    public static function InputMSRP() {
        return new Input(id(book_addition::$msrp).name(book_addition::$msrp).type('text'));
    }

    public static function GetMSRP() {
        return $_REQUEST[book_addition::$msrp];
    }

    public static function InputCost() {
        return new Input(id(book_addition::$cost).name(book_addition::$cost).type('text'));
    }

    public static function GetCost() {
        return $_REQUEST[book_addition::$cost];
    }
}

class page_action {
    public static $action = 'page_action';

    public static function InputAction($value) {
        return new Input(id(page_action::$action).name(page_action::$action).type('hidden').value($value));
    }

    public static function GetAction() {
        return $_REQUEST[page_action::$action];
    }
}