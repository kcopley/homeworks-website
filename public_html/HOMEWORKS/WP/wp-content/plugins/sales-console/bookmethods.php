<?php
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 12/22/2016
 * Time: 2:20 PM
 */

class checkout_constants {
    public static $quantity = 'query_checkout_quantity';
    public static $isbn = 'query_checkout_isbn';
    public static $barcode = 'query_checkout_barcode';
    public static $credit_name = 'query_credit_name';
}
//Sales Methods
function request_sale_quantity() { return 'query_quantity'; }
function request_sale_isbn() { return 'query_isbn'; }
function request_sale_id() { return 'query_id'; }
function request_sale_credit_name() { return 'query_credit_name'; }
function request_sale_credit() { return 'query_credit'; }

function request_sale_clear_action() { return 'sale_clear_action'; }

function request_sale_remove_credit_action() { return 'remove_credit_action'; }
function request_sale_credit_indice() { return 'remove_credit_indice'; }

function request_sale_remove_amount() { return 'request_sale_remove_amount'; }
function sale_remove_all() { return 'sale_remove_all'; }
function sale_remove_one() { return 'sale_remove_one'; }

function request_sale_session_cart() { return 'request_sale_session_cart'; }
function request_sale_session_quantity() { return 'request_sale_session_quantity'; }
function request_sale_session_credit() { return 'request_sale_session_credit'; }

function request_sale_add_item_action() { return 'request_sale_add_item_action'; }
function request_sale_remove_item_action() { return 'request_sale_remove_item_action'; }
function request_sale_credit_action() { return 'request_sale_credit_action'; }
function request_processing() { return 'request_processing'; }

function sales_payment_type() { return 'sales_payment_type'; }
function sales_payment_credit() { return 'sales_payment_credit'; }
function sales_payment_cash() { return 'sales_payment_cash'; }
function sales_payment_check() { return 'sales_payment_check'; }
function sales_payment_phone() { return 'sales_payment_phone'; }

function sales_customer_name() { return 'customer_name'; }
function sales_customer_email() { return 'customer_email'; }
function sales_customer_cash_payment_amount() { return 'sales_customer_cash_payment_amount'; }

function request_sales_Auth() { return 'Auth'; }
function request_sales_CCResponse() { return 'CVV2ResponseMsg'; }
function sales_AuthCodeDeclined() { return 'Declined'; }
function sales_Notes() { return 'Notes'; }
function sales_purchase_error() { return 'M'; }
function sales_incorrect_number() { return 'N'; }

//General constants
function get_consigner_owner() { return '_cmb_consigner_owner'; }
function get_consigner_owner_id() { return get_option(get_consigner_owner()); }
function set_consigner_owner() { update_option('_cmb_consigner_owner', 66755); }