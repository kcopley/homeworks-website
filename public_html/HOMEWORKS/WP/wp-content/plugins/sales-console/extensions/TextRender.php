<?php

/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 12/27/2016
 * Time: 2:43 PM
 */
class TextRender
{
    public $renderable = '';

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
        if (gettype($obj) == 'string' || gettype($obj) == 'boolean' || gettype($obj) == 'integer' || gettype($obj) == 'double'){
            $this->renderable = $this->renderable.$obj;
        }
    }

    public function Render() {
        echo $this->renderable;
    }
}