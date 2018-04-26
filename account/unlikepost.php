<?php

	//Start session and include DB connection file
	session_start();
	include_once "../ROOT_DB_CONNECT.php";

	if (isset($_POST["id"]) && isset($_SESSION["user_name_s"])) {

		//Create variable to hold the id sent thru POST and username
		$id = $_POST["id"];
		$user = $_SESSION["user_name_s"];

		//Check if the user has already liked the image
		$sqlCheckLiked = "SELECT id FROM liked WHERE liked_id = '$id' AND liked_name = '$user' LIMIT 1;";
		if (mysqli_num_rows(mysqli_query($connect, $sqlCheckLiked)) > 0) {

			//Delete record from database
			$sqlUnlike = "DELETE FROM liked WHERE liked_id = '$id' AND liked_name = '$user';
			UPDATE posts SET post_likes = post_likes - 1 WHERE id = '$id';";
			mysqli_query($connect, $sqlUnlike);
		}

	}

?>
