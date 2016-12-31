<?php
/**
 * Created by PhpStorm.
 * User: Kurtis
 * Date: 12/29/2016
 * Time: 2:23 PM
 */

function align($type) {
    return ' align="'.$type.'" ';
}

function width($percent) {
    return ' width="'.$percent.'%" ';
}

function value($str) {
    return ' value="'.$str.'" ';
}

function action($str) {
    return ' action="'.$str.'" ';
}

function id($str) {
    return ' id="'.$str.'" ';
}

function method($str) {
    return ' method="'.$str.'" ';
}

function name($str) {
    return ' name="'.$str.'" ';
}

function colspan($str) {
    return ' colspan="'.$str.'" ';
}

function valign($str) {
    return ' valign="'.$str.'" ';
}

function border($str) {
    return ' border="'.$str.'" ';
}

function cellpadding($str) {
    return ' cellpadding="'.$str.'" ';
}

function cellspacing($str) {
    return ' cellspacing="'.$str.'" ';
}

function type($str) {
    return ' type="'.$str.'" ';
}

function classType($str) {
    return ' class="'.$str.'" ';
}

function style($str){
    return ' style="'.$str.'" ';
}

function size($str){
    return ' size="'.$str.'" ';
}

function form($str){
    return ' form="'.$str.'" ';
}

function add_image($id) {
    set_post_thumbnail($id, absint( $_POST[book_request::$image_set]));
}

function button($value) {
    return new Input(classType('button-primary').type('submit').name('button').value($value));
}