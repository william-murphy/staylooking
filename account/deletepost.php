<?php

    //Start session and include DB connection file
    session_start();
    include_once "../ROOT_DB_CONNECT.php";

    if (isset($_POST["id"]) && isset($_SESSION["user_name_s"])) {

        //Create variable to hold the id sent thru POST and username
        $id = $_POST["id"];
        $user = $_SESSION["user_name_s"];

        //Get the filename and user to check if the user deleting is the user who posted the image
        $sqlGetPostInfo = "SELECT post_name, post_user FROM posts WHERE id='$id';";
        $sqlFileName = mysqli_fetch_array(mysqli_query($connect, $sqlGetPostInfo))['post_name'];
        $sqlPostUser = mysqli_fetch_array(mysqli_query($connect, $sqlGetPostInfo))['post_user'];

        if ($sqlPostUser == $user) {

            //Check for error deleting file
            if (!unlink("../uploads/$sqlFileName")) {

                exit();

            } else {

                //Delete record from database
                $sqlDelete = "DELETE FROM posts WHERE id='$id';";
                mysqli_query($connect, $sqlDelete);

            }

        }else {

            exit();

        }

    }else {

        exit();

    }

?>
