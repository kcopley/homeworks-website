<?php
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 12/22/2016
 * Time: 2:20 PM
 */

//Sales Methods
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