<?php
include_once "includes.php";

selection::GetPages(vars::$consigner_page);
selection::GetIDS();

Consigner::GenerateConsignerSearch()->Render();

$gap = new BR();
$gap->Render();
if ($_REQUEST['reset_query_'.Consigner::$source]) ResetQuery(Consigner::$props);
if ($_REQUEST['reset_query_'.Book::$source]) ResetQuery(Book::$props);

global $wpdb;
switch (page_action::GetAction()) {
	case action_types::get_search(Consigner::$source):
        $_SESSION[action_types::$consigner_books] = false;
        page_action::SetNewAction(action_types::get_search(Consigner::$source));
        StoreQuery(Consigner::$props);
		GenerateSearch(Consigner::$props, Consigner::$source, Consigner::$post_type)->Render();
		break;
    case action_types::get_update(Consigner::$source):
        Update(Consigner::$props, selection::GetID(Consigner::$source));
        page_action::SetNewAction(action_types::get_select(Consigner::$source));
        Consigner::SelectConsigner(selection::GetID(Consigner::$source))->Render();
        break;
    case action_types::get_add(Consigner::$source):
        $consigner_id = Add(Consigner::$props, Consigner::$source, Consigner::$post_type);
        page_action::SetNewAction(action_types::get_select(Consigner::$source));
        Consigner::SelectConsigner($consigner_id)->Render();
        break;
    case action_types::get_delete(Consigner::$source):
        $_POST[action_types::$delete_sure] = true;
        page_action::SetNewAction(action_types::get_select(Consigner::$source));
        Consigner::SelectConsigner(selection::GetID(Consigner::$source))->Render();
        break;
    case action_types::get_delete_sure(Consigner::$source):
        Remove(Consigner::$source, selection::GetID(Consigner::$source));
        page_action::SetNewAction(action_types::get_search(Consigner::$source));
        GenerateSearch(Consigner::$props, Consigner::$source, Consigner::$post_type)->Render();
        break;
    case action_types::get_search(Book::$source):
        $_SESSION[action_types::$consigner_books] = true;
        page_action::SetNewAction(action_types::get_select(Consigner::$source));
        Consigner::SelectConsigner(selection::GetID(Consigner::$source))->Render();
        break;
    case action_types::get_select(Consigner::$source):
        if ($_SESSION[vars::$last_page] == vars::$consigner_page)
            $_SESSION[action_types::$consigner_books] = false;
        page_action::SetNewAction(action_types::get_select(Consigner::$source));
        Consigner::SelectConsigner(selection::GetID(Consigner::$source))->Render();
        break;
    case action_types::$add_book_to_consigner:
        Consigner::add_book(selection::GetID(Consigner::$source), selection::GetID(Book::$source));
        Consigner::SelectConsigner(selection::GetID(Consigner::$source))->Render();
        break;
    case action_types::$remove_book_from_consigner:
        Consigner::remove_book(selection::GetID(Consigner::$source), selection::GetID(Book::$source));
        Consigner::SelectConsigner(selection::GetID(Consigner::$source))->Render();
        break;
    case action_types::$pay_sold_books:
        Consigner::PayOutBooks(selection::GetID(Consigner::$source));
        Consigner::SelectConsigner(selection::GetID(Consigner::$source))->Render();
        break;
    default:
        selection::ResetPages(vars::$consigner_page);
};

?>