<?php

/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 1/17/2017
 * Time: 4:59 PM
 */
class Transaction
{
    public static $source = 'Transaction';
    public static $post_type = 'transactions';
    public static $props;

    public static $id = 'transaction_id';
    public static $invoiceid = 'transaction_invoice';
    public static $schoolcontact = 'transaction_school_contact';
    public static $schoolname = 'transaction_school_name';
    public static $date = 'transaction_date';
    public static $customer_name = 'transaction_cust_name';
    public static $customer_phone = 'transaction_cust_phone';
    public static $customer_email = 'transaction_cust_email';
    public static $customer_address = 'transaction_cust_address';
    public static $transfirstid = 'transaction_transfirst';
    public static $taxrate = 'transaction_taxrate';
    public static $total = 'transaction_total';
    public static $complete = 'transaction_complete';

    public static $book_id = 'transaction_book';
    public static $book_title = 'transaction_book_title';
    public static $book_quantity = 'transaction_book_quantity';
    public static $book_price = 'transaction_price';
    public static $book_refunded_quantity = 'transaction_refunded_book_quantity';
    public static $credit_name = 'transaction_credit_name';
    public static $credit_amount = 'transaction_credit_amount';
    public static $payment_type = 'payment_type';
    public static $payment_amount = 'payment_amount';
    public static $conference = 'conference_sale';
    public static $open_in_checkout = 'open_in_checkout';

    static function init()
    {
        self::$props = array();

        $id = new Text('transaction_id', 'Sale', 'title');
        $id->edit_param = false;

        $invoice = new Text('transaction_invoice', 'Invoice', '_cmb_transaction_invoice');
        $invoice->edit_param = false;
        //$schoolcontact = new Text('transaction_school_contact', 'School Contact', '_cmb_transaction_school_contact');
        $schoolname = new Text('transaction_school_name', 'School Name', '_cmb_transaction_school_name');

        $date = new Date('transaction_date', 'Date', '_cmb_transaction_date');

        $name = new Text('transaction_customer_name', 'Name', '_cmb_transaction_customer_name');
        $phone = new Text('transaction_customer_phone', 'Phone', '_cmb_transaction_customer_phone');
        $email = new Text('transaction_customer_email', 'Email', '_cmb_transaction_customer_email');
        $address = new Text('transaction_customer_address', 'Address', '_cmb_transaction_customer_address');
        $total = new Decimal('transaction_total', 'Total', '_cmb_transaction_total');
        $total->display_prefix = '$';
        $total->cost = true;
        $total->edit_param = false;
        $taxrate = new Text('transaction_taxrate', 'Tax Rate', '_cmb_transaction_taxrate');
        $taxrate->edit_param = false;
        $transfirst = new Text('transaction_transfirst', 'TransFirst', '_cmb_transaction_transfirstid');
        $transfirst->search_param = false;
        $transfirst->display_in_search = false;
        $transfirst->edit_param = false;

        $print = new TransactionButton('print_button');
        $print->button = 'Print Invoice';
        $print->search_param = false;
        $print->display_in_search = false;
        $print->edit_param = false;
        $print->add_param = false;
        $print->custom = new Input(classType('button-primary').onclick("printContent('toPrint');").type('button').value('Print Invoice'));

        $completed = new Radio('transaction_complete', 'Complete', '_cmb_transaction_completed');
        $completed->options = array(1 => 'No', 2 => 'Yes');
        $completed->add_param = false;
        $completed->edit_param = false;
        $completed->display_in_edit = false;

        $conference = new Radio(self::$conference, 'Sale Type', '_cmb_transaction_saletype');
        $conference->options = array(1 => 'Conference', 2 => 'Online');
        $conference->edit_param = false;
        $conference->add_param = false;

        $openincheckout = new ImportButton('checkout_button');
        $openincheckout->formaction = vars::$checkout_page;
        $openincheckout->action = action_types::$import_transaction;
        $openincheckout->button = 'Open in Checkout';
        $openincheckout->edit_param = false;
        $openincheckout->add_param = false;
        $openincheckout->search_param = false;

        self::$props = array(
            self::$id => $id,
            self::$invoiceid => $invoice,
            self::$date => $date,
            self::$conference => $conference,
            self::$customer_name => $name,
            self::$customer_phone => $phone,
            self::$customer_email => $email,
            self::$customer_address => $address,
            self::$schoolname => $schoolname,
            self::$total => $total,
            self::$taxrate => $taxrate,
            self::$transfirstid => $transfirst,
            self::$complete => $completed,
            self::$open_in_checkout => $openincheckout,
            'printing' => $print
        );
    }

    public static function get_payment_text($type) {
        if ($type == checkout_payment::$payment_cash) {
            return 'Cash';
        }
        if ($type == checkout_payment::$payment_credit) {
            return 'Credit';
        }
        if ($type == checkout_payment::$payment_check) {
            return 'Check';
        }
        if ($type == checkout_payment::$payment_phone) {
            return 'Phone';
        }
        return '';
    }

    public static function SelectTransaction($id) {
        $table = new TableArr(width(100).border(0).cellspacing(0).cellpadding(0));
        $table->add_object(
            new Row(
                new Column(width(100).valign('top').style('padding-bottom: 8px;'),
                    new TableArr(width(100) . border(0) . cellspacing(0) . cellpadding(0),
                        new Row(
                            new Column(EditDisplay(Transaction::$props, $id, 2, Transaction::$source, new RenderList()))
                        )
                    )
                )
            ));
        if (!empty(Transaction::get_books($id))) {
            $table->add_object(
                new Row(
                        new Column(style('padding-bottom: 8px;'),
                            self::get_book_list($id)
                        )
                    )
                );
        }
        if (!empty(Transaction::get_refunds($id))) {
            $table->add_object(
                new Row(
                    new Column(style('padding-bottom: 8px;'),
                            self::get_refund_list($id)
                        )
                    )
                );
        }
        return $table;
    }

    public static function get_refund_list($id) {
        $titlewidth = 20;
        $otherwidth = 10;
        $table = new RenderList();
        if (!empty(Transaction::get_refunds($id))) {
            $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100).
                style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'),
                new Row(
                    new Column(width($titlewidth), new H4(style('margin: 0px; font-size: 14px;'), new TextRender('Refunds'))),
                    new Column(width($otherwidth), new H4(style('margin: 0px;'), new TextRender(''))),
                    new Column(width($otherwidth), new H4(style('margin: 0px;'), new TextRender(''))),
                    new Column(width($otherwidth), new H4(style('margin: 0px;'), new TextRender(''))),
                    new Column(width($otherwidth), new H4(style('margin: 0px;'), new TextRender(''))),
                    new Column(width($otherwidth), new H4(style('margin: 0px;'), new TextRender(''))),
                    new Column(width($otherwidth)),
                    new Column()
                )
            );

            $books = Transaction::get_refunds($id);
            foreach ($books as $book) {
                $table->add_object(self::refund_display_transaction($book));
                $table->add_object(
                    new Row(style('border: none; padding: 0px; height: 1px;'),
                        new Column(colspan(10) . style('padding-bottom: 0px;'),
                            new HR(style('margin: 0px;')))));
            }
        }
        return $table;
    }

    public static function get_book_list($id) {
        $titlewidth = 20;
        $otherwidth = 10;
        $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100).
            style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'),
            new Row(
                new Column(width($titlewidth), new H4(style('margin: 0px; font-size: 14px;'), new TextRender('Books'))),
                new Column(width($otherwidth), new H4(style('margin: 0px;'), new TextRender('Publisher'))),
                new Column(width($otherwidth), new H4(style('margin: 0px;'), new TextRender('ISBN'))),
                new Column(width($otherwidth), new H4(style('margin: 0px;'), new TextRender('Barcode'))),
                new Column(width($otherwidth), new H4(style('margin: 0px;'), new TextRender('Used/New'))),
                new Column(width($otherwidth), new H4(style('margin: 0px;'), new TextRender('Quantity'))),
                new Column(width($otherwidth), new H4(style('margin: 0px;'), new TextRender('Price'))),
                new Column()
            )
        );

        $books = Transaction::get_books($id);
        foreach ($books as $book){
            $table->add_object(self::book_display_transaction($book));
            $table->add_object(
                new Row(style('border: none; padding: 0px; height: 1px;'),
                    new Column(colspan(10).style('padding-bottom: 0px;'),
                        new HR(style('margin: 0px;')))));
        }
        return $table;
    }

    function book_display_transaction($book) {
        $book_id = $book[self::$book_id];
        $quantity = $book[self::$book_quantity];
        $price = $book[self::$book_price];
        $title = $book[self::$book_title];

        return new Row(
            Book::$props[Book::$name]->GetDisplay($book_id, Book::$source),
            Book::$props[Book::$publisher]->GetDisplay($book_id, Book::$source),
            Book::$props[Book::$isbn]->GetDisplay($book_id, Book::$source),
            Book::$props[Book::$barcode]->GetDisplay($book_id, Book::$source),
            Book::$props[Book::$condition]->GetDisplay($book_id, Book::$source),
            new Column(style('padding-top: 6px; padding-bottom: 6px;'),
                new TextRender($quantity)),
            new Column(style('padding-top: 6px; padding-bottom: 6px;'),
                new TextRender('$'.number_format($price, 2)))
        );
    }

    function refund_display_transaction($book) {
        $book_id = $book[self::$book_id];
        $quantity = $book[self::$book_quantity];
        $price = $book[self::$book_price];
        $title = $book[self::$book_title];

        return new Row(
            new Column(style('padding-top: 6px; padding-bottom: 6px;'),
                new TextRender($title)),
            new Column(style('padding-top: 6px; padding-bottom: 6px;'),
                new TextRender($book_id)),
            new Column(style('padding-top: 6px; padding-bottom: 6px;'),
                new TextRender(Book::$props[Book::$isbn]->GetValue($book_id))),
            new Column(style('padding-top: 6px; padding-bottom: 6px;'),
                new TextRender(Book::$props[Book::$barcode]->GetValue($book_id))),
            new Column(style('padding-top: 6px; padding-bottom: 6px;'),
                new TextRender(Book::$props[Book::$condition]->GetValue($book_id))),
            new Column(style('padding-top: 6px; padding-bottom: 6px;'),
                new TextRender($quantity)),
            new Column(style('padding-top: 6px; padding-bottom: 6px;'),
                new TextRender('$'.number_format($price, 2)))
        );
    }

    public static function print_formatting($id) {
        $print =
            new Div(style('display: none;'),
                new Div(id('toPrint'),
                    new Paragraph(style('text-align: center;'),
                        new Strong(new TextRender('Home Works for Books')),
                        new BR(),
                        new EM(new TextRender('Your homeschool connection for discounted new and used homeschool materials!'))
                    ),
                    new Paragraph(style('text-align: center;'),
                        new TextRender(get_option('invoiceaddress')),
                        new BR(),
                        new Strong(new TextRender('Phone: ')),
                        new TextRender(get_option('invoicephone')),
                        new BR(),
                        new TextRender('Come visit us online at '),
                        new TextRender(get_option('invoiceURL')),
                        new BR(),
                        new BR(),
                        new TextRender(date("Y/m/d H:i:s")),
                        new BR(),
                        new TextRender('Cashier: '.get_user_name())
                    ),
                    new Paragraph(), //blank
                    new TableArr(border(0).cellpadding(0).cellspacing(0).width(100),
                        Transaction::get_book_printing($id),
                        Transaction::get_refund_book_printing($id),
                        Transaction::get_credit_printing($id),
                        new Row(
                            new Column(),
                            new Column(align('left').colspan(2).valign('middle'),
                                new HR()
                            )
                        ),
                        new Row(
                            new Column(valign('top')),
                            new Column(align('right').valign('top').width(10),
                                new Strong(new TextRender('Subtotal:'))),
                            new Column(style('padding-left: 4px;'),
                                new Strong(new TextRender(' $'.number_format(Transaction::get_subtotal($id), 2)))
                            )
                        ),
                        new Row(
                            new Column(valign('top')),
                            new Column(align('right').valign('top').width(10),
                                new Strong(new TextRender('Refunds:'))),
                            new Column(style('padding-left: 4px;'),
                                new Strong(new TextRender(' -$'.number_format(Transaction::get_refund_total($id), 2)))
                            )
                        ),
                        new Row(
                            new Column(valign('top')),
                            new Column(align('right').valign('top').width(10),
                                new Strong(new TextRender('Credits:'))),
                            new Column(style('padding-left: 4px;'),
                                new Strong(new TextRender(' $'.number_format(Transaction::get_credit_total($id), 2)))
                            )
                        ),
                        new Row(
                            new Column(valign('top')),
                            new Column(align('right').valign('top').width(10),
                                new Strong(new TextRender('Tax:'))),
                            new Column(style('padding-left: 4px;'),
                                new Strong(new TextRender(' $'.number_format(Transaction::get_tax_total($id), 2)))
                            )
                        ),
                        new Row(
                            new Column(),
                            new Column(align('left').colspan(2).valign('middle'),
                                new HR()
                            )
                        ),
                        new Row(
                            new Column(valign('top')),
                            new Column(align('right').valign('top').width(10),
                                new Strong(new TextRender('Total:'))),
                            new Column(style('padding-left: 4px;'),
                                new Strong(new TextRender(' $'.number_format(Transaction::get_total($id), 2)))
                            )
                        ),
                        new Row(
                            new Column(colspan(2), new H4(style('margin: 0px; padding-top: 4px; padding-bottom: 4px;'), new TextRender('Payments:')))
                        ),
                        new Row(style('border: none; padding: 0px; height: 1px;'),
                            new Column(colspan(10).style('padding-bottom: 0px;'),
                                new HR(style('margin: 0px;')))),
                        Transaction::get_payment_printing($id),
                        Transaction::get_refund_printing($id)
                    ),
                    new Paragraph(style('text-align: center;'),
                        new TextRender(get_option('invoicepromo'))
                    ),
                    new Paragraph(style('text-align: center;'),
                        new Strong(new TextRender('Invoice: #')),
                        new TextRender(Transaction::$props[Transaction::$id]->GetValue($id)),
                        new BR(),
                        new TextRender('Customer Copy')
                    )
                )
            );
        $print->Render();
    }

    public static function get_payment_printing($id) {
        $payments = self::get_payment_types($id);
        $list = new RenderList();
        if (!empty($payments)) {
            foreach ($payments as $payment) {
                $type = $payment[self::$payment_type];
                $amount = $payment[self::$payment_amount];
                $list->add_object(
                    new Row(
                        new Column(align('left') . colspan(2) . valign('top'),
                            new TextRender(
                                self::get_payment_text($type)
                            )
                        ),
                        new Column(valign('top') . width(15),
                            new TextRender('-$' . number_format($amount, 2))
                        )
                    )
                );
            }
        }
        return $list;
    }

    public static function get_refund_printing($id) {
        $list = new RenderList();
        if (self::get_refund_amount($id) > 0) {
            $list->add_object(
                new Row(
                    new Column(valign('top')),
                    new Column(align('right').valign('top').width(10),
                        new Strong(new TextRender('Refund:'))),
                    new Column(style('padding-left: 4px;'),
                        new Strong(new TextRender(' $'.number_format(Transaction::get_refund_amount($id), 2)))
                    )
                )
            );
        }
        else {
            $list->add_object(
                new Row(
                    new Column(valign('top')),
                    new Column(align('right').valign('top').width(10),
                        new Strong(new TextRender('Due:'))),
                    new Column(style('padding-left: 4px;'),
                        new Strong(new TextRender(' $'.number_format(0, 2)))
                    )
                )
            );
        }
        return $list;
    }

    public static function get_credit_printing($id) {
        $credits = self::get_credits($id);
        $list = new RenderList();

        if (!empty($credits)) {
            $list->add_object(
                new Row(
                    new Column(colspan(2), new H4(style('margin: 0px; padding-top: 4px; padding-bottom: 4px;'), new TextRender('Credits:')))
                )
            );
            $list->add_object(new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan(10).style('padding-bottom: 0px;'),
                    new HR(style('margin: 0px;')))));
            foreach ($credits as $credit) {
                $name = $credit[self::$credit_name];
                $amount = $credit[self::$credit_amount];
                $list->add_object(
                    new Row(
                        new Column(align('left') . colspan(2) . valign('top'),
                            new TextRender(
                                $name
                            )
                        ),
                        new Column(valign('top') . width(15),
                            new TextRender('-$' . number_format($amount, 2))
                        )
                    )
                );
            }
        }
        return $list;
    }

    public static function get_book_printing($id) {
        $books = self::get_books($id);
        $list = new RenderList();
        if (!empty($books)) {
            $list->add_object(
                new Row(
                    new Column(colspan(2), new H4(style('margin: 0px; padding-top: 4px; padding-bottom: 4px;'), new TextRender('Books:')))
                )
            );
            $list->add_object(new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan(10).style('padding-bottom: 0px;'),
                    new HR(style('margin: 0px;')))));
            foreach ($books as $book) {
                $book_id = $book[self::$book_id];
                $title = $book[self::$book_title];
                $price = $book[self::$book_price];
                $quantity = $book[self::$book_quantity];
                $lineTotal = $price * $quantity;
                $list->add_object(
                    new Row(
                        new Column(align('left') . colspan(2) . valign('top'),
                            new TextRender(
                                '(' . $quantity . ') ' . $title
                            )
                        ),
                        new Column(valign('top') . width(15),
                            new TextRender('$' . number_format($lineTotal, 2))
                        )
                    )
                );
            }
        }
        return $list;
    }

    public static function get_refund_book_printing($id) {
        $books = self::get_refunds($id);
        $list = new RenderList();
        if (!empty($books)) {
            $list->add_object(
                new Row(
                    new Column(colspan(2), new H4(style('margin: 0px; padding-top: 4px; padding-bottom: 4px;'), new TextRender('Refunds:')))
                )
            );
            $list->add_object(new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan(10).style('padding-bottom: 0px;'),
                    new HR(style('margin: 0px;')))));
            foreach ($books as $book) {
                $book_id = $book[self::$book_id];
                $title = $book[self::$book_title];
                $price = $book[self::$book_price];
                $quantity = $book[self::$book_quantity];
                $lineTotal = $price * $quantity;
                $list->add_object(
                    new Row(
                        new Column(align('left') . colspan(2) . valign('top'),
                            new TextRender(
                                '(' . $quantity . ') ' . $title
                            )
                        ),
                        new Column(valign('top') . width(15),
                            new TextRender('$' . number_format($lineTotal, 2))
                        )
                    )
                );
            }
        }
        return $list;
    }

    public static function get_refund_amount($id) {
        $amountPaid = ceil(self::get_total_paid($id) * 100);
        $total = ceil(self::get_total($id) * 100);
        if ($amountPaid > $total) {
            return self::get_total_paid($id) - self::get_total($id);
        }
        else return 0;
    }

    public static function get_total_paid($id) {
        $payments = self::get_payment_types($id);
        $total = 0;
        if (!empty($payments)) {
            foreach ($payments as $payment) {
                $amount = $payment[self::$payment_amount];
                $total = $total + $amount;
            }
        }
        return $total;
    }

    public static function set_books_from_cart($id, $cart) {
        $books = array();
        if (!empty($cart)) {
            foreach ($cart as $key => $value) {
                $book = $key;
                $quantity = $value[checkout_cart::$cart_book_quantity];
                $books[] = self::create_book_transaction($book, $quantity);
            }
        }
        self::set_books($id, $books);
    }

    public static function set_refunds_from_cart($id, $cart) {
        $books = array();
        if (!empty($cart)) {
            foreach ($cart as $key => $value) {
                $book = $key;
                $quantity = $value[checkout_cart::$cart_book_quantity];
                $books[] = self::create_book_transaction($book, $quantity);
            }
        }
        self::set_refunds($id, $books);
    }

    public static function set_credits_from_cart($id, $credits) {
        $creditarr = array();
        if (!empty($credits)) {
            foreach ($credits as $key => $value) {
                $name = $value[checkout_cart::$credit_name];
                $amount = $value[checkout_cart::$credit_amount];
                $creditarr[] = self::create_credit_transaction($name, $amount);
            }
        }
        self::set_credits($id, $creditarr);
    }

    public static function create_payment_type($type, $amount, $transfirst) {
        return array(
            self::$payment_type => $type,
            self::$payment_amount => $amount,
            self::$transfirstid => $transfirst
        );
    }

    public static function get_payment_types($id){
        return get_post_meta($id, '_cmb_transaction_payment_types', true);
    }

    public static function set_payment_types($id, $types){
        update_post_meta($id, '_cmb_transaction_payment_types', $types);
    }

    public static function create_book_transaction($book, $quantity) {
        return array(
            self::$book_id => $book,
            self::$book_quantity => $quantity,
            self::$book_price => Book::$props[Book::$price]->GetValue($book),
            self::$book_title => Book::$props[Book::$name]->GetValue($book),
        );
    }

    public static function create_refund_transaction($book, $quantity) {
        return array(
            self::$book_id => $book,
            self::$book_quantity => $quantity,
            self::$book_price => Book::$props[Book::$price]->GetValue($book),
            self::$book_title => Book::$props[Book::$name]->GetValue($book),
        );
    }

    public static function create_credit_transaction($name, $amount) {
        return array(
            self::$credit_name => $name,
            self::$credit_amount => $amount
        );
    }

    public static function add_payment($id, $type, $amount) {
        $payments = self::get_payment_types($id);
        if (!$payments) {
            $payments = array();
        }
        $payments[] = self::create_payment_type($type, $amount, -1);
        self::set_payment_types($id, $payments);
    }

    public static function add_payment_credit($id, $type, $amount, $transfirst) {
        $payments = self::get_payment_types($id);
        if (!$payments) {
            $payments = array();
        }
        $payments[] = self::create_payment_type($type, $amount, $transfirst);
        self::set_payment_types($id, $payments);
    }

    public static function remove_payment($id, $index) {
        $payments = self::get_payment_types($id);
        if (!$payments) return;
        if ($index > (count($payments) - 1)) return;
        unset($payments[$index]);
        $payments = array_values($payments);
        self::set_payment_types($id, $payments);
    }

    public static function add_book($id, $book, $quantity) {
        $books = self::get_books($id);
        if (!$books){
            $books = array();
        }
        $books[] = self::create_book_transaction($book, $quantity);
        self::set_books($id, $books);
        Transaction::$props[Transaction::$total]->SetValue($id, self::get_total($id));
    }

    public static function refund_book($id, $book, $quantity) {
        $books = self::get_refunds($id);
        if (array_key_exists($book, $books)) {
            $existing_quantity = $books[$book][self::$book_quantity];
            $refunded_quantity = $books[$book][self::$book_refunded_quantity];
            if ($quantity > $existing_quantity) {
                $quantity = $existing_quantity;
            }
            $books[$book][self::$book_quantity] = $existing_quantity - $quantity;
            $books[$book][self::$book_refunded_quantity] = $refunded_quantity + $quantity;
        }
        self::set_refunds($id, $books);
    }

    public static function get_subtotal($id) {
        $books = self::get_books($id);
        $total = 0;
        if (!empty($books)) {
            foreach ($books as $book) {
                $price = $book[self::$book_price];
                $quantity = $book[self::$book_quantity];
                $lineTotal = $price * $quantity;
                $total = $total + $lineTotal;
            }
        }
        return $total;
    }

    public static function get_total($id) {
        return self::get_subtotal($id) + self::get_tax_total($id) - self::get_credit_total($id) - self::get_refund_total($id);
    }

    public static function get_refund_total($id) {
        $refunds = self::get_refunds($id);
        $total = 0;
        if (!empty($refunds)) {
            foreach ($refunds as $refund) {
                $amount = $refund[self::$book_price];
                $total = $total + $amount;
            }
        }
        return $total;
    }

    public static function get_credit_total($id) {
        $credits = self::get_credits($id);
        $total = 0;
        if (!empty($credits)) {
            foreach ($credits as $credit) {
                $amount = $credit[self::$credit_amount];
                $total = $total + $amount;
            }
        }
        return $total;
    }

    public static function get_tax_total($id) {
        $taxpercent = Transaction::$props[Transaction::$taxrate]->GetValue($id);
        $subtotal = self::get_subtotal($id);
        return $subtotal * $taxpercent - self::get_refund_total($id) * $taxpercent;
    }

    public static function get_credits($id) {
        return get_post_meta($id, '_cmb_transaction_credits', true);
    }

    public static function set_credits($id, $credits) {
        update_post_meta($id, '_cmb_transaction_credits', $credits);
    }

    public static function get_books($id) {
        return get_post_meta($id, '_cmb_transaction_books', true);
    }

    public static function get_refunds($id) {
        return get_post_meta($id, '_cmb_transaction_refunds', true);
    }

    public static function set_books($id, $books) {
        update_post_meta($id, '_cmb_transaction_books', $books);
    }

    public static function set_refunds($id, $books) {
        update_post_meta($id, '_cmb_transaction_refunds', $books);
    }
}

Transaction::init();