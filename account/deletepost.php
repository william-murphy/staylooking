<?php

	//Get files from aws sdk for s3 implementation and DB info
	require '../aws/aws-autoloader.php';
	use Aws\S3\S3Client;
	use Aws\S3\Exception\S3Exception;
	include_once "../ROOT_DB_CONNECT.php";
	require_once('../sensitivestrings.php');

	//Start session
	session_start();

	if (isset($_POST["id"]) && isset($_SESSION["user_name_s"])) {

		//Create variable to hold the id sent thru POST and username
		$id = $_POST["id"];
		$user = $_SESSION["user_name_s"];

		//Get the filename and user to check if the user deleting is the user who posted the image
		$sqlGetPostInfo = "SELECT post_name, post_user FROM posts WHERE id='$id';";
		$sqlFileName = mysqli_fetch_array(mysqli_query($connect, $sqlGetPostInfo))['post_name'];
		$sqlPostUser = mysqli_fetch_array(mysqli_query($connect, $sqlGetPostInfo))['post_user'];

		if ($sqlPostUser == $user) {

			//Delete the image
			$bucketName = 'staylooking-posts';
			$keyName = 'posts/'.$sqlFileName;
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
				exit();
			} catch (Exception $e) {
				exit();
			}

			//Delete record from database
			$sqlDelete = "DELETE FROM posts WHERE id='$id';";
			mysqli_query($connect, $sqlDelete);

		}else {

			exit();

		}

	}else {

		exit();

	}

?>
