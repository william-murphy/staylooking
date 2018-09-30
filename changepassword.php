<!DOCTYPE html>

<html lang="en">

	<head>

		<meta charset="utf-8"/>
		<meta property="og.type" content="website"/>
		<meta property="og.site_name" content="StayLooking"/>
		<meta property="og.title" content="Change Password | StayLooking"/>
		<meta property="og.url" content="http://www.staylooking.com"/>

		<link rel="stylesheet" href="style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

		<title>Change Password | StayLooking</title>

	</head>

	<body>

		<header>

			<div class="header-wrapper">

				<div class="header-left">
					<a class="header-links" href="http://staylooking.com/">BROWSE</a>
					<a class="header-links" href="http://staylooking.com/upload/">UPLOAD</a>
					<a class="header-links" href="http://staylooking.com/account/">ACCOUNT</a>
				</div>

				<div class="header-middle">
					<h1>StayLooking</h1>
				</div>

				<div class="header-right">

					<?php

						//Start the session
						session_start();

						//If user is logged in, display logout button
						if (isset($_SESSION['user_name_s'])) {

							//Set logged in variable to avoid redundant checking
							$loggedin = TRUE;

							echo
							'<form action="http://staylooking.com/logout.php" method="POST">
							<button type="submit" name="logout_button">LOG OUT</button>
							</form>';

						}else/*If the user is NOT logged in, display login/signup links*/{

							$loggedin = FALSE;

							echo
							"<a class='header-links' href='http://staylooking.com/login/'>LOG IN</a>
							<a class='header-links' href='http://staylooking.com/signup/'>SIGN UP</a>";

						}

					?>

				</div>

			</div>

		</header>

		<main>

			<form class="signup-form" action="" method="POST">
				<input type="password" name="pwd" placeholder="New Password..."></input>
				<input type="password" name="confirm" placeholder="Confirm Password..."></input>
				<br>
				<button type="submit" name="submit">Change Password</button>
			</form>

			<?php

				$pwd = mysqli_real_escape_string($connect, $_POST['pwd']);
				$confirm = mysqli_real_escape_string($connect, $_POST['confirm']);

				if (isset($_POST['submit']) && isset($_POST['pwd']) && isset($_POST['confirm']) && !empty($_POST['pwd']) && !empty($_POST['confirm'])) {

					if ($loggedin == TRUE) {

						$user = $_SESSION['user_name_s'];

						if (isset($_GET['hash']) && !empty($_GET['hash'])) {

							//Include the database connection file
							include_once "ROOT_DB_CONNECT.php";

							$hash = mysqli_real_escape_string($connect, $_GET['hash']); // Set hash variable

							$sqlFindHash = "SELECT active FROM users WHERE user_name='$user' AND user_hash='$hash' AND active='1';";
							$result = mysqli_query($connect, $sqlFindHash);

							if (mysqli_num_rows($result) > 0) {

								if (($pwd != $confirm) || (!preg_match("/^[a-zA-Z0-9!@#$%]{5,64}/", $pwd))) {

									echo "<p class='content-p'>The passwords you entered don't match, or don't meet the password requirements.</p>";

								}else {

									$hashedpwd = password_hash($pwd, PASSWORD_DEFAULT);
									$sqlUpdatePwd = "UPDATE users SET user_pwd='$hashedpwd' WHERE user_name='$user';";

									mysqli_query($connect, $sqlUpdatePwd) or die("Unknown error changing password.");

									echo "<p class='content-p'>Successfully changed password.</p>";

								}

							}else {

								echo "<p> Error: User account not verified. Click <a class='content-link' href='http://staylooking.com/help/resendverify/'>here</a> to resend the verification email.</p>";

							}

						}else {

							echo "<p> Error: Invalid URL </p>";

						}


					}else {

						header("Location: http://staylooking.com/login/index.php");
						exit();

					}

				}

			?>

		</main>

		<footer>

			<div class="footer-wrapper">

				<div class="footer-left">
					<a href="http://staylooking.com/contact/">CONTACT</a>
					<a href="http://staylooking.com/about/">ABOUT</a>
					<a href="http://staylooking.com/help/">HELP</a>
					<a href="http://staylooking.com/blog/">BLOG</a>
				</div>

				<div class="footer-right">
					<p>© Copyright 2017. All Rights Reserved.</p>
				</div>

			</div>

		</footer>

	</body>

</html>
