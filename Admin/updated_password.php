<?php 
    //start session and forward if not logged in
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['SBUser'])){
        header('Location: ../_Admin/login.php');
    } 
?>
<!DOCTYPE html>
<html  lang="en">
<head>
    
    <!--Requiring database connection-->
    <?php require_once('../Core/easyinit.php'); ?>
    <!--Including all necessary bootstrap information-->
    <?php include('../Includes/boot.php'); ?>

</head>
<body> 

<!--Making space between top and background image so the navbar doesn't cover the image-->
<section class="container-fluid">  
    <center><header class="col-md-12"><p></p></header></center>
</section>

<h1 class="text-center">Your password has been successfully updated!</h1>
<h4 class="text-center"><a href="../index.php" alt="home" id="white"><u>Home Page</u></a></h4>
<br>

<!-- Including footer file -->
<?php include('../Includes/foot.php'); ?>

</body>
</html>