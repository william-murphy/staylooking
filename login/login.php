<?php

	//Include DB connectiion and S3 files
	use Aws\S3\S3Client;
	use Aws\S3\Exception\S3Exception;
	require_once('../sensitivestrings.php');

	//Start the session
	session_start();

	//Check if submit button has been pressed
	if (!empty($_POST)) {

		//Include the DB connection file
		include_once "../ROOT_DB_CONNECT.php";

		//Store the user-submitted username and pass, taking security precautions
		$user_name = mysqli_real_escape_string($connect, $_POST['user_name']);
		$user_pwd = mysqli_real_escape_string($connect, $_POST['user_pwd']);

		/*-----! Error Handlers !-----*/

		//Check if inputs are Empty
		if (empty($user_name) || empty($user_pwd)) {

			echo "http://staylooking.com/login/index.php?status=emptyfield";

		}else {

			//Get user's info with a matching username
			$sqlSearchUserInfo = "SELECT user_name, user_email, user_pwd, user_banned FROM users WHERE user_name='$user_name';";
			$sqlUserInfo = mysqli_query($connect, $sqlSearchUserInfo);
			$sqlGetNumberOfRows = mysqli_num_rows($sqlUserInfo);

			//If no such rows exist, return user and kill script
			if ($sqlGetNumberOfRows != 1) {

				echo "http://staylooking.com/login/index.php?status=usernotfound";

			}else {

				//Fetch array of the previous query for matching usernames
				$userInfoArray = mysqli_fetch_array($sqlUserInfo);

				//De-Hash the password
				$pwdVerify = password_verify($user_pwd, $userInfoArray['user_pwd']);

				if($pwdVerify === FALSE)/*Check if the entered password is incorrect*/{

					echo "http://staylooking.com/login/index.php?status=incorrectpass";

				}elseif ($pwdVerify === TRUE)/*Check if the entered password is correct*/{

					$is_banned = $userInfoArray['user_banned'];
					$user_email = $userInfoArray['user_email'];

					if ($is_banned > 0) {

						require '../aws/aws-autoloader.php';

						//Get the filenames of all the user's posts
						$sqlSearchAllPostNames = "SELECT post_name FROM posts WHERE post_user='$user_name' LIMIT 20;";
						$sqlGetAllPostNames = mysqli_query($connect, $sqlSearchAllPostNames);

						//Loop through the images and delete
						while ($postname = mysqli_fetch_array($sqlGetAllPostNames)['post_name']) {

							//Delete the image
							$bucketName = 'staylooking-posts';
							$keyName = 'posts/'.$postname;
							$IAM_KEY = $ssIAMKey;
							$IAM_SECRET = $ssIAMSecret;

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

						}

						//Delete all user's images and user's records and add their email to the banned list
						$sqlDelete = "INSERT INTO banned (ban_email) VALUES ('$user_email');
						DELETE FROM posts WHERE post_user='$user_name';
						DELETE FROM users WHERE user_name='$user_name';
						DELETE FROM disliked WHERE disliked_name='$user_name';
						DELETE FROM liked WHERE liked_name='$user_name';";
						mysqli_query($connect, $sqlDelete);

						echo "http://staylooking.com/login/index.php?status=userbanned";

					}else {

						//Add the username to the session to 'log in' the user
						$_SESSION['user_name_s'] = $userInfoArray['user_name'];
						//Bring user to the browse page
						echo "http://staylooking.com/";

					}

				}


			}

		}

	}else/*If there is an error submitting, return the user and kill script*/{

		header("Location: http://staylooking.com/login/index.php?status=error");
		exit();

	}

?>
