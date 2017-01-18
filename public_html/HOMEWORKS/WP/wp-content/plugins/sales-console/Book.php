<?php

/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 1/15/2017
 * Time: 7:31 PM
 */
class Book {
    public static $name = 'book_title';
    public static $barcode = 'book_barcode';
    public static $cost = 'book_cost';
    public static $price = 'book_price';
    public static $msrp = 'book_msrp';
    public static $publisher = 'book_publisher';
    public static $isbn = 'book_isbn';
    public static $condition = 'book_condition';
    public static $available = 'book_availability';
    public static $image = 'book_image';

    static function init()
    {
        self::$props = array();

        $title = new Text('book_title', 'Name', 'title');

        $barcode = new Text('book_barcode', 'Barcode', '_cmb_resource_barcode');
        $barcode->exact = 1;
        $barcode->add_param = false;
        $barcode->display_in_add = false;
        $barcode->edit_param = false;

        $cost = new Text('book_cost', 'Cost', '_cmb_resource_cost');
        $price = new Text('book_price', 'Price', '_cmb_resource_price');
        $msrp = new Text('book_msrp', 'MSRP', '_cmb_resource_MSRP');
        $publisher = new Text('book_publisher', 'Publisher', '_cmb_resource_publisher');
        $isbn = new Text('book_isbn', 'ISBN', '_cmb_resource_isbn');

        $condition = new Radio('book_condition', 'Condition', '_cmb_resource_condition');
        $condition->options = array('Used', 'New');

        $availability = new Radio('book_availability', 'Available', '_cmb_resource_available');
        $availability->options = array('Active', 'Inactive');

        $image = new Image('book_image', 'Image', 'image');
        $image->options = array('Yes', 'No');
        $image->add_param = false;
        $image->display_in_add = false;
        $image->edit_param = false;
        $image->display_in_edit = false;

        self::$props = array(
            self::$name => $title,
            self::$barcode => $barcode,
            self::$cost => $cost,
            self::$price => $price,
            self::$msrp => $msrp,
            self::$publisher => $publisher,
            self::$isbn => $isbn,
            self::$condition => $condition,
            self::$available => $availability,
            self::$image => $image
        );

        $remove = new BookButton('remove_book');
        $remove->action = action_types::$remove_book_from_consigner;
        $remove->button = 'Remove';
        self::$consigner_book_props = array(
            self::$name => $title,
            self::$barcode => $barcode,
            self::$cost => $cost,
            self::$isbn => $isbn,
            'remove_book' => $remove
        );
        self::$consigner_sold_book_props = array(
            self::$name => $title,
            self::$barcode => $barcode,
            self::$cost => $cost,
            self::$isbn => $isbn,
        );

        $add = new BookButton('add_book');
        $add->action = action_types::$add_book_to_consigner;
        $add->button = 'Add';
        self::$consigner_book_search_props = array(
            self::$name => $title,
            self::$barcode => $barcode,
            self::$cost => $cost,
            self::$isbn => $isbn,
            'add_book' => $add
        );

        //$online = new Checkbox('book_online', 'Online', '_cmb_resource_online');
    }

    public static $source = 'Book';
    public static $post_type = 'bookstore';
    public static $props ;
    public static $consigner_book_props;
    public static $consigner_sold_book_props;
    public static $consigner_book_search_props;

    public static function GenerateSearchAndAdd() {
        return new TableArr(border(0).cellpadding(0).cellspacing(0).width(100),
            new Row(
                new Column(width(52),
                    GenerateSearchBox(self::$props, self::$source, 'Search:', 'Search Books')
                ),
                new Column(width(2)),
                new Column(width(36),
                    GenerateAddBox(self::$props, self::$source, 'Add:', 'Add Book')
                ),
                new Column(width(10))
            )
        );
    }

    public static function has_book_image($id) {
        return has_post_thumbnail($id);
    }

    public static function get_consigners($book) {
        $consigners = get_post_meta($book, '_cmb_resource_consigners', true);
        if (empty($consigners)) {
            $consigners = array();
            Book::set_consigners($book, $consigners);
        }
        return $consigners;
    }

    public static function get_consigner_count($book) {
        $consigners = Book::get_consigners($book);
        if ($consigners && !empty($consigners))
            return count($consigners);
        else return 0;
    }

    public static function set_consigners($book, $consigners) {
        update_post_meta($book, '_cmb_resource_consigners', $consigners);
    }

    public static function add_book($book_id, $consigner_id) {
        Book::add_consigner_to_book($book_id, $consigner_id);
        Consigner::add_book_to_consigner($consigner_id, $book_id);
    }

    public static function add_consigner_to_book($book, $consigner) {
        $consigners = Book::get_consigners($book);
        $consigners[] = $consigner;
        Book::set_consigners($book, $consigners);
    }

    public static function sell_book($book_id) {
        $consigners = self::get_consigners($book_id);
        if (count($consigners) > 0) {
            $soldconsigner = array_shift($consigners);
        }
        else {
            $soldconsigner = get_consigner_owner_id(); //if quantity is wrong.. assume we sold the book from owner
        }
        if ($soldconsigner != get_consigner_owner_id()) {
            Consigner::remove_book_from_consigner($soldconsigner, $book_id);
            Consigner::consigner_add_sold_book($soldconsigner, $book_id);
        }
        self::set_consigners($book_id, $consigners);

    }

    public static function remove_book($book_id, $consigner_id) {
        self::remove_consigner_from_book($book_id, $consigner_id);
        Consigner::remove_book_from_consigner($consigner_id, $book_id);
    }

    public static function remove_consigner_from_book($book, $consigner) {
        $consigners = Book::get_consigners($book);
        if (($key = array_search($consigner, $consigners)) !== false) {
            unset($consigners[$key]);
        }
        $consigners = array_values($consigners);
        Book::set_consigners($book, $consigners);
    }
}
Book::init();