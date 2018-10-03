<!DOCTYPE html>

<html lang="en">

	<head>

		<meta charset="utf-8"/>
		<meta property="og.type" content="website"/>
		<meta property="og.site_name" content="StayLooking"/>
		<meta property="og.title" content="Log In | StayLooking"/>
		<meta property="og.url" content="http://www.staylooking.com"/>

		<link rel="stylesheet" href="style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<title>Log In | StayLooking</title>

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
							"<a class='header-links' href='http://staylooking.com/login/'><b>LOG IN</b></a>
							<a class='header-links' href='http://staylooking.com/signup/'>SIGN UP</a>";

						}

					?>

				</div>

			</div>

		</header>

		<main>

			<div class="login-form">

				<input type="text" id="user_name" placeholder="Username..."></input>
				<input type="password" id="user_pwd" placeholder="Password..."></input>
				<br>
				<button id="submit">Log In</button>
				<button id="forgotpassword">Forgot Password</button>

				<script type="text/javascript">

					$("#forgotpassword").click(function(){
						window.location.href="http://staylooking.com/help/forgotpassword/";
					});

					$("#submit").click(function () {

						var user_name = document.getElementById("user_name").value;
						var user_pwd = document.getElementById("user_pwd").value;

						$.ajax({
							type: "POST",
							url: "login.php",
							data: {"user_name":user_name, "user_pwd":user_pwd},
							dataType: "text",
							success: function(response){
								window.location.assign(response);
							}
						});
					});

				</script>

			</div>

			<?php

				if (isset($_GET['status'])) {

					switch($_GET['status']) {

						case 'error':
							echo
							"<div class='error'>
							<p>ERROR: Unknown error logging in</p>
							</div>";
							break;
						case 'emptyfield':
							echo
							"<div class='error'>
							<p>ERROR: Required field(s) are missing</p>
							</div>";
							break;
						case 'usernotfound':
							echo
							"<div class='error'>
							<p>ERROR: User with this username not found</p>
							</div>";
							break;
						case 'userbanned':
							echo
							"<div class='error'>
							<p>ERROR: This user has had too many removed posts, and has been banned</p>
							</div>";
							break;
						case 'incorrectpass':
							echo
							"<div class='error'>
							<p>ERROR: Incorrect password</p>
							</div>";
							break;
						case 'signupsuccess':
							echo
							"<div class='error'>
							<p>Successfully signed up!</p>
							</div>";
							break;
						case 'logoutsuccess':
							echo
							"<div class='error'>
							<p>Successfully logged out!</p>
							</div>";
							break;

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
