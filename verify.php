<!DOCTYPE html>

<html lang="en">

	<head>

		<meta charset="utf-8"/>
		<meta property="og.type" content="website"/>
		<meta property="og.site_name" content="StayLooking"/>
		<meta property="og.title" content="Verify Account | StayLooking"/>
		<meta property="og.url" content="http://www.staylooking.com"/>

		<link rel="stylesheet" href="style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

		<title>Verify Account | StayLooking</title>

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

			<?php

				if ($loggedin == TRUE) {

					$user = $_SESSION['user_name_s'];

					if (isset($_GET['hash']) && !empty($_GET['hash'])) {

						//Include the database connection file
						include_once "ROOT_DB_CONNECT.php";

						$hash = mysqli_real_escape_string($connect, $_GET['hash']); // Set hash variable

						$sqlFindHash = "SELECT active FROM users WHERE user_name='$user' AND user_hash='$hash' AND active='0';";
						$result = mysqli_query($connect, $sqlFindHash);

						if (mysqli_num_rows($result) > 0) {

							mysqli_query($connect, "UPDATE users SET active='1' WHERE user_hash='$hash' AND user_name='$user';");
							echo "<p> Your account has been verified! You can now upload pictures </p>";

						}else {

							echo "<p> Error: Already verified or user and hash don't match </p>";

						}


					}else {

						echo "<p> Error: Invalid URL </p>";

					}

				}else {

					header("Location: http://staylooking.com/login/index.php");
					exit();

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
