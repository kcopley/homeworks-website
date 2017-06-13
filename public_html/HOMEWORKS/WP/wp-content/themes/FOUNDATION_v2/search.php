<?php get_header(); 
$path = $_SERVER['DOCUMENT_ROOT'];
include($path.'/homeworks/public_html/HOMEWORKS/WP/wp-content/plugins/sales-console/includes.php');
?>

<article class="row">

    <?php

        $h1 = new H1(classType("offset-by-one"), new TextRender("Search Results"));
        $h1->Render();
    
        $paged = $_REQUEST['set_page_num'];
        if (!$paged || $paged <= 0) $paged = 0;
        $num_per_page = 16;

        $keyword = $_REQUEST['search_name'];
        $publisher = $_REQUEST['search_publisher'];
        $isbn = $_REQUEST['search_ISBN'];

        $arr = array(
            array(
                'key' => '_cmb_resource_available',
                'value' => 2,
                'compare' => '='
            ),
            array(
                'key' => '_cmb_resource_online',
                'value' => 2,
                'compare' => '='
            )
        );
        if ($publisher) {
           $arr[] = array(
               'key' => '_cmb_resource_publisher',
               'value' => $publisher,
               'compare' => 'LIKE'
           );
        }

        if ($isbn) {
            $arr[] = array(
                'key' => '_cmb_resource_isbn',
                'value' => $isbn,
                'compare' => '='
            );
        }

        $largs = array(
            'numberposts' => $num_per_page,
            'posts_per_page' => $num_per_page,
            'order'=> 'ASC',
            'orderby' => 'title',
            'post_type' => 'bookstore',
            'offset' => $num_per_page * $paged,
            'meta_query' => $arr
        );

        if ($keyword) {
            $largs['s'] = $keyword;
        }

	$lpostslist = new WP_Query( $largs );

	$count = $lpostslist->found_posts;

        if ( $lpostslist->have_posts()) {
            $divvy = new Div(style("display: inline-block; width: 80%;"));
            $divvy->add_object(new Div(style("display: inline-block; width: 50%; margin-right: 15px;"),
                    new H4(classType("offset-by-one"), new TextRender('('.$count.') resouces found'))));
            if ($paged > 0) {
                $divvy->add_object(new Form(style("display: inline-block; margin-right: 10px; margin-left: 10px;"),
                    new Input(type("hidden").id("set_page_num").name("set_page_num").value($paged - 1)),
                    button('Previous')
                ));
            }
            if ($paged * $num_per_page < $count){
                $divvy->add_object(new Form(style("display: inline-block; margin-right: 10px; margin-left: 10px;"),
                    new Input(type("hidden").id("set_page_num").name("set_page_num").value($paged + 1)),
                    button('Next')
                ));
            }
            $divvy->Render();
        }
        
        $table = new TableArr(style("table-layout: fixed; width: 100%;").border(0).cellpadding(0).cellspacing(2));

        $offset = false;
        
        if ($count > 0):
            $counter = 0;
            while ( $lpostslist->have_posts() && $counter < $num_per_page) :
                $lpostslist->the_post();

                if (!$offset){
                    $row = new Row();
                    $table->add_object($row);
                }

                $col = new Column(width(15));
                if ( has_post_thumbnail() ) {
                    $col->add_object(new TextRender(get_the_post_thumbnail( null, $size, $attr )));
                } else {
                    $col->add_object(
                        new IMG(src(get_bloginfo( "template_url" ) + '/images/noimage.gif'))
                    );
                }
                $row->add_object($col);

                $row->add_object(new Column(width(1)));

                $col = new Column(width(31));
                $div = new Div(classType("nine columns"),
                    new H4(new A(href(get_permalink()), new TextRender(get_the_title()))),
                    new Paragraph(new TextRender("Publisher: "), new TextRender(get_post_meta($post->ID, '_cmb_resource_publisher', true)),
                            new BR(),
                            new TextRender("Retail price: "), new TextRender('$'.number_format(get_post_meta($post->ID, '_cmb_resource_MSRP', true), 2)),
                            new BR(),
                            new Strong(new TextRender("Price: "), new Span(classType("price")), new TextRender('$'.number_format(get_post_meta($post->ID, '_cmb_resource_price', true), 2)))
                    ),
                    new A(classType("button").href(get_permalink()), new TextRender("See details"))
                );
                $col->add_object($div);
                $row->add_object($col);
                if (!$offset) {
                    $row->add_object(new Column(width(2)));
                    $offset = true;
                }
                else {
                    $offset = false;
                }
                $counter++;
            endwhile;
            $table->Render();
            
        else: ?>
            <div class="eleven columns offset-by-one">
                <h3>Sorry, no results/additional resources were found.</H3>
                <p>Yes, it can happen. However we're sure you'll find what you are looking for with a different search word &hellip;</p>
            </div>
        <?php endif;
        wp_reset_query(); ?>
</article>

<?php get_footer(); ?>