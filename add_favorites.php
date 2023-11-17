<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {       
    $data = json_decode(file_get_contents('php://input'), true);

    $movieId = $data['movieId'];
    $user_id = $_SESSION['user_id'];
   
    $con = mysqli_connect("localhost", "root", "", "moviecatalog");

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    $sql_check = "SELECT * FROM favorites WHERE movie_id = '$movieId' AND user_id = '$user_id'";
    $result_check = mysqli_query($con, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        
        echo json_encode(array('success' => false, 'error' => 'The movie is already added to favorites.'));
    } else {
        $sql_insert = "INSERT INTO favorites (movie_id, user_id) VALUES ('$movieId', '$user_id')";
        $result_insert = mysqli_query($con, $sql_insert);

        if ($result_insert) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'error' => mysqli_error($con)));
        }
    }

    mysqli_close($con);
    exit;
}

?>