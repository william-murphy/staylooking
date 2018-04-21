<!DOCTYPE html>

<html lang="en">

	<head>

		<meta charset="utf-8"/>
		<meta property="og.type" content="website"/>
		<meta property="og.site_name" content="StayLooking"/>
		<meta property="og.title" content="Upload | StayLooking"/>
		<meta property="og.url" content="http://www.staylooking.com"/>

		<link rel="stylesheet" href="style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="http://malsup.github.com/jquery.form.js"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>

		<title>Upload | StayLooking</title>

	</head>

	<body>

		<header>

			<div class="header-wrapper">

				<div class="header-left">
					<a class="header-links" href="http://staylooking.com/">BROWSE</a>
					<a class="header-links" href="http://staylooking.com/upload/"><b>UPLOAD</b></a>
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
							'<a class="header-links" href="http://staylooking.com/login/">LOG IN</a>
							<a class="header-links" href="http://staylooking.com/signup/">SIGN UP</a>';

						}

					?>

				</div>

			</div>

		</header>

		<main>

			<?php

				if ($loggedin === TRUE) {

					echo
					"<div class='upload'>
					<div class='upload-info'>
					<ul>
					<li>Titles must be between 5 and 48 characters</li>
					<li>Titles should accurately describe the content of the image</li>
					<li>Titles and images not about clothes could result in a ban</li>
					<li>Images should be .jpg, .png, or .jpeg</li>
					<li>Images must be less than 1 megabyte</li>
					<li>Users may only have 20 images on their account at one time</li>
					</ul>
					</div>
					<form class='upload-form' action='upload.php' method='POST' enctype='multipart/form-data'>
					<input type='text' name='title' placeholder='Title...'></input>
					<input type='file' name='image'></input>
					<br>
					<div class='g-recaptcha' data-sitekey='6LdmXk0UAAAAAD3-bS66qDz_Sm2mMxjTkMjRUUAt'></div>
					<button type='submit' name='submit'>Upload</button>
					</form>
					</div>";

				}else {

					echo
					"<div class='error'>
					<p> You are not logged in, </p>
					<a class='error-links' href='http://staylooking.com/login/'>Log in here</a>
					<a class='error-links' href='http://staylooking.com/signup/'>Sign up here</a>
					</div>";

				}

			?>

			<?php

				if (isset($_GET['status'])) {

					switch($_GET['status']) {

						case 'error':
							echo
							"<div class='error'>
							<p>ERROR: Unknown error uploading file</p>
							</div>";
							break;
						case 'emptyfield':
							echo
							"<div class='error'>
							<p>ERROR: Required field(s) are empty</p>
							</div>";
							break;
						case 'postlimit':
							echo
							"<div class='error'>
							<p>ERROR: You have too many posts, please delete one to post again</p>
							</div>";
							break;
						case 'badtitle':
							echo
							"<div class='error'>
							<p>ERROR: Invalid title</p>
							</div>";
							break;
						case 'wrongfiletype':
							echo
							"<div class='error'>
							<p>ERROR: File type is not allowed</p>
							</div>";
							break;
						case 'fileerror':
							echo
							"<div class='error'>
							<p>ERROR: File contains errors</p>
							</div>";
							break;
						case 'filesizetoolarge':
							echo
							"<div class='error'>
							<p>ERROR: Image size too big</p>
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
