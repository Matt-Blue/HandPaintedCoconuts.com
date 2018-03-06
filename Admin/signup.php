<?php session_start(); ?>
<!DOCTYPE html>
<html  lang="en">
<head>
    
    <!--Requiring database connection-->
    <?php require_once('../Core/easyinit.php'); ?>
    <!--Including all necessary bootstrap information-->
    <?php include('../Includes/boot.php'); ?>

    <?php
        //get all users organized by name
        $userQuery = $db->query('SELECT * FROM users ORDER BY full_name');
    ?>
    
</head>
<body> 

<!--Making space between top and background image so the navbar doesn't cover the image-->
<section class="container-fluid">  
    <center><header class="col-md-12"><p></p></header></center>
    <h1><br></h1>
</section>

<?php
    //starts out in ?add=1
    if(isset($_GET['add'])){
        $name = ((isset($_POST['name']))?htmlentities($_POST['name'], ENT_QUOTES, "UTF-8"):'');
        $name = trim($name);
        $email = ((isset($_POST['email']))?htmlentities($_POST['email'], ENT_QUOTES, "UTF-8"):'');
        $email = trim($email);
        $password = ((isset($_POST['password']))?htmlentities($_POST['password'], ENT_QUOTES, "UTF-8"):'');
        $password = trim($password);
        $confirm = ((isset($_POST['confirm']))?htmlentities($_POST['confirm'], ENT_QUOTES, "UTF-8"):'');
        $confirm = trim($confirm);
        $permissions = '';
        
        //once you submit the form
        if($_POST){

            $checkzero = 1;//email is in database
            $checkone = 1;//all fields are filled out
            $checktwo = 1;//password is at least 6 characters
            $checkthree = 1;//check if new password matches confirm
            $checkfour = 1;//validate email

            $emailQuery = $db->query("SELECT * FROM users WHERE email = '$email'");
            $emailCount = mysqli_num_rows($emailQuery);

            //email is in database
            if($emailCount !== 0){
                $checkzero = false;
            }

            //all fields are filled out
            $required = array('name', 'email', 'password', 'confirm');
            foreach($required as $f){
                if(empty($_POST[$f])){
                    $checkone = 0;
                }
            }

            //password is at least 6 characters
            if(strlen($password) < 6){
                $checktwo = 0;
            }

            //check if new password matches confirm
            if($password !== $confirm){
                echo "<p>The new passwords do not match.<br></p>";
                $checkthree = 0;
            }

            //validate email
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $checkfour = 0;
            }


            if($checkzero == 0 || $checkone == 0 || $checktwo == 0 || $checkthree == 0 || $checkfour == 0){
                //failure printing
                if($checkone == 0){
                    echo '<p>That email already exists.</p>';
                }
                if($checkone == 0){
                    echo '<p>You must fill out all required fields.</p>';
                }
                if($checktwo == 0){
                    echo '<p>Your password must be at least six characters long.</p>';
                }
                if($checkfour == 0){
                    echo '<p>You must enter a valid email.</p>';
                }
            }
            elseif($checkzero == 1 && $checkone == 1 && $checktwo == 1 && $checkthree == 1 && $checkfour == 1){
                $hashed = password_hash($password,PASSWORD_DEFAULT);
                $query = "INSERT INTO users (full_name, email, password, permissions) VALUES ('$name','$email','$hashed','$permissions')";
                if ($db->query($query) === TRUE) {
                    //header('Location: login.php');
                    ?>
                    <script>window.location = "login.php";</script>
		<?php
                } else {
                    echo "Error: " . $query . "<br>" . $db->error;
                }
                
            }
        }
        ?>
        
            <h1 class="text-center">Sign Up</h1>
            <form action="signup.php?add=1" method="post">

                <div class="form-group col-md-8 col-md-offset-2">
                    <label for="name"><p id="pwhite">Full Name: </p></label>
                    <input type="text" name="name" id="name" class="form-control" value="<?=$name;?>">
                </div>

                <div class="form-group col-md-8 col-md-offset-2">
                    <label for="email"><p id="pwhite">Email: </p></label>
                    <input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
                </div>

                <div class="form-group col-md-8 col-md-offset-2">
                    <label for="password"><p id="pwhite">Password: </p></label>
                    <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
                </div>

                <div class="form-group col-md-8 col-md-offset-2">
                    <label for="confirm"><p id="pwhite">Confirm Password: </p></label>
                    <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
                </div>
                
                <div class="form-group col-md-8 col-md-offset-2">
                    <a href="../index.php" class="btn btn-default">Cancel</a>
                    <input type="submit" value="Signup" class="btn btn-warning"> 
                </div>
            </form>
        
            <div class="col-md-12"><center>
            <h4 class="text-center"><a href="login.php" alt="home" id="white">Login</a></h4>
            <br>
            <h3 class="text-center"><a href="../index.php" alt="home" id="white">Home Page</a></h3>
            <br>
            </center></div>
        
        <?php
    }
    else{
?>

<center>
    <h1>Create an Account</h1>
    <a href="signup.php?add=1" class="btn btn-success" id="add_product_btn">SIGN UP</a>
</center><br>

</div>
<?php } ?>

<!-- Including Social Media -->
<!-- Including footer file -->
<?php include('../Includes/foot.php'); ?>

</body>
</html>