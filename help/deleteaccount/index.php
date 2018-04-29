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

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<title>Delete Account | StayLooking</title>

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

			<h1 class="content-heading">Delete Account</h1>

			<span class="content-notice">By clicking the button below, you understand that all account deletions are final, and cannot be reversed.</span>
			<br>
			<button class="content-button" id="warning">I understand</button>

			<form id="form" method="POST">
				<input class="content-button" name="submit" type="submit" value="Delete Account"></input>
			</form>

			<!-- Hide the 'agree' button and display the 'delete account' button -->
			<script>

				$(document).ready(function() {
					$("#form").hide();
					$("#warning").click(function(){
						$("#warning").fadeOut(function(){ $("#form").fadeIn(); });
					});
				});

			</script>

			<?php

				//Include DB connectiion and S3 files
				require '../../aws/aws-autoloader.php';
				use Aws\S3\S3Client;
				use Aws\S3\Exception\S3Exception;
				require_once('../../sensitivestrings.php');
				include_once "../../ROOT_DB_CONNECT.php";


				if (isset($_POST['submit'])) {

					if ($loggedin === TRUE) {

						//Get the user's name from the session
						$user_name = $_SESSION['user_name_s'];

						//Get the filenames of all the user's posts
						$sqlSearchAllPostNames = "SELECT post_name, id FROM posts WHERE post_user='$user_name' LIMIT 20;";
						$sqlGetAllPostNames = mysqli_query($connect, $sqlSearchAllPostNames);

						//Loop through the images and delete
						while ($postname = mysqli_fetch_array($sqlGetAllPostNames)['post_name']) {

							//Delete the image
							$bucketName = 'staylooking-posts';
							$keyName = 'posts/'.$postname;
							$IAM_KEY = $ssIAMKey;
							$IAM_SECRET = $ssIAMSecret;

							try {
								$s3 = S3Client::factory(
									array(
										'credentials' => array(
											'key' => $IAM_KEY,
											'secret' => $IAM_SECRET
										),
										'version' => 'latest',
										'region' => 'us-east-1'
									)
								);
								$s3->deleteObject(array(
									'Bucket' => $bucketName,
									'Key'    => $keyName
								));
							} catch (S3Exception $e) {
								header("Location: http://staylooking.com/help/deleteaccount/index.php?status=error");
								exit();
							} catch (Exception $e) {
								header("Location: http://staylooking.com/help/deleteaccount/index.php?status=error");
								exit();
							}

						}

						//Delete all user's images and user's records
						$sqlDeleteAccount = "DELETE FROM posts WHERE post_user='$user_name';
						DELETE FROM users WHERE user_name='$user_name';
						DELETE FROM liked WHERE liked_name='$user_name';";

						if (!mysqli_query($connect, $sqlDeleteAccount)) {

							header("Location: http://staylooking.com/help/deleteaccount/index.php?status=error");
							exit();

						}else {

							header("Location: http://staylooking.com/signup/nidex.php?status=accountdeleted");
							exit();

						}


					}else {

						header("Location: http://staylooking.com/help/deleteaccount/index.php?status=error");
						exit();

					}

				}

			?>

			<?php

				if (isset($_GET['status']) && $_GET['status'] == 'error') {

					echo
					"<div class='error'>
					<p>ERROR: Could not delete account, or it has already been deleted. Please try again later.</p>
					</div>";

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
