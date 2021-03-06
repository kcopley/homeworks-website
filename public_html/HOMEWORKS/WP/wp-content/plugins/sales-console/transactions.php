<?php
include_once "includes.php";

selection::GetPages(vars::$transaction_page);
selection::GetIDS();

if ($_REQUEST['reset_query_'.Transaction::$source]) {
    ResetQuery(Transaction::$props);
    unset($_SESSION[Transaction::$calculated_totals_array]);
    unset($_SESSION[Transaction::$calculated_totals_old_array]);
}

global $wpdb;

switch (page_action::GetAction()) {
    case action_types::$get_trans_search_totals:
        unset($_SESSION[Transaction::$calculated_totals_array]);
        unset($_SESSION[Transaction::$calculated_totals_old_array]);
        Transaction::CalculateTransactionTotals();
        break;
};

GenerateTransactionSearch()->Render();

switch (page_action::GetAction()) {
	case action_types::get_search(Transaction::$source):
        page_action::SetNewAction(action_types::get_search(Transaction::$source));
        StoreQuery(Transaction::$props);
		GenerateSearch(Transaction::$props, Transaction::$source, Transaction::$post_type)->Render();
		break;
    case action_types::get_select(Transaction::$source):
        page_action::SetNewAction(action_types::get_select(Transaction::$source));
        Transaction::print_formatting(selection::GetID(Transaction::$source));
        Transaction::SelectTransaction(selection::GetID(Transaction::$source))->Render();
        break;
    case action_types::get_update(Transaction::$source):
        page_action::SetNewAction(action_types::get_select(Transaction::$source));
        Update(Transaction::$props, selection::GetID(Transaction::$source));
        Transaction::print_formatting(selection::GetID(Transaction::$source));
        Transaction::SelectTransaction(selection::GetID(Transaction::$source))->Render();
        break;
    case action_types::get_delete(Transaction::$source):
        $_POST[action_types::$delete_sure] = true;
        page_action::SetNewAction(action_types::get_select(Transaction::$source));
        Transaction::SelectTransaction(selection::GetID(Transaction::$source))->Render();
        break;
    case action_types::get_delete_sure(Transaction::$source):
        page_action::SetNewAction(action_types::get_search(Transaction::$source));
        Remove(Transaction::$source, selection::GetID(Transaction::$source));
        GenerateSearch(Transaction::$props, Transaction::$source, Transaction::$post_type)->Render();
        break;
    case action_types::$get_trans_search_totals:
        page_action::SetNewAction(action_types::get_search(Transaction::$source));
        GenerateSearch(Transaction::$props, Transaction::$source, Transaction::$post_type)->Render();
        break;
    default:
        unset($_SESSION[Transaction::$calculated_totals_array]);
        unset($_SESSION[Transaction::$calculated_totals_old_array]);
        break;
};

function GenerateTransactionSearch()
{
    return new TableArr(border(0).cellpadding(0).cellspacing(0).width(100).style('padding-bottom: 20px;'),
        new Row(
            new Column(width(60),
                GenerateSearchBox(Transaction::$props, Transaction::$source, 'Search', 'Search Transactions')),
            new Column(width(10)),
            new Column(width(30),
                Transaction::GenerateTransactionTotalsDisplay()
            )
        )
    );
}
?>