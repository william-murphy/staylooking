<!DOCTYPE html>

<html lang="en">

	<head>

		<meta charset="utf-8"/>
		<meta property="og.type" content="website"/>
		<meta property="og.site_name" content="StayLooking"/>
		<meta property="og.title" content="Account | StayLooking"/>
		<meta property="og.url" content="http://www.staylooking.com"/>

		<link rel="stylesheet" href="style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<title>Account | StayLooking</title>

		<script type="text/javascript">

			var deleteImage = function(id) {
				$.ajax({
					type: 'POST',
					url: 'deletepost.php',
					data: {id : id},
					success: function(){
						window.location.reload(false);
					}
				});
			}

			var unlikeImage = function(id) {
				$.ajax({
					type: 'POST',
					url: 'unlikepost.php',
					data: {id : id},
					success: function(){
						window.location.reload(false);
					}
				});
			}

			$(document).ready(function() {
				//$('.button-hide').hover(function(){$(this).css("background-color", "#cccccc");});
				$("#button-account").click(function(){
					$("#button-account").css("background-color", "#cccccc");
					$("#button-liked").css("background-color", "#a9a9a9");
					$(".liked-container").fadeOut(function(){ $(".posts-container").fadeIn(); });
				});

				$("#button-liked").click(function(){
					$("#button-liked").css("background-color", "#cccccc");
					$("#button-account").css("background-color", "#a9a9a9");
					$(".posts-container").fadeOut(function(){ $(".liked-container").fadeIn(); });
				});

			});

		</script>

	</head>

	<body>

		<header>

			<div class="header-wrapper">

				<div class="header-left">
					<a class="header-links" href="http://staylooking.com/">BROWSE</a>
					<a class="header-links" href="http://staylooking.com/upload/">UPLOAD</a>
					<a class="header-links" href="http://staylooking.com/account/"><b>ACCOUNT</b></a>
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
					"<div class='hide-button-container'>
					<button class='button-hide' id='button-account'>Your Posts</button>
					<button class='button-hide' id='button-liked'>Liked Posts</button>
					</div>";
				}

			?>

			<div class="posts-container">

				<script type="text/javascript">

					$("#button-account").css("background-color", "#cccccc");

				</script>

				<?php

					//Establish database connection
					include_once "../ROOT_DB_CONNECT.php";

					//Check if user is logged in
					if ($loggedin === TRUE) {

						//Declare variable for username
						$user_name_s = $_SESSION['user_name_s'];

						//Fetch all posts from user
						$sqlRequest = "SELECT id, post_name, post_title, post_likes, post_dislikes FROM posts WHERE post_user = '$user_name_s';";
						$sqlResult = mysqli_query($connect, $sqlRequest);

						//Check if there are 0 images
						if ($sqlResult == FALSE || mysqli_num_rows($sqlResult) < 1) {

							echo
							"<div class='error'>
							<p> You have not made any posts, </p>
							<a href='http://staylooking.com/upload/'>Upload a photo here!</a>
							</div>";

						}else {

							//Loop to organize and display images from user
							while ($sqlRow = mysqli_fetch_array($sqlResult)) {

								//Organize elements of array into variables
								$id = $sqlRow['id'];
								$idP = $id."P";
								$name = $sqlRow['post_name'];
								$title = $sqlRow['post_title'];
								$likes = $sqlRow['post_likes'];
								$dislikes = $sqlRow['post_dislikes'];

								//Echo the html for post info in this iteration
								echo
								"<div class='content-container'>
								<div class='content-image'>
								<img src='https://s3.amazonaws.com/staylooking-posts/posts/$name' alt='image'>
								</div>
								<div class='content-rating'>
								<div class='dislike'>
								<p>&#x1F92E $dislikes</p>
								</div>
								<div class='delete'>
								<button id='$idP'>DELETE</button>
								<script type='text/javascript'>
								$(document).on('click', '#$idP', function(){
								deleteImage($id);
								});
								</script>
								</div>
								<div class='like'>
								<p>&#x1F525 $likes</p>
								</div>
								</div>
								<div class='content-title'>
								<p>$title</p>
								</div>
								</div>";

							}

						}

					}else {

						echo
						"<div class='error'>
						<p> You are not logged in, </p>
						<a class='error-links' href='http://staylooking.com/login/'>Log in here</a>
						<a class='error-links' href='http://staylooking.com/signup/'>Sign up here</a>
						</div>";

					}

				?>

			</div>

			<div class="liked-container">

				<script type="text/javascript">

					$(".liked-container").hide();

				</script>

				<?php

					//Check if user is logged in
					if ($loggedin === TRUE) {

						//Fetch all liked posts from user
						$sqlRequest = "SELECT liked_id FROM liked WHERE liked_name = '$user_name_s';";
						$sqlResult = mysqli_query($connect, $sqlRequest);

						//Check if there are 0 images
						if ($sqlResult === FALSE || mysqli_num_rows($sqlResult) < 1) {

							echo
							"<div class='error'>
							<p> You have not liked any posts, </p>
							<a href='http://staylooking.com/'>Start browsing here!</a>
							</div>";

						}else {

							//Merge the multidimensional array into one array
							$merged_array = call_user_func_array('array_merge', mysqli_fetch_all($sqlResult));

							//Loop through the array and print the image for each corresponding id
							foreach ($merged_array as $i) {

								$sqlGetLikedInfo = "SELECT id, post_name, post_title, post_likes, post_dislikes FROM posts WHERE id='$i';";
								$sqlLikedInfo = mysqli_fetch_array(mysqli_query($connect, $sqlGetLikedInfo));

								//Organize elements of array into variables
								$id = $sqlLikedInfo['id'];
								$idL = $id."L";
								$name = $sqlLikedInfo['post_name'];
								$title = $sqlLikedInfo['post_title'];
								$likes = $sqlLikedInfo['post_likes'];
								$dislikes = $sqlLikedInfo['post_dislikes'];

								//Echo the html for post info in this iteration
								echo
								"<div class='content-container'>
								<div class='content-image'>
								<img src='https://s3.amazonaws.com/staylooking-posts/posts/$name' alt='image'>
								</div>
								<div class='content-rating'>
								<div class='dislike'>
								<p>&#x1F92E $dislikes</p>
								</div>
								<div class='delete'>
								<button id='$idL'>UNLIKE</button>
								<script type='text/javascript'>
								$(document).on('click', '#$idL', function(){
								unlikeImage($id);
								});
								</script>
								</div>
								<div class='like'>
								<p>&#x1F525 $likes</p>
								</div>
								</div>
								<div class='content-title'>
								<p>$title</p>
								</div>
								</div>";

							}

						}

					}

				?>

			</div>

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
