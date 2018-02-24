<?php

    /*-----! Store the necessary parameters !-----*/
    $dbServer = "localhost";    //Name of server containing database
    $dbUsername = "root";       //Username of database
    $dbPassword = "RqXwrVLmhPPNZZyT5dJNTL7e";           //Password of database
    $dbName = "root_db";        //Name of database

    //Connect to the database
    $connect = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbName);

    //Check if connection fails, and return error message
    if (!$connect) {

        die("Connection failed: " . mysqli_connect_error());

    }

 ?>
