<?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['SBUser'])){
        header('Location: ../Admin/login.php');
    } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <!--Requiring database connection-->
    <?php require_once('../Core/easyinit.php'); ?>
    <!--Including all necessary bootstrap information-->
    <?php include('../Includes/boot.php'); ?>

    <!--setting password-->
    <?php
        //setting data from post and user_data
        $hashed = $user_data['password'];
        $old_password = ((isset($_POST['old_password']))?htmlentities($_POST['old_password'], ENT_QUOTES, "UTF-8"):'');
        $old_password = trim($old_password);
        $password = ((isset($_POST['password']))?htmlentities($_POST['password'], ENT_QUOTES, "UTF-8"):'');
        $password = trim($password);
        $confirm = ((isset($_POST['confirm']))?htmlentities($_POST['confirm'], ENT_QUOTES, "UTF-8"):'');
        $confirm = trim($confirm);
        $new_hashed = password_hash($password, PASSWORD_DEFAULT);
        $user_id = $user_data['id'];
    ?>

</head>
<body> 

<div id="login-form">
    <div>

        <?php
            $checkone = 1;//all fields must be filled out
            $checktwo = 1;//Password must be at least 6 characters
            $checkthree = 1;//check if new password matches confirm
            $checkfour = 1;//verify password
            if($_POST){
                //all fields must be filled out
                if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
                    echo '<p>You must enter your old password and new password twice.<br></p>';
                    $checkone = 0;
                }

                //Password must be at least 6 characters
                if(strlen($password)<6){
                    echo '<p>Password must be at least 6 characters.<br></p>';
                    $checktwo = 0;
                }

                //check if new password matches confirm
                if($password !== $confirm){
                    echo "<p>The new password and confirm new password do not match.<br></p>";
                    $checkthree = 0;
                }

                //verify password
                if(!password_verify($old_password, $hashed)){
                    echo '<p>Your old password does not match our records.<br></p>';
                    $checkfour = 0;
                }

                //Check for errors
                if($checkone==0 || $checktwo==0 || $checkthree==0 || $checkfour==0){}
                else if($checkone==1 && $checktwo==1 && $checkthree==1 && $checkfour==1){
                    //change password
                    $db->query("UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'");
                    header('Location: updated_password.php');
                }
            }
        ?>

    </div>
    <h2 class="text-center">Change Password</h2><hr>
    <form action="change_password.php" method="post">

        <div class="form-group">
            <label for="old_password"><h4>Old Password: </h4></label>
            <input type="password" name="old_password" id="old_password" class="form-control" value="<?=$old_password?>">
        </div>

        <div class="form-group">
            <label for="password"><h4>New Password: </h4></label>
            <input type="password" name="password" id="password" class="form-control" value="<?=$password?>">
        </div>

        <div class="form-group">
            <label for="confirm"><h4>Confirm New Password: </h4></label>
            <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm?>">
        </div>

        <div class="form-group">
            <a href="../index.php" class="btn btn-default">Cancel</a>
            <input type="submit" value="Change Password" class="btn btn-warning">
        </div>
            
    </form>    
</div>

<h4 class="text-center"><a href="../index.php" alt="home" id="white"><u>Home Page</u></a></h4>
<br>

<!-- Including footer file -->
<?php include('../Includes/foot.php'); ?>

</body>
</html>