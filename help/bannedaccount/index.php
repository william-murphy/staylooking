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

    <title>Banned Account | StayLooking</title>

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

                        echo "<a class='header-links' href='http://staylooking.com/login/'>LOG IN</a>
                              <a class='header-links' href='http://staylooking.com/signup/'>SIGN UP</a>";

                    }

                ?>
            </div>
        </div>
    </header>

    <main>

        <h1 class="content-heading">Account Banned</h1>

        <p class="content-p">If your account was banned, it very likely
            was because of some questionable posts you made, however if
            you think something is wrong and your account shouldn't be
            banned, head to the <a class="content-link" href="http://staylooking.com/contact/">contact</a>
            page and send an email to the address shown there with the
            subject line 'Account Banned' as well as a description of
            the issue. Please note that there is a very low chance your
            account will be brought back. So, please consider making a
            new account at the <a class="content-link" href="http://staylooking.com/signup/">sign up</a> page.
        </p>

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
