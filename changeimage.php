<?php

    //Establish database connection and start session

    include_once "../ROOT_DB_CONNECT.php";

    //Retrieve cookie for ID list and store in variable
    $idList = unserialize($_COOKIE['idList']);
    $id = $idList[0];

    //Check if the changeType is set
    if (!isset($_POST['changeType'])) {

        //Fetch the necessary info
        $sqlFetchImageInfo = "SELECT post_name, post_title, post_likes, post_dislikes FROM posts WHERE id='$id';";
        $sqlImageInfo = mysqli_fetch_array(mysqli_query($connect, $sqlFetchImageInfo));
        $title = $sqlImageInfo['post_title'];
        $name = $sqlImageInfo['post_name'];
        $likes = $sqlImageInfo['post_likes'];
        $dislikes = $sqlImageInfo['post_dislikes'];

        echo "
        <div class='content-image'>
            <img src='http://localhost/root/uploads/$name' alt='Image'>
        </div>
        <div class='content-rating'>
            <div class='rating'>
                <button id='dislikeButton'>$dislikes</button>
            </div>
            <div class='report'>
                <button id='reportButton'>REPORT</button>
            </div>
            <div class='rating'>
                <button id='likeButton'>$likes</button>
            </div>
        </div>
        <div class='content-title'>
            <p id='currentTitle'>$title</p>
        </div>
        ";

    }else {

        //Switch the changeType variable to find out which button was pressed
        switch($_POST['changeType']) {

            case 'report':

                //Update the post's reports for funzies
                $sqlAddReport = "UPDATE posts SET post_reports = post_reports + 1 WHERE id = '$id';";
                mysqli_query($connect, $sqlAddReport);

                //Fetch the post's number of reports and name
                $sqlFetchReportsAndName = "SELECT post_reports, post_name FROM posts WHERE id='$id' LIMIT 1;";
                $sqlReportsAndName = mysqli_fetch_array(mysqli_query($connect, $sqlFetchReportsAndName));
                $reports = $sqlReportsAndName['post_reports'];
                $name = $sqlReportsAndName['post_name'];

                //Test if the reports has reached the limit
                if ($reports >= 10) {

                    //Delete the image
                    unlink("http://localhost/root/uploads/$name");

                    //Save the user's name, then delete record from database
                    $reporteduser = mysqli_fetch_array(mysqli_query($connect, "SELECT post_user FROM posts WHERE id='$id';"))['post_user'];
                    mysqli_query($connect, "DELETE FROM posts WHERE id='$id';");

                    //Add report to offender's account
                    mysqli_query($connect, "UPDATE users SET user_reports = user_reports + 1 WHERE user_name='$reporteduser';");

                    //Check if the user has reached report limit
                    $sqlReportsOnUser = mysqli_fetch_array(mysqli_query($connect, "SELECT user_reports FROM users WHERE user_name='$reporteduser';"))['user_reports'];
                    if ($sqlReportsOnUser >= 10) {

                        //Change user_banned to true
                        mysqli_query($connect, "UPDATE users SET user_banned = 1 WHERE user_name = '$reporteduser';");

                    }

                }

            break;

            case 'like':

                //Start session and shnag username
                session_start();
                $user = $_SESSION["user_name_s"];

                //Check if the user has already liked the image
                $sqlCheckDuplicateLike = "SELECT * FROM liked WHERE liked_id = '$id' AND liked_name = '$user' LIMIT 1;";
                if (mysqli_num_rows(mysqli_query($connect, $sqlCheckDuplicateLike)) < 1) {

                    //Update the post's likes to add 1
                    $sqlAddLike = "UPDATE posts SET post_likes = post_likes + 1 WHERE id = '$id';";
                    mysqli_query($connect, $sqlAddLike);

                    //Insert the like into the database
                    $sqlInsertLike = "INSERT INTO liked
                    (liked_name, liked_id)
                    VALUES ('$user', '$id');";
                    mysqli_query($connect, $sqlInsertLike);

                }

            break;

            case 'dislike':

                //Update the post's dislikes to add 1
                $sqlAddDislike = "UPDATE posts SET post_dislikes = post_dislikes + 1 WHERE id = '$id';";
                mysqli_query($connect, $sqlAddDislike);

            break;

        }

        //After performing action on previous post, shift the previous id out of array
        array_shift($idList);

        //Check if array is empty, if so create new one
        if (empty($idList)) {

            //Set up date with 6 hours subtracted
            $date = new DateTime('now', new DateTimeZone('America/New_York'));
            $dateModified = $date -> modify(' - 6 hours');
            $dateFormatted = $dateModified -> format('Y-m-d H:i:s');

            //Fetch Images for Array
            $sqlGetImages = "SELECT id FROM posts/*WHERE post_date >= '$dateFormatted' ORDER BY id*/LIMIT 100;";
            $sqlQuery = mysqli_query($connect, $sqlGetImages);

            $idList = [];

            while ($row = mysqli_fetch_array($sqlQuery)) {

                array_push($idList, $row[0]);

            }

            //Set new cookie for list of image IDs
            $id = $idList[0];
            setcookie("idList", serialize($idList), time() + 43200, "/");

        }else {

            //Set id variable equal to the new 0 element of list, update cookie
            $id = $idList[0];
            setcookie("idList", serialize($idList), time() + 43200, "/");

        }

        //Fetch the necessary info
        $sqlFetchImageInfo = "SELECT post_name, post_title, post_likes, post_dislikes FROM posts WHERE id='$id';";
        $sqlImageInfo = mysqli_fetch_array(mysqli_query($connect, $sqlFetchImageInfo));
        $title = $sqlImageInfo['post_title'];
        $name = $sqlImageInfo['post_name'];
        $likes = $sqlImageInfo['post_likes'];
        $dislikes = $sqlImageInfo['post_dislikes'];

        echo "
        <div class='content-image'>
            <img src='http://localhost/root/uploads/$name' alt='Image'>
        </div>
        <div class='content-rating'>
            <div class='rating'>
                <button id='dislikeButton'>$dislikes</button>
            </div>
            <div class='report'>
                <button id='reportButton'>REPORT</button>
            </div>
            <div class='rating'>
                <button id='likeButton'>$likes</button>
            </div>
        </div>
        <div class='content-title'>
            <p id='currentTitle'>$title</p>
        </div>
        ";

    }


?>
