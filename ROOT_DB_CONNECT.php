<?php

	//Require the ss file
	require_once('sensitivestrings.php');

	/*-----! Store the necessary parameters !-----*/
	$dbServer = $ssServer;    //Name of server containing database
	$dbUsername = $ssUsername;      //Username of database
	$dbPassword = $ssPassword;        //Password of database
	$dbName = $ssName;        //Name of database

	//Connect to the database
	$connect = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbName);

	//Check if connection fails, and return error message
	if (!$connect) {

		die("Connection failed: " . mysqli_connect_error());

	}

?>
