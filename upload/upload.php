<?php

    //Initialize the session
    session_start();

    //Check if the session variable for username is set (user is logged in)
    if (isset($_SESSION['user_name_s'])) {

        //Check if the upload button has been pressed
        if (isset($_POST['submit'])) {

          //Get strings file
          require_once('../sensitivestrings.php');

          //Verify Recaptcha
          $response = $_POST["g-recaptcha-response"];
          $url = 'https://www.google.com/recaptcha/api/siteverify';
          $data = array(
             'secret' => $ssRecaptchaSecretKey_U,
             'response' => $_POST["g-recaptcha-response"]
          );
          $options = array(
              'http' => array (
                   'method' => 'POST',
                   'content' => http_build_query($data)
              )
          );
          $context  = stream_context_create($options);
          $verify = file_get_contents($url, false, $context);
          $captcha_success=json_decode($verify);

          //Check for captcha error, if none continue
          if ($captcha_success->success==false) {

            header("Location: http://staylooking.com/upload/index.php?status=recaperror");
            exit();

          }else {

            //Include database connection file
            include_once "../ROOT_DB_CONNECT.php";

            //Store title, username, and current date
            $post_title = mysqli_real_escape_string($connect, $_POST['title']);
            $user_name_s = $_SESSION['user_name_s'];
            $currentDate = new DateTime('now', new DateTimeZone('America/New_York'));
            $date = $currentDate -> format('Y-m-d H:i:s');

            //Store info about submitted file
            $f_data = $_FILES['image'];
            $f_Name = $_FILES['image']['name'];
            $f_TmpName = $_FILES['image']['tmp_name'];
            $f_Size = $_FILES['image']['size'];
            $f_Error = $_FILES['image']['error'];
            $f_Type = $_FILES['image']['type'];

            //Fetch the submitted file's extension
            $f_ExplodedName = explode('.', $f_Name);
            $f_Ext = strtolower(end($f_ExplodedName));

            //Array of allowed file extensions
            $allowed_ext = array('jpg', 'jpeg', 'png');

            //Check if title or image is empty
            if(empty($post_title) || empty($f_Name)) {

                header("Location: http://staylooking.com/upload/index.php?status=emptyfield");
                exit();

            }else {

                //Check if the user has exceeded the allowed number of posts
                $sqlGetNumOfPosts = "SELECT id FROM posts WHERE post_user='$user_name_s';";
                $sqlNumOfPosts = mysqli_num_rows(mysqli_query($connect, $sqlGetNumOfPosts));

                if ($sqlNumOfPosts >= 20) {

                    header("Location: http://staylooking.com/upload/index.php?status=postlimit");
                    exit();

                }else {

                    //Check if the title matches requirements
                    if (!preg_match("/^[a-zA-Z0-9 !#$%&*(),.+?-]{5,48}/", $post_title)) {

                        header("Location: http://staylooking.com/upload/index.php?status=badtitle");
                        exit();


                    }else {

                        //Check if the submitted file's extension is in the array of allowed extensions
                        if (!in_array($f_Ext, $allowed_ext)) {

                            header("Location: http://staylooking.com/upload/index.php?status=wrongfiletype");
                            exit();

                        }else {

                            //Check if submitted file contains errors
                            if ($f_Error !== 0) {

                                header("Location: http://staylooking.com/upload/index.php?status=fileerror");
                                exit();

                            }else {

                                //Check if the submitted file is too large
                                if ($f_Size > 1000000) {

                                    header("Location: http://staylooking.com/upload/index.php?status=filesizetoolarge");
                                    exit();

                                }else {

                                    //Get the most recent post
                                    $sqlGetLatestID = "SELECT id FROM posts ORDER BY id DESC LIMIT 1;";
                                    $sqlQuery = mysqli_query($connect, $sqlGetLatestID);

                                    //Create unique number
                                    $last_id = (mysqli_fetch_array($sqlQuery)['id']) + 1;

                                    //Combine username, unique number, and the file ext to form new file name
                                    $f_NewName = $last_id.".".$f_Ext;

                                    //Insert new post into database
                                    $sqlInsertNewPost = "INSERT INTO
                                    posts (post_name, post_user, post_title, post_reports, post_likes, post_dislikes, post_date)
                                    VALUES ('$f_NewName', '$user_name_s', '$post_title', 0, 0, 0, '$date');";
                                    mysqli_query($connect, $sqlInsertNewPost);
                                    $last_id = mysqli_insert_id($connect);

                                    //Get files from aws sdk for s3 implementation
                                    require 'aws.phar';
                                    use Aws/S3/S3Client;
                                    use Aws/S3/Exception/S3Exception;

                                    //AWS info
                                    $bucketName = 'staylooking-posts';
                                    $IAM_KEY = $ssIAMKey;
                                    $IAM_SECRET = $ssIAMSecret;

                                    //Connect to AWS
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

                                    } catch (Exception $e) {
                                      die("Error: ".$e->getMessage()."Please refresh and try again.");
                                    }

                                    //Add to S3
                                    $keyName = 'posts/'.$f_NewName;
                                    $pathInS3 = 'https://s3.us-east-1.amazonaws.com/'.$bucketName.'/'.$keyName;

                                    $caught = false;

                                    try {
                                      //Uploaded:
                                      $s3->putObject(
                                        array(
                                          'Bucket'=>$bucketName,
                                          'Key'=>$keyName,
                                          'SourceFile'=>$f_TmpName,
                                          'StorageClass'=>'REDUCED_REDUNDANCY'
                                        )
                                      );
                                    } catch (S3Exception $e) {

                                      $caught = true;
                                      mysqli_query($connect, "DELETE FROM posts WHERE id='$last_id';");
                                      die("Error: ".$e->getMessage()."Please refresh and try again.");

                                    } catch (Exception $e) {

                                      $caught = true;
                                      mysqli_query($connect, "DELETE FROM posts WHERE id='$last_id';");
                                      die("Error: ".$e->getMessage()."Please refresh and try again.");

                                    }

                                    //Test if caught and send user to account page
                                    if ($caught != true) {
                                      header("Location: http://staylooking.com/account/");
                                    }

                                }

                            }

                        }

                    }

                }

            }

          }

        }else/*If error submitting,return to the upload page*/{

            header("Location: http://staylooking.com/upload/index.php?status=error");
            exit();

        }

    }else {

        header("Location: http://staylooking.com/login/");
        exit();

    }

?>
