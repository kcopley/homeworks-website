<?php
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 12/30/2016
 * Time: 2:32 PM
 */

/**
 * Takes an input array of the properties from the static class book_properties.
 * If the input array contains one of them, it will display it - the value of the key does not matter.
 * @return TableArr
 */
function search_books()
{
    $default = array(
        book_properties::$title => true,
        book_properties::$barcode => true,
        book_properties::$isbn => true,
        book_properties::$publisher => true,
        //book_properties::$cost => true,
        book_properties::$price => true,
        book_properties::$quantity => true
    );

    $displays = func_get_args();
    $props = array();

    $additionalRenders = new RenderList();
    $counter = 0;
    foreach ($displays as $disp) {
        if (method_exists($disp, 'Render')) {
            $additionalRenders->add_object($disp);
        }
        else {
            $props[$counter] = $disp;
        }
    }

    if (count($props) > 0) {
        $display = $props[0];
    }
    else {
        $display = $default;
    }

    $consignerID = -1;
    if (array_key_exists(book_properties::$consigner_id, $display)) {
        $consignerID = $display[book_properties::$consigner_id];
    }

    $table =
        new TableArr(id('formtable').width(100).border(0).cellspacing(0).cellpadding(0).style('margin: 10px 0 5px;'));

    if (array_key_exists(book_properties::$title, $display)){
        $table->add_object(
            new Column(width(30).style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Title'))
        );
    }
    if (array_key_exists(book_properties::$barcode, $display)){
        $table->add_object(
            new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Barcode'))
        );
    }
    if (array_key_exists(book_properties::$publisher, $display)){
        $table->add_object(
            new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Publisher'))
        );
    }
    if (array_key_exists(book_properties::$isbn, $display)){
        $table->add_object(
            new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('ISBN'))
        );
    }
    if (array_key_exists(book_properties::$cost, $display)){
        $table->add_object(
            new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Cost'))
        );
    }
    if (array_key_exists(book_properties::$MSRP, $display)){
        $table->add_object(
            new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('MSRP'))
        );
    }
    if (array_key_exists(book_properties::$price, $display)){
        $table->add_object(
            new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Price'))
        );
    }
    if (array_key_exists(book_properties::$quantity, $display)){
        $table->add_object(
            new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px').align('center'), new TextRender('Quantity'))
        );
    }
    if (array_key_exists(book_properties::$condition, $display)){
        $table->add_object(
            new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px').align('center'), new TextRender('Condition'))
        );
    }
    if (array_key_exists(book_properties::$availability, $display)){
        $table->add_object(
            new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px').align('center'), new TextRender('Available'))
        );
    }
    if (array_key_exists(book_properties::$hasimage, $display)){
        $table->add_object(
            new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px').width(5).align('center'),
                new TextRender('Image?'))
        );
    }
    if ($consignerID != -1){
        $table->add_object(
            new Column(width(5).align('center').style('padding-bottom: 8px; font-weight: bold; font-size: 14px'),
                new TextRender('Add'))
        );
    }

    $table->add_object(
        new Row(style('border: none; padding-bottom: 8px; height: 1px;'),
            new Column(colspan(count($display).style('padding-bottom: 8px;')),
                new HR(style('margin: 0px;')))));

    $display_post_num = book_request::GetBooksPerPage();
    $current_page = book_request::GetCurrentPage();
    if (!$current_page) $current_page = 1;

    $offset = ($current_page - 1) * $display_post_num;
    if ($offset < 0) $offset = 0;

    $query = QueryBook($display_post_num, $offset);

    while ($query->have_posts()):
        $query->the_post();
        global $post;
        $product_id = $post->ID;
        $table->add_object(
            book_display($display, $product_id, $consignerID));
        $table->add_object(
            new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan(count($display).style('padding-bottom: 0px;')),
                    new HR(style('margin: 0px;')))
            ));
    endwhile;

    $renderlist = new RenderList();
    $renderlist->add_object($table);

    $pageTable = new TableArr(id('formtable').width(100).border(0).cellspacing(0).cellpadding(0).style('padding-top: 5px; margin: 5px 0 5px;'));
    if ($query->found_posts > $display_post_num) {
        $row = new Row();
        $row->add_object(new Column(width(82)));
        $row->add_object(
            new Column(align('center').width(6).style('padding: 3px;'),
                new H4(new TextRender('Page: ' . $current_page))
            )
        );
        if ($current_page > 1) {
            $row->add_object(
                new Column(align('center').width(6).style('padding: 3px;'),
                    new Form(
                        book_request::Store(),
                        page_action::InputAction(action_types::$search),
                        book_request::InputCurrentPage($current_page - 1),
                        GetAdditionalRenders($additionalRenders),
                        button('Previous')
                    )
                )
            );
        }
        else {
            $row->add_object(new Column(align('center').width(6).style('padding: 3px;')));
        }
        if ($current_page * $display_post_num < $query->found_posts) {
            $row->add_object(
                new Column(align('center').width(6).style('padding: 3px;'),
                    new Form(
                        book_request::Store(),
                        page_action::InputAction(action_types::$search),
                        book_request::InputCurrentPage($current_page + 1),
                        GetAdditionalRenders($additionalRenders),
                        button('Next')
                    )
                )
            );
        }
        else {
            $row->add_object(new Column(align('center').width(6).style('padding: 3px;')));
        }
        $pageTable->add_object(
            $row
        );
    }
    $renderlist->add_object($pageTable);

    return $renderlist;
}

function GetAdditionalRenders($additionalRenders) {
    if ($additionalRenders != null) {
        return $additionalRenders;
    }
    return new RenderList();
}

function book_display($display, $id, $consignerID) {
    $row = new Row();

    if (array_key_exists(book_properties::$title, $display)){
        if (array_key_exists(book_properties::$selectable, $display)){
            $row->add_object(new Column(
                new Form(method('POST').name('select_book'),
                    selection::InputBook($id),
                    book_request::Store(),
                    page_action::InputAction(action_types::$select_book),
                    new Input(classType('button').type('submit').name('button').value(get_the_title($id)).style('background:none!important; border:none; 
                        padding:0!important; font-family:arial,sans-serif; color:#069; box-shadow: 0 0px 0 #ccc; cursor:pointer;'))
                )
            ));
        }
        else {
            $row->add_object(new Column(
                new TextRender(book_properties::get_book_title($id))
            ));
        }
    }
    if (array_key_exists(book_properties::$barcode, $display)){
        $row->add_object(new Column(
            new TextRender(book_properties::get_book_barcode($id))
        ));
    }
    if (array_key_exists(book_properties::$publisher, $display)){
        $row->add_object(new Column(
            new TextRender(book_properties::get_book_publisher($id))
        ));
    }
    if (array_key_exists(book_properties::$isbn, $display)){
        $row->add_object(new Column(
            new TextRender(book_properties::get_book_isbn($id))
        ));
    }
    if (array_key_exists(book_properties::$cost, $display)){
        $row->add_object(new Column(
            new TextRender('$'.book_properties::get_book_cost($id))
        ));
    }
    if (array_key_exists(book_properties::$MSRP, $display)){
        $row->add_object(new Column(
            new TextRender('$'.book_properties::get_book_msrp($id))
        ));
    }
    if (array_key_exists(book_properties::$price, $display)){
        $row->add_object(new Column(
            new TextRender('$'.book_properties::get_book_saleprice($id))
        ));
    }
    if (array_key_exists(book_properties::$quantity, $display)){
        $row->add_object(new Column(align('center'),
            new TextRender(book_properties::get_consigner_count($id))
        ));
    }
    if (array_key_exists(book_properties::$condition, $display)){
        $row->add_object(new Column(align('center'),
            new TextRender(book_properties::get_book_condition($id))
        ));
    }
    if (array_key_exists(book_properties::$availability, $display)){
        $row->add_object(new Column(align('center'),
            new TextRender(book_properties::get_book_availablity($id))
        ));
    }
    if (array_key_exists(book_properties::$hasimage, $display)){
        $row->add_object(
            new Column(width(5).align('center'),
                book_properties::get_image_form($id, action_types::$add_image_to_book_search)
            )
        );
    }
    if (array_key_exists(book_properties::$consigner_id, $display)){
        $row->add_object(
            new Column(width(5).align('center'),
                new Form(
                    page_action::InputAction(action_types::$add_book_to_consigner),
                    book_request::Store(),
                    consigner_request::Store(),
                    selection::InputBook($id),
                    selection::InputConsigner($consignerID),
                    button('Add')
                )
            )
        );
    }
    return $row;
}

function request_form_books() {
    if (count(func_get_args()) > 0) {
        $used = true;
    }
    else {
        $used = false;
    }
    return new Form(action('').id('library').method('post').name('library_search'),
        new Column(width(15).align('left').valign('top').style('text-align: left;'),
            new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100),
                new Row (
                    new Column(align('right').width(7), new Label(new TextRender('Title:'))),
                    new Column(width(93).style('padding-left: 5px;'), book_request::InputTitle())
                ),
                new Row (
                    new Column(align('right').width(7), new Label(new TextRender('Barcode:'))),
                    new Column(width(93).style('padding-left: 5px;'), book_request::InputBarcode())
                ),
                new Row (
                    new Column(align('right').width(7), new Label(new TextRender('ISBN:'))),
                    new Column(width(93).style('padding-left: 5px;'), book_request::InputISBN())
                ),
                new Row (
                    new Column(align('right').width(7), new Label(new TextRender('Publisher:'))),
                    new Column(width(93).style('padding-left: 5px;'), book_request::InputPublisher())
                ),
                new Row (
                    new Column(align('right').width(7), new Label(new TextRender('Price:'))),
                    new Column(width(93).style('padding-left: 5px;'), book_request::InputPrice())
                ),
                new Row (
                    new Column(width(35).style('padding-bottom: 15px; padding-top: 10px;'),
                        page_action::InputAction(action_types::$search),
                        book_request::InputCurrentPage(1),
                        button('Search')
                    )
                )
            )
        ),
        new Column(width(18).align('left').valign('top').style('text-align: left;'),
            new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100),
                new Row(
                    new Column(align('right').width(40), new Label('', new TextRender('Department:'))),
                    new Column(style('padding-left: 5px;').width(60), new TextRender(wp_dropdown_categories(array(
                        'hide_empty' => 0,
                        'name' => book_request::$department,
                        'hierarchical' => true,
                        'show_option_all' => 'Choose one',
                        'echo' => 0
                    ))))
                ),
                new Row(
                    new Column(align('right').width(40), new Label(new TextRender('Availability:'))),
                    new Column(style('padding-left: 5px;').width(60),
                        new Input(type('radio').name(book_request::$availability).value('Active')),
                        new TextRender('Active '),
                        new Input(type('radio').name(book_request::$availability).value('Inactive')),
                        new TextRender('Inactive '))
                ),
                new Row(
                    new Column(align('right').width(40), new Label(new TextRender('Condition:'))),
                    GetUsedNewRadio($used)
                ),
                new Row(
                    new Column(align('right').width(40), new Label(new TextRender('Books / Page:'))),
                    new Column(width(60).style('padding-left: 5px;'),
                        book_request::InputBooksPerPage()
                    )
                )
            )
        )
    );
}

function GetUsedNewRadio($usedValue) {
    $column = new Column(style('padding-left: 5px;').width(60));
    $column->add_object(
        new Input(type('radio').name(book_request::$condition).value('New')));
    $column->add_object(new TextRender('New '));

    if ($usedValue) {
        $column->add_object(
            new Input(type('radio').name(book_request::$condition).value('Used').checkedAttr('true')));
        $column->add_object(new TextRender('Used '));
    }
    else {
        $column->add_object(
            new Input(type('radio').name(book_request::$condition).value('Used')));
        $column->add_object(new TextRender('Used '));
    }
    return $column;
}
?>





