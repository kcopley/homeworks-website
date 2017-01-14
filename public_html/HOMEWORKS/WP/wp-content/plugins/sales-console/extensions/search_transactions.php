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
function search_transactions()
{
    $default = array(
        transaction_properties::$id => true,
        transaction_properties::$date => true,
        transaction_properties::$customer_name => true,
        transaction_properties::$total => true,
        transaction_properties::$transfirstid => true,
        transaction_properties::$taxrate => true,
        transaction_properties::$removeable => true,
        transaction_properties::$completed => true,
        transaction_properties::$printable => true,
        transaction_properties::$selectable => true
    );

    $displays = func_get_args();
    if (count($displays) <= 0){
        $display = $default;
    }
    else {
        $display = $displays[0];
    }

    $table =
        new TableArr(id('formtable').width(100).border(0).cellspacing(0).cellpadding(0).style('margin: 10px 0 50px;'));

    if (array_key_exists(transaction_properties::$id, $display)){
        $table->add_object(
            new Column(width(25).style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('ID'))
        );
    }
    if (array_key_exists(transaction_properties::$date, $display)){
        $table->add_object(
            new Column(width('auto').style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Date'))
        );
    }
    if (array_key_exists(transaction_properties::$total, $display)){
        $table->add_object(
            new Column(width('auto').style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Total'))
        );
    }
    if (array_key_exists(transaction_properties::$customer_name, $display)){
        $table->add_object(
            new Column(width('auto').style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Name'))
        );
    }
    if (array_key_exists(transaction_properties::$customer_address, $display)){
        $table->add_object(
            new Column(width('auto').style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Address'))
        );
    }
    if (array_key_exists(transaction_properties::$customer_email, $display)){
        $table->add_object(
            new Column(width('auto').style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Email'))
        );
    }
    if (array_key_exists(transaction_properties::$transfirstid, $display)){
        $table->add_object(
            new Column(width('auto').style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('TransFirst ID'))
        );
    }
    if (array_key_exists(transaction_properties::$taxrate, $display)){
        $table->add_object(
            new Column(width('auto').style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Tax Rate'))
        );
    }

    $table->add_object(
        new Row(style('border: none; padding-bottom: 8px; height: 1px;'),
            new Column(colspan(count($display).style('padding-bottom: 8px;')),
                new HR(style('margin: 0px;')))));

    $query = QueryTransaction();
    while ($query->have_posts()):
        $query->the_post();
        global $post;
        $transaction = $post->ID;
        $table->add_object(transaction_display($display, $transaction));
        $table->add_object(
            new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan(count($display).style('padding-bottom: 0px;')),
                    new HR(style('margin: 0px;')))
            ));
    endwhile;
    return $table;
}

function transaction_display($display, $id) {
    $row = new Row();

    if (array_key_exists(transaction_properties::$id, $display)){
        if (array_key_exists(transaction_properties::$selectable, $display)){
            $row->add_object(new Column(
                new Form(method('POST').name('select_transaction'),
                    selection::InputTransaction($id),
                    transaction_request::Store(),
                    page_action::InputAction(action_types::$select_transaction),
                    new Input(classType('button').type('submit').name('button').value('Transaction #'.transaction_properties::get_id($id)).style('background:none!important; border:none; 
                        padding:0!important; font-family:arial,sans-serif; color:#069; box-shadow: 0 0px 0 #ccc; cursor:pointer;'))
                )
            ));
        }
        else {
            $row->add_object(new Column(
                new TextRender('Transaction #'.transaction_properties::get_id($id))
            ));
        }
    }
    if (array_key_exists(transaction_properties::$date, $display)){
        $row->add_object(new Column(
            new TextRender(transaction_properties::get_date($id))
        ));
    }
    if (array_key_exists(transaction_properties::$total, $display)){
        $row->add_object(new Column(
            new TextRender('$'.number_format(transaction_properties::get_stored_total($id), 2))
        ));
    }
    if (array_key_exists(transaction_properties::$customer_name, $display)){
        $row->add_object(new Column(
            new TextRender(transaction_properties::get_customer_name($id))
        ));
    }
    if (array_key_exists(transaction_properties::$customer_address, $display)){
        $row->add_object(new Column(
            new TextRender(transaction_properties::get_customer_address($id))
        ));
    }
    if (array_key_exists(transaction_properties::$customer_email, $display)){
        $row->add_object(new Column(
            new TextRender(transaction_properties::get_customer_email($id))
        ));
    }
    if (array_key_exists(transaction_properties::$transfirstid, $display)){
        $row->add_object(new Column(
            new TextRender(transaction_properties::get_transfirstid($id))
        ));
    }
    if (array_key_exists(transaction_properties::$taxrate, $display)){
        $row->add_object(new Column(
            new TextRender(transaction_properties::get_taxrate($id))
        ));
    }
    if (array_key_exists(transaction_properties::$removeable, $display)) {
        $row->add_object(new Column(align('center'),
            new Form(
                selection::InputTransaction($id),
                transaction_request::Store(),
                page_action::InputAction(action_types::$delete_transaction),
                button('Remove')
            )
        ));
    }
    return $row;
}

function request_form_transactions() {
    $leftwidth = 45;
    $rightwidth = 55;
    return new Form(action('').id('library').method('post').name('transaction_search'),
        new Column(width(12).align('left').valign('top').style('text-align: left;'),
            new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('ID:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'), transaction_request::InputID())
                ),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('Date From:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'), transaction_request::InputDateFrom())
                ),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('Date To:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'), transaction_request::InputDateTo())
                ),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('Total From:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'), transaction_request::InputTotalFrom())
                ),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('Total To:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'), transaction_request::InputTotalTo())
                ),
                new Row (
                    new Column(width($leftwidth)),
                    new Column(width($rightwidth).align('right').style('padding-left: 5px; padding-top: 5px;'),
                        page_action::InputAction(action_types::$search_transactions),
                        button('Search')
                    )
                )
            )
        ),
        new Column(width(18).align('left').valign('top').style('text-align: left;'),
            new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('Name:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'), transaction_request::InputCustomerName())
                ),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('Email:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'), transaction_request::InputCustomerEmail())
                ),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('Address:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'), transaction_request::InputCustomerEmail())
                ),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('Tax Rate:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'), transaction_request::InputTaxRate())
                ),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('TransFirst ID:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'), transaction_request::InputTransFirstID())
                ),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('Complete?:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'),
                        new Input(type('radio').name(transaction_request::$completed).value(1).checkedAttr('true')),
                        new TextRender('True '),
                        new Input(type('radio').name(transaction_request::$completed).value(-1)),
                        new TextRender('False '),
                        new Input(type('radio').name(transaction_request::$completed).value('all')),
                        new TextRender('All '))
                )
            )
        )
    );
}

?>





