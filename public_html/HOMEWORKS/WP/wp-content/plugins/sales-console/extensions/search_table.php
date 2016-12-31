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
        book_properties::$cost => true,
        book_properties::$price => true,
        book_properties::$quantity => true
    );

    $displays = func_get_args();
    if (count($displays) <= 0){
        $display = $default;
    }
    else {
        $display = $displays[0];
    }

    $consignerID = -1;
    if (count($displays) > 1) {
        $consignerID = $displays[1];
    }

    $table =
        new TableArr(id('formtable').width(100).border(0).cellspacing(0).cellpadding(0).style('margin: 10px 0 50px;'));

    if (array_key_exists(book_properties::$title, $display)){
        $table->add_object(
            new Column(width(25), new TextRender('Title'))
        );
    }
    if (array_key_exists(book_properties::$barcode, $display)){
        $table->add_object(
            new Column(new TextRender('Barcode'))
        );
    }
    if (array_key_exists(book_properties::$publisher, $display)){
        $table->add_object(
            new Column(new TextRender('Publisher'))
        );
    }
    if (array_key_exists(book_properties::$isbn, $display)){
        $table->add_object(
            new Column(new TextRender('ISBN'))
        );
    }
    if (array_key_exists(book_properties::$cost, $display)){
        $table->add_object(
            new Column(new TextRender('Cost'))
        );
    }
    if (array_key_exists(book_properties::$MSRP, $display)){
        $table->add_object(
            new Column(new TextRender('MSRP'))
        );
    }
    if (array_key_exists(book_properties::$price, $display)){
        $table->add_object(
            new Column(new TextRender('Price'))
        );
    }
    if (array_key_exists(book_properties::$quantity, $display)){
        $table->add_object(
            new Column(new TextRender('Quantity'))
        );
    }
    if (array_key_exists(book_properties::$condition, $display)){
        $table->add_object(
            new Column(new TextRender('Condition'))
        );
    }
    if (array_key_exists(book_properties::$availability, $display)){
        $table->add_object(
            new Column(new TextRender('Available'))
        );
    }
    if (array_key_exists(book_properties::$hasimage, $display)){
        $table->add_object(
            new Column(width(5).align('center'),
                new TextRender('Image?'))
        );
    }
    if ($consignerID != -1){
        $table->add_object(
            new Column(width(5).align('center'),
                new TextRender('ADd'))
        );
    }

    $table->add_object(
        new Row(colspan(count($display)),
            new HR()));

    $query = QueryBook();
    while ($query->have_posts()):
        $query->the_post();
        global $post;
        $product_id = $post->ID;
        $table->add_object(
            book_display($display, $product_id));
    endwhile;
    return $table;
}

function book_display($display, $id) {
    $row = new Row();

    if (array_key_exists(book_properties::$title, $display)){
        if (array_key_exists(book_properties::$selectable, $display)){
            $row->add_object(new Column(
                new Form(method('POST').name('select_book'),
                    new Input(type('hidden').name(selection::$book).value($id)),
                    book_request::Store(),
                    page_action::InputAction(action_types::$select_book),
                    new Input(classType('button').type('submit').name('button').value(get_the_title($id)).style('background:none!important; border:none; 
                        padding:0!important; font-family:arial,sans-serif; color:#069; cursor:pointer;'))
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
            new TextRender(book_properties::get_book_cost($id))
        ));
    }
    if (array_key_exists(book_properties::$MSRP, $display)){
        $row->add_object(new Column(
            new TextRender(book_properties::get_book_msrp($id))
        ));
    }
    if (array_key_exists(book_properties::$price, $display)){
        $row->add_object(new Column(
            new TextRender(book_properties::get_book_saleprice($id))
        ));
    }
    if (array_key_exists(book_properties::$quantity, $display)){
        $row->add_object(new Column(
            new TextRender(book_properties::get_consigner_count($id))
        ));
    }
    if (array_key_exists(book_properties::$condition, $display)){
        $row->add_object(new Column(
            new TextRender(book_properties::get_book_condition($id))
        ));
    }
    if (array_key_exists(book_properties::$availability, $display)){
        $row->add_object(new Column(
            new TextRender(book_properties::get_book_availablity($id))
        ));
    }
    if (array_key_exists(book_properties::$hasimage, $display)){
        $color = 'red';
        $text = 'No';
        if (book_properties::get_book_image($id)){
            $text = 'Yes';
            $color = 'green';
        }
        $row->add_object(
            new Column(width(5).align('center'),
                new Form(method('post').id($id).name($id),
                    page_action::InputAction(action_types::$add_image_to_book),
                    book_request::Store(),
                    new Input(id(selection::$book).type('hidden').name(selection::$book).value($id)),
                    new Input(id($id).type('button').classType('upload_image_button').style('color: '.$color.';').value($text)),
                    new Input(type('hidden').name(book_request::$image_set).id(book_request::$image_set))
                )
            )
        );
    }
    return $row;
}

?>





