<?php

    /*-----! Store the necessary parameters !-----*/
    $dbServer =  "staylooking-db.cuutdsnifhtv.us-east-1.rds.amazonaws.com";    //Name of server containing database
    $dbUsername = "root1";       //Username of database
    $dbPassword = "tonkaballs17";           //Password of database
    $dbName = "staylooking";        //Name of database

    //Connect to the database
    $connect = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbName);

    //Check if connection fails, and return error message
    if (!$connect) {

        die("Connection failed: " . mysqli_connect_error());

    }

 ?>
