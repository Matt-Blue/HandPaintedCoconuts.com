<!--Requiring database connection-->
<?php require_once('../Core/easyinit.php'); ?>
<!--Including all necessary bootstrap information-->
<?php include('../Includes/boot.php'); ?>

<!DOCTYPE html>
<html  lang="en">
<head>

    <!--setting email and password-->
    <?php
        $email = ((isset($_POST['email']))?htmlentities($_POST['email'], ENT_QUOTES, "UTF-8"):'');
        $email = trim($email);
        $password = ((isset($_POST['password']))?htmlentities($_POST['password'], ENT_QUOTES, "UTF-8"):'');
        $password = trim($password);
    ?>

</head>
<body> 

<div id="login-form">
    <div>

        <?php
            $checkone = 1;//must have email and password
            $checktwo = 1;//validate email
            $checkthree = 1;//Password must be at least 6 characters
            $checkfour = 1;//check if email exists in database
            $checkfive = 1;//verify password
            if($_POST){
                //form validation
                if(empty($_POST['email']) || empty($_POST['password'])){
                    echo '<p>You must provide an email and password.<br></p>';
                    $checkone = 0;
                }

                //validate email
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    echo '<p>You must enter a valid email.<br></p>';
                    $checktwo = 0;
                }

                //Password must be at least 6 characters
                if(strlen($password)<6){
                    echo '<p>Password must be at least 6 characters.</br>';
                    $checkthree = 0;
                }

                //check if email exists in database
                $query = $db->query("SELECT * FROM users WHERE email = '$email'");
                $user = mysqli_fetch_assoc($query);
                $userCount = mysqli_num_rows($query);
                if($userCount < 1){
                    echo '<p>That email doesn\'t exist in our database.</br>';
                    $checkfour = 0;
                }

                //verify password
                if(!password_verify($password, $user['password'])){
                    echo '<p>The password does not match our records. Please try again.</p>';
                    $checkfive = 0;
                }

                //Check for errors
                if($checkone==0 || $checktwo==0 || $checkthree==0 || $checkfour==0 || $checkfive==0){}
                else if($checkone==1 && $checktwo==1 && $checkthree==1 && $checkfour==1 && $checkfive==1){
                    //log user in
                    $user_id = $user['id'];
                    login($user_id);
                }
            }
        ?>

    </div>
    <h2 class="text-center">Login</h2><hr>
    <form action="login.php" method="post">

        <div class="form-group">
            <label for="email"><h4>Email: </h4></label>
            <input type="email" name="email" id="email" class="form-control" value="<?=$email?>">
        </div>

        <div class="form-group">
            <label for="password"><h4>Password: </h4></label>
            <input type="password" name="password" id="password" class="form-control" value="<?=$password?>">
        </div>

        <div class="form-group">
            <input type="submit" value="Login" class="btn btn-warning">
        </div>
            
    </form>    
</div>

<h4 class="text-center"><a href="signup.php?add=1" alt="home" id="white">Signup</a></h4>
<br>
<h3 class="text-center"><a href="../index.php" alt="home" id="white">Home Page</a></h3>
<br>

<!--Login function-->
<?php
    function login($user_id){
        $_SESSION['SBUser'] = $user_id; 
        //header('Location: ../index.php');
        ?>
		<script type="text/javascript">
			window.location.href = '../index.php';
		</script>
	<?php
    }
?>

<!-- Including footer file -->
<?php include('../Includes/foot.php'); ?>

</body>
</html>