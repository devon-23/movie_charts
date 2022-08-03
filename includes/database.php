<?php

/**
 *  Get the database connection 
 * 
 *  @return object Connection to a MySQL server
 */
function getDB() {
    $db_host = "127.0.0.1";
    $db_name = "movies";
    $db_user = "root";
    $db_pass = "";

    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    if (mysqli_connect_error()) {
        echo mysqli_connect_error();
        exit;
    }
    return $conn;
}
