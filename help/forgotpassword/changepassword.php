<?php

	include_once "../../ROOT_DB_CONNECT.php";
	session_start();

	if (isset($_POST['submit'])) {

		$email = mysqli_real_escape_string($connect, $_POST['email']);
		$name = $_SESSION['user_name_s'];
		$pwd = $_POST['pwd'];
		$confirm = $_POST['confirm'];
		$sqlGetEmail = "SELECT * FROM users WHERE user_email='$email' AND user_name='$name';";
		$result = mysqli_query($connect, $sqlGetEmail);

		if (mysqli_num_rows($result) > 0) {

			if ($pwd == $confirm) {

				$hashedPwd = password_hash($user_pwd, PASSWORD_DEFAULT);
				$sqlUpdatePwd = "UPDATE users SET user_pwd = '$hashedPwd' WHERE user_name='$name';";
				if (!mysqli_query($connect, $sqlUpdatePwd)) {

					echo "<p class='content-p'>Unknown error changing password.</p>";
					exit();

				}else {

					echo "<p class='content-p'>Successfully changed password.</p>";
					exit();

				}

			}else {

				echo "<p class='content-p'>The passwords you entered don't match.</p>";
				exit();

			}

		}else {

			echo "<p class='content-p'>Email given doesn't match the email associated with your account.</p>";
			exit();

		}

	}else {

		header("Location: http://staylooking.com/help/forgotpassword/index.php");
		exit();

	}


?>
