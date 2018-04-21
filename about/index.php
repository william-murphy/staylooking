<!DOCTYPE html>

<html lang="en">

	<head>

		<meta charset="utf-8"/>
		<meta property="og.type" content="website"/>
		<meta property="og.site_name" content="StayLooking"/>
		<meta property="og.title" content="About | StayLooking"/>
		<meta property="og.url" content="http://www.staylooking.com"/>

		<link rel="stylesheet" href="style.css" type="text/css">

		<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

		<title>About | StayLooking</title>

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

			<h1 class="content-heading"> About StayLooking </h1>

			<p class="content-p">
			StayLooking is a fashion based image sharing service. Users can upload
			a photo of a piece or outfit they are interested in or own and get opinions on
			it through the likes and dislikes the image receives. The 'browse' functionality
			of StayLooking is where users like or dislike the pieces posted by other users.
			Users may then view all of the images they've liked, in case they want to
			purchase an item they see on StayLooking.
			</p>

			<hr width="40%" align="LEFT">
			<span class="content-notice">This website is still very much in an early development phase, and bugs are to be expected.</span>
			<br>
			<span class="content-notice">Please report any bugs at the <a class="content-link" href="http://staylooking.com/help/">help</a> page.</span>
			<hr width="40%" align="LEFT">
			<h1 class="content-heading"> About the Creator </h1>

			<p class="content-p">
			I started programming as a hobby around 2015, and began building StayLooking around June of 2017.
			If you have questions for me, please check the <a class="content-link" href="http://staylooking.com/contact/">contact</a> page.
			</p>

		</main>

		<footer>

			<div class="footer-wrapper">

				<div class="footer-left">
					<a href="http://staylooking.com/contact/">CONTACT</a>
					<a href="http://staylooking.com/about/"><b>ABOUT</b></a>
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
