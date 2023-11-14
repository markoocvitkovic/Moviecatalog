<?php

    session_start();

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {   
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\style.css">
    <title>MovieCatalog</title>
</head>
<body>
    <div class="container">
        <nav>
            <div class="logo">
               <span>MovieCatalog </span>
            </div>
            <input type="checkbox" id="click">
            <label for="click" class="menu-btn">
            <i class="fas fa-bars"></i>
            </label>
            <ul>
               <li><a class="active" href="favorites.php"><i class="fa-solid fa-house"></i> Favorites</a></li>
               <li><a class="active" href="update.php"><i class="fa-solid fa-house"></i> Account</a></li>
               <li><a class="active" href="logout.php"><i class="fa-solid fa-house"></i> Logout</a></li>
            </ul>
         </nav>
         <div class="welcome-message">
            <p>Dobrodošli, ID korisnika: <?php echo $user_id; ?></p>
        </div>
        <div class="search">
            <input type="text" placeholder="Search Movie...">
            <button>Search</button>
        </div>
        <div class="movies-container favorites">
            <h1>Favorites</h1>
            <div class="movies-grid">                
            </div>
        </div>
        <div class="movies-container trending">
            <h1>Trending</h1>
            <div class="movies-grid">               
            </div>
        </div>
        <div class="movies-container comedy">
            <h1>Comedy</h1>
            <div class="movies-grid">               
            </div>
        </div>
        <div class="movies-container action">
            <h1>Action</h1>
            <div class="movies-grid">               
            </div>
        </div>
        <div class="movies-container science_fiction">
            <h1>Science fiction</h1>
            <div class="movies-grid">               
            </div>
        </div>
        <div class="movies-container drama">
            <h1>Drama</h1>
            <div class="movies-grid">               
            </div>
        </div>
    </div>
    <div class="popup-container"> 
    </div>
    <script src="app.js"></script>
</body>
</html>