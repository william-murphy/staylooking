<?php

	//Check if the 'logout' button has been pressed
	if (isset($_POST['logout_button'])) {

		session_start(); //Initiate session
		session_unset(); //Uninitiate session
		session_destroy(); //Destroy the session

		//Bring user to the login page and kill the script
		header("Location: http://staylooking.com/login/index.php?status=logoutsuccess");
		exit();

	}

?>
