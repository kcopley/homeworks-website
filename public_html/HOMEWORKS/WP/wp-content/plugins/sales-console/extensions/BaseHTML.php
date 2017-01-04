<?php

/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 12/27/2016
 * Time: 2:09 PM
 */
class BaseHTML
{
    public $renderables = array();
    public $mod = '';
    public $type = '';

    public $close_tags = true;

    function __construct()
    {
        $args = func_get_args()[0];
        //var_dump($args);
        if ($args) {
            foreach ($args as $arg) {
                $this->add_object($arg);
            }
        }
    }

    public function add_object($obj) {
        if (method_exists($obj, 'Render')) {
            $this->renderables[] = $obj;
        }
        else {
            $this->mod = $this->mod.$obj;
        }
    }

    public function Render() {
        if ($this->close_tags) {
            echo '<' . $this->type . ' ' . $this->mod . ' >';
            foreach ($this->renderables as $row) {
                $row->Render();
            }
            echo '</' . $this->type . '>';
        }
        else {
            echo '<' . $this->type . ' ' . $this->mod . ' ';
            foreach ($this->renderables as $row) {
                $row->Render();
            }
            echo ' />';
        }
    }
}