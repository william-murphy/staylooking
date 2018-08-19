<!DOCTYPE html>

<html lang="en">

	<head>

		<meta charset="utf-8"/>
		<meta property="og.type" content="website"/>
		<meta property="og.site_name" content="StayLooking"/>
		<meta property="og.title" content="Resend Verification | StayLooking"/>
		<meta property="og.url" content="http://www.staylooking.com"/>

		<link rel="stylesheet" href="style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

		<title>Resend Verification | StayLooking</title>

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

			<h1 class='content-main-heading'> Resend Verification Email </h1>

			<?php

				if ($loggedin == TRUE) {

					include_once "../../ROOT_DB_CONNECT.php";
					$user = $_SESSION['user_name_s'];

					//Check if user is already verified
					$active = mysqli_fetch_array(mysqli_query($connect,"SELECT active FROM users WHERE user_name='$user';"))['active'];
					if ($active < 1) {

						$sqlGetUserInfo = "SELECT user_email, user_hash FROM users WHERE user_name='$user';";
						$info = mysqli_fetch_array(mysqli_query($connect, $sqlGetUserInfo));
						$email = $info['user_email'];
						$hash = $info['user_hash'];

						//Send email verification
						$to = $email;
						$subject = "StayLooking | Email Verification";
						$headers = 'From:noreply@staylooking.com' . "\r\n";
						$message = "
						Please verify your email in case you forget your password.

						------------
						Username: ".$user."
						------------

						Please click this link to activate your account:
						http://www.staylooking.com/verify.php?hash=".$hash."

						";

						mail($to, $subject, $message, $headers);

						echo "<p class='content-p'> Another verification email has been sent to the email associated with this account. </p>";

					}else {

						echo "<p class='content-p'> Your account is already verified. </p>";

					}

				}else {

					echo "<p class='content-p'> You must be logged in to verify your account. Log in <a href='http://staylooking.com/login/index.php'>here</a></p>";

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
