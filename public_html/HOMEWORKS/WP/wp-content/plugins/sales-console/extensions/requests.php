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

    public static $search_consigner = 'search_consigner_action';
    public static $add_book_to_owner = 'add_book_to_owner_action';
    public static $select_consigner = 'select_consigner_action';
    public static $remove_consigner = 'remove_consigner_action';
    public static $update_consigner = 'update_consigner_action';
    public static $add_consigner = 'add_consigner_action';
    public static $add_book_to_consigner = 'add_book_to_consigner_action';
    public static $remove_book_from_consigner = 'remove_book_from_consigner_action';

    public static $search_transactions = 'search_transactions_action';
    public static $select_transaction = 'select_transaction_action';
    public static $delete_transaction = 'delete_transaction_action';

    public static $add_item_checkout = 'add_item_to_checkout';
    public static $add_credit_checkout = 'add_credit_to_checkout';
    public static $remove_item_checkout = 'remove_item_from_checkout';
    public static $remove_item_refund = 'remove_item_from_refund';
    public static $remove_credit_checkout = 'remove_credit_to_checkout';
    public static $clear_checkout_cart = 'clear_checkout_cart';
    public static $clear_checkout_credit = 'clear_checkout_credit';
    public static $clear_checkout = 'clear_checkout';

    public static $pre_card_submission = 'pre_card_submission';
    public static $process_payment = 'process_payment';
}

class selection {
    public static $book = 'selected_book';
    public static $consigner = 'selected_consigner';
    public static $transaction = 'selected_transaction';

    public static function InputBook($id) {
        return new Input(id(selection::$book).type('hidden').name(selection::$book).value($id));
    }

    public static function InputConsigner($id) {
        return new Input(id(selection::$consigner).type('hidden').name(selection::$consigner).value($id));
    }

    public static function InputTransaction($id) {
        return new Input(id(selection::$transaction).type('hidden').name(selection::$transaction).value($id));
    }

    public static function GetBook() {
        return $_REQUEST[selection::$book];
    }

    public static function GetConsigner() {
        return $_REQUEST[selection::$consigner];
    }

    public static function GetTransaction() {
        return $_REQUEST[selection::$transaction];
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

