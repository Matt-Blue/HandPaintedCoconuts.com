<?php
    require_once('../Core/easyinit.php');
    //unset session and forward
    unset($_SESSION['SBUser']);
    header('Location: login.php');
?>