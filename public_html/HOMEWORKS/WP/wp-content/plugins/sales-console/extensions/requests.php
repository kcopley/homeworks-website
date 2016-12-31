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
    public static $delete_book_sure = 'delete_book_sure_action';
    public static $change_book = 'change_book_action';
    public static $add_image_to_book_search = 'add_image_to_book_search';
    public static $add_image_to_book_edit = 'add_image_to_book_edit';

    public static $add_book_to_owner = 'add_book_to_owner_action';
    public static $select_consigner = 'select_consigner_action';
}

class selection {
    public static $book = 'selected_book';
    public static $consigner = 'selected_consigner';

    public static function InputBook($id) {
        return new Input(id(selection::$book).type('hidden').name(selection::$book).value($id));
    }

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

    public static $image_set = 'image_attachment_id';

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
        $args = func_get_args();
        $form = '';
        if (count($args) > 0){
            $form = $args[0];
        }
        if (!empty($form)){
            $list = new RenderList(
                new Input(form($form).id(book_request::$department).name(book_request::$department).type('hidden').value(self::GetDepartment())),
                new Input(form($form).id(book_request::$condition).name(book_request::$condition).type('hidden').value(self::GetCondition())),
                new Input(form($form).id(book_request::$price).name(book_request::$price).type('hidden').value(self::GetPrice())),
                new Input(form($form).id(book_request::$availability).name(book_request::$availability).type('hidden').value(self::GetAvailability())),
                new Input(form($form).id(book_request::$publisher).name(book_request::$publisher).type('hidden').value(self::GetPublisher())),
                new Input(form($form).id(book_request::$title).name(book_request::$title).type('hidden').value(self::GetTitle())),
                new Input(form($form).id(book_request::$barcode).name(book_request::$barcode).type('hidden').value(self::GetBarcode())),
                new Input(form($form).id(book_request::$isbn).name(book_request::$isbn).type('hidden').value(self::GetISBN()))
            );
        }
        else {
            $list = new RenderList(
                new Input(id(book_request::$department).name(book_request::$department).type('hidden').value(self::GetDepartment())),
                new Input(id(book_request::$condition).name(book_request::$condition).type('hidden').value(self::GetCondition())),
                new Input(id(book_request::$price).name(book_request::$price).type('hidden').value(self::GetPrice())),
                new Input(id(book_request::$availability).name(book_request::$availability).type('hidden').value(self::GetAvailability())),
                new Input(id(book_request::$publisher).name(book_request::$publisher).type('hidden').value(self::GetPublisher())),
                new Input(id(book_request::$title).name(book_request::$title).type('hidden').value(self::GetTitle())),
                new Input(id(book_request::$barcode).name(book_request::$barcode).type('hidden').value(self::GetBarcode())),
                new Input(id(book_request::$isbn).name(book_request::$isbn).type('hidden').value(self::GetISBN()))
            );
        }
        return $list;
    }
}

class book_editing
{
    public static $title = 'edit_title';
    public static $cost = 'edit_cost';
    public static $price = 'edit_price';
    public static $MSRP = 'edit_MSRP';
    public static $publisher = 'edit_publisher';
    public static $isbn = 'edit_isbn';
    public static $condition = 'edit_condition';
    public static $availability = 'edit_availability';
    public static $barcode = 'edit_barcode';

    public static function GetTitle() {
        return $_REQUEST[book_editing::$title];
    }

    public static function GetCost() {
        return $_REQUEST[book_editing::$cost];
    }

    public static function GetPrice() {
        return $_REQUEST[book_editing::$price];
    }

    public static function GetMSRP() {
        return $_REQUEST[book_editing::$MSRP];
    }

    public static function GetPublisher() {
        return $_REQUEST[book_editing::$publisher];
    }

    public static function GetISBN() {
        return $_REQUEST[book_editing::$isbn];
    }

    public static function GetCondition() {
        return $_REQUEST[book_editing::$condition];
    }

    public static function GetAvailability() {
        return $_REQUEST[book_editing::$availability];
    }

    public static function GetBarcode() {
        return $_REQUEST[book_editing::$barcode];
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
        return str_replace('$', '', get_post_meta($post_id, '_cmb_resource_cost', true));
    }

    public static function set_book_cost($post_id, $cost) {
        update_post_meta($post_id, '_cmb_resource_cost', str_replace('$', '', $cost));
    }

    public static function get_book_msrp($post_id) {
        return str_replace('$', '', get_post_meta($post_id, '_cmb_resource_MSRP', true));
    }

    public static function set_book_msrp($post_id, $msrp) {
        update_post_meta($post_id, '_cmb_resource_MSRP', str_replace('$', '', $msrp));
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

    public static function get_consigners($book) {
        $consigners = get_post_meta($book, '_cmb_resource_consigners', true);
        if (empty($consigners)) {
            $consigners = array();
            book_properties::set_consigners($book, $consigners);
        }
        return $consigners;
    }

    public static function get_consigner_count($book) {
        $consigners = book_properties::get_consigners($book);
        if ($consigners && !empty($consigners))
            return count($consigners);
        else return 0;
    }

    public static function set_consigners($book, $consigners) {
        update_post_meta($book, '_cmb_resource_consigners', $consigners);
    }

    public static function get_image_form($id, $action) {
        $color = 'red';
        $text = 'No';
        if (book_properties::get_book_image($id)){
            $text = 'Yes';
            $color = 'green';
        }
        return new Form(method('post').id($id).name($id),
            page_action::InputAction($action),
            book_request::Store(),
            selection::InputBook($id),
            new Input(id($id).type('button').classType('upload_image_button').style('color: '.$color.';').value($text)),
            new Input(type('hidden').name(book_request::$image_set).id(book_request::$image_set))
        );
    }

    function add_book($book_id, $consigner_id) {
        book_properties::add_consigner_to_book($book_id, $consigner_id);
        consigner_properties::add_book_to_consigner($consigner_id, $book_id);
    }

    private static function add_consigner_to_book($book, $consigner) {
        $consigners = book_properties::get_consigners($book);
        $consigners[] = $consigner;
        set_consigners($book, $consigners);
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
    public static $quantity = 'add_quantity';

    public static function GetCondition() {
        return $_REQUEST[book_addition::$condition];
    }

    public static function GetAvailability() {
        return $_REQUEST[book_addition::$availability];
    }

    public static function GetDepartment() {
        return $_REQUEST[book_addition::$department];
    }

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

    public static function InputQuantity() {
        return new Input(id(book_addition::$quantity).name(book_addition::$quantity).type('text'));
    }

    public static function GetQuantity() {
        return $_REQUEST[book_addition::$quantity];
    }

    public static function add_book_post($booktitle, $bookcategory) {
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
        book_properties::set_book_sku($postid, $newbarcode);
        return $postid;
    }

    public static function add_book() {
        $postid = add_book_post(book_addition::GetTitle(), book_addition::GetDepartment());
        book_properties::set_book_cost($postid, book_addition::GetCost());
        book_properties::set_book_condition($postid, book_addition::GetCondition());
        book_properties::set_book_sku($postid, book_addition::GetISBN());
        book_properties::set_book_msrp($postid, book_addition::GetMSRP());
        book_properties::set_book_publisher($postid, book_addition::GetPublisher());
        book_properties::set_book_saleprice($postid, book_addition::GetPrice());
        book_properties::set_book_availablity($postid, book_addition::GetAvailability());

        for ($i = 0; $i < book_addition::GetQuantity(); $i++) {
            add_book_and_consigner($postid, get_consigner_owner());
        }
        return $postid;
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

class consigner_properties {
    public static function get_consigner_name($consigner) {
        return get_the_title($consigner);
    }

    public static function get_books($consigner) {
        $ret = get_post_meta($consigner, "_cmb_consigner_books", true);
        if (!$ret){
            $ret = array();
        }
        return $ret;
    }

    public static function set_books($consigner, $books) {
        update_post_meta($consigner, "_cmb_consigner_books", $books);
    }

    public static function get_consigner_sold_books($consigner) {
        $ret = get_post_meta($consigner, "_cmb_consigner_sold_books", true);
        if (!$ret){
            $ret = array();
        }
        return $ret;
    }

    public static function set_consigner_sold_books($consigner, $books) {
        update_post_meta($consigner, "_cmb_consigner_sold_books", $books);
    }

    public static function get_consigner_id($consigner) {
        return get_post_meta($consigner, "_cmb_consigner_id", true);
    }

    public static function set_consigner_id($consigner, $id) {
        update_post_meta($consigner, "_cmb_consigner_id", $id);
    }

    public static function get_consigner_date($consigner) {
        return get_post_meta($consigner, "_cmb_consigner_date", true);
    }

    public static function set_consigner_date($consigner, $date) {
        update_post_meta($consigner, "_cmb_consigner_date", $date);
    }

    public static function consigner_add_sold_book($consigner_id, $book_id) {
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

    private static function add_consigner_to_book($consigner, $book) {
        $books = consigner_properties::get_books($consigner);
        $books[] = $book;
        consigner_properties::set_books($consigner, $books);
    }
}

class consigner_addition {
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
}