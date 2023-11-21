<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
    <link rel="stylesheet" href="css/style_login.css"/>
    <link rel="stylesheet" href="css/style.css"/>
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
    </nav>
    <?php
    require('db.php');

    if (isset($_REQUEST['username'])) {
        $username = stripslashes($_REQUEST['username']);   
        $username = mysqli_real_escape_string($con, $username);
        $firstname = stripslashes($_REQUEST['firstname']);
        $firstname = mysqli_real_escape_string($con, $firstname);
        $lastname = stripslashes($_REQUEST['lastname']);
        $lastname = mysqli_real_escape_string($con, $lastname);
        $email = stripslashes($_REQUEST['email']);
        $email = mysqli_real_escape_string($con, $email);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($con, $password);
        $create_datetime = date("Y-m-d H:i:s");        
        $dob = stripslashes($_REQUEST['dob']);
        $dob = mysqli_real_escape_string($con, $dob);
        
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $query = "INSERT INTO `users1` (username, firstname, lastname, password, email, create_datetime, dob)
                  VALUES ('$username', '$firstname', '$lastname', '$hashed_password', '$email', NOW(), '$dob')";

        $result = mysqli_query($con, $query);
        if ($result) {
            echo "<div class='form'>
                <h3>You are registered successfully.</h3><br/>
                <p class='link'>Click here to <a href='login.php'>Login</a></p>
                </div>";
        } else {
            echo "<div class='form'>
                <h3>Required fields are missing.</h3><br/>
                <p class='link'>Click here to <a href='registration.php'>registration</a> again.</p>
                </div>";
        }
    } else {
    ?>
    <form class="form" action="" method="post">
        <h1 class="login-title">Registration</h1>
        <input type="text" class="login-input" name="username" placeholder="Username" required />
        <input type="text" class="login-input" name="firstname" placeholder="First Name"/>
        <input type="text" class="login-input" name="lastname" placeholder="Last Name"/>
        <input type="text" class="login-input" name="email" placeholder="Email Adress">       
        <input type="date" class="login-input" name="dob" placeholder="Date of Birth"/>
        <input type="password" class="login-input" name="password" placeholder="Password">
        <input type="submit" name="submit" value="Register" class="login-button">
        <p class="link">Already have an account? <a href="login.php">Login here</a></p>
    </form>
    <?php
    }
    ?>
</div>
</body>
</html>
