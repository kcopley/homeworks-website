<?php

/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 1/15/2017
 * Time: 7:31 PM
 */
class Book {
    public static $var_name = 0;
    public static $var_type = 1;
    public static $var_db = 2;
    public static $var_search = 3;
    public static $var_edit = 4;
    public static $var_add = 5;
    public static $var_display = 6;
    public static $var_format = 7;
    public static $var_radio = 8;

    public static $search_prefix = 'search_';
    public static $edit_prefix = 'edit_';
    public static $add_prefix = 'add_';

    public static $props = array(
        //variable,                 data type, database reference, search, edit, add, display, formatted-variable, radio-categories
        array('book_title',     'text', 'title',                true, true, true, true, 'Name'),
        array('book_barcode',   'text', '_cmb_resource_barcode',  true, false, true, true, 'Barcode'),
        array('book_cost',     'text', '_cmb_resource_cost',  true, true, true, true, 'Cost'),
        array('book_price',     'text', '_cmb_resource_price',  true, true, true, true, 'Price'),
        array('book_msrp',       'text', '_cmb_resource_MSRP',  true, true, true, true, 'MSRP'),
        array('book_publisher',    'text', '_cmb_resource_publisher',  true, true, true, true, 'Publisher'),
        array('book_isbn',  'text', '_cmb_resource_isbn',  true, true, true, true, 'ISBN'),
        array('book_condition',   'radio', '_cmb_resource_condition',  true, true, true, true, 'Condition', array('All', 'Used', 'New')),
        array('book_availability',   'radio', '_cmb_resource_available',  true, false, false, true, 'Available', array('All', 'Active', 'Inactive')),
        array('book_online',     'checkbox', '_cmb_resource_online',  false, true, true, true, 'Online'),
        array('book_image',     'radio', 'image',  true, true, false, true, 'Image', array('All', 'Yes', 'No')),
    );

    public static function GenerateInput($prop, $prefix, $postfix) {
        return new Input(id($prefix.$prop[Book::$var_name].$postfix).name($prefix.$prop[Book::$var_name].$postfix).type($prop[Book::$var_type]));
    }

    public static function GenerateInputRadio($prop, $prefix, $postfix, $filter) {
        //radio options
        $options = $prop[Book::$var_radio];
        $list = new RenderList();
        if ($prop[Book::$var_format] != 'Available')
            $list->add_object(new TextRender($prop[Book::$var_format].': '));
        foreach ($options as $option) {
            if ($option == $filter) continue;
            $list->add_object(new TextRender(
                $option.': '
            ));
            $list->add_object(
                new Input(id($prefix.$prop[Book::$var_name].$postfix).name($prefix.$prop[Book::$var_name].$postfix).type($prop[Book::$var_type]).value($option))
            );
        }
        return $list;
    }

    public static function GenerateSearchAndAdd() {
        return new TableArr(border(0).cellpadding(0).cellspacing(0).width(100),
            new Row(
                new Column(width(45),
                    Book::GenerateSearchBox()
                ),
                new Column(width(45),
                    Book::GenerateAddBox()
                ),
                new Column(width(10))
            )
        );
    }

    public static function GenerateSearchBox() {
        $leftwidth = 45;
        $rightwidth = 55;

        $outsidetable = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100).
            style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'));
        $outsidetable->add_object(new Row(
            new Column(
                new Strong(new TextRender('Book Search'))
            )
        ));
        $outsiderow = new Row();
        $outsidetable->add_object($outsiderow);
        $form = new Form(action('').method('post').name('book_search'));
        $form->add_object($outsidetable);

        $counter = 0;
        $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100));
        $outsiderow->add_object(new Column(valign('top').width(30),
            $table
        ));
        foreach (Book::$props as $prop) {
            if ($prop[Book::$var_search]) {
                if ($counter > 3) {
                    $counter = 0;
                    $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100));
                    $outsiderow->add_object(
                        new Column(valign('top').width(30),
                            $table
                        )
                    );
                }
                if ($prop[Book::$var_type] == 'radio') {
                    $table->add_object(
                        new Row(
                            new Column(colspan(2).style('padding-left: 5px;').align('center'),
                                Book::GenerateInputRadio($prop, Book::$search_prefix, '', '')
                            )
                        )
                    );
                }
                else {
                    $table->add_object(
                        new Row(
                            new Column(align('right').width($leftwidth), new Label(new TextRender($prop[Book::$var_format].':'))),
                            new Column(width($rightwidth).style('padding-left: 5px;'),
                                Book::GenerateInput($prop, Book::$search_prefix, '')
                            )
                        )
                    );
                }
                $counter = $counter + 1;
            }
        }

        $table->add_object(
            new Row(
                new Column(colspan(2).align('center').style('padding-left: 5px; padding-top: 5px;'),
                    page_action::InputAction(action_types::$search_books),
                    button('Search Books')
                )
            )
        );
        return $form;
    }

    public static function GenerateAddBox() {
        $leftwidth = 45;
        $rightwidth = 55;

        $outsidetable = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100).
            style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'));
        $outsidetable->add_object(new Row(
            new Column(
                new Strong(new TextRender('Add Book'))
            )
        ));
        $outsiderow = new Row();
        $outsidetable->add_object($outsiderow);
        $form = new Form(action('').method('post').name('book_search'));
        $form->add_object($outsidetable);

        $counter = 0;
        $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100));
        $outsiderow->add_object(new Column(valign('top').width(30),
            $table
        ));
        foreach (Book::$props as $prop) {
            if ($prop[Book::$var_add]) {
                if ($counter > 3) {
                    $counter = 0;
                    $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100));
                    $outsiderow->add_object(
                        new Column(valign('top').width(30),
                            $table
                        )
                    );
                }
                if ($prop[Book::$var_type] == 'radio') {
                    $table->add_object(
                        new Row(
                            new Column(colspan(2).style('padding-left: 5px;').align('center'),
                                Book::GenerateInputRadio($prop, Book::$add_prefix, '', 'All')
                            )
                        )
                    );
                }
                else {
                    $table->add_object(
                        new Row(
                            new Column(align('right').width($leftwidth), new Label(new TextRender($prop[Book::$var_format].':'))),
                            new Column(width($rightwidth).style('padding-left: 5px;'),
                                Book::GenerateInput($prop, Book::$add_prefix, '')
                            )
                        )
                    );
                }
                $counter = $counter + 1;
            }
        }

        $table->add_object(
            new Row(
                new Column(colspan(2).align('center').style('padding-left: 5px; padding-top: 5px;'),
                    page_action::InputAction(action_types::$add_book),
                    button('Search Books')
                )
            )
        );
        return $form;
    }

    public static function GenerateQuery($num_posts, $offset) {
        $args = array(
            'posts_per_page' => $num_posts,
            'order' => 'ASC',
            'orderby' => 'date',
            'post_type' => 'bookstore',
            'offset' => $offset
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
                'value' => book_request::GetPublisher(),
                'compare' => 'LIKE'
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
        if (book_request::GetPermAvailability() && book_request::GetPermAvailability() != 'All') {
            $meta_query_array[] = array(
                'key' => '_cmb_resource_perm_available',
                'value' => book_request::GetPermAvailability()
            );
        };

        $args['meta_query'] = $meta_query_array;
        return new WP_Query($args);
    }

    public static function Search()
    {
        $displays = func_get_args();
        $props = array();

        $consignerID = -1;
        if (array_key_exists(book_properties::$consigner_id, $display)) {
            $consignerID = $display[book_properties::$consigner_id];
        }

        $table =
            new TableArr(id('formtable').width(100).border(0).cellspacing(0).cellpadding(0).style('margin: 10px 0 5px;'));

        if (array_key_exists(book_properties::$title, $display)){
            $table->add_object(
                new Column(width(30).style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Title'))
            );
        }
        if (array_key_exists(book_properties::$barcode, $display)){
            $table->add_object(
                new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Barcode'))
            );
        }
        if (array_key_exists(book_properties::$publisher, $display)){
            $table->add_object(
                new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Publisher'))
            );
        }
        if (array_key_exists(book_properties::$isbn, $display)){
            $table->add_object(
                new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('ISBN'))
            );
        }
        if (array_key_exists(book_properties::$cost, $display)){
            $table->add_object(
                new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Cost'))
            );
        }
        if (array_key_exists(book_properties::$MSRP, $display)){
            $table->add_object(
                new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('MSRP'))
            );
        }
        if (array_key_exists(book_properties::$price, $display)){
            $table->add_object(
                new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Price'))
            );
        }
        if (array_key_exists(book_properties::$quantity, $display)){
            $table->add_object(
                new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px').align('center'), new TextRender('Quantity'))
            );
        }
        if (array_key_exists(book_properties::$condition, $display)){
            $table->add_object(
                new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px').align('center'), new TextRender('Condition'))
            );
        }
        if (array_key_exists(book_properties::$availability, $display)){
            $table->add_object(
                new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px').align('center'), new TextRender('Available'))
            );
        }
        if (array_key_exists(book_properties::$perm_availability, $display)){
            $table->add_object(
                new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px').align('center'), new TextRender('Online Availability'))
            );
        }
        if (array_key_exists(book_properties::$hasimage, $display)){
            $table->add_object(
                new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px').width(5).align('center'),
                    new TextRender('Image?'))
            );
        }
        if ($consignerID != -1){
            $table->add_object(
                new Column(width(5).align('center').style('padding-bottom: 8px; font-weight: bold; font-size: 14px'),
                    new TextRender('Add'))
            );
        }

        $table->add_object(
            new Row(style('border: none; padding-bottom: 8px; height: 1px;'),
                new Column(colspan(count($display).style('padding-bottom: 8px;')),
                    new HR(style('margin: 0px;')))));

        $display_post_num = book_request::GetBooksPerPage();
        $current_page = book_request::GetCurrentPage();
        if (!$current_page) $current_page = 1;

        $offset = ($current_page - 1) * $display_post_num;
        if ($offset < 0) $offset = 0;

        $query = QueryBook($display_post_num, $offset);

        while ($query->have_posts()):
            $query->the_post();
            global $post;
            $product_id = $post->ID;
            $table->add_object(
                book_display($display, $product_id, $consignerID));
            $table->add_object(
                new Row(style('border: none; padding: 0px; height: 1px;'),
                    new Column(colspan(count($display).style('padding-bottom: 0px;')),
                        new HR(style('margin: 0px;')))
                ));
        endwhile;

        $renderlist = new RenderList();
        $renderlist->add_object($table);

        $pageTable = new TableArr(id('formtable').width(100).border(0).cellspacing(0).cellpadding(0).style('padding-top: 5px; margin: 5px 0 5px;'));
        if ($query->found_posts > $display_post_num) {
            $row = new Row();
            $row->add_object(new Column(width(82)));
            $row->add_object(
                new Column(align('center').width(6).style('padding: 3px;'),
                    new H4(new TextRender('Page: ' . $current_page))
                )
            );
            if ($current_page > 1) {
                $row->add_object(
                    new Column(align('center').width(6).style('padding: 3px;'),
                        new Form(
                            book_request::Store(),
                            page_action::InputAction(action_types::$search_books),
                            book_request::InputCurrentPage($current_page - 1),
                            GetAdditionalRenders($additionalRenders),
                            button('Previous')
                        )
                    )
                );
            }
            else {
                $row->add_object(new Column(align('center').width(6).style('padding: 3px;')));
            }
            if ($current_page * $display_post_num < $query->found_posts) {
                $row->add_object(
                    new Column(align('center').width(6).style('padding: 3px;'),
                        new Form(
                            book_request::Store(),
                            page_action::InputAction(action_types::$search_books),
                            book_request::InputCurrentPage($current_page + 1),
                            GetAdditionalRenders($additionalRenders),
                            button('Next')
                        )
                    )
                );
            }
            else {
                $row->add_object(new Column(align('center').width(6).style('padding: 3px;')));
            }
            $pageTable->add_object(
                $row
            );
        }
        $renderlist->add_object($pageTable);

        return $renderlist;
    }

    public static function has_book_image($id) {
        return has_post_thumbnail($id);
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
        if (book_properties::has_book_image($id)){
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

    public static function add_book($book_id, $consigner_id) {
        book_properties::add_consigner_to_book($book_id, $consigner_id);
        consigner_properties::add_book_to_consigner($consigner_id, $book_id);
    }

    public static function add_consigner_to_book($book, $consigner) {
        $consigners = book_properties::get_consigners($book);
        $consigners[] = $consigner;
        book_properties::set_consigners($book, $consigners);
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
            consigner_properties::remove_book_from_consigner($soldconsigner, $book_id);
            consigner_properties::consigner_add_sold_book($soldconsigner, $book_id);
        }
        self::set_consigners($book_id, $consigners);

    }

    public static function remove_book($book_id, $consigner_id) {
        self::remove_consigner_from_book($book_id, $consigner_id);
        consigner_properties::remove_book_from_consigner($consigner_id, $book_id);
    }

    public static function remove_consigner_from_book($book, $consigner) {
        $consigners = book_properties::get_consigners($book);
        if (($key = array_search($consigner, $consigners)) !== false) {
            unset($consigners[$key]);
        }
        $consigners = array_values($consigners);
        book_properties::set_consigners($book, $consigners);
    }
}