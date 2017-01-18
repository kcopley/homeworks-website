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

    public static $book_id = 'id';
    public static $book_paid = 'paid';
    public static $book_price = 'price';

    public static $name = 'consigner_name';
    public static $id = 'consigner_id';
    public static $date = 'consigner_date';
    public static $email = 'consigner_email';
    public static $address = 'consigner_address';
    public static $paypal = 'consigner_paypal';
    public static $phone = 'consigner_phone';
    public static $info = 'consigner_info';
    public static $totalpaid = 'consigner_paid';
    public static $totalowed = 'consigner_owed';

    static function init()
    {
        self::$props = array();

        $title = new Text('consigner_name', 'Name', 'title');
        $date = new Date('consigner_date', 'Date', '_cmb_consigner_date');

        $id = new Text('consigner_id', 'ID', '_cmb_consigner_id');
        $id->edit_param = false;
        $id->add_param = false;
        $id->display_in_add = false;

        $email = new Text('consigner_email', 'Email', '_cmb_consigner_email');
        $address = new Text('consigner_address', 'Address', '_cmb_consigner_address');
        $paypal = new Text('consigner_paypal', 'PayPal', '_cmb_consigner_paypal');
        $phone = new Text('consigner_phone', 'Phone', '_cmb_consigner_phone');

        $info = new TextBox('consigner_info', 'Info', '_cmb_consigner_info');
        $info->search_param = false;
        $info->display_in_search = false;

        $paid = new Text('consigner_paid', 'Paid', '_cmb_consigner_totalpaid');
        $paid->search_param = false;
        $paid->display_in_search = false;
        $paid->add_param = false;
        $paid->edit_param = false;
        $paid->display_in_edit = false;

        $owed = new Text('consigner_owed', 'Owed', '_cmb_consigner_totalowed');
        $owed->search_param = false;
        $owed->display_in_search = false;
        $owed->add_param = false;
        $owed->edit_param = false;
        $owed->display_in_edit = false;

        self::$props = array(
            self::$name => $title,
            self::$id => $id,
            self::$date => $date,
            self::$email => $email,
            self::$address => $address,
            self::$paypal => $paypal,
            self::$phone => $phone,
            self::$info => $info,
            self::$totalpaid => $paid,
            self::$totalowed => $owed
        );
    }

    public static function GenerateConsignerSearch()
    {
        return new TableArr(border(0).cellpadding(0).cellspacing(0).width(90),
            new Row(
                new Column(width(45).align('left').valign('top').style('text-align: left;'),
                    GenerateSearchBox(Consigner::$props, 'Consigner', 'Search:', 'Search Consigners')),
                new Column(width(5)),
                new Column(width(45).align('left').valign('top').style('text-align: left;'),
                    GenerateAddBox(Consigner::$props, 'Consigner', 'Add:', 'Add Consigner'))
                //new Column(width(4))
            )
        );
    }

    public static function SelectConsigner($id) {
        return new TableArr(width(100).border(0).cellspacing(0).cellpadding(0),
            new Row(
                new Column(width(54).valign('top'),
                    new TableArr(width(100).border(0).cellspacing(0).cellpadding(0),
                        new Row(
                            new Column(EditDisplay(Consigner::$props, $id, 4, Consigner::$source))
                        ),
                        new Row(
                            new Column(self::ShowTotals($id))
                        ),
                        new Row(
                            new Column(style('padding-top: 12px;'),
                                self::DisplayConsignerBooks($id)
                            )
                        ),
                        new Row(
                            new Column(
                                self::DisplaySoldConsignerBooks($id)
                            )
                        )
                    )

                ),
                new Column(width(1).style('border-right-style: solid; border-width: 1px; border-color: #D0D0D0;')),
                new Column(width(1)),
                new Column(valign('top'),
                    self::GenerateBookSearch($id)
                )
            )
        );
    }

    public static function ShowTotals($id) {
        $list = new RenderList();
        $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100).
            style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'));
        $list->add_object($table);

        $table->add_object(new Row(
            new Column(style('padding-bottom: 8px;'), new Strong(new TextRender('Totals:')))
        ));
        $row = new Row();
        $table->add_object(
            $row
        );

        $table->add_object(
            new Row(
                new Column(new Strong(new TextRender('Total Paid:'))),
                new Column(
                    new TextRender(self::get_total_paid($id))
                ),
                new Column(new Strong(new TextRender('Total Owed:'))),
                new Column(
                    new TextRender(self::get_total_owed($id))
                ),
                new Column(colspan(10).align('right'),
                    new Form(
                        page_action::InputAction(action_types::$pay_sold_books),
                        selection::SetID($id, Consigner::$source),
                        button('Pay All')
                    )
                )
            )
        );
        return $list;
    }

    public static function DisplayConsignerBooks($id) {
        $list = new RenderList();
        $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100).
            style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'));
        $list->add_object($table);

        $table->add_object(new Row(
            new Column(style('padding-bottom: 8px;'), new Strong(new TextRender('Current Books:')))
        ));
        $row = new Row();
        $table->add_object(
            $row
        );
        $counter = 0;
        foreach (Book::$consigner_book_props as $prop) {
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

        $table->add_object(
            self::get_current_consigner_books($id)
        );
        return $list;
    }

    public static function DisplaySoldConsignerBooks($id) {
        $list = new RenderList();
        $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100).
            style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'));
        $list->add_object($table);

        $table->add_object(new Row(
            new Column(style('padding-bottom: 8px;'), new Strong(new TextRender('Sold Books:')))
        ));
        $row = new Row();
        $table->add_object(
            $row
        );
        $counter = 0;
        foreach (Book::$consigner_sold_book_props as $prop) {
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
            new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Paid?'))
        );

        $table->add_object(
            self::get_sold_consigner_books($id)
        );
        return $list;
    }

    public static function get_current_consigner_books($id) {
        $books = self::get_books($id);
        $list = new RenderList();
        foreach ($books as $book) {
            $list->add_object(Display(Book::$consigner_book_props, $book, Book::$source));
        }
        return $list;
    }

    public static function get_sold_consigner_books($id) {
        $books = self::get_sold_books($id);
        $list = new RenderList();
        foreach ($books as $book) {
            $list->add_object(
                self::DisplaySoldBook(Book::$consigner_sold_book_props, $book[self::$book_paid], $book[self::$book_id], Book::$source)
            );
        }

        return $list;
    }

    static function DisplaySoldBook($props, $paid, $id, $source) {
        $row = new Row();
        $counter = 0;
        foreach ($props as $prop) {
            if ($prop->display_in_search) {
                $row->add_object($prop->GetDisplay($id, $source));
                $counter++;
            }
        }
        $row->add_object(new Column(align('left'), new TextRender($paid)));
        $row->add_object(
            new Column(width(20))
        );
        return $row;
    }

    public static function GenerateBookSearch($id) {
        $list = new RenderList();
        if ($_SESSION[action_types::$consigner_books] == true) {
            StoreQuery(Book::$props);
            $list->add_object(
                GenerateSearch(Book::$consigner_book_search_props, Book::$source, Book::$post_type)
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

    public static function consigner_add_sold_book($consigner_id, $book_id) {
        $books = Consigner::get_sold_books($consigner_id);
        if (!$books) $books = array();
        $books[] = self::create_sold_book($book_id, 'No');
        Consigner::set_consigner_sold_books($consigner_id, $books);
    }

    public static function create_sold_book($book_id, $paid, $price) {
        return array(
            self::$book_id => $book_id,
            self::$book_paid => $paid,
            self::$book_price => $price
        );
    }

    public static function consigner_remove_sold_book($consigner_id, $book_id) {
        $books = Consigner::get_sold_books($consigner_id);
        if (($key = array_search($book_id, $books)) !== false) {
            unset($books[$key]);
        }
        $books = array_values($books);
        Consigner::set_consigner_sold_books($consigner_id, $books);
    }

    public static function PayOutBooks($consigner) {
        $soldbooks = self::get_sold_books($consigner);
        $newsoldbooks = array();
        foreach ($soldbooks as $book) {
            $newsoldbooks[] = self::create_sold_book($book[self::$book_id], 'Yes', $book[self::$book_price]);
        }
        self::set_consigner_sold_books($consigner, $newsoldbooks);
        self::get_total_paid($consigner);
        self::get_total_owed($consigner);
    }

    public static function get_total_paid($consigner) {
        $soldbooks = self::get_sold_books($consigner);
        $total = 0;
        foreach ($soldbooks as $book) {
            if ($book[self::$book_paid] == 'Yes')
                $total = $total + $book[self::$book_price];
        }
        self::$props[self::$totalpaid]->SetValue($consigner, $total);
        return $total;
    }

    public static function get_total_owed($consigner) {
        $soldbooks = self::get_sold_books($consigner);
        $total = 0;
        foreach ($soldbooks as $book) {
            if ($book[self::$book_paid] == 'No')
                $total = $total + $book[self::$book_price];
        }
        self::$props[self::$totalowed]->SetValue($consigner, $total);
        return $total;
    }

    public static function add_book_to_consigner($consigner, $book) {
        if ($consigner != get_consigner_owner_id()) {
            $books = Consigner::get_books($consigner);
            $books[] = $book;
            Consigner::set_books($consigner, $books);
        }
    }

    public static function get_owner() {
        return get_option('_cmb_consigner_owner');
    }

    public static function update_owner($postid) {
        update_option('_cmb_consigner_owner', $postid, true);
    }

    public static function add_book($consignerID, $bookID){
        Consigner::add_book_to_consigner($consignerID, $bookID);
        Book::add_consigner_to_book($bookID, $consignerID);
        self::get_total_paid($consignerID);
        self::get_total_owed($consignerID);
    }

    public static function remove_book($consigner_id, $book_id) {
        self::remove_book_from_consigner($consigner_id, $book_id);
        Book::remove_consigner_from_book($book_id, $consigner_id);
        self::get_total_paid($consigner_id);
        self::get_total_owed($consigner_id);
    }

    public static function remove_book_from_consigner($consigner, $book) {
        if ($consigner != get_consigner_owner_id()) {
            $books = Consigner::get_books($consigner);
            if (($key = array_search($book, $books)) !== false) {
                unset($books[$key]);
            }
            $books = array_values($books);
            Consigner::set_books($consigner, $books);
        }
    }

    public static function remove_consigner($id) {
        $books = self::get_books($id);
        foreach ($books as $book) {
            Book::remove_consigner_from_book($book, $id);
        }
        self::set_books($id, array());
        wp_delete_post($id);
    }
}

Consigner::init();