<?php

/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 12/27/2016
 * Time: 2:43 PM
 */
class RenderList
{
    public $renderables = array();

    function __construct()
    {
        $args = func_get_args();
        if ($args) {
            if (!is_array($args)){
                $this->add_object($args);
            }
            else {
                foreach ($args as $arg) {
                    $this->add_object($arg);
                }
            }
        }
    }

    public function add_object($obj) {
        if (method_exists($obj, 'Render')) {
            $this->renderables[] = $obj;
        }
    }

    public function Render() {
        foreach ($this->renderables as $row) {
            $row->Render();
        }
    }
}