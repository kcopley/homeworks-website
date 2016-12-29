<?php

include_once 'Column.php';
include_once 'Form.php';
include_once 'Input.php';
include_once 'Label.php';
include_once "Renderable.php";
include_once "Row.php";
include_once "Table.php";
include_once "utility.php";


/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 12/29/2016
 * Time: 3:06 PM
 */
class action_types {
    public static $search = 'search_action';
    public static $add_book = 'add_book_action';
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
        return $_REQUEST[book_request::$price];
    }

    public static function GetCondition(){
        return $_REQUEST[book_request::$condition];
    }

    public static function Store() {
        
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