<?php
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 1/1/2017
 * Time: 9:31 PM
 */

class checkout_payment {
    public static $name = 'trans_customer_name';
    public static $email = 'trans_customer_email';
    public static $card_number = 'trans_customer_card_number';
    public static $card_expiration_month = 'trans_customer_card_expiration_month';
    public static $card_expiration_year = 'trans_customer_card_expiration_year';
    public static $card_verification = 'trans_customer_card_verification';

    public static $address = 'trans_customer_address';
    public static $city = 'trans_customer_city';
    public static $state = 'trans_customer_state';
    public static $zip = 'trans_customer_zip';

    public static $transaction_id = 'transaction_id';
    public static $payment_type = 'payment_type';

    public static $payment_cash = 'payment_cash';
    public static $payment_credit = 'payment_credit';
    public static $payment_check = 'payment_check';
    public static $payment_phone = 'payment_phone';

    public static $display_credit = 'display_credit';
    public static $display_phone = 'display_phone';
    public static $display_cash = 'display_cash';
    public static $display_check = 'display_check';

    public static $amount_paid = 'amount_paid';
    public static $refund_amount = 'refund_amount';

    public static $total_amount_paid = 'total_amount_paid';
    public static $current_transaction_id = 'current_transaction_id';

    public static function GetTotalAmountPaid() {
        if ($_SESSION[self::$total_amount_paid]) {
            return $_SESSION[self::$total_amount_paid];
        }
        else return 0;
    }

    public static function SetTotalAmountPaid($value) {
        $_SESSION[self::$total_amount_paid] = $value;
    }

    public static function GetPaymentType() {
        return $_REQUEST[self::$payment_type];
    }

    public static function GetAmountPaid() {
        return $_REQUEST[self::$amount_paid];
    }

    public static function InputAmountPaid() {
        return new Input(id(self::$amount_paid).name(self::$amount_paid).type('text').value(number_format(get_total() - checkout_payment::GetTotalAmountPaid(), 2)));
    }

    public static function GetTransactionID() {
        return $_REQUEST[self::$transaction_id];
    }

    public static function InputTransactionID() {
        return new Input(id(self::$transaction_id).name(self::$transaction_id).type('text'));
    }

    public static function GetName() {
        return $_REQUEST[self::$name];
    }

    public static function InputName() {
        return new Input(id(self::$name).name(self::$name).type('text'));
    }

    public static function GetCardVerification() {
        return $_REQUEST[self::$name];
    }

    public static function InputCardVerification() {
        return new Input(id(self::$card_verification).name(self::$card_verification).type('text'));
    }

    public static function GetEmail() {
        return $_REQUEST[self::$email];
    }

    public static function InputEmail() {
        return new Input(id(self::$email).name(self::$email).type('text'));
    }

    public static function GetCardNumber() {
        return $_REQUEST[self::$card_number];
    }

    public static function InputCardNumber() {
        return new Input(id(self::$card_number).name(self::$card_number).type('text'));
    }

    public static function GetCardExpirationMonth() {
        return $_REQUEST[self::$card_expiration_month];
    }

    public static function InputCardExpirationMonth() {
        return new Input(id(self::$card_expiration_month).name(self::$card_expiration_month).type('text'));
    }

    public static function GetCardExpirationYear() {
        return $_REQUEST[self::$card_expiration_year];
    }

    public static function InputCardExpirationYear() {
        return new Input(id(self::$card_expiration_year).name(self::$card_expiration_year).type('text'));
    }

    public static function GetState() {
        return $_REQUEST[self::$state];
    }

    public static function InputState() {
        return new Input(id(self::$state).name(self::$state).type('text'));
    }

    public static function GetAddress() {
        return $_REQUEST[self::$address];
    }

    public static function InputAddress() {
        return new Input(id(self::$address).name(self::$address).type('text'));
    }

    public static function GetCity() {
        return $_REQUEST[self::$city];
    }

    public static function InputCity() {
        return new Input(id(self::$city).name(self::$city).type('text'));
    }

    public static function GetZip() {
        return $_REQUEST[self::$zip];
    }

    public static function InputZip() {
        return new Input(id(self::$zip).name(self::$zip).type('text'));
    }

    public static function SetRefund($value) {
        $_REQUEST[self::$refund_amount] = $value;
    }

    public static function GetRefund() {
        return $_REQUEST[self::$refund_amount];
    }
}

class checkout_width_constants {
    public static $leftWidth = 70;
    public static $rightWidth = 30;

    public static $bookTitleWidth = 60;
    public static $bookQuantityWith = 20;
    public static $bookPriceWidth = 15;
    public static $bookExtraWidth = 30;
}

class checkout_request {
    public static $quantity = 'query_checkout_quantity';
    public static $isbn = 'query_checkout_isbn';
    public static $barcode = 'query_checkout_barcode';
    public static $credit_name = 'query_credit_name';
    public static $credit_amount = 'query_credit_amount';

    public static $remove_amount = 'cart_remove_amount';
    public static $remove_credit_index = 'cart_credit_index';

    public static function GetBarcode() {
        if ($_REQUEST[self::$barcode])
            return $_REQUEST[self::$barcode];
        else return -1;
    }

    public static function GetISBN() {
        if ($_REQUEST[self::$isbn])
            return $_REQUEST[self::$isbn];
        else return -1;
    }

    public static function GetQuantity() {
        return $_REQUEST[self::$quantity];
    }

    public static function GetCreditName() {
        if ($_REQUEST[self::$credit_name])
            return $_REQUEST[self::$credit_name];
        else return 'Credit';
    }

    public static function GetCreditAmount() {
        if ($_REQUEST[self::$credit_amount])
            return $_REQUEST[self::$credit_amount];
        else return -1;
    }

    public static function GetRemoveQuantity() {
        return $_REQUEST[self::$remove_amount];
    }

    public static function GetCreditIndex() {
        return $_REQUEST[self::$remove_credit_index];
    }
}

class checkout_cart {
    public static $session_cart = 'session_cart';
    public static $session_credit = 'session_credit';

    public static $cart_book_id = 'cart_book_barcode';
    public static $cart_book_quantity = 'cart_book_quantity';

    public static $credit_name = 'cart_credit_name';
    public static $credit_amount = 'cart_credit_amount';

    public static function GetCart() {
        return $_SESSION[self::$session_cart];
    }

    public static function SetCart($cart) {
        $_SESSION[self::$session_cart] = $cart;
    }

    public static function GetCredit() {
        return $_SESSION[self::$session_credit];
    }

    public static function SetCredit($credits) {
        $_SESSION[self::$session_credit] = $credits;
    }

    public static function add_book($barcode, $isbn, $quantity) {
        $book = null;
        if ($barcode != -1) {
            $query = new WP_Query(
                array(
                    'post_type' => 'bookstore',
                    'meta_key' => '_cmb_resource_barcode',
                    'meta_value' => $barcode,
                    'numberposts' => 1,
                    'exact' => 1,
                )
            );
            while ($query->have_posts()):
                $query->the_post();
                global $post;
                $book = $post->ID;
                break;
            endwhile;
        }
        else if ($isbn != -1) {
            $query = new WP_Query(
                array(
                    'post_type' => 'bookstore',
                    'meta_key' => '_cmb_resource_isbn',
                    'meta_value' => $isbn,
                    'numberposts' => 1,
                    'exact' => 1,
                )
            );
            while ($query->have_posts()):
                $query->the_post();
                global $post;
                $book = $post->ID;
                break;
            endwhile;
        }
        if ($book != null) {
            self::add_book_to_cart($book, $quantity);
        }
    }

    public static function add_book_to_cart($book, $quantity) {
        if ($book == null) return;

        $cart = self::GetCart();
        if (!$cart) $cart = array();

        if (array_key_exists($book, $cart)) {
            $cart[$book][self::$cart_book_quantity] = $cart[$book][self::$cart_book_quantity] + $quantity;
        }
        else {
            $cart[$book] = self::create_cart_entry($book, $quantity);
        }
        self::SetCart($cart);
    }

    public static function create_cart_entry($book, $quantity) {
        return array(
            self::$cart_book_id => $book,
            self::$cart_book_quantity => $quantity
        );
    }

    public static function remove_book_from_cart($book, $quantity) {
        if ($book == null) return;

        $cart = self::GetCart();
        if (array_key_exists($book, $cart)) {
            $book_arr = $cart[$book];
            if ($quantity == 'all') {
                unset($cart[$book]);
            }
            else {
                $book_arr[self::$cart_book_quantity] = $book_arr[self::$cart_book_quantity] - $quantity;

                if ($book_arr[self::$cart_book_quantity] <= 0) {
                    unset($cart[$book]);
                } else {
                    $cart[$book] = $book_arr;
                }
            }
        }
        self::SetCart($cart);
    }

    public static function clear_cart() {
        unset($_SESSION[self::$session_cart]);
        $_SESSION[self::$session_cart] = array();
    }

    public static function clear_credit() {
        unset($_SESSION[self::$session_credit]);
        $_SESSION[self::$session_credit] = array();
    }

    public static function clear_all() {
        self::clear_cart();
        self::clear_credit();
    }

    public static function add_credit($credit_name, $amount) {
        if ($credit_name == null) return;

        $credit = self::GetCredit();
        if (!$credit) $credit = array();

        $credit[] = self::create_credit_entry($credit_name, $amount);

        self::SetCredit($credit);
    }

    public static function create_credit_entry($credit, $amount) {
        return array(
            self::$credit_name => $credit,
            self::$credit_amount => $amount
        );
    }

    public static function remove_credit($indice) {
        $credit = self::GetCredit();

        unset($credit[$indice]);
        $credit = array_values($credit);

        self::SetCredit($credit);
    }
}