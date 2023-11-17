<?php
    session_start();

    if (!isset($_SESSION['admin_username'])) {
        header("Location: login_admin.php");
        exit();
    }

    $con = mysqli_connect("localhost", "root", "", "moviecatalog");

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    if (isset($_POST['delete_user'])) {
        $userId = $_POST['delete_user'];

        $deleteQuery = "DELETE FROM users WHERE id='$userId'";
        $deleteResult = mysqli_query($con, $deleteQuery);

        if ($deleteResult) {
            echo "User deleted successfully.";
           
        } else {
            echo "Error deleting user: " . mysqli_error($con);
        }
    }

    $query = "SELECT id, username, firstname, lastname, email, create_datetime, dob FROM users"; 
    $result = mysqli_query($con, $query);

    if ($result) {
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        echo "Error fetching users: " . mysqli_error($con);
    }

    mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\style.css"/>
    <nav>
            <div class="logo">
               <span>MoviesCatalog </span>
            </div>
            <input type="checkbox" id="click">
            <label for="click" class="menu-btn">
            <i class="fas fa-bars"></i>
            <ul>     
               <li><a class="active" href="login_admin.php"><i class="fa-solid fa-house"></i> Logout</a></li>
            </ul>
            </label>            
         </nav>
    <title>Admin Dashboard</title>
    <style>
        body {color: white;}
        h2, h3 {color: white;}
        a {color: white;}
        hr {border-color: white;}
        button {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
           
        }
        button:hover {
            background-color: #900;
        }
    </style>
</head>
<body>
    <div style="margin-right: 20px; margin-top: 20px;">
    <h2>Dobrodošli, Admin!</h2>
    <h3>Svi korisnici:</h3>
    <ul>
        <?php foreach ($users as $user) : ?>
            <li>
                <strong>Username:</strong> <?php echo $user['username']; ?><br>
                <strong>First Name:</strong> <?php echo $user['firstname']; ?><br>
                <strong>Last Name:</strong> <?php echo $user['lastname']; ?><br>
                <strong>Email:</strong> <?php echo $user['email']; ?><br>
                <strong>Create Datetime:</strong> <?php echo $user['create_datetime']; ?><br>
                <strong>Date of Birth:</strong> <?php echo $user['dob']; ?><br>
                <form method="post" action="">
                    <input type="hidden" name="delete_user" value="<?php echo $user['id']; ?>">
                    <button type="submit">Delete user</button>
                </form>
                <hr>
            </li>
        <?php endforeach; ?>
    </ul>    
</body>
</html>
