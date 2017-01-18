<?php

include_once "includes.php";
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 1/17/2017
 * Time: 10:53 PM
 */

class verify {
    public static $verify_books = 'verify_books';
    public static $verify_consigners = 'verify_consigners';
    public static $verify_transactions = 'verify_transactions';
    public static $reset_counters = 'verify_reset_counters';
    public static $verify_radios = 'verify_radios';
    public static $get_book_totals = 'get_book_totals';
    public static $set_db_name = 'set_db_name';

    public static $set_bids = 'set_bids';
    public static $set_cids = 'set_cids';
    public static $set_tids = 'set_tids';
}

switch (page_action::GetAction()){
    case verify::$verify_books:
        verify_book_database();
        break;
    case verify::$verify_consigners:
        verify_consigner_database();
        break;
    case verify::$verify_transactions:
        verify_transaction_database();
        break;
    case verify::$verify_radios:
        verify_radios();
        break;
    case verify::$get_book_totals:
        get_book_total();
        break;
    case verify::$set_bids:
        set_bids();
        break;
    case verify::$set_cids:
        set_cids();
        break;
    case verify::$set_tids:
        set_tids();
        break;
    case verify::$set_db_name:
        set_db_website();
        break;
}

$list = new RenderList(
    new TableArr(
        new Row(
            new Column(width(30),
                new TableArr(width(100).cellpadding(0).cellspacing(5),
                    new Row(
                        new Column(width(10).align('right'),
                            new TextRender('Update Books')
                        ),
                        new Column(width(16),
                            new Form(
                                page_action::InputAction(verify::$verify_books),
                                button('Start')
                            )
                        )
                    ),
                    new Row(
                        new Column(width(10).align('right'),
                            new TextRender('Verify Radios')
                        ),
                        new Column(width(12),
                            new Form(
                                page_action::InputAction(verify::$verify_radios),
                                button('Start')
                            )
                        )
                    ),
                    new Row(
                        new Column(width(10).align('right'),
                            new TextRender('Update Consigners')
                        ),
                        new Column(width(12),
                            new Form(
                                page_action::InputAction(verify::$verify_consigners),
                                button('Start')
                            )
                        )
                    ),
                    new Row(
                        new Column(width(10).align('right'),
                            new TextRender('Update Transactions')
                        ),
                        new Column(width(12),
                            new Form(
                                page_action::InputAction(verify::$verify_transactions),
                                button('Start')
                            )
                        )
                    ),
                    new Row(
                        new Column(width(10).align('right'),
                            new TextRender('Get Book Totals')
                        ),
                        new Column(width(12),
                            new Form(
                                page_action::InputAction(verify::$get_book_totals),
                                button('Start')
                            )
                        )
                    ),
                    new Row(
                        new Column(width(10).align('right'),
                            new TextRender('Set Database Website')
                        ),
                        new Column(width(12),
                            new Form(
                                page_action::InputAction(verify::$set_db_name),
                                button('Start')
                            )
                        )
                    )
                )
            ),
            new Column(width(20),
                new TableArr(width(100).cellpadding(3).cellspacing(5),
                    new Row(
                        new Column(width(10).align('right'),
                            new TextRender('Set Last Barcode ID')
                        ),
                        new Column(
                            new Form(
                                page_action::InputAction(verify::$set_bids),
                                new Input(style('margin: 6px;').type('text').name('bid').id('bid')),
                                button('Set')
                            )
                        )
                    ),
                    new Row(
                        new Column(width(10).align('right'),
                            new TextRender('Set Last Consigner ID')
                        ),
                        new Column(
                            new Form(
                                page_action::InputAction(verify::$set_cids),
                                new Input(style('margin: 6px;').type('text').name('cid').id('cid')),
                                button('Set')
                            )
                        )
                    ),
                    new Row(
                        new Column(width(10).align('right'),
                            new TextRender('Set Last Transaction ID')
                        ),
                        new Column(
                            new Form(
                                page_action::InputAction(verify::$set_tids),
                                new Input(style('margin: 6px;').type('text').name('tid').id('tid')),
                                button('Set')
                            )
                        )
                    )
                )
            ),
            new Column(width(50)
            )
        )
    )
);

static $name = 'book_title';
static $barcode = 'book_barcode';
static $cost = 'book_cost';
static $price = 'book_price';
static $msrp = 'book_msrp';
static $publisher = 'book_publisher';
static $isbn = 'book_isbn';
static $condition = 'book_condition';
static $available = 'book_availability';
static $online = 'book_online';

function verify_book_database() {
    $args = array(
        'numberposts' => -1,
        'posts_per_page' => -1,
        'post_type' => 'bookstore',
        'cache_results' => false
    );


    $query = new WP_Query($args);
    $counter = 0;
    while ($query->have_posts()):
        $query->the_post();
        global $post;
        $book = $post->ID;
        verify_book($book);
        $counter++;
    endwhile;

    echo 'Completed '.$counter.' books.';
}

function verify_radios() {
    //$books = $_REQUEST['numbooks'];
    //$current = $_SESSION['num_books_processed'];

    $args = array(
        'numberposts' => -1,
        'posts_per_page' => -1,
        'post_type' => 'bookstore',
        'cache_results' => false
    );


    $query = new WP_Query($args);
    $counter = 0;
    while ($query->have_posts()):
        $query->the_post();
        global $post;
        $book = $post->ID;
        verify_radio($book);
        $counter++;
    endwhile;

    echo 'Completed '.$counter.' books.';
}

function getVal($id, $s) {
    return get_post_meta($id, $s, true);
}

function verify_radio($id) {
    $var = Book::$props[Book::$online]->GetValue($id);
    if (!$var) $var = 1;
    else $var = 2;
    Book::$props[Book::$online]->SetValue($id, $var);

    $count = Book::get_consigner_count($id);
    if ($count <= 0) {
        Book::$props[Book::$available]->SetValue($id, 1);
    }
    else {
        Book::$props[Book::$available]->SetValue($id, 2);
    }

    $var = Book::$props[Book::$condition]->GetValue($id);
    if (!$var) $var = 1;
    else $var = 2;
    Book::$props[Book::$condition]->SetValue($id, $var);
}

function verify_book($id) {
    $title = get_the_title($id);
    if (!$title){
        wp_delete_post($id);
        $_REQUEST['num_books_deleted']++;
        return;
    }
    Book::$props[Book::$name]->SetValue($id, $title);

    $barcode = getVal($id, '_cmb_resource_barcode');
    if (!$barcode) {
        $barcode = -1;
    }
    Book::$props[Book::$barcode]->SetValue($id, $barcode);

    $publisher = getVal($id, '_cmb_resource_publisher');
    if (!$publisher) $publisher = 'NOTSET';
    Book::$props[Book::$publisher]->SetValue($id, $publisher);

    $price = getVal($id, '_cmb_resource_price');
    if (!$price) $price = 'NOTSET';
    $price = str_replace('$', '', $price);
    Book::$props[Book::$price]->SetValue($id, $price);

    $cost = getVal($id, '_cmb_resource_cost');
    if (!$cost) $cost = 'NOTSET';
    $cost = str_replace('$', '', $cost);
    Book::$props[Book::$cost]->SetValue($id, $cost);

    $available = getVal($id, '_cmb_resource_available');
    if (!$available) $available = 'NOTSET';
    else if ($available == 'Inactive') $available = 1;
    else if ($available == 'Active') $available = 2;
    Book::$props[Book::$online]->SetValue($id, $available);

    $MSRP = getVal($id, '_cmb_resource_MSRP');
    if (!$MSRP) $MSRP = 'NOTSET';
    $MSRP = str_replace('$', '', $MSRP);
    Book::$props[Book::$msrp]->SetValue($id, $MSRP);

    $isbn = getVal($id, '_cmb_resource_u-sku');
    if (!$isbn)
        $isbn = getVal($id, '_cmb_resource_sku');
    if (!$isbn)
        $isbn = 'NOTSET';
    Book::$props[Book::$isbn]->SetValue($id, $isbn);
    delete_post_meta($id, '_cmb_resource_u-sku');
    delete_post_meta($id, '_cmb_resource_sku');

    $condition = getVal($id, '_cmb_resource_condition');
    if (!$condition) $condition = 'NOTSET';
    else if ($condition == 'Used') $condition = 1;
    else if ($condition == 'New') $condition = 2;
    Book::$props[Book::$condition]->SetValue($id, $condition);

    Book::set_consigners($id, array());
    $quantity = getVal($id, '_cmb_resource_quantity');
    for ($i = 0; $i < $quantity; $i++) {
        Book::add_book($id, get_consigner_owner_id());
    }
    delete_post_meta($id, '_cmb_resource_quantity');

    $count = Book::get_consigner_count($id);
    if ($count <= 0) {
        Book::$props[Book::$available]->SetValue($id, 1);
    }
    else {
        Book::$props[Book::$available]->SetValue($id, 2);
    }
}

function get_book_total() {
    $args = array(
        'numberposts' => -1,
        'posts_per_page' => -1,
        'post_type' => 'bookstore',
        'cache_results' => false
    );


    $query = new WP_Query($args);

    $totalCost = 0;
    $totalPrice = 0;
    $totalBooksNoCost = 0;
    $totalBooksNoPrice = 0;
    while ($query->have_posts()):
        $query->the_post();
        global $post;
        $book = $post->ID;

        $val = Book::$props[Book::$cost]->GetValue($book);
        $quantity = Book::$props[Book::$quantity]->GetValue($book);
        if (!$val) {
            $totalBooksNoCost++;
        }
        else {
            $totalCost += $val * $quantity;
        }
        $val = Book::$props[Book::$price]->GetValue($book);
        if (!$val) {
            $totalBooksNoPrice++;
        }
        else {
            $totalPrice += $val * $quantity;
        }
    endwhile;

    echo 'Total cost of inventory: $'.number_format($totalCost, 2);
    echo "<br>";
    echo 'Total sale price of inventory: $'.number_format($totalPrice, 2);
    echo "<br>";
    echo $totalBooksNoCost.' books had no cost attached.';
    echo "<br>";
    echo $totalBooksNoPrice.' books had no sale price attached.';
    echo "<br>";
}

function verify_consigner_database() {}

function verify_transaction_database() {
    $args = array(
        'numberposts' => -1,
        'posts_per_page' => -1,
        'post_type' => 'purchases',
        'cache_results' => false
    );


    $query = new WP_Query($args);
    $counter = 0;
    while ($query->have_posts()):
        $query->the_post();
        global $post;
        $t = $post->ID;
        verify_transaction($t);
        $counter++;
    endwhile;

    echo 'Completed '.$counter.' transactions.';
}

function verify_transaction($id) {
    $title = get_the_title($id);
    if (!$title){
        wp_delete_post($id);
        return;
    }
    Transaction::$props[Transaction::$id]->SetValue($id, $title);
/*
    post-type = purchases
    update_post_meta($postid, '_cmb_order_invoice', $invoice);
    update_post_meta($postid, '_cmb_transfirst', $transid);
    update_post_meta($postid, '_cmb_customer_address', $address);
    update_post_meta($postid, '_cmb_customer_email', $email);
    update_post_meta($postid, '_cmb_customer_organization', $school);
    update_post_meta($postid, '_cmb_order_summary', $summary);
    update_post_meta($postid, '_cmb_purchase_price', $purchaseprice);
    update_post_meta($postid, '_cmb_purchase_tax', $ordertax);
    _cmb_payment_type
    _cmb_customer_payment
*/
}

function set_bids() {
    update_option('_cmb_resource_lastBarcode', $_REQUEST['bid']);
}

function set_cids() {
    update_option('_cmb_consigner_lastID', $_REQUEST['cid']);
}

function set_tids() {
    update_option('_cmb_transaction_lastID', $_REQUEST['tid']);
}

function set_db_website() {
    // Connect to your MySQL database.
    $hostname = "localhost";
    $username = "homewot5_admin";
    $password = "2012homeworksbooks";
    $database = "homewot5_wpsite";

    mysql_connect($hostname, $username, $password);

// The find and replace strings.
    $find = 'localhost/homeworks/public_html/?';
    $replace = 'localhost/homeworks/public_html/index.php?';

    $loop = mysql_query("
    SELECT
        concat('UPDATE ',table_schema,'.',table_name, ' SET ',column_name, '=replace(',column_name,', ''{$find}'', ''{$replace}'');') AS s
    FROM
        information_schema.columns
    WHERE
        table_schema = '{$database}'")
    or die ('Cant loop through dbfields: ' . mysql_error());

    while ($query = mysql_fetch_assoc($loop))
    {
        mysql_query($query['s']);
    }
}

$list->Render();