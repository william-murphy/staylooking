<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8"/>
    <meta property="og.type" content="website"/>
    <meta property="og.site_name" content="StayLooking"/>
    <meta property="og.title" content="Browse | StayLooking"/>
    <meta property="og.url" content="http://www.staylooking.com"/>

    <link rel="stylesheet" href="style.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <title>Browse | StayLooking</title>

    <script>

    $(document).ready(function() {

        $(document).on('click', '#start', function() {
            $.ajax({
                type: 'POST',
                url: 'changeimage.php',
                dataType: 'html',
                success: function(response){
                    $('#main').html(response);
                }
            });
        });

        $(document).on('click', '#reportButton', function() {
            $.ajax({
                type: "POST",
                url: 'changeimage.php',
                dataType: 'html',
                data: {"changeType":"report"},
                success: function(response){
                    $("#main").html(response);
                }
            });
        });

        $(document).on('click', '#likeButton', function() {
            $.ajax({
                type: "POST",
                url: 'changeimage.php',
                dataType: 'html',
                data: {"changeType":"like"},
                success: function(response){
                    $("#main").html(response);
                }
            });
        });

        $(document).on('click', '#dislikeButton', function() {
            $.ajax({
                type: "POST",
                url: 'changeimage.php',
                dataType: 'html',
                data: {"changeType":"dislike"},
                success: function(response){
                    $("#main").html(response);
                }
            });
        });
    });

    </script>

</head>

<body>

    <header>
        <div class="header-wrapper">
            <div class="header-left">
                <a class="header-links" href="http://staylooking.com/"><b>BROWSE</b></a>
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

        <?php

            if ($loggedin === TRUE) {

                //Establish database connection
                include_once "ROOT_DB_CONNECT.php";

                //Set up date with 12 hours subtracted
                $date = new DateTime('now', new DateTimeZone('America/New_York'));
                $dateModified = $date -> modify(' - 6 hours');
                $dateFormatted = $dateModified -> format('Y-m-d H:i:s');

                //Check if cookie for image IDs has been set
                if (!isset($_COOKIE['idList'])) {

                    //Fetch Images for Array
                    $sqlGetImages = "SELECT id FROM posts/*WHERE post_date >= '$dateFormatted' ORDER BY id*/LIMIT 100;";
                    $sqlQuery = mysqli_query($connect, $sqlGetImages);

                    $idList = [];

                    while ($row = mysqli_fetch_array($sqlQuery)) {

                        array_push($idList, $row[0]);

                    }

                    //Set cookie for list of image IDs and get first id
                    $id = $idList[0];
                    setcookie("idList", serialize($idList), time() + 43200, "/");

                }

                //Display the button to begin browsing
                echo "<div id='main' class='main'>
                          <button class='start' id='start'>BEGIN BROWSING</button>
                      </div>";

            }else {

                echo "<div class='error'>
                          <p> You are not logged in, </p>
                          <a class='error-links' href='http://staylooking.com/login/'>Log in here</a>
                          <a class='error-links' href='http://staylooking.com/signup/'>Sign up here</a>
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
