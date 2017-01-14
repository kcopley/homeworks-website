<?php
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 1/1/2017
 * Time: 7:40 PM
 */
function QueryConsigner() {
    $args = array(
        'numberposts' => -1,
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'date',
        'post_type' => 'consigners'
    );

    $meta_query_array = array('relation' => 'AND');

    if (consigner_request::GetName()) {
        $args['s'] = consigner_request::GetName();
    }
    if (consigner_request::GetID()) {
        $meta_query_array[] = array(
            'key' => '_cmb_consigner_id',
            'value' => consigner_request::GetID()
        );
    }
    if (consigner_request::GetDateFrom() || consigner_request::GetDateTo()) {
        $meta_query_array[] =
            array(
                'key' => '_cmb_consigner_date',
                'value' => array(consigner_request::GetDateFrom(), consigner_request::GetDateTo()),
                'compare' => 'BETWEEN',
                'type' => 'DATE'
            );
    }

    $args['meta_query'] = $meta_query_array;
    return new WP_Query($args);
}

class consigner_properties {
    public static $name = 'consigner_name';
    public static $date = 'consigner_date';
    public static $id = 'consigner_id';
    public static $consigner_email = 'consigner_email';
    public static $consigner_address = 'consigner_address';
    public static $consigner_phone = 'consigner_phone';
    public static $consigner_paypal = 'consigner_paypal';
    public static $consigner_additional_info = 'consigner_additional_info';
    public static $delete = 'consigner_deletable';
    public static $selectable = 'consigner_selectable';
    public static $paid_column = 'paid_column';

    public static $book_id = 'id';
    public static $book_paid = 'paid';

    public static function get_consigner_name($consigner) {
        return get_the_title($consigner);
    }

    public static function set_consigner_name($id, $title) {
        $titleupdate = array(
            'ID'           => $id,
            'post_title'   => $title,
        );
        wp_update_post($titleupdate);
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

    public static function get_sold_books($consigner) {
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

    public static function get_consigner_paypal($consigner) {
        return get_post_meta($consigner, "_cmb_consigner_paypal", true);
    }

    public static function set_consigner_paypal($consigner, $paypal) {
        update_post_meta($consigner, "_cmb_consigner_paypal", $paypal);
    }

    public static function get_consigner_info($consigner) {
        return get_post_meta($consigner, "_cmb_consigner_info", true);
    }

    public static function set_consigner_info($consigner, $info) {
        update_post_meta($consigner, "_cmb_consigner_info", $info);
    }

    public static function get_consigner_address($consigner) {
        return get_post_meta($consigner, "_cmb_consigner_address", true);
    }

    public static function set_consigner_address($consigner, $address) {
        update_post_meta($consigner, "_cmb_consigner_address", $address);
    }

    public static function get_consigner_email($consigner) {
        return get_post_meta($consigner, "_cmb_consigner_email", true);
    }

    public static function set_consigner_email($consigner, $email) {
        update_post_meta($consigner, "_cmb_consigner_email", $email);
    }

    public static function get_consigner_phone($consigner) {
        return get_post_meta($consigner, "_cmb_consigner_phone", true);
    }

    public static function set_consigner_phone($consigner, $phone) {
        update_post_meta($consigner, "_cmb_consigner_phone", $phone);
    }

    public static function consigner_add_sold_book($consigner_id, $book_id) {
        $books = consigner_properties::get_sold_books($consigner_id);
        if (!$books) $books = array();
        $books[] = self::create_sold_book($book_id);
        consigner_properties::set_consigner_sold_books($consigner_id, $books);
    }

    public static function create_sold_book($book_id) {
        return array(
            self::$book_id => $book_id,
            self::$book_paid => 'No'
        );
    }

    function consigner_remove_sold_book($consigner_id, $book_id) {
        $books = consigner_properties::get_sold_books($consigner_id);
        if (($key = array_search($book_id, $books)) !== false) {
            unset($books[$key]);
        }
        $books = array_values($books);
        consigner_properties::set_consigner_sold_books($consigner_id, $books);
    }

    public static function add_book_to_consigner($consigner, $book) {
        if (consigner_properties::get_consigner_id($consigner) != 0) {
            $books = consigner_properties::get_books($consigner);
            $books[] = $book;
            consigner_properties::set_books($consigner, $books);
        }
    }

    public static function get_owner() {
        return get_option('_cmb_consigner_owner');
    }

    public static function update_owner($postid) {
        update_option('_cmb_consigner_owner', $postid, true);
    }

    public static function add_book($consignerID, $bookID){
        consigner_properties::add_book_to_consigner($consignerID, $bookID);
        book_properties::add_consigner_to_book($bookID, $consignerID);
    }

    public static function remove_book($consigner_id, $book_id) {
        self::remove_book_from_consigner($consigner_id, $book_id);
        book_properties::remove_consigner_from_book($book_id, $consigner_id);
    }

    public static function remove_book_from_consigner($consigner, $book) {
        if (consigner_properties::get_consigner_id($consigner) != 0) {
            $books = consigner_properties::get_books($consigner);
            if (($key = array_search($book, $books)) !== false) {
                unset($books[$key]);
            }
            $books = array_values($books);
            consigner_properties::set_books($consigner, $books);
        }
    }

    public static function remove_consigner($id) {
        $books = self::get_books($id);
        foreach ($books as $book) {
            book_properties::remove_consigner_from_book($book, $id);
        }
        self::set_books($id, array());
        wp_delete_post($id);
    }
}

class consigner_addition {

    public static $name = 'add_name';
    public static $date = 'add_date';
    public static $id = 'add_id';
    public static $address = 'add_address';
    public static $email = 'add_email';
    public static $paypal = 'add_paypal';
    public static $info = 'add_additional_info';
    public static $phone = 'add_phone';

    public static function InputName() {
        return new Input(id(self::$name).name(self::$name).type('text'));
    }

    public static function GetName() {
        return $_REQUEST[self::$name];
    }

    public static function InputDate() {
        return new Input(id(self::$date).name(self::$date).type('date'));
    }

    public static function GetDate() {
        return $_REQUEST[self::$date];
    }

    public static function InputID() {
        return new Input(id(self::$id).name(self::$id).type('text'));
    }

    public static function GetID() {
        return $_REQUEST[self::$id];
    }

    public static function InputEmail() {
        return new Input(id(self::$email).name(self::$email).type('text'));
    }

    public static function GetEmail() {
        return $_REQUEST[self::$email];
    }

    public static function InputAddress() {
        return new Input(id(self::$address).name(self::$address).type('text'));
    }

    public static function GetAddress() {
        return $_REQUEST[self::$address];
    }

    public static function InputPaypal() {
        return new Input(id(self::$paypal).name(self::$paypal).type('text'));
    }

    public static function GetPaypal() {
        return $_REQUEST[self::$paypal];
    }

    public static function InputPhone() {
        return new Input(id(self::$phone).name(self::$phone).type('text'));
    }

    public static function GetPhone() {
        return $_REQUEST[self::$phone];
    }

    public static function InputAdditionalInfo() {
        return new TextArea(id(self::$info).name(self::$info).type('text'));
    }

    public static function GetAdditionalInfo() {
        return $_POST[self::$info];
    }

    public static function add_consigner() {
        //Get the input data from form
        $consignertitle = self::GetName();
        $consignerdate = self::GetDate();
        if (!$consignertitle)
            return;
        if (!$consignerdate) {
            date_default_timezone_set('America/Chicago');
            $consignerdate = date('Y-m-d');
        }

        $phone = self::GetPhone();
        $address = self::GetAddress();
        $paypal = self::GetPaypal();
        $info = self::GetAdditionalInfo();
        $email = self::GetEmail();

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
            consigner_properties::update_owner($postid);
        }
        $newConsignerID = $lastConsignerID + 1;
        update_option('_cmb_consigner_lastID', $newConsignerID);

        consigner_properties::set_consigner_id($postid, $newConsignerID);
        consigner_properties::set_consigner_date($postid, $consignerdate);
        consigner_properties::set_consigner_address($postid, $address);
        consigner_properties::set_consigner_phone($postid, $phone);
        consigner_properties::set_consigner_paypal($postid, $paypal);
        consigner_properties::set_consigner_info($postid, $info);
        consigner_properties::set_consigner_email($postid, $email);
        return $postid;
    }
}

class consigner_editing {

    public static $name = 'edit_name';
    public static $date = 'edit_date';
    public static $id = 'edit_id';

    public static $phone = 'edit_phone';
    public static $email = 'edit_email';
    public static $address = 'edit_address';
    public static $info = 'edit_info';
    public static $paypal = 'edit_paypal';

    public static function GetName() {
        return $_REQUEST[self::$name];
    }

    public static function GetDate() {
        return $_REQUEST[self::$date];
    }

    public static function GetID() {
        return $_REQUEST[self::$id];
    }

    public static function GetPhone() {
        return $_REQUEST[self::$phone];
    }

    public static function GetPaypal() {
        return $_REQUEST[self::$paypal];
    }

    public static function GetAddress() {
        return $_REQUEST[self::$address];
    }

    public static function GetInfo() {
        return $_POST[self::$info];
    }

    public static function GetEmail() {
        return $_REQUEST[self::$email];
    }

    public static function UpdateConsigner($id) {
        if (self::GetName()) consigner_properties::set_consigner_name($id, self::GetName());
        if (self::GetID()) consigner_properties::set_consigner_id($id, self::GetID());
        if (self::GetDate()) consigner_properties::set_consigner_date($id, self::GetDate());
        if (self::GetEmail()) consigner_properties::set_consigner_email($id, self::GetEmail());
        if (self::GetPhone()) consigner_properties::set_consigner_phone($id, self::GetPhone());
        if (self::GetAddress()) consigner_properties::set_consigner_address($id, self::GetAddress());
        if (self::GetInfo()) consigner_properties::set_consigner_info($id, self::GetInfo());
        if (self::GetPaypal()) consigner_properties::set_consigner_paypal($id, self::GetPaypal());
    }
}

class consigner_request {
    public static $name = 'query_consigner_name';
    public static $id = 'query_consigner_id';
    public static $datefrom = 'query_consigner_date_from';
    public static $dateto = 'query_consigner_date_to';

    public static $search_books = 'query_book_search';
    public static $back_to_book = 'back_to_book';

    public static function InputName() {
        return new Input(id(consigner_request::$name).name(consigner_request::$name).type('text'));
    }

    public static function GetName() {
        return $_REQUEST[consigner_request::$name];
    }

    public static function InputID() {
        return new Input(id(consigner_request::$id).name(consigner_request::$id).type('text'));
    }

    public static function GetID() {
        return $_REQUEST[consigner_request::$id];
    }

    public static function InputDateFrom() {
        date_default_timezone_set('America/Chicago');
        return new Input(id(consigner_request::$datefrom).name(consigner_request::$datefrom).type('date').value(date("Y-m-d", mktime(0, 0, 0, 1, 1, 2014))));
    }

    public static function GetDateFrom() {
        date_default_timezone_set('America/Chicago');
        return $_REQUEST[consigner_request::$datefrom];
    }

    public static function InputDateTo() {
        date_default_timezone_set('America/Chicago');
        return new Input(id(consigner_request::$dateto).name(consigner_request::$dateto).type('date').value(date('Y-m-d')));
    }

    public static function GetDateTo() {
        date_default_timezone_set('America/Chicago');
        return $_REQUEST[consigner_request::$dateto];
    }

    public static function GetBookSearch() {
        return $_REQUEST[self::$search_books];
    }

    public static function GetBackToBook() {
        return $_REQUEST[self::$back_to_book];
    }

    public static function Store() {
        $args = func_get_args();
        $form = '';
        if (count($args) > 0){
            $form = $args[0];
        }
        if (!empty($form)){
            $list = new RenderList(
                new Input(form($form).id(consigner_request::$datefrom).name(consigner_request::$datefrom).type('hidden').value(self::GetDateFrom())),
                new Input(form($form).id(consigner_request::$dateto).name(consigner_request::$dateto).type('hidden').value(self::GetDateTo())),
                new Input(form($form).id(consigner_request::$id).name(consigner_request::$id).type('hidden').value(self::GetID())),
                new Input(form($form).id(consigner_request::$name).name(consigner_request::$name).type('hidden').value(self::GetName())),
                new Input(form($form).id(consigner_request::$search_books).name(consigner_request::$search_books).type('hidden').value(self::GetBookSearch()))
            );
        }
        else {
            $list = new RenderList(
                new Input(id(consigner_request::$datefrom).name(consigner_request::$datefrom).type('hidden').value(self::GetDateFrom())),
                new Input(id(consigner_request::$dateto).name(consigner_request::$dateto).type('hidden').value(self::GetDateTo())),
                new Input(id(consigner_request::$id).name(consigner_request::$id).type('hidden').value(self::GetID())),
                new Input(id(consigner_request::$name).name(consigner_request::$name).type('hidden').value(self::GetName())),
                new Input(id(consigner_request::$search_books).name(consigner_request::$search_books).type('hidden').value(self::GetBookSearch()))
            );
        }
        return $list;
    }
}