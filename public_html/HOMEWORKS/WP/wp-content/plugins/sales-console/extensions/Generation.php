<?php

/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 1/16/2017
 * Time: 12:36 PM
 */

function StoreQuery($props) {
    foreach ($props as $key => $prop) {
        if (method_exists($prop, 'SetSessionValue')) {
            $prop->SetSessionValue(vars::$search_prefix);
        }
    }
}

function ResetQuery($props) {
    foreach ($props as $key => $prop) {
        if (method_exists($prop, 'UnsetSessionValue')) {
            $prop->UnsetSessionValue(vars::$search_prefix);
        }
    }
}

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
                selection::SetID(1, vars::$current_page),
                new Input(type('hidden').name('reset_query_'.$source).id('reset_query_'.$source).value('true')),
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

function GenerateQuery($props, $post_type, $display_post_num, $offset) {
    $args = array(
        'numberposts' => $display_post_num,
        'posts_per_page' => $display_post_num,
        'order' => 'ASC',
        'orderby' => 'date',
        'post_type' => $post_type,
    );
    if ($offset != -1) {
        $args['offset'] = $offset;
    }

    if ($post_type == Transaction::$post_type) {
        $args['order'] = 'DESC';
    }

    add_filter('get_meta_sql','cast_decimal_precision');
    $meta_query_array = array('relation' => 'AND');
    $args['meta_query'] = $meta_query_array;


    foreach ($props as $key => $prop) {
        if ($prop->search_param) {
            if (method_exists($prop, 'GetQuery')) {
                $args = $prop->GetQuery($args, vars::$search_prefix);
            }
        }
    }
    return new WP_Query($args);
}

function GenerateSearch($props, $source, $post_type) {
    $table = new TableArr(id('formtable').width(100).border(0).cellspacing(0).cellpadding(0).style('margin: 10px 0 50px;'));

    $toprow = new Row();
    $table->add_object($toprow);
    $nextRow = new Row();
    $table->add_object($nextRow);
    $counter = 0;
    $cols = array();
    foreach ($props as $key => $prop) {
        if ($prop->display_in_search) {
            if ($counter == 0) {
                $col =  new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender($prop->format));
                $cols[] = $col;
                $nextRow->add_object(
                    $col
                );
            }
            else {
                $col = new Column(style('padding-bottom: 8px; font-weight: bold; font-size: 14px'), new TextRender($prop->format));
                $cols[] = $col;
                $nextRow->add_object(
                    $col
                );
            }
            $counter++;
        }
    }

    $width = 100 / ($counter + 1);
    $first = true;
    foreach ($cols as $col) {
        if ($first) {
            $col->add_object(width($width * 2));
            $first = false;
        }
        else {
            $col->add_object(width($width));
        }
    }

    $table->add_object(
        new Row(style('border: none; padding-bottom: 8px; height: 1px;'),
            new Column(colspan($counter+1).style('padding-bottom: 8px;'),
                new HR(style('margin: 0px;')))));

    $display_post_num = 25;
    $current_page = selection::GetID(vars::$current_page);
    if (!$current_page) $current_page = 1;

    $offset = ($current_page - 1) * $display_post_num;
    if ($offset < 0) $offset = 0;

    $query = GenerateQuery($props, $post_type, $display_post_num, $offset);

    $toprow->add_object(new Column(new Strong(new TextRender('Search found '.$query->found_posts.' posts.'))));
    while ($query->have_posts()):
        $query->the_post();
        global $post;
        $id = $post->ID;
        $table->add_object(Display($props, $id, $source));
        $table->add_object(
            new Row(style('border: none; padding: 0px; height: 1px;'),
                new Column(colspan($counter+1).style('padding-bottom: 0px;'),
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
                        selection::SetID($current_page - 1, vars::$current_page),
                        page_action::InputAction(action_types::get_search($source)),
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
                        selection::SetID($current_page + 1, vars::$current_page),
                        page_action::InputAction(action_types::get_search($source)),
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

function Display($props, $id, $source) {
    $row = new Row();
    $counter = 0;
    $cols = array();
    foreach ($props as $key => $prop) {
        if ($prop->display_in_search) {
            $col = $prop->GetDisplay($id, $source);
            $cols[] = $col;
            $row->add_object($col);
            $counter++;
        }
    }
    $width = 100 / ($counter + 1);
    $first = true;
    foreach ($cols as $col) {
        if ($first) {
            $col->add_object(width($width * 2));
            $first = false;
        }
        else {
            $col->add_object(width($width));
        }
    }
    return $row;
}

function EditDisplay($props, $id, $rows, $source, $extraDivs) {
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
    $outsidetable->add_object($outsiderow);

    $tableArr = array();
    $colwidth = 100 / $rows;
    $counter = 0;

    for ($i = 0; $i < $rows; $i++) {
        $table = new TableArr(border(0).cellpadding(4).cellspacing(4).id('formtable').width(100));
        $outsiderow->add_object(new Column(valign('top').width($colwidth),
            $table
        ));
        $tableArr[] = $table;
    }

    $rowCounter = 1;
    foreach ($props as $key => $prop) {
        if ($prop->display_in_edit) {
            if ($counter == $rows) {
                $counter = 0;
            }
            $edi = $prop->GetEditForm($id, $leftwidth, $rightwidth, 'edit_form');
            $tableArr[$counter]->add_object(
                $edi
            );

            $counter = $counter + 1;
        }
    }

    if ($_POST[action_types::$delete_sure]) {
        $outsidetable->add_object(
            new Row(
                new Column(align('left').style('padding-top: 5px;')
                ),
                new Column(colspan($rows).align('center').style('padding-top: 5px;'),
                    new Strong(new TextRender('Are you sure?'))
                )
            )
        );
    }
    $outsidetable->add_object(
        new Row(
            new Column(align('left').style('padding-top: 5px;')
            ),
            new Column(colspan($rows).align('center').style('padding-top: 5px;'),
                new Div(align('right').style('display:inline-block; padding-right: 5px;'),
                    selection::SetIDForm($id, $source, 'edit_form'),
                    page_action::InputActionForm(action_types::get_update($source), 'edit_form'),
                    new Input(form('edit_form').classType('button-primary').type('submit').name('button').value('Update '.$source))
                ),
                new Div(align('right').style('display:inline-block; padding-left: 5px;'),
                    $backtoresults
                ),
                new Div(align('right').style('display:inline-block; padding-left: 5px;'),
                    $deletelist
                ),
                new Div(align('right').style('display: inline-block; padding-left: 5px;'),
                    $extraDivs
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

    if ($source == Consigner::$source) {
        //Set up ID
        $lastID = get_option('_cmb_consigner_lastID');
        if ($lastID == false) {
            add_option('_cmb_consigner_lastID', 0);
            $lastID = get_option('_cmb_consigner_lastID');
            Consigner::update_owner($postid);
        }
        $newID = $lastID + 1;
        update_option('_cmb_consigner_lastID', $newID);

        Consigner::$props[Consigner::$id]->SetValue($postid, $newID);
    }
    else if ($source == Book::$source){
        $lastBarcode = get_option('_cmb_resource_lastBarcode');
        if (!$lastBarcode){
            add_option('_cmb_resource_lastBarcode', 20000);
            $lastBarcode = get_option('_cmb_resource_lastBarcode');
        }
        $newbarcode = $lastBarcode + 1;
        update_option('_cmb_resource_lastBarcode', $newbarcode);
        Book::$props[Book::$barcode]->SetValue($postid, $newbarcode);
    }
    else if ($source == Transaction::$source){
        $newID = get_next_invoice();
        Transaction::$props[Transaction::$props]->SetValue($postid, $newID);
    }
    return $postid;
}

function Remove($source, $id) {
    if ($source == Book::$source) {
        $consigners = Book::get_consigners($id);
        if (!empty($consigners)) {
            foreach ($consigners as $consigner) {
                $consigner_book_list = Consigner::get_books($consigner);
                if (!empty($consigner_book_list)) {
                    $temparr = array();
                    foreach ($consigner_book_list as $book) {
                        if ($id != $book) {
                            $temparr[] = $book;
                        }
                    }
                    Consigner::set_books($consigner, $temparr);
                }
            }
        }
    }
    if ($source == Consigner::$source) {
        $books = Consigner::get_books($id);
        if (!empty($books)) {
            foreach ($books as $book) {
                $book_consigners = Book::get_consigners($book);
                if (!empty($book_consigners)) {
                    $temparr = array();
                    foreach ($book_consigners as $consigner) {
                        if ($id != $consigner) {
                            $temparr[] = $consigner;
                        }
                    }
                    Book::set_consigners($book, $temparr);
                }
            }
        }
    }
    wp_delete_post($id);
}

function Update($props, $id) {
    foreach ($props as $key => $prop) {
        if (method_exists($prop, 'GetPostValue') && method_exists($prop, 'SetValue')) {
            if ($prop->edit_param && $prop->GetPostValue(vars::$edit_prefix)) {
                $prop->SetValue($id, $prop->GetPostValue(vars::$edit_prefix));
            }
        }
    }
}