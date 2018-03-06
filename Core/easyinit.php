<?php
    $db = mysqli_connect("localhost", "mbluestein88", "#Mattblue88", "mblueste_login");
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
    //if (!isset($_SESSION)) { session_start(); }
    if(mysqli_connect_errno()){
        echo 'Database connection failed with the following errors: ' . mysqli_connect_error();
        die();
    }

    //cookie defined terms
    define('CART_COOKIE','Sisd3J45sjd9IJlI');
    define('CART_COOKIE_EXPIRE', time() + (86400*30));

    //checkout defined terms
    define('TAXRATE',0.06);
    define('CURRENCY', 'usd');

    //cart creation
    $cart_id = '';
    if(isset($_COOKIE[CART_COOKIE])){
        $cart_id = htmlentities($_COOKIE[CART_COOKIE], ENT_QUOTES, "UTF-8");
    }

    //user login
    if(isset($_SESSION['SBUser'])){
        $user_id = $_SESSION['SBUser'];
        $query = $db->query("SELECT * FROM users WHERE id = '$user_id'");
        $user_data = mysqli_fetch_assoc($query);
        $user_name = $user_data['full_name'];
    }
?>