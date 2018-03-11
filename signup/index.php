<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8"/>
    <meta property="og.type" content="website"/>
    <meta property="og.site_name" content="StayLooking"/>
    <meta property="og.title" content="Sign Up | StayLooking"/>
    <meta property="og.url" content="http://www.staylooking.com"/>

    <link rel="stylesheet" href="style.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>

    <title>Sign Up | StayLooking</title>

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

                        echo '<form action="http://staylooking.com/logout.php" method="POST">
                                  <button type="submit" name="logout_button">LOG OUT</button>
                              </form>';

                    }else/*If the user is NOT logged in, display login/signup links*/{

                        $loggedin = FALSE;

                        echo '<a class="header-links" href="http://staylooking.com/login/">LOG IN</a>
                              <a class="header-links" href="http://staylooking.com/signup/"><b>SIGN UP</b></a>';

                    }

                ?>
            </div>
        </div>
    </header>

    <main>

        <ul class="signup-info">
            <li>Please enter a valid email address</li>
            <li>We will not share your email with any third parties</li>
            <li>Usernames must be unique</li>
            <li>Usernames must be between 5 and 20 characters</li>
            <li>Usernames may only contain letters, numbers, or underscores</li>
            <li>Passwords must be between 5 and 64 characters</li>
            <li>Passwords may contain only letters, numbers, and '!', '@', '#', '$', '%'.</li>
            <li>Do not share your password with anyone.</li>
            <li>Use a different password for every website.</li>
        </ul>

        <div class="signup-form">
            <input type="text" id="user_email" placeholder="Email Address..."></input>
            <input type="text" id="user_name" placeholder="Username..."></input>
            <input type="password" id="user_pwd" placeholder="Password..."></input>
            <br>
            <div class="g-recaptcha" data-sitekey="6LdPWEgUAAAAAFMFbgaCSz5bTt-Z19WRwWiJo-67"></div>
            <button type="submit" id="submit">Sign Up</button>

            <script type="text/javascript">
                $("#submit").click(function () {
                    var user_email = document.getElementById("user_email").value;
                    var user_name = document.getElementById("user_name").value;
                    var user_pwd = document.getElementById("user_pwd").value;
                    $.ajax({
                        type: "POST",
                        url: "signup.php",
                        data: {"user_email":user_email, "user_name":user_name, "user_pwd":user_pwd},
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
                        echo "<div class='error'>
                                  <p>ERROR: Unknown error signing up</p>
                              </div>";
                    break;
                    case 'accountdeleted':
                        echo "<div class='error'>
                                  <p>Your account has been removed.</p>
                              </div>";
                    case 'emptyfield':
                        echo "<div class='error'>
                                  <p>ERROR: Required field(s) are missing</p>
                              </div>";
                    break;
                    case 'invalidemail':
                        echo "<div class='error'>
                                  <p>ERROR: Invalid email</p>
                              </div>";
                    break;
                    case 'invalidusername':
                        echo "<div class='error'>
                                  <p>ERROR: Username already taken or contains forbidden characters</p>
                              </div>";
                    break;
                    case 'invalidpassword':
                        echo "<div class='error'>
                                  <p>ERROR: Invalid password</p>
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
