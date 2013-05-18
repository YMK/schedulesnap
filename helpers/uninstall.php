<?php

/*
 * This file just removes everything from the database.
 */
    // Connect to database
    require('dataBase.php');
    $database = new dataBase("localhost","amckinlay","mong00s31","schedulesnap");
    $database->connect();
    echo "Connected";

    $query = "DROP table user";
    $database->sql($query);
    $query = "DROP table rota";
    $database->sql($query);
    $query = "DROP table strip";
    $database->sql($query);
    $query = "DROP table block";
    $database->sql($query);
    $query = "DROP table attribute";
    $database->sql($query);
    $query = "DROP table rotasUser";
    $database->sql($query);
    $query = "DROP table userAttribute";
    $database->sql($query);
    $query = "DROP table blockEntry";
    $database->sql($query);
?>
