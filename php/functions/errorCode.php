<?php
function errorCode ( $code ) {
    $error = '<h1 class="error">OOPS!</h1>';
    $error .= '<p class="error">Er is iets misgegaan met de form! Error code: ' . $code . ".";
    return $error;
}