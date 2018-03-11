<?php

    //Check if the 'signup' button has been pressed
    if (!empty($_POST)) {

      require_once('../recaptchalib.php');
      $privatekey = "6LdPWEgUAAAAAH7zLxIby7JDzuOe5NRrujLS2jbu";
      $resp = recaptcha_check_answer ($privatekey,
              $_SERVER["REMOTE_ADDR"],
              $_POST["recaptcha_challenge_field"],
              $_POST["recaptcha_response_field"]);

      if (!$resp->is_valid) {
        // What happens when the CAPTCHA was entered incorrectly
        die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
         "(reCAPTCHA said: " . $resp->error . ")");
      } else {

        //Include the database connection file
        include_once "../ROOT_DB_CONNECT.php";

        //Store the user-submitted username, pass, and email, taking security precautions
        $user_email = mysqli_real_escape_string($connect, $_POST['user_email']);
        $user_name = mysqli_real_escape_string($connect, $_POST['user_name']);
        $user_pwd = mysqli_real_escape_string($connect, $_POST['user_pwd']);

        /*-----! Error Handlers !-----*/

        //Check for empty fields
        if (empty($user_email) || empty($user_name) || empty($user_pwd)) {

            echo "http://staylooking.com/signup/index.php?status=emptyfield";

        }else {

            //Get number of rows with matching email
            $sqlSearchEmail = "SELECT * FROM users WHERE user_email='$user_email';";
            $sqlNumOfRows1 = mysqli_num_rows(mysqli_query($connect, $sqlSearchEmail));
            $sqlBannedEmail = "SELECT * FROM banned WHERE ban_email='$user_email';";
            $sqlNumOfRows2 = mysqli_num_rows(mysqli_query($connect, $sqlBannedEmail));

            //Check if email is valid
            if (!filter_var($user_email, FILTER_VALIDATE_EMAIL) || $sqlNumOfRows1 > 0 || $sqlNumOfRows2 > 0) {

                echo "http://staylooking.com/signup/index.php?status=invalidemail";

            }else {

                //Get number of rows with a matching username
                $sqlSearchUserName = "SELECT * FROM users WHERE user_name='$user_name';";
                $sqlNumOfRows = mysqli_num_rows(mysqli_query($connect, $sqlSearchUserName));

                //Check if the username is taken or doesn't meet requirements
                if ($sqlNumOfRows > 0 || !preg_match("/^[a-zA-Z0-9_]{5,20}/", $user_name)) {

                    echo "http://staylooking.com/signup/index.php?status=invalidusername";

                }else {

                    //Check if password is valid
                    if (!preg_match("/^[a-zA-Z0-9!@#$%]{5,64}/", $user_pwd)) {

                        echo "http://staylooking.com/signup/index.php?status=invalidpassword";

                    }else {

                        //Hash the password
                        $hashedpwd = password_hash($user_pwd, PASSWORD_DEFAULT);

                        //Insert the user info into the database
                        $sqlInsertUserInfo = "INSERT INTO users
                        (user_email, user_name, user_pwd, user_reports, user_banned)
                        VALUES ('$user_email', '$user_name', '$hashedpwd', 0, 0);";
                        mysqli_query($connect, $sqlInsertUserInfo);

                        //Bring user to login page
                        echo "http://staylooking.com/login/index.php?status=signupsuccess";

                    }

                }

            }

        }

      }

    }else/*If there is an error submitting, return the user and kill script*/{

        header("Location: http://staylooking.com/signup/index.php?status=error");
        exit();

    }

?>
