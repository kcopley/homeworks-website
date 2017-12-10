<?php
include_once "includes.php";
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 1/17/2017
 * Time: 10:53 PM
 */

class inventory {
    public static $add_checked = 'add_checked_book';
    public static $remove_checked = 'remove_checked_book';
    public static $reset_checked = 'reset_checked_book';
}

switch (page_action::GetAction()){
    case inventory::$add_checked:
        Book::add_checked_book($_REQUEST['add_id']);
        break;
    case inventory::$remove_checked:
        Book::remove_checked_book($_REQUEST['remove_id']);
        break;
    case inventory::$reset_checked:
        Book::reset_checked_book($_REQUEST['reset_id']);
        break;
}

$list = new RenderList(
    new TableArr(
        new Row(
            new Column(width(40),
                new TableArr(width(100).cellpadding(0).cellspacing(2),
                    new Row(
                        new Column(align('right'),
                            new TextRender('Add Checked Book')
                        ),
                        new Column(
                            new Form(
                                page_action::InputAction(inventory::$add_checked),
                                new Input(style('margin: 6px;').type('text').name('add_id').id('add_id').autofocus()),
                                new Input(classType('button-primary').type('submit').name('button').value('Add'))
                            )
                        )
                    ),
                    new Row(
                        new Column(align('right'),
                            new TextRender('Remove Checked Book')
                        ),
                        new Column(
                            new Form(
                                page_action::InputAction(inventory::$remove_checked),
                                new Input(style('margin: 6px;').type('text').name('remove_id').id('remove_id')),
                                button('Remove')
                            )
                        )
                    ),
                    new Row(
                        new Column(align('right'),
                            new TextRender('Reset Checked Book')
                        ),
                        new Column(
                            new Form(
                                page_action::InputAction(inventory::$reset_checked),
                                new Input(style('margin: 6px;').type('text').name('reset_id').id('reset_id')),
                                button('Reset')
                            )
                        )
                    )
                )
            ),
            new Column(width(40)
            ),
            new Column(width(15).align('center')
            )
        )
    )
);

$list->Render();

?>

