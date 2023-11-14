<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css\style_login.css"/>
    <link rel="stylesheet" href="css\style.css"/>
</head>
<body>
    <div class="container">
        <nav>
            <div class="logo">
                <span>MoviesCatalog</span>
            </div>
            <input type="checkbox" id="click">
            <label for="click" class="menu-btn">
                <i class="fas fa-bars"></i>
            </label>            
        </nav>
        
        <?php
    require('db.php');
    session_start();

    // Admin login
    if (isset($_POST['admin_username'])) {
        $admin_username = stripslashes($_REQUEST['admin_username']);
        $admin_username = mysqli_real_escape_string($con, $admin_username);
        $admin_password = stripslashes($_REQUEST['admin_password']);
        $admin_password = mysqli_real_escape_string($con, $admin_password);

        $admin_query = "SELECT * FROM `admin` WHERE admin_username='$admin_username'
                        AND admin_password='$admin_password'";
        $admin_result = mysqli_query($con, $admin_query) or die(mysqli_error($con));
        $admin_rows = mysqli_num_rows($admin_result);

        if ($admin_rows == 1) {
            $_SESSION['admin_username'] = $admin_username;          
            header("Location: admin_dashboard.php");
        } else {
            echo "<div class='form'>
                    <h3>Incorrect Admin Username/Password.</h3><br/>
                    <p class='link'>Click here to <a href='login.php'>Login</a> again.</p>
                </div>";
        }
    } else {
?>             
        <form class="form" method="post" name="admin_login">
            <h1 class="login-title">Admin Login</h1>
            <input type="text" class="login-input" name="admin_username" placeholder="Admin Username" autofocus="true"/>
            <input type="password" class="login-input" name="admin_password" placeholder="Admin Password"/>
            <input type="submit" value="Login" name="admin_submit" class="login-button"/>
            <p class="link">Not an admin? <a href="login.php">User Login</a></p>
        </form>
        
        <?php
            }
        ?>
        
    </div>
</body>
</html>
