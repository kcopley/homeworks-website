<table id="formtable" border="0" cellspacing="0" cellpadding="0" class="inlineTable">
    <tr>
        <td align="left" valign="top">
            <form method="POST" action="" name="search">
                <table id="formtable" border="0" cellspacing="0" cellpadding="0" class="inlineTable">
                    <tr>
                        <td width="7%" align="right" valign="top">
                            <label>Title:</label>&nbsp;
                        </td>
                        <td width="93%" valign="top"><input type="text" id="search_title" name="search_title"><br/>&nbsp;&nbsp;&nbsp;or
                        </td>
                    </tr>
                    <tr>
                        <td width="7%" align="right" valign="top"><label>Publisher:</label>&nbsp;</td>
                        <td width="93%" valign="top"><input type="text" id="search_publisher"
                                                            name="search_publisher"><br/>&nbsp;&nbsp;&nbsp;or
                        </td>
                    </tr>
                    <tr>
                        <td width="7%" align="right" valign="top"><label>ISBN:</label>&nbsp;</td>
                        <td width="93%" valign="top"><input type="text" id="search_ISBN" name="search_ISBN"></td>
                    </tr>
                    <tr>
                        <td width="7%" align="right" valign="top"><label>Consigner ID:</label>&nbsp;</td>
                        <td width="93%" valign="top"><input type="text" id="search_Consigner" name="search_Consigner">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="right" valign="top">
                            <input type="hidden" name="action" value="match">
                            <input type="submit" name="button" class="button-primary" value="Search">
                        </td>
                    </tr>
                </table>
            </form>
        </td>

        <td align="left" valign="top" style="padding-left: 10px;">
            <form method="POST" action="" name="search">
                <table id="formtable" border="0" cellspacing="0" cellpadding="0" class="inlineTable">
                    <tr>
                        <td align="right"><label>Title:</label></td>
                        <td style="padding-left: 5px;"><input type="text" name="booktitle"
                                                              value="<?php
                                                              echo stripslashes($_REQUEST['search_title']);
                                                              ?>"></td>
                    </tr>
                    <tr>
                        <td align="right"><label>Quantity:</label></td>
                        <td style="padding-left: 5px;"><input type="text" name="newquantity"></td>
                    </tr>
                    <tr>
                        <td align="right"><label>Department:</label></td>
                        <td style="padding-left: 5px;"><?php
                            wp_dropdown_categories(array(
                                'hide_empty' => 0,
                                'name' => 'bookcategory',
                                'hierarchical' => true,
                                'show_option_all' => 'Choose one'
                            ));
                            ?></td>
                    </tr>
                    <tr>
                        <td align="right"><label>Availability:</label></td>
                        <td style="padding-left: 5px;">&nbsp;&nbsp;<input type="radio" name="available" value="Active">
                            Active&nbsp;&nbsp;&nbsp;<input
                                type="radio" name="available" value="Inactive"> Inactive
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><label>Cost: $</label></td>
                        <td style="padding-left: 5px;"><input type="text" name="cost"></td>
                    </tr>
                    <tr>
                        <td align="right"><label>Price: $</label></td>
                        <td style="padding-left: 5px;"><input type="text" name="price"></td>
                    </tr>
                    <tr>
                        <td align="right"><label>MSRP: $</label></td>
                        <td style="padding-left: 5px;"><input type="text" name="MSRP"></td>
                    </tr>
                    <tr>
                        <td align="right"><label>Vendor:</label></td>
                        <td style="padding-left: 5px;"><input type="text" name="vendor"
                                                              value="<?php
                                                              echo stripslashes($_REQUEST['search_publisher']);
                                                              ?>"></td>
                    </tr>
                    <tr>
                        <td align="right"><label>Condition:</label></td>
                        <td style="padding-left: 5px;">&nbsp;&nbsp;<input type="radio" name="condition" value="New"> New&nbsp;&nbsp;&nbsp;<input
                                type="radio" name="condition" value="Used"> Used
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><label>ISBN:</label></td>
                        <td style="padding-left: 5px;"><input type="text" name="newISBN" value="<?php
                            echo $_REQUEST['search_ISBN'];
                            ?>"></td>
                    </tr>
                    <tr>
                        <td align="right"><label>Consigner ID:</label></td>
                        <td style="padding-left: 5px;"><input type="text" name="consignerID"</td>
                    </tr>
                    <tr>
                        <td align="right"></td>
                        <td style="padding-left: 5px;">
                            <input type="hidden" name="action" value="add"/>
                            <input type="hidden" name="message" value="new"/>
                            <input type="submit" class="button-primary" value="Add"/>
                        </td>
                    </tr>
                </table>
            </form>
        </td>

        <td align="left" valign="top" style="padding-left: 25px;">
            <?php

            global $wpdb;
            $library_title = $_REQUEST['search_title'];
            $search_publisher = $_REQUEST['search_publisher'];
            $search_ISBN = $_REQUEST['search_ISBN'];
            $action = $_REQUEST['action'];

            switch ($action) {
                case "match":
                    search($library_title, $search_publisher, $search_ISBN);
                    break;

                case "increment":
                    $product_id = $_REQUEST['product_id'];
                    change_amount($product_id, 1);
                    search($library_title, $search_publisher, $search_ISBN);
                    break;

                case "decrement":
                    $product_id = $_REQUEST['product_id'];
                    change_amount($product_id, -1);
                    search($library_title, $search_publisher, $search_ISBN);
                    break;

                case "add":

                    global $wpdb;


                    break;
            };
            ?> </td>
    </tr>
</table>

<?php
function change_amount($product_id, $quantity)
{
    $qty = $quantity;
    $oldstock = get_post_meta($product_id, '_cmb_resource_quantity', true);
    $newstock = $oldstock + $qty;
    update_post_meta($product_id, '_cmb_resource_quantity', $newstock);

    $status = get_post_meta($product_id, '_cmb_resource_available', true);

    if ($status == "Inactive") {
        update_post_meta($product_id, '_cmb_resource_available', 'Active');
    }
}

function search($library_title, $search_publisher, $search_ISBN)
{
    if ($library_title != "") {
        $args = array(
            'post_type' => 'bookstore',
            'posts_per_page' => -1,
            's' => $library_title
        );
    } elseif ($search_publisher != "") {
        $args = array(
            'post_type' => 'bookstore',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_cmb_resource_publisher',
                    'value' => $search_publisher
                )
            )
        );
    } else {
        $args = array(
            'post_type' => 'bookstore',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => '_cmb_resource_u-sku',
                    'value' => $search_ISBN,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => '_cmb_resource_sku',
                    'value' => $search_ISBN,
                    'compare' => 'LIKE'
                )
            )
        );
    }
    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) {
        ?>
        <hr/>
        <h4 style="font-size: 14px; margin: 10px 0 0;">Search results:</h4>
        <table id="searchtable" width="100%" border="0" cellspacing="0" cellpadding="0"
               style="margin: 10px 0 50px;" class="inlineTable">
            <tr>
                <th align="left">&nbsp;&nbsp;&nbsp;Title</th>
                <th align="left">ISBN</th>
                <th align="left">Publisher</th>
                <th align="center">Cost</th>
                <th align="center">MSRP</th>
                <th align="center">Sale price</th>
                <th align="center">Condition</th>
                <th align="center">Qty</th>
                <th align="left">&nbsp;</th>
                <th align="left">&nbsp;</th>
            </tr>

            <tr>
                <td colspan="10">
                    <hr/>
                </td>
            </tr>

            <?php
            while ($the_query->have_posts()):
                $the_query->the_post();
                ?>

                <tr>
                    <td width="30%" valign="top"><?php
                        the_title();
                        ?></td>

                    <td width="15%" valign="top" bgcolor="#f2f2f2"><?php
                        global $post;
                        echo get_post_meta($post->ID, '_cmb_resource_sku', true);
                        ?><?php
                        global $post;
                        echo get_post_meta($post->ID, '_cmb_resource_u-sku', true);
                        ?></td>

                    <td width="15%" valign="top"><?php
                        global $post;
                        echo get_post_meta($post->ID, '_cmb_resource_publisher', true);
                        ?></td>

                    <td width="5%" align="center" valign="top" bgcolor="#f2f2f2"><?php
                        global $post;
                        echo get_post_meta($post->ID, '_cmb_resource_cost', true);
                        ?></td>

                    <td width="5%" align="center" valign="top"><?php
                        global $post;
                        echo get_post_meta($post->ID, '_cmb_resource_MSRP', true);
                        ?></td>

                    <td width="10%" align="center" valign="top" bgcolor="#f2f2f2"><?php
                        global $post;
                        echo get_post_meta($post->ID, '_cmb_resource_price', true);
                        ?></td>

                    <td width="5%" align="center" valign="top"><?php
                        global $post;
                        echo get_post_meta($post->ID, '_cmb_resource_condition', true);
                        ?></td>

                    <td width="5%" align="center" valign="top" bgcolor="#f2f2f2"><?php
                        global $post;
                        echo get_post_meta($post->ID, '_cmb_resource_quantity', true);
                        ?></td>

                    <td valign="top" align="left" class="update" style="padding: 3px 4px 4px;">
                        <form id="addlibrary" action="" method="post">
                            <input type="hidden" name="product_id" value="<?php
                            the_ID();
                            ?>"/>
                            <input type="hidden" name="action" value="increment"/>
                            <?php
                            if ($library_title != "") {
                                ?>
                                <input type="hidden" name="search_title" value=<?php
                                echo "'$library_title''";
                                ?>/>
                                <?php
                            }
                            if ($search_publisher != "") {
                                ?>
                                <input type="hidden" name="search_publisher" value=<?php
                                echo "'$search_publisher'";
                                ?>/>
                                <?php
                            }
                            if ($search_ISBN != "") {
                                ?>
                                <input type="hidden" name="search_ISBN" value=<?php
                                echo "'$search_ISBN'";
                                ?>/>
                                <?php
                            }
                            ?>
                            <input type="submit" class="button-primary" value="+1"/>
                        </form>
                    </td>

                    <td valign="top" align="left" class="update" style="padding: 3px 4px 4px;">
                        <form id="addlibrary" action="" method="post">
                            <input type="hidden" name="product_id" value="<?php
                            the_ID();
                            ?>"/>
                            <input type="hidden" name="action" value="decrement"/>
                            <?php
                            if ($library_title != "") {
                                ?>
                                <input type="hidden" name="search_title" value=<?php
                                echo "'$library_title''";
                                ?>/>
                                <?php
                            }
                            if ($search_publisher != "") {
                                ?>
                                <input type="hidden" name="search_publisher" value=<?php
                                echo "'$search_publisher'";
                                ?>/>
                                <?php
                            }
                            if ($search_ISBN != "") {
                                ?>
                                <input type="hidden" name="search_ISBN" value=<?php
                                echo "'$search_ISBN'";
                                ?>/>
                                <?php
                            }
                            ?>
                            <input type="submit" class="button-primary" value="-1"/>
                        </form>
                    </td>

                </tr>

                <?php
            endwhile;
            ?>

        </table>

        <?php
    } else {
        ?>
        <p>No results were found. Complete the fields to a new entry into the library.</p>
        <?php
    }
}

?>