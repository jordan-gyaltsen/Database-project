<?php

function getNextId($tableName,$attName,$db) {
    $max_id_query = "SELECT MAX($attName) FROM $tableName;";
    $max_id_result = mysqli_query($db, $max_id_query);
    $max_id_row = mysqli_fetch_row($max_id_result);
    $max_id = $max_id_row[0];
    $next_id = $max_id + 1;
    return $next_id;
} 

function isWeekend($date) {
    return (date('N', strtotime($date)) >= 6);
}

function beforeFriday($date) {
    return (date('N', strtotime($date)) < 5);
}
?>