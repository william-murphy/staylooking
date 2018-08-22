<!DOCTYPE html>

<html lang="en">

	<head>

		<meta charset="utf-8"/>
		<meta property="og.type" content="website"/>
		<meta property="og.site_name" content="StayLooking"/>
		<meta property="og.title" content="Help | StayLooking"/>
		<meta property="og.url" content="http://www.staylooking.com"/>

		<link rel="stylesheet" href="style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

		<title>Help | StayLooking</title>

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

			<h1 class="content-main-heading">Help</h1>

			<h2 class="content-heading">Why does something not work?</h2>

			<p class="content-p">
				Because I need to fix it, please email me
				with the subject 'Found Bug' and a description of the issue
				to the email on the <a class="content-link" href="http://staylooking.com/contact/">contact</a>
				page.
			</p>

			<h2 class="content-heading">Why am I banned?</h2>

			<p class="content-p">
				Each post is allowed 10 reports
				before it is removed, and each user is allowed
				10 removed posts before they are banned. However,
				if you think an issue has ocurred with the automatic
				banning system, go <a class="content-link" href="http://staylooking.com/help/bannedaccount/">here</a>
			</p>

			<h2 class="content-heading">How do I delete my account?</h2>

			<p class="content-p">
				If you wish to delete your account
				for whatever reason at all, go <a class="content-link" href="http://staylooking.com/help/deleteaccount/">here</a>
			</p>

			<h2 class="content-heading">How can I retrieve a forgotten password?</h2>

			<p class="content-p">
				If you have forgotten your password
				and need to retrieve either, go <a class="content-link" href="http://staylooking.com/help/forgotpassword/">here</a>
			</p>

			<h2 class="content-heading">I lost the email verification link.</h2>

			<p class="content-p">
				Click <a class="content-link" href="http://staylooking.com/help/resendverify/">here</a> to resend the verification email.
			</p>

		</main>

		<footer>

			<div class="footer-wrapper">

				<div class="footer-left">
					<a href="http://staylooking.com/contact/">CONTACT</a>
					<a href="http://staylooking.com/about/">ABOUT</a>
					<a href="http://staylooking.com/help/"><b>HELP</b></a>
					<a href="http://staylooking.com/blog/">BLOG</a>
				</div>

				<div class="footer-right">
					<p>© Copyright 2017. All Rights Reserved.</p>
				</div>

			</div>

		</footer>

	</body>

</html>
