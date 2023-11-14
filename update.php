<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Update Profile</title>
    <link rel="stylesheet" href="css\update.css"/>
    <link rel="stylesheet" href="css\style.css"/>
</head>
<body>
<div class="container">
        <nav>
            <div class="logo">
               <span>MoviesCatalog </span>
            </div>
            <input type="checkbox" id="click">
            <label for="click" class="menu-btn">
            <i class="fas fa-bars"></i>
            </label>
            <ul>
               <li><a class="active" href="home.php"><i class="fa-solid fa-house"></i> Home</a></li>
               <li><a class="active" href="favorites.php"><i class="fa-solid fa-house"></i> Favorites</a></li>
               <li><a class="active" href="logout.php"><i class="fa-solid fa-house"></i> Logout</a></li>
            </ul>
         </nav>
        <?php
            require('db.php');
            session_start();

            // Check if user is logged in
            if (!isset($_SESSION['username'])) {
                header("Location: login.php");
                exit();
            }

            $username = $_SESSION['username'];

            if (isset($_POST['submit'])) {
                $newUsername = mysqli_real_escape_string($con, $_POST['new_username']);
                $newFirstName = mysqli_real_escape_string($con, $_POST['new_firstname']);
                $newLastName = mysqli_real_escape_string($con, $_POST['new_lastname']);
                $newEmail = mysqli_real_escape_string($con, $_POST['new_email']);
                $newPassword = mysqli_real_escape_string($con, $_POST['new_password']);
                $newDOB = mysqli_real_escape_string($con, $_POST['new_dob']);

                $updateQuery = "UPDATE `users` SET 
                                username = '$newUsername', 
                                firstname = '$newFirstName', 
                                lastname = '$newLastName', 
                                email = '$newEmail', 
                                password = '" . md5($newPassword) . "', 
                                dob = '$newDOB' 
                                WHERE username = '$username'";

                $updateResult = mysqli_query($con, $updateQuery);

                if ($updateResult) {
                    echo "<div class='form'>
                        <h3>Your profile has been updated successfully.</h3><br/>                  
                        </div>";
                } else {
                    echo "<div class='form'>
                        <h3>Error updating profile. Please try again.</h3><br/>
                        <p class='link'>Click here to <a href='update.php'>update profile</a> again.</p>
                        </div>";
                }
            } else {
              
                $selectQuery = "SELECT * FROM `users` WHERE username = '$username'";
                $result = mysqli_query($con, $selectQuery);
                $row = mysqli_fetch_assoc($result);
        ?>
                <form class="form" action="" method="post">
                    <h1 class="login-title">Update Profile</h1>
                    <input type="text" class="login-input" name="new_username" placeholder="New Username" value="<?php echo $row['username']; ?>" required />
                    <input type="text" class="login-input" name="new_firstname" placeholder="New First Name" value="<?php echo $row['firstname']; ?>" />
                    <input type="text" class="login-input" name="new_lastname" placeholder="New Last Name" value="<?php echo $row['lastname']; ?>" />
                    <input type="text" class="login-input" name="new_email" placeholder="New Email Address" value="<?php echo $row['email']; ?>" />
                    <input type="date" class="login-input" name="new_dob" placeholder="New Date of Birth" value="<?php echo $row['dob']; ?>" />
                    <input type="password" class="login-input" name="new_password" placeholder="New Password">
                    <input type="submit" name="submit" value="Update Profile" class="login-button">           
                </form>
        <?php
            }
        ?>
</div>
</body>
</html>
