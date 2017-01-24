<?php
include_once "includes.php";

selection::GetPages(vars::$library_page);
selection::GetIDS();

if ($_REQUEST['reset_query_'.Book::$source]) ResetQuery(Book::$props);

//Enqueue the JS scripts
wp_enqueue_media();

//Generate the top search / add book section
Book::GenerateSearchAndAdd()->Render();

global $wpdb;
switch (page_action::GetAction()) {
    case action_types::get_search(Book::$source):
        $_SESSION[action_types::$consigner_books] = false;
        page_action::SetNewAction(action_types::get_search(Book::$source));
        StoreQuery(Book::$props);
        GenerateSearch(Book::$props, Book::$source, Book::$post_type)->Render();
        break;
    case action_types::add_image(Book::$source):
        add_image(selection::GetID(Book::$source));
        page_action::SetNewAction($_SESSION[vars::$new_action]);
        if ($_SESSION[vars::$new_action] == action_types::get_search(Book::$source)) {
            GenerateSearch(Book::$props, Book::$source, Book::$post_type)->Render();
        }
        else if ($_SESSION[vars::$new_action] == action_types::get_select(Book::$source)) {
            Book::SelectBook(selection::GetID(Book::$source))->Render();
        }
        else {
            GenerateSearch(Book::$props, Book::$source, Book::$post_type)->Render();
        }
        break;
    case action_types::get_select(Book::$source):
        page_action::SetNewAction(action_types::get_select(Book::$source));
        Book::SelectBook(selection::GetID(Book::$source))->Render();
        break;
    case action_types::get_add(Book::$source):
        $id = Add(Book::$props, Book::$source, Book::$post_type);
        page_action::SetNewAction(action_types::get_select(Book::$source));
        Book::SelectBook($id)->Render();
        break;
    case action_types::get_update(Book::$source):
        Update(Book::$props, selection::GetID(Book::$source));
        page_action::SetNewAction(action_types::get_select(Book::$source));
        Book::SelectBook(selection::GetID(Book::$source))->Render();
        break;
    case action_types::$add_book_to_owner_search:
        Book::add_book(selection::GetID(Book::$source), get_consigner_owner_id());
        GenerateSearch(Book::$props, Book::$source, Book::$post_type)->Render();
        break;
    case action_types::$add_book_to_owner_select:
        $id = get_consigner_wp_id($_REQUEST[vars::$edit_prefix.Book::$consigner_id]);
        if (!$id) $id = get_consigner_owner_id();
        Book::add_book(selection::GetID(Book::$source), $id);
        Book::SelectBook(selection::GetID(Book::$source))->Render();
        break;
    case action_types::get_delete(Book::$source):
        $_POST[action_types::$delete_sure] = true;
        page_action::SetNewAction(action_types::get_select(Book::$source));
        Book::SelectBook(selection::GetID(Book::$source))->Render();
        break;
    case action_types::get_delete_sure(Book::$source):
        Remove(Book::$source, selection::GetID(Book::$source));
        page_action::SetNewAction(action_types::get_search(Book::$source));
        GenerateSearch(Book::$props, Book::$source, Book::$post_type)->Render();
        break;
    case action_types::$remove_book_from_consigner:
        page_action::SetNewAction(action_types::get_select(Book::$source));
        Book::remove_book(selection::GetID(Book::$source), selection::GetID(Consigner::$source));
        Book::SelectBook(selection::GetID(Book::$source))->Render();
        break;
    default:
        selection::ResetPages(vars::$library_page);
};

//Add the scripts to the page
script_input();

media_selector_print_scripts();

function script_input() {
    ?>
    <script type='text/javascript'>
        jQuery(document).ready( function($) {
            $(".selectBox").click(function( event ){
                var element = event.srcElement;
                var parent = element.parentElement.parentElement;
                var box = $(parent.getElementsByClassName("checkboxes")[0]);
                box.toggle();
            });
        });
    </script>
    <?php
}

function media_selector_print_scripts() {
    ?><script type='text/javascript'>
        jQuery(document).ready( function($) {
            var file_frame;
            jQuery('.upload_image_button').on('click', function( event ){

                var element = event.target;
                var post_id = event.target.id;
                var parent = element.parentElement;

                event.preventDefault();
                if ( file_frame ) {
                    file_frame.open();
                    return;
                }
                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select a image to upload',
                    button: {
                        text: 'Use this image',
                    },
                    multiple: false	// Set to true to allow multiple files to be selected
                });

                // When an image is selected, run a callback.
                file_frame.on( 'select', function() {
                    // We set multiple to false so only get one image from the uploader
                    attachment = file_frame.state().get('selection').first().toJSON();
                    attach = parent.elements["image_attachment_id"];
                    attach.value = attachment.id;
                    parent.submit();
                });
                // Finally, open the modal
                file_frame.open();
            });
        });
    </script><?php
}
?>