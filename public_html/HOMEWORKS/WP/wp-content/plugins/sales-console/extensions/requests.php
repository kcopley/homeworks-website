<?php
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 12/29/2016
 * Time: 3:06 PM
 */
class action_types {
    public static $delete_sure = 'deletion_sure';
    public static $consigner_books = 'search_consigner_books';
    public static function get_search($source) {
        return 'search_'.$source.'_action';
    }
    public static function get_add($source) {
        return 'add_'.$source.'_action';
    }
    public static function get_select($source) {
        return 'select_'.$source.'_action';
    }
    public static function get_update($source) {
        return 'update_'.$source.'_action';
    }
    public static function get_delete($source) {
        return 'delete_'.$source.'_action';
    }
    public static function get_delete_sure($source) {
        return 'delete_'.$source.'_sure_action';
    }
    public static function add_image($source) {
        return 'add_image_'.$source;
    }

    public static $search_books = 'search_book_action';
    public static $add_book = 'add_book_action';
    public static $select_book = 'select_book_action';
    public static $edit_book = 'update_book_action';
    public static $delete_book = 'delete_book_action';
    public static $delete_book_sure = 'delete_book_sure_action';

    public static $add_image_to_book_search = 'add_image_to_book_search';
    public static $add_image_to_book_edit = 'add_image_to_book_edit';

    public static $pay_sold_books = 'pay_sold_books_action';
    public static $search_consigner = 'search_consigner_action';
    public static $add_book_to_owner = 'add_book_to_owner_action';
    public static $select_consigner = 'select_consigner_action';
    public static $remove_consigner = 'remove_consigner_action';
    public static $update_consigner = 'update_consigner_action';
    public static $add_consigner = 'add_consigner_action';
    public static $add_book_to_consigner = 'add_book_to_consigner_action';
    public static $remove_book_from_consigner = 'remove_book_from_consigner_action';
    public static $remove_sold_book_from_consigner = 'remove_sold_book_from_consigner_action';

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
    public static function SetID($id, $source) {
        return new Input(id('selection_'.$source).type('hidden').name('selection_'.$source).value($id));
    }

    public static function SetIDForm($id, $source, $form) {
        return new Input(form($form).id('selection_'.$source).type('hidden').name('selection_'.$source).value($id));
    }

    public static function GetID($source) {
        return $_SESSION['selection_'.$source];
    }

    public static function GetIDPost($source) {
        return $_REQUEST['selection_'.$source];
    }

    public static function GetIDS() {
        if (self::GetIDPost(Book::$source)) $_SESSION['selection_'.Book::$source] = self::GetIDPost(Book::$source);
        if (self::GetIDPost(Consigner::$source)) $_SESSION['selection_'.Consigner::$source] = self::GetIDPost(Consigner::$source);
        if (self::GetIDPost(Transaction::$source)) $_SESSION['selection_'.Transaction::$source] = self::GetIDPost(Transaction::$source);
        if (self::GetIDPost(vars::$current_page)) $_SESSION['selection_'.vars::$current_page] = self::GetIDPost(vars::$current_page);
    }

    public static function ResetPage() {
        $_SESSION['selection_'.vars::$current_page] = 1;
    }

    public static function ResetPages($page) {
        $_SESSION[vars::$last_page] = $page;
        $_SESSION[vars::$new_page] = $page;
    }

    public static function GetPages($page) {
        if ($_SESSION[vars::$new_page] == $page) return;
        if ($_POST[vars::$went_back]) {
            $_SESSION[vars::$last_page] = $_SESSION[vars::$last2_page];
            $_SESSION[vars::$new_page] = $page;
            $_SESSION[vars::$last2_page] = null;
        }
        else {
            $_SESSION[vars::$last2_page] = $_SESSION[vars::$last_page];
            $_SESSION[vars::$last_page] = $_SESSION[vars::$new_page];
            $_SESSION[vars::$new_page] = $page;
        }
    }
}

class page_action {
    public static $action = 'page_action';

    public static function InputAction($value) {
        return new Input(id(page_action::$action).name(page_action::$action).type('hidden').value($value));
    }

    public static function InputActionForm($value, $form) {
        return new Input(form($form).id(page_action::$action).name(page_action::$action).type('hidden').value($value));
    }

    public static function ResetActions($action) {
        $_SESSION[vars::$last_action] = $action;
        $_SESSION[vars::$new_action] = $action;
    }

    public static function SetNewAction($action) {
        if (strpos($action, 'search') !== false){
            selection::ResetPages($_SESSION[vars::$new_page]);
            self::ResetActions($action);
            return;
        }
        if ($_SESSION[vars::$new_action] == $action) return;
        if ($_POST[vars::$went_back]) {
            $_SESSION[vars::$last_action] = $_SESSION[vars::$last2_action];
            $_SESSION[vars::$new_action] = $action;
            $_SESSION[vars::$last2_action] = null;
        }
        else {
            $_SESSION[vars::$last2_action] = $_SESSION[vars::$last_action];
            $_SESSION[vars::$last_action] = $_SESSION[vars::$new_action];
            $_SESSION[vars::$new_action] = $action;
        }
    }

    public static function GetAction() {
        return $_REQUEST[page_action::$action];
    }
}
