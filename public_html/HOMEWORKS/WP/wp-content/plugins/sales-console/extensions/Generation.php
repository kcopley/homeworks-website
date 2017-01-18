<?php

/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 1/16/2017
 * Time: 12:36 PM
 */

function GenerateSearchBox($props, $source, $title, $button) {
    $leftwidth = 20;
    $rightwidth = 80;

    $outsidetable = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100).
        style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'));
    $outsidetable->add_object(new Row(
        new Column(new Strong(new TextRender($title)))
    ));
    $outsiderow = new Row();
    $outsidetable->add_object($outsiderow);
    $form = new Form(method('post'));
    $form->add_object($outsidetable);

    $counter = 0;
    $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100));
    $columns = array();
    $col = new Column(valign('top'),
        $table
    );
    $columns[] = $col;
    $outsiderow->add_object(
        $col
    );

    foreach ($props as $key => $prop) {
        if ($prop->search_param) {
            if ($counter > 3) {
                $counter = 0;
                $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100));
                $col = new Column(valign('top').align('left'),
                    $table
                );
                $columns[] = $col;
                $outsiderow->add_object(
                    $col
                );
            }
            $inp = $prop->GetInputSearch($leftwidth, $rightwidth, vars::$search_prefix);
            $table->add_object($inp);
            $counter = $counter + 1;
        }
    }

    $totalColumns = count($columns);
    foreach ($columns as $column) {
        $column->add_object(width(100 / $totalColumns));
    }

    $table->add_object(
        new Row(
            new Column(colspan(2).width($rightwidth).align('center').style('padding-left: 5px; padding-top: 5px;'),
                page_action::InputAction(action_types::get_search($source)),
                button($button)
            )
        )
    );
    return $form;
}

function GenerateAddBox($props, $source, $title, $button) {
    $leftwidth = 20;
    $rightwidth = 80;

    $outsidetable = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100).
        style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'));
    $outsidetable->add_object(new Row(
        new Column(new Strong(new TextRender($title)))
    ));
    $outsiderow = new Row();
    $outsidetable->add_object($outsiderow);
    $form = new Form(method('post'));
    $form->add_object($outsidetable);

    $counter = 0;
    $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100));
    $columns = array();
    $col = new Column(valign('top'),
        $table
    );
    $columns[] = $col;
    $outsiderow->add_object(
        $col
    );

    foreach ($props as $key => $prop) {
        if ($prop->add_param) {
            if ($counter > 3) {
                $counter = 0;
                $table = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100));
                $col = new Column(valign('top').align('left'),
                    $table
                );
                $columns[] = $col;
                $outsiderow->add_object(
                    $col
                );
            }
            $inp = $prop->GetInputAdd($leftwidth, $rightwidth, vars::$add_prefix);
            $table->add_object($inp);
            $counter = $counter + 1;
        }
    }

    $totalColumns = count($columns);
    foreach ($columns as $column) {
        $column->add_object(width(100 / $totalColumns));
    }

    $table->add_object(
        new Row(
            new Column(colspan(2).width($rightwidth).align('center').style('padding-left: 5px; padding-top: 5px;'),
                page_action::InputAction(action_types::get_add($source)),
                button($button)
            )
        )
    );
    return $form;
}

function GenerateQuery($props, $post_type) {
    $args = array(
        'numberposts' => 25,
        'posts_per_page' => 25,
        'order' => 'ASC',
        'orderby' => 'date',
        'post_type' => $post_type
    );

    add_filter('get_meta_sql','cast_decimal_precision');
    $meta_query_array = array('relation' => 'AND');
    $args['meta_query'] = $meta_query_array;

    foreach ($props as $key => $prop) {
        if ($prop->search_param) {
            $args = $prop->GetQuery($args);
        }
    }
    return new WP_Query($args);
}

function GenerateSearch($props, $source, $post_type) {
    $table = new TableArr(id('formtable').width(100).border(0).cellspacing(0).cellpadding(0).style('margin: 10px 0 50px;'));

    $counter = 0;
    foreach ($props as $key => $prop) {
        if ($prop->search_param) {
            if ($counter == 0) {
                $table->add_object(
                    new Column(width(20).style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender($prop->format))
                );
            }
            else {
                $table->add_object(
                    new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender($prop->format))
                );
            }
            $counter++;
        }
    }
    $table->add_object(
        new Column(width(20))
    );

    $table->add_object(
        new Row(style('border: none; padding-bottom: 8px; height: 1px;'),
            new Column(colspan($counter+1).style('padding-bottom: 8px;'),
                new HR(style('margin: 0px;')))));

    $query = GenerateQuery($props, $post_type);
    while ($query->have_posts()):
        $query->the_post();
        global $post;
        $id = $post->ID;
        $table->add_object(Display($props, $id, $source));
        $table->add_object(
            new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan($counter+5).style('padding-bottom: 0px;'),
                    new HR(style('margin: 0px;')))
            ));
    endwhile;
    return $table;
}

function Display($props, $id, $source) {
    $row = new Row();
    $counter = 0;
    foreach ($props as $key => $prop) {
        if ($prop->display_in_search) {
            $row->add_object($prop->GetDisplay($id, $source));
            $counter++;
        }
    }
    $row->add_object(
        new Column(width(20))
    );
    return $row;
}

function EditDisplay($props, $id, $rows, $source) {
    $leftwidth = 15;
    $rightwidth = 85;

    $lastType = '';

    if (strpos($_SESSION[vars::$last_action], 'search') !== false){
        $lastType = 'Back to Search Results';
    }
    else {
        if ($_SESSION[vars::$last_page] == vars::$library_page) {
            $lastType = 'Back to Book';
        } else if ($_SESSION[vars::$last_page] == vars::$consigner_page) {
            $lastType = 'Back to Consigner';
        } else if ($_SESSION[vars::$last_page] == vars::$transaction_page) {
            $lastType = 'Back to Transaction';
        }
    }
    if ($_SESSION[vars::$last_action] != null) {
        $formresults = new Form(method('post').name('backtoresults').id('backtoresults').action($_SESSION[vars::$last_page]));
        $backtoresults = new RenderList(
            new Input(form('backtoresults').id(vars::$went_back).name(vars::$went_back).type('hidden').value(true)),
            new Input(form('backtoresults').id(page_action::$action).name(page_action::$action).type('hidden').value($_SESSION[vars::$last_action])),
            new Input(form('backtoresults').classType('button-primary').type('submit').name('button').value($lastType))
        );
    }

    $formdelete =  new Form(method('post').name('delete_book').id('delete_book'));

    if ($_POST[action_types::$delete_sure]) {
        $deletelist = new RenderList(
            page_action::InputActionForm(action_types::get_delete_sure($source), 'delete_book'),
            selection::SetIDForm($id, $source, 'delete_book'),
            new Input(form('delete_book').classType('button-primary').type('submit').name('button').value('Remove'))
        );
    }
    else {
        $deletelist = new RenderList(
            page_action::InputActionForm(action_types::get_delete($source), 'delete_book'),
            selection::SetIDForm($id, $source, 'delete_book'),
            new Input(form('delete_book').classType('button-primary').type('submit').name('button').value('Remove'))
        );
    }

    $outsidetable = new TableArr(border(0).cellpadding(0).cellspacing(2).id('formtable').width(100).
        style('padding: 10px; border: solid; border-width: 1px; border-color: #D0D0D0;'));
    $outsidetable->add_object(new Row(
        new Column(
            new Strong(new TextRender($source.':')))
        )
    );
    $outsidetable->add_object($formdelete);
    $outsidetable->add_object($formresults);
    $outsiderow = new Row();
    $form = new Form(method('post').name('edit_form').id('edit_form'));
    $outsidetable->add_object($form);
    $form->add_object($outsiderow);

    $colwidth = $rows * 10;
    $counter = 0;
    $table = new TableArr(border(0).cellpadding(4).cellspacing(4).id('formtable').width(100));
    $outsiderow->add_object(new Column(valign('top').width($colwidth),
        $table
    ));

    $rowCounter = 1;
    foreach ($props as $key => $prop) {
        if ($prop->display_in_edit) {
            if ($counter > ($rows - 1)) {
                $counter = 0;
                $table = new TableArr(border(0).cellpadding(4).cellspacing(4).id('formtable').width(100));
                $outsiderow->add_object(
                    new Column(valign('top').width($colwidth),
                        $table
                    )
                );
                $rowCounter++;
            }
            $edi = $prop->GetEditForm($id, $leftwidth, $rightwidth, 'edit_form');
            $table->add_object(
                $edi
            );

            $counter = $counter + 1;
        }
    }

    if ($_POST[action_types::$delete_sure]) {
        $form->add_object(
            new Row(
                new Column(align('left').style('padding-top: 5px;')
                ),
                new Column(colspan($rows).align('center').style('padding-top: 5px;'),
                    new Strong(new TextRender('Are you sure?'))
                )
            )
        );
    }
    $form->add_object(
        new Row(
            new Column(align('left').style('padding-top: 5px;')
            ),
            new Column(colspan($rows).align('center').style('padding-top: 5px;'),
                new Div(align('right').style('display:inline-block; padding-right: 5px;'),
                    selection::SetID($id, $source),
                    page_action::InputAction(action_types::get_update($source)),
                    button('Update '.$source)
                ),
                new Div(align('right').style('display:inline-block; padding-left: 5px;'),
                    $backtoresults
                ),
                new Div(align('right').style('display:inline-block; padding-left: 5px;'),
                    $deletelist
                )
            )
        )
    );
    return $outsidetable;
}

function Add($props, $source, $post_type) {
    $postid = null;
    date_default_timezone_set('America/Chicago');
    foreach ($props as $key => $prop) {
        if ($prop->add_param) {
            if ($prop->db_value == 'title') {
                $title = $prop->GetPostValue(vars::$add_prefix);
                $order = array(
                    'post_title' => $title,
                    'post_status' => 'publish',
                    'post_author' => 4,
                    'post_type' => $post_type
                );
                $postid = wp_insert_post($order);
            }
        }
    }
    if ($postid == null) return null;
    $idprop = null;
    foreach ($props as $key => $prop) {
        if ($prop->name == 'consigner_id' || $prop->name == 'book_barcode')
            $idprop = $prop;
        else if ($prop->add_param) {
            if ($prop->db_value != 'title') {
                $val = $prop->GetPostValue(vars::$add_prefix);
                if (get_class($prop) == 'Date' && !$val) $val = date('Y-m-d');
                $prop->SetValue($postid, $val);
            }
        }
    }

    if ($source == 'Consigner' && $idprop != null) {
        //Set up ID
        $lastID = get_option('_cmb_consigner_lastID');
        if ($lastID == false) {
            add_option('_cmb_consigner_lastID', 20000);
            $lastID = get_option('_cmb_consigner_lastID');
            Consigner::update_owner($postid);
        }
        $newID = $lastID + 1;
        update_option('_cmb_consigner_lastID', $newID);

        $idprop->SetValue($postid, $newID);
    }
    else if ($source == 'Book' && $idprop != null){
        $lastBarcodeExists = get_option('_cmb_resource_lastBarcode');
        $lastBarcode = 15000;
        if ($lastBarcodeExists == false){
            add_option('_cmb_resource_lastBarcode', 15000);
            $lastBarcode = get_option('_cmb_resource_lastBarcode');
        }
        $newbarcode = $lastBarcode + 1;
        update_option('_cmb_resource_lastBarcode', $newbarcode);
        $idprop->SetValue($postid, $newbarcode);
    }
    return $postid;
}

function Remove($source, $id) {
    if ($source == 'Book') {
        $consigners = book_properties::get_consigners($id);
        if (!empty($consigners)) {
            foreach ($consigners as $consigner) {
                $consigner_book_list = consigner_properties::get_books($consigner);
                if (!empty($consigner_book_list)) {
                    $temparr = array();
                    foreach ($consigner_book_list as $book) {
                        if ($product_id != $book) {
                            $temparr[] = $book;
                        }
                    }
                    consigner_properties::set_books($consigner, $temparr);
                }
            }
        }
    }
    wp_delete_post($id);
}

function Update($props, $id) {
    foreach ($props as $key => $prop) {
        if ($prop->edit_param && $prop->GetPostValue(vars::$edit_prefix)) {
            $prop->SetValue($id, $prop->GetPostValue(vars::$edit_prefix));
        }
    }
}