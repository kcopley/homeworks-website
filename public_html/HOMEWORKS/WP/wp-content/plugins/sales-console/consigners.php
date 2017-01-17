<?phpinclude_once "includes.php";Consigner::GenerateConsignerSearch()->Render();$gap = new BR();$gap->Render();global $wpdb;switch (page_action::GetAction()) {	case action_types::get_search(Consigner::$source):		GenerateSearch(Consigner::$props, Consigner::$source, Consigner::$post_type)->Render();		break;    case action_types::get_update(Consigner::$source):        Update(Consigner::$props, selection::GetID(Consigner::$source));        Consigner::SelectConsigner(selection::GetID(Consigner::$source))->Render();        break;    case action_types::get_add(Consigner::$source):        $consigner_id = Add(Consigner::$props, Consigner::$source, Consigner::$post_type);        Consigner::SelectConsigner($consigner_id)->Render();        break;    case action_types::get_delete(Consigner::$source):        $_POST[action_types::$delete_sure] = true;        Consigner::SelectConsigner(selection::GetID(Consigner::$source))->Render();        break;    case action_types::get_delete_sure(Consigner::$source):        Remove(Consigner::$source, selection::GetID(Consigner::$source));        GenerateSearch(Consigner::$props, Consigner::$source, Consigner::$post_type)->Render();        break;    case action_types::get_search(Book::$source):        $_SESSION[action_types::$consigner_books] = true;        Consigner::SelectConsigner(selection::GetID(Consigner::$source))->Render();        break;    case action_types::get_select(Consigner::$source):        Consigner::SelectConsigner(selection::GetID(Consigner::$source))->Render();        break;    case action_types::$add_book_to_consigner:        consigner_properties::add_book(selection::GetConsigner(), selection::GetBook());        select_consigner(selection::GetConsigner())->Render();        break;    case action_types::$remove_book_from_consigner:        consigner_properties::remove_book(selection::GetConsigner(), selection::GetBook());        select_consigner(selection::GetConsigner())->Render();        break;};function display_consigner_books($id) {    $list = new RenderList();    $books = consigner_properties::get_books($id);    if (!empty($books) && consigner_properties::get_consigner_id($id) != 0) {        foreach ($books as $book) {            $list->add_object(                new Row(                    new Column(new TextRender(book_properties::get_book_title($book))),                    new Column(new TextRender(book_properties::get_book_isbn($book))),                    new Column(new TextRender('$'.book_properties::get_book_cost($book))),                    new Column(new TextRender(book_properties::get_book_barcode($book))),                    new Column(),                    new Column(                        new Form(align('center'),                            page_action::InputAction(action_types::$remove_book_from_consigner),                            consigner_request::Store(),                            book_request::Store(),                            selection::InputConsigner($id),                            selection::InputBook($book),                            button('Remove')                        )                    )                )            );        }    }    return $list;}function display_sold_consigner_books($id) {    $list = new RenderList();    $books = consigner_properties::get_sold_books($id);    if (!empty($books) && consigner_properties::get_consigner_id($id) != 0) {        foreach ($books as $book) {            $id = $book[consigner_properties::$book_id];            $paid = $book[consigner_properties::$book_paid];            $list->add_object(                new Row(                    new Column(new TextRender(book_properties::get_book_title($id))),                    new Column(new TextRender(book_properties::get_book_isbn($id))),                    new Column(new TextRender('$'.book_properties::get_book_cost($id))),                    new Column(new TextRender(book_properties::get_book_barcode($id))),                    new Column(width(10).align('center'), new TextRender($paid)),                    new Column(                        new Form(align('center'),                            page_action::InputAction(action_types::$remove_book_from_consigner),                            consigner_request::Store(),                            book_request::Store(),                            selection::InputConsigner($id),                            selection::InputBook($book),                            button('Remove')                        )                    )                )            );        }    }    return $list;}function GenerateResults($id) {    $renderlist = new RenderList();    if (consigner_request::GetBookSearch()) {        $renderlist->add_object(            search_books(                array(                    book_properties::$title => true,                    book_properties::$barcode => true,                    book_properties::$publisher => true,                    book_properties::$isbn => true,                    book_properties::$consigner_id => $id                ),                new RenderList(                    selection::InputConsigner($id),                    consigner_request::Store()                )            )        );    }    return $renderlist;}?>