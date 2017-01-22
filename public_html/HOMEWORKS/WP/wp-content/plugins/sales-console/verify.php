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
    public static $delete_transactions = 'delete_transactions';
    public static $reset_counters = 'verify_reset_counters';
    public static $verify_radios = 'verify_radios';
    public static $get_book_totals = 'get_book_totals';
    public static $set_db_name = 'set_db_name';

    public static $set_conference_name = 'set_conference_name';
    public static $set_shipping_margin = 'set_shipping_margin';
    public static $set_multiple_categories = 'set_multiple_categories';

    public static $set_bids = 'set_bids';
    public static $set_cids = 'set_cids';
    public static $set_tids = 'set_tids';
}

switch (page_action::GetAction()){
    case verify::$verify_books:
        verify_book_database();
        break;
    case verify::$verify_transactions:
        verify_transaction_database();
        break;
    case verify::$delete_transactions:
        delete_transaction_database();
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
    case verify::$set_conference_name:
        set_conference_name();
        break;
    case verify::$set_shipping_margin:
        set_shipping_margin();
        break;
    case verify::$set_multiple_categories:
        set_multiple_categories_option();
        break;
}

$list = new RenderList(
    new TableArr(
        new Row(
            new Column(width(40),
                new TableArr(width(100).cellpadding(0).cellspacing(2),
                    new Row(
                        new Column(align('right'),
                            new TextRender('Set Last Barcode ID')
                        ),
                        new Column(
                            new Form(
                                page_action::InputAction(verify::$set_bids),
                                new Input(style('margin: 6px;').type('text').name('bid').id('bid').value(get_option('_cmb_resource_lastBarcode'))),
                                button('Set')
                            )
                        )
                    ),
                    new Row(
                        new Column(align('right'),
                            new TextRender('Set Last Consigner ID')
                        ),
                        new Column(
                            new Form(
                                page_action::InputAction(verify::$set_cids),
                                new Input(style('margin: 6px;').type('text').name('cid').id('cid').value(get_option('_cmb_consigner_lastID'))),
                                button('Set')
                            )
                        )
                    ),
                    new Row(
                        new Column(align('right'),
                            new TextRender('Set Last Transaction ID')
                        ),
                        new Column(
                            new Form(
                                page_action::InputAction(verify::$set_tids),
                                new Input(style('margin: 6px;').type('text').name('tid').id('tid').value(get_option('_cmb_transaction_lastID'))),
                                button('Set')
                            )
                        )
                    ),
                    new Row(
                        new Column(align('right'),
                            new TextRender('Set Conference Name')
                        ),
                        new Column(
                            new Form(
                                page_action::InputAction(verify::$set_conference_name),
                                new Input(style('margin: 6px;').type('text').name(vars::$conference_name_option).id(vars::$conference_name_option).value(get_option(vars::$conference_name_option))),
                                button('Set')
                            )
                        )
                    ),
                    new Row(
                        new Column(align('right'),
                            new TextRender('Set Shipping Margin')
                        ),
                        new Column(
                            new Form(
                                page_action::InputAction(verify::$set_shipping_margin),
                                new Input(style('margin: 6px;').type('text').name(vars::$shipping_margin_option).id(vars::$shipping_margin_option).value(get_option(vars::$shipping_margin_option))),
                                button('Set')
                            )
                        )
                    ),
                    new Row(
                        new Column(align('right'),
                            new TextRender('Set Multiple Categories')
                        ),
                        new Column(
                            new Form(
                                page_action::InputAction(verify::$set_multiple_categories),
                                new Input(style('margin: 6px;').type('text').name(vars::$allow_multiple_categories_option).id(vars::$allow_multiple_categories_option).value(get_option(vars::$allow_multiple_categories_option))),
                                button('Set')
                            )
                        ),
                        new Column(
                            new TextRender('True = 1; False = anything else')
                        )
                    ),
                    new Row(
                        new Column(align('right'),
                            new TextRender('Get Book Totals')
                        ),
                        new Column(
                            new Form(
                                page_action::InputAction(verify::$get_book_totals),
                                button('Start')
                            )
                        )
                    )
                )
            ),
            new Column(width(40)
            ),
            new Column(width(15).align('center'),
                new Strong(new TextRender('Don\'t touch these lightly!')),
                new TableArr(width(100).cellpadding(0).cellspacing(5).align('right'),
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
                            new TextRender('Delete Transactions')
                        ),
                        new Column(width(12),
                            new Form(
                                page_action::InputAction(verify::$delete_transactions),
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

    $testISBN = Book::$props[Book::$isbn]->GetValue($id);
    if ($testISBN) return;

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
    Book::$props[Book::$online]->SetValue($id, 2);

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

    delete_post_meta($id, '_cmb_resource_u-sku');
    delete_post_meta($id, '_cmb_resource_sku');
}

function get_book_total() {
    $args = array(
        'numberposts' => -1,
        'posts_per_page' => -1,
        'post_type' => Book::$post_type,
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

function delete_transaction_database() {
    $args = array(
        'numberposts' => -1,
        'posts_per_page' => -1,
        'post_type' => Transaction::$post_type,
        'cache_results' => false
    );


    $query = new WP_Query($args);
    $counter = 0;
    while ($query->have_posts()):
        $query->the_post();
        global $post;
        $t = $post->ID;
        wp_delete_post($t);
        $counter++;
    endwhile;

    echo 'Completed '.$counter.' transactions.';
}

function verify_transaction_database() {
    $args = array(
        'numberposts' => 1000,
        'posts_per_page' => 1000,
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
    if (Transaction::$props[Transaction::$complete]->GetValue($id)) {
        return;
    }

    $title = get_the_title($id);
    if (!$title){
        wp_delete_post($id);
        return;
    }
    $order = array(
        'post_title' => $title,
        'post_status' => 'publish',
        'post_author' => 4,
        'post_type' => Transaction::$post_type
    );
    $postid = wp_insert_post($order);

    Transaction::$props[Transaction::$customer_name]->SetValue($postid, $title);
    Transaction::$props[Transaction::$complete]->SetValue($postid, 2);
    Transaction::$props[Transaction::$id]->SetValue($postid, $title);
    $invoice = get_post_meta($id, '_cmb_order_invoice', true);
    if ($invoice) {
        $invoice = substr($invoice, 5);
        Transaction::$props[Transaction::$invoiceid]->SetValue($postid, $invoice);
    }

    Transaction::$props[Transaction::$conference]->SetValue($postid, 2);

    $trans = get_post_meta($id, '_cmb_transfirst', true);
    Transaction::$props[Transaction::$transfirstid]->SetValue($postid, $trans);

    $addressphone = get_post_meta($id, '_cmb_customer_address', true);
    if ($addressphone) {
        if ($addressphone == 'No shipping address available (conference sale)') {
            Transaction::$props[Transaction::$conference]->SetValue($postid, 1);
        }
        else {
            $posphone = strpos($addressphone, 'Phone:');
            $split = substr($addressphone, $posphone + 6);
            $phone = trim($split);
            Transaction::$props[Transaction::$customer_phone]->SetValue($postid, $phone);

            $address = trim(substr($addressphone, 0, $posphone));
            Transaction::$props[Transaction::$customer_address]->SetValue($postid, $address);
        }
    }

    $email = get_post_meta($id, '_cmb_customer_email', true);
    if ($email)
        Transaction::$props[Transaction::$customer_email]->SetValue($postid, $email);

    $org = get_post_meta($id, '_cmb_customer_organization', true);
    if ($org)
        Transaction::$props[Transaction::$schoolname]->SetValue($postid, $org);

    date_default_timezone_set('America/Chicago');
    Transaction::$props[Transaction::$date]->SetValue($postid, get_the_date('Y-m-d', $id));

    $summ = get_post_meta($id, '_cmb_order_summary', true);
    if ($summ) {
        $books = preg_split('/\((.*?)\)/', $summ, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        if (!empty($books)) {
            $count = count($books);
            for ($i = 0; $i < $count; $i += 2) {
                $book = trim($books[$i]);
                $qty = trim($books[$i + 1]);
                if ($book == '' || $qty == '') continue;

                $bid = get_book_by_title($book);
                if ($bid && intval($qty)) {
                    Transaction::add_book_fast($postid, $bid, intval($qty));
                }
            }
        }
    }

    $taxtotal = str_replace('$', '', get_post_meta($id, '_cmb_purchase_tax', true));
    $price = str_replace('$', '', get_post_meta($id, '_cmb_purchase_price', true));
    $total = $price + $taxtotal;
    Transaction::$props[Transaction::$total]->SetValue($postid, $total);

    Transaction::add_payment($postid, checkout_payment::$payment_credit, $total);

    if ($taxtotal) {
        update_post_meta($postid, Transaction::$old_taxedamount_location, $taxtotal);
    }

    Transaction::$props[Transaction::$complete]->SetValue($id, 2);
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

function set_conference_name() {
    $name = $_REQUEST[vars::$conference_name_option];
    if (!$name) {
        $name = '';
    }
    update_option(vars::$conference_name_option, $name);
}

function set_multiple_categories_option() {
    $name = $_REQUEST[vars::$allow_multiple_categories_option];
    if (!$name) {
        $name = '';
    }
    update_option(vars::$allow_multiple_categories_option, $name);
}

function set_shipping_margin() {
    $name = $_REQUEST[vars::$shipping_margin_option];
    if (!$name) {
        $name = '';
    }
    update_option(vars::$shipping_margin_option, $name);
}

$list->Render();