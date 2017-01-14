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

function checkedAttr($str){
    return ' checked="'.$str.'" ';
}

function maxlength($str){
    return ' maxlength="'.$str.'" ';
}

function placeholder($str){
    return ' placeholder="'.$str.'" ';
}

function onclick($str){
    return ' onclick="'.$str.'" ';
}

function forAttr($str){
    return ' for="'.$str.'" ';
}

function ariahidden($str){
    return ' aria-hidden="'.$str.'" ';
}

function tabindex($str){
    return ' tabindex="'.$str.'" ';
}

function add_image($id) {
    set_post_thumbnail($id, absint( $_POST[book_request::$image_set]));
}

function button($value) {
    return new Input(classType('button-primary').type('submit').name('button').value($value));
}

function get_user_name() {
    $user = wp_get_current_user();
    return $user->first_name;
}

function get_state_comma($state) {
    if ($state != ""){
        return ', ';
    }
    else return '';
}