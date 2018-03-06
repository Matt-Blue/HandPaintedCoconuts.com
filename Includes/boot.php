<?php 
    //start session if it is not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Hand Painted Coconuts</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>   

<!--Including default style sheet based on being logged in or not-->
<?php 
    if(isset($_SESSION['SBUser'])){
        ?>
            <link href="/Includes/styleBack.css" rel="stylesheet" type="text/css"> 
        <?php
    }else{
        ?>
            <link href="/Includes/style.css" rel="stylesheet" type="text/css"> 
        <?php
    }
?>
<!--Including stripe library-->
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
<?php session_start(); ?>
<!DOCTYPE html>
<html  lang="en">
<head> 
<link rel="icon" href="http://oliveandvinnies.com/wp-content/uploads/2014/12/Dollarphotoclub_64131565.jpg" />