<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $con = mysqli_connect("localhost", "root", "", "moviecatalog");

    if (mysqli_connect_errno()) {
        echo json_encode(array('error' => 'Failed to connect to MySQL: ' . mysqli_connect_error()));
        exit;
    }
    
    $userId = mysqli_real_escape_string($con, $userId); 
    $sql = "SELECT * FROM favorites WHERE user_id = '$userId'";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        echo json_encode(array('error' => 'Error fetching favorites: ' . mysqli_error($con)));
        exit;
    }

    $favorites = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $favorites[] = array(
            'movie_id' => $row['movie_id'],
        );
    }

    mysqli_close($con);

    echo json_encode(array('favorites' => $favorites));
    exit;
} else {
    echo json_encode(array('error' => 'User ID not provided'));
    exit;
}
?>
