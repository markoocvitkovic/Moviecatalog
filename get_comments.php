<?php
session_start();

    if (isset($_GET['movieId'])) {
        $movieId = $_GET['movieId'];
    
        $con = mysqli_connect("localhost", "root", "", "moviecatalog");
    
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    
        $sql = "SELECT * FROM comments WHERE movie_id = '$movieId'";
        $result = mysqli_query($con, $sql);    
        $comments = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $comments[] = array(
                'comment' => $row['comment'],
                'rating' => $row['rating'],
            );
        }    
        mysqli_close($con);    
        echo json_encode(array('comments' => $comments));
        exit;
    }    
?>