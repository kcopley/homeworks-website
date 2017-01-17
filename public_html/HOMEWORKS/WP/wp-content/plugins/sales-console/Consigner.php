<?php

/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 1/15/2017
 * Time: 7:32 PM
 */
class Consigner {
    public static $source = 'Consigner';
    public static $post_type = 'consigners';
    public static $props;

    public static function Selected() {
        return selection::GetConsigner();
    }

    static function init()
    {
        self::$props = array();

        self::$props[] = new Text('consigner_name', 'Name', 'title');
        self::$props[] = new Date('consigner_date', 'Date', '_cmb_consigner_date');

        $id = new Text('consigner_id', 'ID', '_cmb_consigner_id');
        $id->edit_param = false;
        $id->add_param = false;
        $id->display_in_add = false;
        self::$props[] = $id;

        self::$props[] = new Text('consigner_email', 'Email', '_cmb_consigner_email');
        self::$props[] = new Text('consigner_address', 'Address', '_cmb_consigner_address');
        self::$props[] = new Text('consigner_paypal', 'PayPal', '_cmb_consigner_paypal');
        self::$props[] = new Text('consigner_phone', 'Phone', '_cmb_consigner_phone');

        $info = new TextBox('consigner_info', 'Info', '_cmb_consigner_info');
        $info->search_param = false;
        $info->display_in_search = false;
        self::$props[] = $info;
    }

    public static function StoreQuery() {
    }

    public static function GenerateConsignerSearch()
    {
        return new TableArr(border(0).cellpadding(0).cellspacing(0).width(75),
            new Row(
                new Column(align('left').valign('top').style('text-align: left;'),
                    GenerateSearchBox(Consigner::$props, 'Consigner', 'Search:', 'Search Consigners')),
                new Column(width(4)),
                new Column(align('left').valign('top').style('text-align: left;'),
                    GenerateAddBox(Consigner::$props, 'Consigner', 'Add:', 'Add Consigner')),
                new Column(width(70))
            )
        );
    }

    public static function SelectConsigner($id) {
        return new TableArr(width(100).border(0).cellspacing(0).cellpadding(0),
            new Row(
                new Column(width(54).valign('top'),
                    EditDisplay(Consigner::$props, $id, 4, Consigner::$source)
                ),
                new Column(width(1).style('border-right-style: solid; border-width: 1px; border-color: #D0D0D0;')),
                new Column(width(1)),
                new Column(valign('top'),
                    self::GenerateBookSearch($id)
                )
            )
        );
    }

    public static function GenerateBookSearch($id) {
        $list = new RenderList();
        if ($_SESSION[action_types::$consigner_books] == true) {
            $list->add_object(
                GenerateSearch(Book::$props, Book::$source, Book::$post_type)
            );
        }
        return new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100),
            new Row(
                new Column(
                    GenerateSearchBox(Book::$props, Book::$source, 'Search:', 'Search Books')
                )
            ),
            new Row(
                new Column(
                    $list
                )
            )
        );
    }

    public static function ConsignerBookSearch() {
        return new TableArr(id('formtable').width(100).border(0).cellspacing(0).cellpadding(0),
            new Row(
                new Column(width(49).valign('top'),
                    new TableArr(id('formtable').border(0).cellspacing(0).cellpadding(0).style('margin: 10px 0 10px;'),
                        new Row(
                            new Column(new H4(style('margin: 10px 0px 0px 0px;'), new TextRender('Books'))),
                            new Column(new H4(style('margin: 10px 0px 0px 0px;'), new TextRender('ISBN'))),
                            new Column(new H4(style('margin: 10px 0px 0px 0px;'), new TextRender('Cost'))),
                            new Column(new H4(style('margin: 10px 0px 0px 0px;'), new TextRender('Barcode'))),
                            new Column(width(10)),
                            new Column(width(6))
                        ),
                        new Row(new Column(colspan(6), new HR())),
                        display_consigner_books($id),
                        new Row(
                            new Column(new H4(new TextRender('Books Sold'))),
                            new Column(colspan(3), new HR()),
                            new Column(width(10), new H4(align('center'), new TextRender('Paid?'))),
                            new Column(colspan(1), new HR())
                        ),
                        display_sold_consigner_books($id)
                    )
                )
            )
        )
            ;

    }

    public function get_books($consigner) {
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

Consigner::init();