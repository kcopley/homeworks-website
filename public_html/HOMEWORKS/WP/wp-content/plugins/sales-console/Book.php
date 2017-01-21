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
    public static $departments = 'book_departments';
    public static $isbn = 'book_isbn';
    public static $condition = 'book_condition';
    public static $available = 'book_availability';
    public static $online = 'book_online';
    public static $image = 'book_image';
    public static $quantity = 'book_quantity';
    public static $bookaddbutton = 'book_add_button';
    public static $editaddbutton = 'book_edit_add_button';
    public static $consigner_id = 'consigner_id_input';
    public static $image_set = 'image_attachment_id';

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
        $cost->display_prefix = '$';
        $cost->exact = true;
        $price = new Text('book_price', 'Price', '_cmb_resource_price');
        $price->display_prefix = '$';
        $price->exact = true;
        $msrp = new Text('book_msrp', 'MSRP', '_cmb_resource_MSRP');
        $msrp->display_prefix = '$';
        $msrp->exact = true;
        $publisher = new Text('book_publisher', 'Publisher', '_cmb_resource_publisher');
        $isbn = new Text('book_isbn', 'ISBN', '_cmb_resource_isbn');

        $department = new Category('category_types', 'Department', 'category');
        //$department->search_param = false;

        $quantity = new Quantity('book_quantity', 'Quantity', 'quantity');
        $quantity->search_param = false;
        $quantity->edit_param = false;

        $consignerid = new ConsignerID('consigner_id', 'Consigner', '');
        $consignerid->search_param = false;
        $consignerid->display_in_search = false;
        $consignerid->edit_param = false;
        $consignerid->display_in_edit = false;

        $addbutton = new BookButton('book_add_button');
        $addbutton->action = action_types::$add_book_to_owner_search;
        $addbutton->button = 'Add';
        $addbutton->add_param = false;
        $addbutton->display_in_add = false;
        $addbutton->display_in_edit = false;
        $addbutton->edit_param = false;
        $addbutton->search_param = false;

        $condition = new Radio('book_condition', 'Condition', '_cmb_resource_condition');
        $condition->options = array(1 => 'Used', 2 => 'New');

        $availability = new Radio('book_availability', 'Available', '_cmb_resource_available');
        $availability->options = array(2 => 'Active', 1 => 'Inactive');
        $availability->add_param = false;

        $online = new Radio('book_online', 'Online', '_cmb_resource_online');
        $online->options = array(2 => 'Yes', 1 => 'No');

        $image = new Image('book_image', 'Image', 'image');
        $image->options = array(2 => 'Yes', 1 => 'No');
        $image->add_param = false;
        $image->display_in_add = false;
        $image->edit_param = false;
        $image->display_in_edit = true;

        self::$props = array(
            self::$name => $title,
            self::$barcode => $barcode,
            self::$cost => $cost,
            self::$price => $price,
            self::$msrp => $msrp,
            self::$departments => $department,
            self::$publisher => $publisher,
            self::$isbn => $isbn,
            self::$condition => $condition,
            self::$available => $availability,
            self::$online => $online,
            self::$image => $image,
            self::$quantity => $quantity,
            self::$bookaddbutton => $addbutton,
            self::$consigner_id => $consignerid,
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
                    GenerateSearchBox(self::$props, self::$source, 'Search Books:', 'Search Books')
                ),
                new Column(width(2)),
                new Column(width(44),
                    GenerateAddBox(self::$props, self::$source, 'Add Book:', 'Add Book')
                ),
                new Column(width(2))
            )
        );
    }

    public static function SelectBook($id) {
        $form = new Form(id('add_book_select_button').name('add_book_select_button'));
        $addbutton = new RenderList(
            page_action::InputActionForm(action_types::$add_book_to_owner_select, 'add_book_select_button'),
            selection::SetIDForm($id, Book::$source, 'add_book_select_button'),
            new TextRender('Consigner ID: '),
            new Input(form('add_book_select_button').type('text').id(vars::$edit_prefix.Book::$consigner_id).name(vars::$edit_prefix.Book::$consigner_id)),
            new Input(form('add_book_select_button').classType('button-primary').type('submit').name('button').value('Add'))
        );
        return new TableArr(width(100).border(0).cellspacing(0).cellpadding(0),
            new Row(
                new Column(width(100).valign('top'),
                    new TableArr(width(100).border(0).cellspacing(0).cellpadding(0),
                        new Row(
                            new Column(
                                $form,
                                EditDisplay(Book::$props, $id, 3, Book::$source, $addbutton)
                            )
                        )
                    )
                )
            ),
            new Row(
                new Column(width(100).valign('top'),
                    new TableArr(width(100).border(0).cellspacing(0).cellpadding(0),
                        new Row(
                            new Column(Book::display_consigners($id))
                        )
                    )
                )
            )
        );
    }

    public static function display_consigners($id) {
        $consigners = Book::get_consigners($id);

        $list = new RenderList();
        if (!empty($consigners)) {
            $outsidetable = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100).
                style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'));
            $row = new Row();
            $outsidetable->add_object($row);
            $counter = 0;
            foreach (Consigner::$props as $key => $prop) {
                if ($prop->search_param) {
                    if ($counter == 0) {
                        $row->add_object(
                            new Column(width(20).style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender($prop->format))
                        );
                    }
                    else {
                        $row->add_object(
                            new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender($prop->format))
                        );
                    }
                    $counter++;
                }
            }
            $row->add_object(
                new Column(width(20))
            );

            foreach ($consigners as $consigner) {
                $outsidetable->add_object(Display(Consigner::$props, $consigner, Consigner::$source));
            }
            $list->add_object($outsidetable);
        }
        return $list;
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

    public static function update_count($book) {
        $count = self::get_consigner_count($book);
        if ($count <= 0) {
            Book::$props[Book::$available]->SetValue($book, 1);
        }
        else {
            Book::$props[Book::$available]->SetValue($book, 2);
        }
    }

    public static function add_book($book_id, $consigner_id) {
        Book::add_consigner_to_book($book_id, $consigner_id);
        Consigner::add_book_to_consigner($consigner_id, $book_id);
        Book::update_count($book_id);
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