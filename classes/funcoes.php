<?php
function filtra_string($variavel){
    return filter_var(trim($variavel), FILTER_SANITIZE_STRING);
}

function filtra_int($variavel){
    return filter_var(trim($variavel), FILTER_SANITIZE_NUMBER_INT);
}

function filtra_float($variavel){
    return filter_var(trim($variavel), FILTER_SANITIZE_NUMBER_FLOAT);
}