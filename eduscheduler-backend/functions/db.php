<?php


$con = mysqli_connect('localhost', 'root', '', 'eduschedulerdb');


function escape($string) {
    global $con;
    return mysqli_real_escape_string($con, $string);
}

function query($query) {
    global $con;
    return mysqli_query($con, $query);
}

function confirm($result) {
    global $con;
    if(!$result) {
        die("QUERY FAILED" . mysqli_error($con));
    }
}

function fetch_assoc($result) {
    return mysqli_fetch_assoc($result);
}

function fetch_all($result) {
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

