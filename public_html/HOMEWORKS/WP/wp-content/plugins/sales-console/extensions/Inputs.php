<?php
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 1/16/2017
 * Time: 12:32 PM
 */

class __input {
    public $name;
    public $format;
    public $db_value;

    public $search_param;
    public $display_in_search;
    public $edit_param;
    public $display_in_edit;
    public $add_param;
    public $display_in_add;

    //default to true
    function __construct($name, $format, $db_value)
    {
        $this->name = $name;
        $this->format = $format;
        $this->db_value = $db_value;

        $this->search_param = true;
        $this->display_in_search = true;
        $this->add_param = true;
        $this->display_in_add = true;
        $this->edit_param = true;
        $this->display_in_edit = true;
    }

    public function GetValue($id) {
        if ($this->db_value == 'title') {
            return get_the_title($id);
        }
        else if ($this->db_value == 'image')
            return has_post_thumbnail($id);
        else
            return get_post_meta($id, $this->db_value, true);
    }

    public function SetValue($id, $value) {
        if ($this->db_value == 'title') {
            $titleupdate = array(
                'ID'           => $id,
                'post_title'   => $value,
            );
            wp_update_post($titleupdate);
        }
        else {
            update_post_meta($id, $this->db_value, $value);
        }
    }

    public function GetPostValue($prefix) {
        return $_REQUEST[$prefix.$this->name];
    }

    public function GetSessionValue($prefix) {
        return $_SESSION[$prefix.$this->name];
    }

    public function SetSessionValue($prefix) {
        $val = $this->GetPostValue($prefix);
        if (!$val) $val = $this->GetSessionValue($prefix);
        if ($val) $_SESSION[$prefix.$this->name] = $val;
    }

    public function UnsetSessionValue($prefix) {
        unset($_SESSION[$prefix.$this->name]);
    }
}

class Radio extends __input {
    public $options;

    public function GetInputSearch($leftWidth, $rightWidth, $prefix) {
        $row = new Row();

        $row->add_object(new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))));
        $col = new Column(width($rightWidth).style('padding-left: 5px; display: inline;'));
        $row->add_object($col);
        $col->add_object(new TextRender('All: '));
        $col->add_object(new Input(id($prefix.$this->name).name($prefix.$this->name).type('radio').value('All')));
        foreach ($this->options as $key => $value) {
            $col->add_object(new TextRender($value.': '));
            $col->add_object(new Input(id($prefix.$this->name).name($prefix.$this->name).type('radio').value($key)));
        }

        return $row;
    }

    public function GetInputAdd($leftWidth, $rightWidth, $prefix) {
        $row = new Row();

        $row->add_object(new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))));
        $col = new Column(width($rightWidth).style('padding-left: 5px;'));
        $row->add_object($col);

        foreach ($this->options as $key => $value) {
            $col->add_object(new TextRender($value.': '));
            $col->add_object(new Input(id($prefix.$this->name).name($prefix.$this->name).type('radio').value($key)));
        }

        return $row;
    }

    public function GetQuery($args, $prefix) {
        $query = $_SESSION[$prefix.$this->name];
        if ($query && $query != 'All') {
            $args['meta_query'][] =  array(
                'key' => $this->db_value,
                'value' => $query,
                'compare' => 'LIKE'
            );
        }
        return $args;
    }

    public function GetDisplay($id, $source) {
        return new Column(style('padding-bottom: 8px; font-size: 14px'), new TextRender($this->options[$this->GetValue($id)]));
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        $row = new Row();
        $row->add_object(new Column(align('right').valign('center').width($leftwidth), new Label(new TextRender($this->format.':'))));
        $col =  new Column(width($rightwidth).style('padding-left: 5px;'));
        $row->add_object($col);

        $val = $this->options[$this->GetValue($id)];

        if ($this->edit_param) {
            foreach ($this->options as $key => $value) {
                if ($val == $value) {
                    $col->add_object(new TextRender($value.': '));
                    $col->add_object(new Input(checkedAttr(1).form($form).id(vars::$edit_prefix.$this->name).name(vars::$edit_prefix.$this->name).type('radio').value($key)));
                }
                else {
                    $col->add_object(new TextRender($value.': '));
                    $col->add_object(new Input(form($form).id(vars::$edit_prefix.$this->name).name(vars::$edit_prefix.$this->name).type('radio').value($key)));
                }
            }
        }
        else {
            $col->add_object(new Strong(new TextRender($val)));
        }
        return $row;
    }
}

class Image extends __input {
    public $options;
    private function get_image_form($id, $source) {
        $color = 'red';
        $text = 'No';
        if ($this->GetValue($id)){
            $text = 'Yes';
            $color = 'green';
        }
        return
            new Form(method('post').id($id).name($id),
                page_action::InputAction(action_types::add_image($source)),
                selection::SetID($id, $source),
                new Input(type('hidden').name(Book::$image_set).id(Book::$image_set)),
                new Input(id($id).type('button').classType('upload_image_button').style('color: '.$color.';').value($text))
            );
    }

    public function GetInputSearch($leftWidth, $rightWidth, $prefix) {
        $row = new Row();
        $row->add_object(new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))));
        $col = new Column(width($rightWidth).style('padding-left: 5px;'));
        $row->add_object($col);
        $col->add_object(new TextRender('All: '));
        $col->add_object(new Input(id($prefix.$this->name).name($prefix.$this->name).type('radio').value('All')));
        foreach ($this->options as $key => $value) {
            $col->add_object(new TextRender($value.': '));
            $col->add_object(new Input(id($prefix.$this->name).name($prefix.$this->name).type('radio').value($key)));
        }
        return $row;
    }

    public function GetInputAdd($leftWidth, $rightWidth, $prefix) {
        $row = new Row();
        return $row;
    }

    public function GetQuery($args, $prefix) {
        $query = $_SESSION[$prefix.$this->name];
        if ($query) {
            if ($query == 'All') {
                return $args;
            }
            else if ($query == 2)
            {
                $args['meta_query'][] =  array(
                    'key' => '_thumbnail_id',
                    'compare' => 'EXISTS'
                );
            }
            else {
                $args['meta_query'][] =  array(
                    'key' => '_thumbnail_id',
                    'compare' => 'NOT EXISTS'
                );
            }
        }
        return $args;
    }

    public function GetDisplay($id, $source) {
        return new Column($this->get_image_form($id, $source));
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        return new Row(
            new Column(align('right').width($leftwidth), new Label(new TextRender($this->format.':'))),
            new Column(align('left').width($rightwidth).style('padding-left: 5px;'),
                $this->get_image_form($id, Book::$source)
            )
        );
    }
}

class Date extends __input  {
    function GetInputAdd($leftWidth, $rightWidth, $prefix) {
        $list = new RenderList();
        $list->add_object(
            new Row(
                new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))),
                new Column(width($rightWidth).style('padding-left: 5px;'),
                    new Input(style('width: 100%;').id($prefix.$this->name).name($prefix.$this->name).type('date'))
                )
            )
        );
        return $list;
    }

    function GetInputSearch($leftWidth, $rightWidth, $prefix) {
        $list = new RenderList();
        $list->add_object(
            new Row(
                new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))),
                new Column(width($rightWidth / 2).style('padding-left: 5px; display: inline;'),
                    new Label(new TextRender(' From: ')),
                    new Input(style('width: 35%;').id($prefix.$this->name.'_from').name($prefix.$this->name.'_from').type('date')),
                    new Label(new TextRender(' To: ')),
                    new Input(style('width: 35%;').id($prefix.$this->name.'_to').name($prefix.$this->name.'_to').type('date'))
                )
            )
        );
        return $list;
    }

    public function SetSessionValue($prefix)
    {
        $datefrom = $_REQUEST[$prefix.$this->name.'_from'];
        if (!$datefrom) $datefrom = $_SESSION[$prefix.$this->name.'_from'];

        $dateto = $_REQUEST[$prefix.$this->name.'_to'];
        if (!$dateto) $dateto = $_SESSION[$prefix.$this->name.'_to'];

        $_SESSION[$prefix.$this->name.'_from'] = $datefrom;
        $_SESSION[$prefix.$this->name.'_to'] = $dateto;
    }

    public function UnsetSessionValue($prefix)
    {
        unset($_SESSION[$prefix.$this->name.'_from']);
        unset($_SESSION[$prefix.$this->name.'_to']);
    }

    function GetQuery($args, $prefix) {
        date_default_timezone_set('America/Chicago');
        $datefrom = $_SESSION[$prefix.$this->name.'_from'];
        if (!$datefrom) $datefrom = date("Y-m-d", mktime(0, 0, 0, 1, 1, 2014));
        $dateto = $_SESSION[$prefix.$this->name.'_to'];
        if (!$dateto) $dateto = date('Y-m-d');

        if ($datefrom && $dateto)
            $args['meta_query'][] = array(
                'key' => $this->db_value,
                'value' => array($datefrom, $dateto),
                'compare' => 'BETWEEN',
                'type' => 'DATE'
            );
        return $args;
    }

    public function GetDisplay($id, $source) {
        return new Column(style('padding-bottom: 8px; font-size: 14px'), new TextRender(get_post_meta($id, $this->db_value, true)));
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        return new Row(
            new Column(align('right').width($leftwidth), new Label(new TextRender($this->format.':'))),
            new Column(width($rightwidth).style('padding-left: 5px;'),
                new Input(form($form).style('width: 90%;').id(vars::$edit_prefix.$this->name).name(vars::$edit_prefix.$this->name).type('date').value($this->GetValue($id)))
            )
        );
    }
}

class Decimal extends __input  {
    public $cost = false;
    public $display_prefix;

    function GetInputAdd($leftWidth, $rightWidth, $prefix) {
        $list = new RenderList();
        $list->add_object(
            new Row(
                new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))),
                new Column(width($rightWidth).style('padding-left: 5px;'),
                    new Input(style('width: 100%;').id($prefix.$this->name).name($prefix.$this->name).type('text'))
                )
            )
        );
        return $list;
    }

    function GetInputSearch($leftWidth, $rightWidth, $prefix) {
        $list = new RenderList();
        $list->add_object(
            new Row(
                new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))),
                new Column(width($rightWidth).style('padding-left: 5px;'),
                    new TableArr(width(100).cellpadding(0).cellspacing(0),
                        new Row(
                            new Column(new Label(new TextRender(' From:'))),
                            new Column(new Input(style('width: 80%; display: inline;').id($prefix.$this->name.'_from').name($prefix.$this->name.'_from').type('text')))
                        ),
                        new Row(
                            new Column(new Label(new TextRender(' To:'))),
                            new Column(new Input(style('width: 80%; display: inline;').id($prefix.$this->name.'_to').name($prefix.$this->name.'_to').type('text')))
                        )
                    )
                )
            )
        );
        return $list;
    }

    public function SetSessionValue($prefix)
    {
        $valfrom = $_REQUEST[$prefix.$this->name.'_from'];
        if (!$valfrom) $valfrom = $_SESSION[$prefix.$this->name.'_from'];

        $valto = $_REQUEST[$prefix.$this->name.'_to'];
        if (!$valto) $valto = $_SESSION[$prefix.$this->name.'_to'];

        $_SESSION[$prefix.$this->name.'_from'] = $valfrom;
        $_SESSION[$prefix.$this->name.'_to'] = $valto;
    }

    public function UnsetSessionValue($prefix)
    {
        unset($_SESSION[$prefix.$this->name.'_from']);
        unset($_SESSION[$prefix.$this->name.'_to']);
    }

    function GetQuery($args, $prefix) {
        add_filter('get_meta_sql','cast_decimal_precision');

        $totalfrom = $_SESSION[$prefix.$this->name.'_from'];
        if (!$totalfrom) $totalfrom = -9999999.0;
        $totalto = $_SESSION[$prefix.$this->name.'_to'];
        if (!$totalto) $totalto = 9999999.0;

        if ($totalfrom) {
            $args['meta_query'][] = array(
                'key' => $this->db_value,
                'value' => $totalfrom,
                'compare' => '>',
                'type' => 'DECIMAL'
            );
        }
        if ($totalto) {
            $args['meta_query'][] = array(
                'key' => $this->db_value,
                'value' => $totalto,
                'compare' => '<',
                'type' => 'DECIMAL'
            );
        }

        return $args;
    }

    public function GetDisplay($id, $source) {
        if ($this->cost) {
            return new Column(style('padding-bottom: 8px; font-size: 14px'), new TextRender($this->display_prefix.number_format(get_post_meta($id, $this->db_value, true), 2)));
        }
        else {
            return new Column(style('padding-bottom: 8px; font-size: 14px'), new TextRender(get_post_meta($id, $this->db_value, true)));
        }
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        if ($this->edit_param) {
            return new Row(
                new Column(align('right') . width($leftwidth), new Label(new TextRender($this->format . ':'))),
                new Column(width($rightwidth) . style('padding-left: 5px;'),
                    new Input(form($form) . style('width: 90%;') . id(vars::$edit_prefix . $this->name) . name(vars::$edit_prefix . $this->name) . type('number') . value($this->GetValue($id)))
                )
            );
        }
        else {
            if ($this->cost) {
                return new Row(
                    new Column(align('right') . width($leftwidth), new Label(new TextRender($this->format . ':'))),
                    new Column(width($rightwidth) . style('padding-left: 5px;'),
                        new Strong(new TextRender($this->display_prefix.number_format($this->GetValue($id), 2)))
                    )
                );
            }
            else {
                return new Row(
                    new Column(align('right') . width($leftwidth), new Label(new TextRender($this->format . ':'))),
                    new Column(width($rightwidth) . style('padding-left: 5px;'),
                        new Strong(new TextRender($this->GetValue($id)))
                    )
                );
            }
        }
    }
}

class TextBox extends __input  {
    public function GetInputSearch($leftWidth, $rightWidth, $prefix) {
        $row = new Row(
            new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))),
            new Column(width($rightWidth).style('padding-left: 5px;'),
                new TextArea(style('width: 90%; padding: 4px 3px 4px').type('text').id($prefix.$this->name).name($prefix.$this->name))
            )
        );
        return $row;
    }

    public function GetInputAdd($leftWidth, $rightWidth, $prefix) {
        $row = new Row(
            new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))),
            new Column(width($rightWidth).style('padding-left: 5px;'),
                new TextArea(style('width: 90%; padding: 4px 3px 4px').type('text').id($prefix.$this->name).name($prefix.$this->name))
            )
        );
        return $row;
    }

    public function GetQuery($args, $prefix) {
        return $args;
    }

    public function GetDisplay($id, $source) {
        return new Column(style('padding-bottom: 8px; font-size: 14px'), new TextRender(get_post_meta($id, $this->db_value, true)));
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        return new Row(
            new Column(align('right').width($leftwidth), new Label(new TextRender($this->format.':'))),
            new Column(width($rightwidth).style('padding-left: 5px;'),
                new TextArea(form($form).style('width: 90%;').type('text').id(vars::$edit_prefix.$this->name).name(vars::$edit_prefix.$this->name), new TextRender($this->GetValue($id)))
            )
        );
    }
}

class Quantity extends __input {
    public $exact = -1;
    public $display_prefix;

    public function GetValue($id)
    {
        $consigners = Book::get_consigner_count($id);
        return $consigners;
    }

    public function SetValue($id, $value)
    {
        $consigner = Book::$props[Book::$consigner_id]->GetPostValue(vars::$add_prefix);
        $consigner = get_consigner_wp_id($consigner);
        if (!$consigner) {
            $consigner = get_consigner_owner_id();
        }
        for ($i = 0; $i < $value; $i++) {
            Book::add_book($id, $consigner);
        }
    }

    public function GetInputSearch($leftWidth, $rightWidth, $prefix) {
        $list = new RenderList();
        return $list;
    }

    public function GetInputAdd($leftWidth, $rightWidth, $prefix) {
        $row = new Row(
            new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))),
            new Column(width($rightWidth).style('padding-left: 5px;'),
                new Input(style('width: 100%;').id($prefix.$this->name).name($prefix.$this->name).type('text'))
            )
        );
        return $row;
    }

    public function GetQuery($args, $prefix) {
        return $args;
    }

    public function GetDisplay($id, $source) {
        return new Column(style('font-size: 14px; padding-right: 2px;'),
            new TextRender($this->display_prefix.$this->GetValue($id)));
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        $val = $this->GetValue($id);
        return new Row(
            new Column(align('right').width($leftwidth), new Label(new TextRender($this->format.':'))),
            new Column(width($rightwidth).style('padding-left: 5px; width: 90%;'),
                new Strong(new TextRender($val))
            )
        );
    }
}

class ConsignerID extends __input {
    public $exact = -1;
    public $display_prefix;

    public function GetValue($id)
    {
    }

    public function SetValue($id, $value)
    {
    }

    public function GetInputSearch($leftWidth, $rightWidth, $prefix) {
        $list = new RenderList();
        return $list;
    }

    public function GetInputAdd($leftWidth, $rightWidth, $prefix) {
        $row = new Row(
            new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))),
            new Column(width($rightWidth).style('padding-left: 5px;'),
                new Input(style('width: 100%;').id($prefix.$this->name).name($prefix.$this->name).type('text'))
            )
        );
        return $row;
    }

    public function GetQuery($args, $prefix) {
        return $args;
    }

    public function GetDisplay($id, $source) {
        return new RenderList();
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        $row = new Row(

        );
        return $row;
    }
}

class Text extends __input  {
    public $exact = -1;
    public $display_prefix;

    public function GetInputSearch($leftWidth, $rightWidth, $prefix) {
        $row = new Row(
            new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))),
            new Column(width($rightWidth).style('padding-left: 5px;'),
                new Input(style('width: 100%;').id($prefix.$this->name).name($prefix.$this->name).type('text'))
            )
        );
        return $row;
    }

    public function GetInputAdd($leftWidth, $rightWidth, $prefix) {
        $row = new Row(
            new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))),
            new Column(width($rightWidth).style('padding-left: 5px;'),
                new Input(style('width: 100%;').id($prefix.$this->name).name($prefix.$this->name).type('text'))
            )
        );
        return $row;
    }

    public function GetQuery($args, $prefix) {
        $query = $_SESSION[$prefix.$this->name];
        if ($query) {
            if ($this->db_value == 'title') {
                    $args['s'] = $query;
            }
            else {
                if ($this->exact != -1) {
                    $args['meta_query'][] =  array(
                        'key' => $this->db_value,
                        'value' => $query
                    );
                }
                else {
                    $args['meta_query'][] =  array(
                        'key' => $this->db_value,
                        'value' => $query,
                        'compare' => 'LIKE'
                    );
                }
            }
        }

        return $args;
    }

    public function GetDisplay($id, $source) {
        if ($this->db_value == 'title') {
            $action = vars::GetPage($source);
            return new Column(style('padding-right: 8px;'),
                new Form(method('POST').action($action),
                    page_action::InputAction(action_types::get_select($source)),
                    selection::SetID($id, $source),
                    new Input(classType('button').type('submit').name('button').value(get_the_title($id)).style('background:none!important; border:none; 
                        padding:0!important; font-family:arial,sans-serif; color:#069; box-shadow: 0 0px 0 #ccc; cursor:pointer;'))
                )
            );
        }
        else {
            return new Column(style('font-size: 14px; padding-right: 2px;'),
                new TextRender($this->display_prefix.get_post_meta($id, $this->db_value, true)));
        }
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        $val = $this->GetValue($id);
        if ($this->edit_param) {
            return new Row(
                new Column(align('right').width($leftwidth), new Label(new TextRender($this->format.':'))),
                new Column(width($rightwidth).style('padding-left: 5px;'),
                    new Input(style('width: 90%;').form($form).id(vars::$edit_prefix.$this->name).name(vars::$edit_prefix.$this->name).type('text').value($val))
                )
            );
        }
        else {
            return new Row(
                new Column(align('right').width($leftwidth), new Label(new TextRender($this->format.':'))),
                new Column(width($rightwidth).style('padding-left: 5px; width: 90%;'),
                    new Strong(new TextRender($val))
                )
            );
        }
    }
}

class Category extends __input  {
    public $exact = -1;
    public $display_prefix;

    function get_dropdown($prefix) {
        /*
        $categories = get_categories(array('fields' => 'id=>slug'));

        $div = new Div(id('checkboxes').name('checkboxes').classType('checkboxes'));
        if ($form == -1) {
            foreach ($categories as $catID => $name) {
                $div->add_object(
                    new Label(forAttr($catID),
                        new Input(type('checkbox').id($prefix.$catID).name($prefix.$catID),
                            new TextRender(
                                $name
                            )
                        )
                    )
                );
            }
        }
        else {
            foreach ($categories as $catID => $name) {
                $div->add_object(
                    new Label(forAttr($catID),
                        new Input(form($form).type('checkbox').id($prefix.$catID).name($prefix.$catID),
                            new TextRender(
                                $name
                            )
                        )
                    )
                );
            }
        }
        return new Div(classType('multiselect'),
            new Div(classType('selectBox'),
                new Select(
                    new Option(new TextRender('Select an option'))
                ),
                new Div(classType('overSelect'))
            ),
            $div
        );
        */
        $text = wp_dropdown_categories(
            array(
                'hide_empty' => 0,
                'name' => $prefix.$this->name,
                'hierarchical' => true,
                'show_option_all' => 'Choose one',
                'echo' => 0,
                'value_field' => 'term_id'
            )
        );
        if (get_option(vars::$allow_multiple_categories_option) == 1) {
            $text = str_replace('<select', '<select multiple ', $text);
        }
        return new TextRender(
            $text
        );
    }

    function get_dropdown_checked($val, $prefix, $form) {
        /*
        $categories = get_categories(array('fields' => 'id=>slug'));
        $cats = wp_get_post_categories($val, array('fields' => 'ids'));
        $div = new Div(id('checkboxes').name('checkboxes').classType('checkboxes'));
        foreach ($categories as $catID => $name) {
            if (in_array($catID, $cats)) {
                $div->add_object(
                    new Label(forAttr($catID),
                        new Input(form($form).type('checkbox').checkedAttr(true).id($prefix.$catID).name($prefix.$catID),
                            new TextRender(
                                $name
                            )
                        )
                    )
                );
            }
            else {
                $div->add_object(
                    new Label(forAttr($catID),
                        new Input(form($form).type('checkbox').id($prefix.$catID).name($prefix.$catID),
                            new TextRender(
                                $name
                            )
                        )
                    )
                );
            }
        }
        return new Div(classType('multiselect'),
            new Div(classType('selectBox'),
                new Select(
                    new Option(new TextRender('Select an option'))
                ),
                new Div(classType('overSelect'))
            ),
            $div
        );
        */

        $text = wp_dropdown_categories(
            array(
                'hide_empty' => 0,
                'name' => $prefix.$this->name,
                'hierarchical' => true,
                'show_option_all' => 'Choose one',
                'echo' => 0,
                'selected' => $val,
                'value_field' => 'term_id'
            )
        );
        if (get_option(vars::$allow_multiple_categories_option) == 1) {
            $text = str_replace('<select', '<select '.form($form).' multiple ', $text);
        }
        else {
            $text = str_replace('<select', '<select '.form($form), $text);
        }
        return new TextRender(
            $text
        );
    }

    public function GetInputSearch($leftWidth, $rightWidth, $prefix) {
        $row = new Row(
            new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))),
            new Column(width($rightWidth).style('padding-left: 5px;'),
                $this->get_dropdown($prefix)
            )
        );
        return $row;
    }

    public function GetInputAdd($leftWidth, $rightWidth, $prefix) {
        $row = new Row(
            new Column(align('right').width($leftWidth), new Label(new TextRender($this->format.':'))),
            new Column(width($rightWidth).style('padding-left: 5px;'),
                $this->get_dropdown($prefix)
            )
        );
        return $row;
    }

    public function GetPostValue($prefix)
    {
        /*
        $categories = get_categories(array('fields' => 'id=>slug'));
        $ret = array();
        foreach ($categories as $catID => $name) {
            $precat = $prefix.$catID;
            if (isset($_REQUEST[$precat])) {
                $ret[] = $catID;
            }
        }
        return $ret;
        */
        return $_POST[$prefix.$this->name];
    }

    public function GetValue($id)
    {
        $defaults = array('fields' => 'ids');
        $ret = wp_get_post_categories($id, $defaults)[0];
        return $ret;
    }

    public function SetValue($id, $value)
    {
        wp_set_post_categories($id, $value);
    }

    public function GetQuery($args, $prefix) {
        $query = $this->GetPostValue($prefix);

        if ($query) {
            if ($this->db_value == 'category') {
                $args['category__in'] = $query;
            }
        }
        return $args;
    }

    public function GetDisplay($id, $source) {
        $args = array('fields' => 'names');
        $cats = wp_get_post_categories($id, $args);
        $col = new Column(style('font-size: 14px; padding-right: 2px;'));
        $first = true;
        foreach ($cats as $cat) {
            if (!$first) {
                $col->add_object(new TextRender(', '));
            }
            $col->add_object(new TextRender($cat));
            $first = false;
        }
        return $col;
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        $val = $this->GetValue($id);
        return new Row(
            new Column(align('right').width($leftwidth), new Label(new TextRender($this->format.':'))),
            new Column(width($rightwidth).style('padding-left: 5px;'),
                $this->get_dropdown_checked($val, vars::$edit_prefix, $form)
            )
        );
    }
}

class Subtotal extends __input {
    public function GetPostValue($prefix)
    {
    }

    public function GetSessionValue($prefix)
    {
    }

    public function GetValue($id)
    {
        return Transaction::get_subtotal($id) - Transaction::get_refund_total($id);
    }

    public function UnsetSessionValue($prefix)
    {
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        $val = $this->GetValue($id);
        return new Row(
            new Column(align('right').width($leftwidth), new Label(new TextRender($this->format.':'))),
            new Column(width($rightwidth).style('padding-left: 5px; width: 90%;'),
                new Strong(new TextRender('$'.number_format($val, 2)))
            )
        );
    }
}

class TaxTotal extends __input {
    public function GetPostValue($prefix)
    {
    }

    public function GetSessionValue($prefix)
    {
    }

    public function GetValue($id)
    {
        return Transaction::get_tax_total($id);
    }

    public function UnsetSessionValue($prefix)
    {
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        $val = $this->GetValue($id);
        return new Row(
            new Column(align('right').width($leftwidth), new Label(new TextRender($this->format.':'))),
            new Column(width($rightwidth).style('padding-left: 5px; width: 90%;'),
                new Strong(new TextRender('$'.number_format($val, 2)))
            )
        );
    }
}

class BookButton {
    public $button;
    public $action;
    public $custom;

    public $search_param;
    public $display_in_search;
    public $edit_param;
    public $display_in_edit;
    public $add_param;
    public $display_in_add;

    function __construct($name)
    {
        $this->name = $name;

        $this->search_param = true;
        $this->display_in_search = true;
        $this->add_param = true;
        $this->display_in_add = true;
        $this->edit_param = true;
        $this->display_in_edit = true;
    }

    public function GetInputSearch($leftWidth, $rightWidth, $prefix) {
    }

    public function GetInputAdd($leftWidth, $rightWidth, $prefix) {
    }

    public function GetQuery($args, $prefix) {
        return $args;
    }

    public function GetDisplay($id, $source) {
        if ($this->custom) {
            $button = $this->custom;
        }
        else {
            $button = new Input(classType('button-primary').type('submit').name('button').value($this->button));
        }
        return
            new Column(
                new Form(
                    page_action::InputAction($this->action),
                    selection::SetID($id, Book::$source),
                    $button
                )
            );
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        if ($this->custom) {
            $button = $this->custom;
        }
        else {
            $button = new Input(classType('button-primary').type('submit').name('button').value($this->button));
        }
        return new Row(
            new Column(width($leftwidth)),
            new Column(width($rightwidth).align('left'),
                new Form(
                    page_action::InputAction($this->action),
                    selection::SetID($id, Book::$source),
                    $button
                )
            )
        );
    }
}

class ConsignerRemoveButton {
    public $button;
    public $action;
    public $custom;

    public $search_param;
    public $display_in_search;
    public $edit_param;
    public $display_in_edit;
    public $add_param;
    public $display_in_add;

    function __construct($name)
    {
        $this->name = $name;

        $this->search_param = true;
        $this->display_in_search = true;
        $this->add_param = true;
        $this->display_in_add = true;
        $this->edit_param = true;
        $this->display_in_edit = true;
    }

    public function GetInputSearch($leftWidth, $rightWidth, $prefix) {
    }

    public function GetInputAdd($leftWidth, $rightWidth, $prefix) {
    }

    public function GetQuery($args, $prefix) {
        return $args;
    }

    public function GetDisplay($id, $source) {
        if ($this->custom) {
            $button = $this->custom;
        }
        else {
            $button = new Input(classType('button-primary').type('submit').name('button').value($this->button));
        }
        return
            new Column(
                new Form(
                    page_action::InputAction($this->action),
                    selection::SetID($id, Consigner::$source),
                    $button
                )
            );
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        if ($this->custom) {
            $button = $this->custom;
        }
        else {
            $button = new Input(classType('button-primary').type('submit').name('button').value($this->button));
        }
        return new Row(
            new Column(width($leftwidth)),
            new Column(width($rightwidth).align('left'),
                new Form(
                    page_action::InputAction($this->action),
                    selection::SetID($id, Book::$source),
                    $button
                )
            )
        );
    }
}

class TransactionButton {
    public $button;
    public $action;
    public $custom;
    public $formaction;

    public $search_param;
    public $display_in_search;
    public $edit_param;
    public $display_in_edit;
    public $add_param;
    public $display_in_add;

    function __construct($name)
    {
        $this->name = $name;

        $this->search_param = true;
        $this->display_in_search = true;
        $this->add_param = true;
        $this->display_in_add = true;
        $this->edit_param = true;
        $this->display_in_edit = true;
    }

    public function GetInputSearch($leftWidth, $rightWidth, $prefix) {
    }

    public function GetInputAdd($leftWidth, $rightWidth, $prefix) {
    }

    public function GetQuery($args, $prefix) {
        return $args;
    }

    public function GetDisplay($id, $source) {
        if ($this->custom) {
            $button = $this->custom;
            $button->add_object(form($id.$source));
        }
        else {
            $button = new Input(form($id.$source).classType('button-primary').type('submit').name('button').value($this->button));
        }
        if ($this->formaction) {
            return new Column(
                new Form(id($id.$source).name($id.$source).action($this->formaction),
                    page_action::InputActionForm($this->action, $id.$source),
                    selection::SetIDForm($id, Transaction::$source, $id.$source),
                    $button
                )
            );
        }
        return new Column(
            new Form(id($id.$source).name($id.$source),
                page_action::InputActionForm($this->action, $id.$source),
                selection::SetIDForm($id, Transaction::$source, $id.$source),
                $button
            )
        );
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        if ($this->custom) {
            $button = $this->custom;
            $button->add_object(form($id));
        }
        else {
            $button = new Input(form($id).classType('button-primary').type('submit').name('button').value($this->button));
        }

        if ($this->formaction) {
            return new Row(
                new Column(width($leftwidth)),
                new Column(width($rightwidth),
                    new Form(id($id).name($id).action($this->formaction),
                        page_action::InputActionForm($this->action, $id),
                        selection::SetIDForm($id, Transaction::$source, $id),
                        $button
                    )
                )
            );
        }
        return new Row(
            new Column(width($leftwidth)),
            new Column(width($rightwidth),
                new Form(id($id).name($id),
                    page_action::InputActionForm($this->action, $id),
                    selection::SetIDForm($id, Transaction::$source, $id),
                    $button
                )
            )
        );
    }
}

class ImportButton {
    public $button;
    public $action;
    public $custom;
    public $formaction;

    public $search_param;
    public $display_in_search;
    public $edit_param;
    public $display_in_edit;
    public $add_param;
    public $display_in_add;

    function __construct($name)
    {
        $this->name = $name;

        $this->search_param = true;
        $this->display_in_search = true;
        $this->add_param = true;
        $this->display_in_add = true;
        $this->edit_param = true;
        $this->display_in_edit = true;
    }

    public function GetInputSearch($leftWidth, $rightWidth, $prefix) {
    }

    public function GetInputAdd($leftWidth, $rightWidth, $prefix) {
    }

    public function GetQuery($args, $prefix) {
        return $args;
    }

    public function GetDisplay($id, $source) {
        $block = false;
        if (Transaction::$props[Transaction::$complete]->GetValue($id) == 2) {
            //$block = true;
        }

        if ($this->custom) {
            $button = $this->custom;
            $button->add_object(form($id.$source));
        }
        else {
            if ($block) $button = new Input(form($id.$source).classType('button').name('button').value($this->button));
            else $button = new Input(form($id.$source).classType('button-primary').type('submit').name('button').value($this->button));
        }
        if ($this->formaction) {
            return new Column(
                new Form(id($id.$source).name($id.$source).action($this->formaction),
                    page_action::InputActionForm($this->action, $id.$source),
                    selection::SetIDForm($id, Transaction::$source, $id.$source),
                    $button
                )
            );
        }
        return new Column(
            new Form(id($id.$source).name($id.$source),
                page_action::InputActionForm($this->action, $id.$source),
                selection::SetIDForm($id, Transaction::$source, $id.$source),
                $button
            )
        );
    }

    public function GetEditForm($id, $leftwidth, $rightwidth, $form) {
        $block = false;
        if (Transaction::$props[Transaction::$complete]->GetValue($id) == 2) {
            //$block = true;
        }
        if ($this->custom) {
            $button = $this->custom;
            $button->add_object(form($id.'import'));
        }
        else {
            if ($block) $button = new Input(form($id.'import').classType('button').name('button').value($this->button));
            else $button = new Input(form($id.'import').classType('button-primary').type('submit').name('button').value($this->button));
        }

        if ($this->formaction) {
            return new Row(
                new Column(width($leftwidth)),
                new Column(width($rightwidth),
                    new Form(id($id.'import').name($id.'import').action($this->formaction),
                        page_action::InputActionForm($this->action, $id.'import'),
                        selection::SetIDForm($id, Transaction::$source, $id.'import'),
                        $button
                    )
                )
            );
        }
        return new Row(
            new Column(width($leftwidth)),
            new Column(width($rightwidth),
                new Form(id($id).name($id),
                    page_action::InputActionForm($this->action, $id),
                    selection::SetIDForm($id, Transaction::$source, $id),
                    $button
                )
            )
        );
    }
}