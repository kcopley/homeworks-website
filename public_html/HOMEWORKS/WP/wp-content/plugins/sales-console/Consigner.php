<?php

/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 1/15/2017
 * Time: 7:32 PM
 */
class Consigner {
    public static function Selected() {
        return selection::GetConsigner();
    }

    public static $var_name = 0;
    public static $var_type = 1;
    public static $var_db = 2;
    public static $var_search = 3;
    public static $var_edit = 4;
    public static $var_add = 5;
    public static $var_format = 6;

    public static $search_prefix = 'search_';
    public static $edit_prefix = 'edit_';
    public static $add_prefix = 'add_';

    public static $props = array(
        //variable,                 data type, database reference, search, edit, add, formatted-variable
        array('consigner_name',     'text', 'title',                true, true, true, 'Name'),
        array('consigner_date',     'date', '_cmb_consigner_date',  true, true, true, 'Date'),
        array('consigner_id',       'text', '_cmb_consigner_id',  true, true, false, 'ID'),
        array('consigner_email',    'text', '_cmb_consigner_email',  true, true, true, 'Email'),
        array('consigner_address',  'text', '_cmb_consigner_address',  true, true, true, 'Address'),
        array('consigner_paypal',   'text', '_cmb_consigner_paypal',  true, true, true, 'PayPal'),
        array('consigner_phone',   'text', '_cmb_consigner_phone',  true, true, true, 'Phone'),
        array('consigner_info',     'textarea', '_cmb_consigner_info',  false, true, true, 'Info'),
    );

    public static function StoreQuery() {
    }

    public static function GenerateQuery() {
        $args = array(
            'numberposts' => -1,
            'posts_per_page' => -1,
            'order' => 'ASC',
            'orderby' => 'date',
            'post_type' => 'consigners'
        );

        $meta_query_array = array('relation' => 'AND');

        foreach (Consigner::$props as $prop) {
            if ($prop[Consigner::$var_search]) {
                if ($prop[Consigner::$var_db] == 'title') {
                    if (self::GetSearchValue($prop))
                        $args['s'] = self::GetSearchValue($prop);
                }
                else if ($prop[Consigner::$var_type] == 'date') {
                    date_default_timezone_set('America/Chicago');
                    $datefrom = $_REQUEST[Consigner::$search_prefix.Consigner::$props[Consigner::$var_name].'_from'];
                    if (!$datefrom) $datefrom = date("Y-m-d", mktime(0, 0, 0, 1, 1, 2014));
                    $dateto = $_REQUEST[Consigner::$search_prefix.Consigner::$props[Consigner::$var_name].'_to'];
                    if (!$dateto) $dateto = date('Y-m-d');

                    $meta_query_array[] =
                        array(
                            'key' => $prop[Consigner::$var_db],
                            'value' => array($datefrom, $dateto),
                            'compare' => 'BETWEEN',
                            'type' => 'DATE'
                        );
                }
                else {
                    if ($_REQUEST[Consigner::$search_prefix.$prop[Consigner::$var_name]]) {
                        $meta_query_array[] = array(
                            'key' => $prop[Consigner::$var_db],
                            'value' => self::GetSearchValue($prop),
                            'compare' => 'LIKE'
                        );
                    }
                }
            }
        }
        $args['meta_query'] = $meta_query_array;

        return new WP_Query($args);
    }

    public static function GenerateSearchBox() {
        $leftwidth = 45;
        $rightwidth = 55;

        $outsidetable = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100).
            style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'));
        $outsidetable->add_object(new Row(
            new Column(new Strong(new TextRender('Search:')))
        ));
        $outsiderow = new Row();
        $outsidetable->add_object($outsiderow);
        $form = new Form(action('').method('post').name('consigner_search'));
        $form->add_object($outsidetable);

        $counter = 0;
        $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100));
        $outsiderow->add_object(new Column(valign('top'),
            $table
        ));
        foreach (Consigner::$props as $prop) {
            if ($prop[Consigner::$var_search]) {
                if ($counter > 3) {
                    $counter = 0;
                    $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100));
                    $outsiderow->add_object(
                        new Column(valign('top'),
                            $table
                        )
                    );
                }
                if ($prop[Consigner::$var_type] == 'date') {
                    $table->add_object(
                        new Row(
                            new Column(align('right').width($leftwidth), new Label(new TextRender($prop[Consigner::$var_format].' From:'))),
                            new Column(width($rightwidth / 2).style('padding-left: 5px;'),
                                Consigner::GenerateInput($prop, Consigner::$search_prefix, '_from')
                            )
                        )
                    );
                    $table->add_object(
                        new Row(
                            new Column(align('right').width($leftwidth), new Label(new TextRender($prop[Consigner::$var_format].' To:'))),
                            new Column(width($rightwidth / 2).style('padding-left: 5px;'),
                                Consigner::GenerateInput($prop, Consigner::$search_prefix, '_to')
                            )
                        )
                    );
                    $counter = $counter + 2;
                }
                else {
                    $table->add_object(
                        new Row(
                            new Column(align('right').width($leftwidth), new Label(new TextRender($prop[Consigner::$var_format].':'))),
                            new Column(width($rightwidth).style('padding-left: 5px;'),
                                Consigner::GenerateInput($prop, Consigner::$search_prefix, '')
                            )
                        )
                    );
                    $counter = $counter + 1;
                }
            }
        }

        $table->add_object(
            new Row(
                new Column(),
                new Column(width($rightwidth).align('right').style('padding-left: 5px; padding-top: 5px;'),
                    page_action::InputAction(action_types::$search_consigner),
                    button('Search Consigners')
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
            new Column(new Strong(new TextRender('Add:')))
        ));
        $outsiderow = new Row();
        $outsidetable->add_object($outsiderow);
        $form = new Form(action('').method('post').name('consigner_search'));
        $form->add_object($outsidetable);

        //inside
        $counter = 0;
        $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100));
        $outsiderow->add_object(new Column(valign('top'),
            $table
        ));
        foreach (Consigner::$props as $prop) {
            if ($prop[Consigner::$var_add]) {
                if ($counter > 3) {
                    $counter = 0;
                    $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100));
                    $outsiderow->add_object(new Column(valign('top'),
                        $table
                    ));
                }
                $table->add_object(
                    new Row(
                        new Column(align('right').width($leftwidth), new Label(new TextRender($prop[Consigner::$var_format].':'))),
                        new Column(width($rightwidth).style('padding-left: 5px;'),
                            Consigner::GenerateInput($prop, Consigner::$add_prefix, '')
                        )
                    )
                );
                $counter = $counter + 1;
            }
        }

        $table->add_object(
            new Row(
                new Column(),
                new Column(width($rightwidth).align('right').style('padding-left: 5px; padding-top: 5px;'),
                    page_action::InputAction(action_types::$add_consigner),
                    button('Add Consigner')
                )
            )
        );
        return $form;
    }

    public static function GenerateInput($prop, $prefix, $postfix) {
        if ($prop[Consigner::$var_type] == 'textarea') {
            return new TextArea(type('text').id($prefix.$prop[Consigner::$var_name].$postfix).name($prefix.$prop[Consigner::$var_name].$postfix));
        }
        else
            return new Input(id($prefix.$prop[Consigner::$var_name].$postfix).name($prefix.$prop[Consigner::$var_name].$postfix).type($prop[Consigner::$var_type]));
    }

    public static function GenerateInputWithValue($prop, $prefix, $postfix, $value, $form) {
        if ($prop[Consigner::$var_type] == 'textarea') {
            return new TextArea(form($form).style('width: 90%; padding: 4px 3px 4px').type('text').id($prefix.$prop[Consigner::$var_name].$postfix).name($prefix.$prop[Consigner::$var_name].$postfix), new TextRender($value));
        }
        else
            return new Input(form($form).style('width: 90%; padding: 4px 3px 4px').id($prefix.$prop[Consigner::$var_name].$postfix).name($prefix.$prop[Consigner::$var_name].$postfix).type($prop[Consigner::$var_type]).value($value));
    }

    public static function Search() {
        $table = new TableArr(id('formtable').width(100).border(0).cellspacing(0).cellpadding(0).style('margin: 10px 0 50px;'));

        $counter = 0;
        foreach (Consigner::$props as $prop) {
            if ($prop[Consigner::$var_search]) {
                if ($counter == 0) {
                    $table->add_object(
                        new Column(width(20).style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender($prop[Consigner::$var_format]))
                    );
                }
                else {
                    $table->add_object(
                        new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender($prop[Consigner::$var_format]))
                    );
                }
                $counter++;
            }
        }
        $table->add_object(
            new Column(width(20))
        );

        $table->add_object(
            new Row(style('border: none; padding-bottom: 8px; height: 1px;'),
                new Column(colspan($counter+1).style('padding-bottom: 8px;'),
                    new HR(style('margin: 0px;')))));

        $query = Consigner::GenerateQuery();
        while ($query->have_posts()):
            $query->the_post();
            global $post;
            $consigner = $post->ID;
            $table->add_object(Consigner::SearchDisplay($consigner));
            $table->add_object(
                new Row(style('border: none; padding: 0px; height: 1px;'),
                    new Column(colspan($counter.style('padding-bottom: 0px;')),
                        new HR(style('margin: 0px;')))
                ));
        endwhile;
        return $table;
    }

    public static function SearchDisplay($id) {
        $row = new Row();
        $counter = 0;
        foreach (Consigner::$props as $prop) {
            if ($prop[Consigner::$var_search]) {
                if ($prop[Consigner::$var_db] == 'title') {
                    $row->add_object(new Column(
                        new Form(method('POST').name('select_consigner'),
                            selection::InputConsigner($id),
                            page_action::InputAction(action_types::$select_consigner),
                            new Input(classType('button').type('submit').name('button').value(Consigner::GetValue($id, $prop[Consigner::$var_db])).style('background:none!important; border:none; 
                        padding:0!important; font-family:arial,sans-serif; color:#069; box-shadow: 0 0px 0 #ccc; cursor:pointer;'))
                        )
                    ));
                }
                else {
                    $row->add_object(
                        new Column(style('padding-bottom: 8px; font-size: 14px'), new TextRender(Consigner::GetValue($id, $prop[Consigner::$var_db])))
                    );
                }
                $counter++;
            }
        }
        $row->add_object(
            new Column(width(20))
        );
        return $row;
    }

    public static function SelectConsigner($id) {
        return new TableArr(width(100).border(0).cellspacing(0).cellpadding(0),
            new Row(
                new Column(width(54).valign('top'),
                    self::EditDisplay($id)
                ),
                new Column(width(1).style('border-right-style: solid; border-width: 1px; border-color: #D0D0D0;')),
                new Column(width(1)),
                new Column(valign('top'),
                    Consigner::GenerateBookSearch($id)
                )
            )
        );
    }

    public static function EditDisplay($id) {
        $leftwidth = 15;
        $rightwidth = 85;

        if ($_REQUEST['back_to_book']) {
            $formresults = new Form(method('post').name('backtoresults').id('backtoresults').action($_REQUEST['back_to_book']));
            $backtoresults = new RenderList(
                new Input(form('backtoresults').id(selection::$book).type('hidden').name(selection::$book).value(selection::GetBook())),
                new Input(form('backtoresults').id(page_action::$action).name(page_action::$action).type('hidden').value(action_types::$select_book)),
                new Input(form('backtoresults').classType('button-primary').type('submit').name('button').value('Back to Book'))
            );
        }
        else {
            $formresults = new Form(method('post') . name('backtoresults') . id('backtoresults'));
            $backtoresults = new RenderList(
                new Input(form('backtoresults') . id(page_action::$action) . name(page_action::$action) . type('hidden') . value(action_types::$search_consigner)),
                new Input(form('backtoresults') . id(selection::$consigner) . type('hidden') . name(selection::$consigner) . value(selection::GetConsigner())),
                new Input(form('backtoresults') . classType('button-primary') . type('submit') . name('button') . value('Back to Search Results'))
            );
        }
        $outsidetable = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100).
            style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'));
        $outsidetable->add_object(new Row(
            new Column(
                new Strong(new TextRender('Consigner:')))
        ));
        $outsidetable->add_object($formresults);
        $outsiderow = new Row();
        $form = new Form(method('post').name('edit_form').id('edit_form'));
        $outsidetable->add_object($form);
        $form->add_object($outsiderow);

        $colwidth = 30;
        $counter = 0;
        $table = new TableArr(border(0).cellpadding(4).cellspacing(4).id('formtable').width(100));
        $outsiderow->add_object(new Column(valign('top').width($colwidth),
            $table
        ));
        foreach (Consigner::$props as $prop) {
            if ($prop[Consigner::$var_edit]) {
                if ($counter > 3) {
                    $counter = 0;
                    $table = new TableArr(border(0).cellpadding(4).cellspacing(4).id('formtable').width(100));
                    $outsiderow->add_object(
                        new Column(valign('top').width($colwidth),
                            $table
                        )
                    );
                }
                $table->add_object(
                    new Row(
                        new Column(align('right').width($leftwidth), new Label(new TextRender($prop[Consigner::$var_format].':'))),
                        new Column(width($rightwidth).style('padding-left: 5px;'),
                            Consigner::GenerateInputWithValue($prop, Consigner::$edit_prefix, '', Consigner::GetValue($id, $prop[Consigner::$var_db]), 'edit_form')
                        )
                    )
                );
                $counter = $counter + 1;
            }
        }

        $form->add_object(
            new Row(
                new Column(align('left').style('padding-top: 5px;')
                ),
                new Column(align('center').style('padding-top: 5px;'),
                    new Div(align('left').style('display:inline-block; padding-right: 5px;'),
                        page_action::InputAction(action_types::$update_consigner),
                        selection::InputConsigner($id),
                        button('Update Consigner')
                    ),
                    new Div(align('right').style('display:inline-block; padding-left: 5px;'),
                        $backtoresults
                    )
                )
            )
        );
        return $outsidetable;
    }

    public static function GenerateBookSearch($id) {
        return new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100),
            new Row(
                new Column(
                    Book::GenerateSearchBox()
                )
            //display search setup here
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

    public static function GetValue($id, $db) {
        if ($db == 'title') return get_the_title($id);
        return get_post_meta($id, $db, true);
    }

    public static function GetSearchValue($prop) {
        return $_REQUEST[Consigner::$search_prefix.$prop[Consigner::$var_name]];
    }

    public static function SetValue($id, $db, $value) {
        if ($db == 'title') {
            $titleupdate = array(
                'ID'           => $id,
                'post_title'   => $value,
            );
            wp_update_post($titleupdate);
        }
        else {
            update_post_meta($id, $db, $value);
        }
    }

    public static function GetAddValue($prop) {
        return $_REQUEST[Consigner::$add_prefix.$prop[Consigner::$var_name]];
    }

    public static function GetEditValue($prop) {
        if ($prop[Consigner::$var_type] == 'date') {
            if (!$_REQUEST[Consigner::$edit_prefix.$prop[Consigner::$var_name]])
                return date('Y-m-d');
            else {
                return $_REQUEST[Consigner::$edit_prefix.$prop[Consigner::$var_name]];
            }
        }
        else {
            return $_REQUEST[Consigner::$edit_prefix.$prop[Consigner::$var_name]];
        }
    }

    public static function Add() {
        $postid = null;
        date_default_timezone_set('America/Chicago');
        foreach (Consigner::$props as $prop) {
            if ($prop[Consigner::$var_add]) {
                if ($prop[Consigner::$var_db] == 'title') {
                    $title = self::GetAddValue($prop);
                    $order = array(
                        'post_title' => $title,
                        'post_status' => 'publish',
                        'post_author' => 4,
                        'post_type' => 'consigners'
                    );
                    $postid = wp_insert_post($order);
                }
            }
        }
        if ($postid == null) return null;
        foreach (Consigner::$props as $prop) {
            if ($prop[Consigner::$var_add]) {
                if ($prop[Consigner::$var_db] != 'title') {
                    $val = self::GetAddValue($prop);
                    if ($prop[Consigner::$var_type] == 'date' && !$val) $val = date('Y-m-d');
                    self::SetValue($postid, $prop[Consigner::$var_db], $val);
                }
            }
        }

        //Set up ID
        $lastConsignerID = get_option('_cmb_consigner_lastID');
        if ($lastConsignerID == false){
            add_option('_cmb_consigner_lastID', -1);
            $lastConsignerID = get_option('_cmb_consigner_lastID');
            Consigner::update_owner($postid);
        }
        $newConsignerID = $lastConsignerID + 1;
        update_option('_cmb_consigner_lastID', $newConsignerID);

        self::SetValue($postid, '_cmb_consigner_id', $newConsignerID);
        return $postid;
    }

    public static function Update($id) {
        foreach (Consigner::$props as $prop) {
            if ($prop[Consigner::$var_edit] && self::GetEditValue($prop)) {
                Consigner::SetValue($id, $prop[Consigner::$var_db], self::GetEditValue($prop));
            }
        }
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