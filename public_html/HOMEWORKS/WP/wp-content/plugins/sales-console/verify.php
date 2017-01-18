<?php

include_once "includes.php";
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 1/17/2017
 * Time: 10:53 PM
 */

switch (page_action::GetAction()){
    case action_types::$verify_books:
        echo "Starting verification.<br>";
        verify_book_database();
        echo "Verification complete. Processed ".$_SESSION['num_books_processed']." books in total.";
        break;
    case action_types::$verify_consigners:
        echo "Starting verification.<br>";
        verify_consigner_database();
        echo "Verification complete. Processed ".$_SESSION['num_consigners_processed']." books in total.";
        break;
    case action_types::$verify_transactions:
        echo "Starting verification.<br>";
        verify_transaction_database();
        echo "Verification complete. Processed ".$_SESSION['num_transactions_processed']." books in total.";
        break;
    case action_types::$set_bids:
        set_bids();
        break;
    case action_types::$set_cids:
        set_cids();
        break;
    case action_types::$set_tids:
        set_tids();
        break;
    case action_types::$reset_counters:
        reset_counters();
        break;
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

function reset_counters() {
    $_SESSION['num_books_processed'] = 0;
    $_SESSION['num_consigners_processed'] = 0;
    $_SESSION['num_transactions_processed'] = 0;
}

$list = new RenderList(
    new TableArr(
        new Row(
            new Column(
                new Form(
                    page_action::InputAction(action_types::$verify_books),
                    new Input(type('text').name('numbooks').id('numbooks').value(50)),
                    button('Verify Books')
                )
            ),
            new Column(width(1)),
            new Column(
                new TextRender('Processed '.$_SESSION['num_books_processed'].' books in total.')
            )
        ),
        new Row(
            new Column(
                new Form(
                    page_action::InputAction(action_types::$verify_consigners),
                    new Input(type('text').name('numbooks').id('numconsigners').value(200)),
                    button('Verify Consigners')
                )
            )
        ),
        new Row(
            new Column(
                new Form(
                    page_action::InputAction(action_types::$verify_transactions),
                    new Input(type('text').name('numbooks').id('numtransactions').value(200)),
                    button('Verify Transactions')
                )
            )
        ),
        new Row(
            new Column(
                new Form(
                    page_action::InputAction(action_types::$set_bids),
                    new Input(type('text').name('bid').id('bid')),
                    button('Set Last Barcode ID')
                )
            )
        ),
        new Row(
            new Column(
                new Form(
                    page_action::InputAction(action_types::$set_cids),
                    new Input(type('text').name('cid').id('cid')),
                    button('Set Last Consigner ID')
                )
            )
        ),
        new Row(
            new Column(
                new Form(
                    page_action::InputAction(action_types::$set_tids),
                    new Input(type('text').name('tid').id('tid')),
                    button('Set Last Transaction ID')
                )
            )
        ),
        new Row(
            new Column(
                new Form(
                    page_action::InputAction(action_types::$reset_counters),
                    button('Reset Counters')
                )
            )
        )
    )
);

function verify_book_database() {
    $books = $_REQUEST['numbooks'];
    $current = $_SESSION['num_books_processed'];

    $args = array(
        'numberposts' => $books,
        'posts_per_page' => $books,
        'post_type' => 'bookstore',
        'offset' => $current,
        'cache_results' => false
    );


    $query = new WP_Query($args);

    while ($query->have_posts()):
        $query->the_post();
        global $post;
        $book = $post->ID;
        verify_book($book);
    endwhile;

    $_SESSION['num_books_processed'] = $books + $current;
}

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

function getVal($id, $s) {
    return get_post_meta($id, $s, true);
}

function verify_book($id) {
    $title = get_the_title($id);
    if (!$title){
        wp_delete_post($id);
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
    if (!$price) $price = -1.0;
    $price = str_replace('$', '', $price);
    Book::$props[Book::$price]->SetValue($id, $price);

    $cost = getVal($id, '_cmb_resource_cost');
    if (!$cost) $cost = -1.0;
    $cost = str_replace('$', '', $cost);
    Book::$props[Book::$cost]->SetValue($id, $cost);

    $available = getVal($id, '_cmb_resource_available');
    if (!$available) $available = 'NOTSET';
    Book::$props[Book::$online]->SetValue($id, $available);

    $MSRP = getVal($id, '_cmb_resource_MSRP');
    if (!$MSRP) $MSRP = -1.0;
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
    Book::$props[Book::$condition]->SetValue($id, $condition);

    Book::set_consigners($id, array());
    $quantity = getVal($id, '_cmb_resource_quantity');
    for ($i = 0; $i < $quantity; $i++) {
        Book::add_book($id, get_consigner_owner_id());
    }
    delete_post_meta($id, '_cmb_resource_quantity');

    $count = Book::get_consigner_count($id);
    if ($count <= 0) {
        Book::$props[Book::$available]->SetValue($id, 'Inactive');
    }
    else {
        Book::$props[Book::$available]->SetValue($id, 'Active');
    }
}

function verify_consigner_database() {

}

function verify_transaction_database() {

}

$list->Render();