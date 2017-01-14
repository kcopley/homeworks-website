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
function search_consigners()
{
    $default = array(
        consigner_properties::$name => true,
        consigner_properties::$date => true,
        consigner_properties::$id => true,
        consigner_properties::$selectable => true,
        consigner_properties::$delete => true,
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

    if (array_key_exists(consigner_properties::$name, $display)){
        $table->add_object(
            new Column(width(25).style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Name'))
        );
    }
    if (array_key_exists(consigner_properties::$id, $display)){
        $table->add_object(
            new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('ID'))
        );
    }
    if (array_key_exists(consigner_properties::$date, $display)){
        $table->add_object(
            new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender('Date Added'))
        );
    }
    $table->add_object(
        new Row(style('border: none; padding-bottom: 8px; height: 1px;'),
            new Column(colspan(count($display).style('padding-bottom: 8px;')),
                new HR(style('margin: 0px;')))));

    $query = QueryConsigner();
    while ($query->have_posts()):
        $query->the_post();
        global $post;
        $consigner = $post->ID;
        $table->add_object(
            consigner_display($display, $consigner));
        $table->add_object(
            new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan(count($display).style('padding-bottom: 0px;')),
                    new HR(style('margin: 0px;')))
            ));
    endwhile;
    return $table;
}

function consigner_display($display, $id) {
    $row = new Row();

    if (array_key_exists(consigner_properties::$name, $display)){
        if (array_key_exists(consigner_properties::$selectable, $display)){
            $row->add_object(new Column(
                new Form(method('POST').name('select_consigner'),
                    selection::InputConsigner($id),
                    consigner_request::Store(),
                    page_action::InputAction(action_types::$select_consigner),
                    new Input(classType('button').type('submit').name('button').value(get_the_title($id)).style('background:none!important; border:none; 
                        padding:0!important; font-family:arial,sans-serif; color:#069; box-shadow: 0 0px 0 #ccc; cursor:pointer;'))
                )
            ));
        }
        else {
            $row->add_object(new Column(
                new TextRender(consigner_properties::get_consigner_name($id))
            ));
        }
    }
    if (array_key_exists(consigner_properties::$id, $display)){
        $row->add_object(new Column(
            new TextRender(consigner_properties::get_consigner_id($id))
        ));
    }
    if (array_key_exists(consigner_properties::$date, $display)){
        $row->add_object(new Column(
            new TextRender(consigner_properties::get_consigner_date($id))
        ));
    }
    if (array_key_exists(consigner_properties::$date, $display) && consigner_properties::get_consigner_id($id) != 0) {
        $row->add_object(new Column(
            new Form(
                selection::InputConsigner($id),
                consigner_request::Store(),
                page_action::InputAction(action_types::$remove_consigner),
                button('Remove')
            )
        ));
    }
    return $row;
}

function request_form_consigners() {
    $leftwidth = 45;
    $rightwidth = 55;
    return new Form(action('').id('library').method('post').name('consigner_search'),
        new Column(width(12).align('left').valign('top').style('text-align: left;'),
            new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('Name:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'), consigner_request::InputName())
                ),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('ID:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'), consigner_request::InputID())
                ),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('Date From:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'), consigner_request::InputDateFrom())
                ),
                new Row (
                    new Column(align('right').width($leftwidth), new Label(new TextRender('Date To:'))),
                    new Column(width($rightwidth).style('padding-left: 5px;'), consigner_request::InputDateTo())
                ),
                new Row (
                    new Column(width($leftwidth)),
                    new Column(width($rightwidth).align('right').style('padding-left: 5px; padding-top: 5px;'),
                        page_action::InputAction(action_types::$search_consigner),
                        button('Search')
                    )
                )
            )
        )
    );
}

?>





