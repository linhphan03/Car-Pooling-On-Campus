<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gettysburg CarPool</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <?php
    	session_start();
    	
       include("navbar.php");
       
       if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uid'])) {
    		// Sanitize the input to prevent XSS or other attacks
    		$uid = htmlspecialchars(trim($_POST['uid']));
    
    		// Set the session variable
    		$_SESSION['uid'] = $uid;

    		// Redirect to the same page or another page if needed
    		header("refresh:1;url=index.php");
    		exit();
	}

        if($_GET["menu"]){
            $menu = $_GET["menu"];
            switch ($menu){
                case "home":
                    include("home.php");
                    break;
                case "profile":
                    include("profile.php");
                    break;
                case "faq":
                    include("faq.php");
                    break;
                case "about":
                    include("about.php");
                    break;
                case "login":
                    
                    break;
                case "logout":
                    header("refresh:1;url=index.php");
		      unset($_SESSION['uid']);
                    break;
            }
        }else{

        }
    ?>
    
    <!-- Hero Section -->
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-md-11 col-lg-9"> 
                <div class="row">
                    <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center text-center">
                        <div class="w-100">
                            <h1 class="display-3 fw-bold">Car Sharing</h1>
                            <h4 class="mb-4">Convenient and affordable rides</h4>
                            <p class="lead">Find a ride or offer one to others. Save money, reduce carbon footprint, and make transportation easier at Gettysburg College.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex justify-content-center align-items-center">
                        <img src="landing.png" alt="Main Image" class="img-fluid" style="max-height: 400px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="jumbotron text-center" style="margin-bottom:0">
  	<p>Gettysburg CarPooling is a Gettysburg College CS-360 Project developed by Linh Tran, Prabesh Bista, and Spencer Hagan. 2025</p>
    </div>


    <!-- Adds JS to the Application -->

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
