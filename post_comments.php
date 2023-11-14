<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {       
       
        $data = json_decode(file_get_contents('php://input'), true);

        $movieId = $data['movieId'];
        $comment = $data['comment'];
        $rating = $data['rating'];
        $user_id = $_SESSION['user_id'];
       
        $con = mysqli_connect("localhost", "root", "", "moviecatalog");

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $sql = "INSERT INTO comments (movie_id, comment, rating,user_id) VALUES ('$movieId', '$comment', '$rating','$user_id')";
        $result = mysqli_query($con, $sql);

        if ($result) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'error' => mysqli_error($con)));
        }
        mysqli_close($con);
        exit;
    }    
?>