<?php
include_once "BaseHTML.php";
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 12/27/2016
 * Time: 2:09 PM
 */
class HR extends BaseHTML
{
    function __construct()
    {
        parent::__construct(func_get_args());
        $this->type = 'hr';
    }
}