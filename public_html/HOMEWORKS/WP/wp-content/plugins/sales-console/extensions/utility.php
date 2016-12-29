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
    return ' action="'.$str.'" ';
}

function method($str) {
    return ' method="'.$str.'" ';
}

function name($str) {
    return ' name="'.$str.'" ';
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

function button($value) {
    return new InputHTML(classType('button-primary').type('submit').name('button').value($value));
}