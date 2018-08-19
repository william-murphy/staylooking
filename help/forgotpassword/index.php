<!DOCTYPE html>

<html lang="en">

	<head>

		<meta charset="utf-8"/>
		<meta property="og.type" content="website"/>
		<meta property="og.site_name" content="StayLooking"/>
		<meta property="og.title" content="Forgot Password | StayLooking"/>
		<meta property="og.url" content="http://www.staylooking.com"/>

		<link rel="stylesheet" href="style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

		<title>Forgot Password | StayLooking</title>

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

			<h1>Forgotten Password</h1>

			<form class="content-form" method="POST">
				<input class="content-input" name="email" type="text" placeholder="This Account's Email..."></input>
				<input class="content-input" name="pwd" type="password" placeholder="New password..."></input>
				<input class="content-input" name="confirm" type="password" placeholder="Confirm password..."></input>
				<input class="content-button" name="submit" type="submit" value="Change Password"></input>
			</form>

			<?php

				include_once "../../ROOT_DB_CONNECT.php";
				session_start();

				if (isset($_POST['submit'])) {

					if ($loggedin == true) {

						$email = mysqli_real_escape_string($connect, $_POST['email']);
						$name = $_SESSION['user_name_s'];
						$pwd = mysqli_real_escape_string($connect, $_POST['pwd']);
						$confirm = mysqli_real_escape_string($connect, $_POST['confirm']);
						$sqlGetEmail = "SELECT * FROM users WHERE user_email='$email' AND user_name='$name';";
						$result = mysqli_query($connect, $sqlGetEmail);

						if (mysqli_num_rows($result) > 0) {

							if ($pwd == $confirm || preg_match("/^[a-zA-Z0-9!@#$%]{5,64}/", $pwd)) {

								$hash = password_hash($pwd, PASSWORD_DEFAULT);
								$sqlUpdatePwd = "UPDATE users SET user_pwd='$hash' WHERE user_name='$name';";
								if (!mysqli_query($connect, $sqlUpdatePwd)) {

									echo "<p class='content-p'>Unknown error changing password.</p>";
									exit();

								}else {

									echo "<p class='content-p'>Successfully changed password.</p>";
									exit();

								}

							}else {

								echo "<p class='content-p'>The passwords you entered don't match, or don't meet the password requirements.</p>";
								exit();

							}

						}else {

							echo "<p class='content-p'>Email given doesn't match the email associated with your account.</p>";
							exit();

						}

					}else {

						echo "<p class='content-p'>You must be signed in to change your password.</p>";
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
