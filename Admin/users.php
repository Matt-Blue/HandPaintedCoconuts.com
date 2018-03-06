<?php session_start(); ?>
<!DOCTYPE html>
<html  lang="en">
<head>
    
    <!--Requiring database connection-->
    <?php require_once('../Core/easyinit.php'); ?>
    <!--Including all necessary bootstrap information-->
    <?php include('../Includes/boot.php'); ?>
    <!--Including navigation bar-->
    <?php include('../Includes/nav.php'); ?>

    <?php
        //query and order all users
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
    //deletes the user
    if(isset($_GET['delete'])){
        $delete_id = htmlentities($_GET['delete'], ENT_QUOTES, "UTF-8");
        $db->query("DELETE FROM users WHERE id = '$delete_id'");
        $_SESSION['success_flash'] = 'User has been deleted!';
        header('Location: users.php');
    }
    //add user
    if(isset($_GET['add'])){
        //get post data
        $name = ((isset($_POST['name']))?htmlentities($_POST['name'], ENT_QUOTES, "UTF-8"):'');
        $name = trim($name);
        $email = ((isset($_POST['email']))?htmlentities($_POST['email'], ENT_QUOTES, "UTF-8"):'');
        $email = trim($email);
        $password = ((isset($_POST['password']))?htmlentities($_POST['password'], ENT_QUOTES, "UTF-8"):'');
        $password = trim($password);
        $confirm = ((isset($_POST['confirm']))?htmlentities($_POST['confirm'], ENT_QUOTES, "UTF-8"):'');
        $confirm = trim($confirm);
        $permissions = ((isset($_POST['permissions']))?htmlentities($_POST['permissions'], ENT_QUOTES, "UTF-8"):'');
        $permissions = trim($permissions);
        
        if($_POST){

            $checkzero = 1;//make sure the email is in the database
            $checkone = 1;//make sure everything is filled in
            $checktwo = 1;//make sure password is at least six characters
            $checkthree = 1;//check if password and confirm match
            $checkfour = 1;//validate email 

            $emailQuery = $db->query("SELECT * FROM users WHERE email = '$email'");
            $emailCount = mysqli_num_rows($emailQuery);

            //make sure the email is in the database
            if($emailCount !== 0){
                $checkzero = false;
            }

            //make sure everything is filled in
            $required = array('name', 'email', 'password', 'confirm', 'permissions');
            foreach($required as $f){
                if(empty($_POST[$f])){
                    $checkone = 0;
                }
            }

            //make sure password is at least six characters
            if(strlen($password) < 6){
                $checktwo = 0;
            }

            //check if new password matches confirm
            if($password !== $confirm){
                echo "<p>The new passwords do not match.<br></p>";
                $checkthree = 0;
            }

            //validate email (premade function)
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
                //Add user to database
                $hashed = password_hash($password,PASSWORD_DEFAULT);
                echo 'name: '.$name.'
                email: '.$email.'
                password: '.$hashed.'
                permissions: '.$permissions.'
                ';
                $query = "INSERT INTO users (full_name, email, password, permissions) VALUES ('$name','$email','$hashed','$permissions')";
                if ($db->query($query) === TRUE) {
                    header('Location: users.php');
                } else {
                    echo "Error: " . $query . "<br>" . $db->error;
                }
                
            }
        }
        ?>
            <h1 class="text-center">Add a new user</h1>
            <form action="users.php?add=1" method="post">

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
                    <label for="name"><p id="pwhite">Permissions: </p></label>
                    <select class="form-control" name="permissions">
                        <option value=""<?=(($permissions = '')?' selected': '');?>></option>
                        <option value="editor"<?=(($permissions = 'editor')?' selected': '');?>>Editor</option>
                        <option value="admin"<?=(($permissions = 'admin')?' selected': '');?>>Admin</option>
                    </select>
                </div>
                <div class="form-group col-md-8 col-md-offset-2">
                    <a href="users.php" class="btn btn-default">Cancel</a>
                    <input type="submit" value="Add" class="btn btn-primary"> 
                </div>
            </form>
        <?php
    }
    else{
?>

<!--Main users page when add and delete are not set-->
<center>
    <h1>Users</h1>
    <a href="users.php?add=1" class="btn btn-success" id="add_product_btn">Add New User</a>
</center><br>

<!--Retreives and displays all user data in a table-->
<div id="whiteback">
<table class="table table-bordered table-striped table-condensed">
    <thead><th></th><th>Name</th><th>Email</th><th>Join Date</th><th>Last Login</th><th>Permissions</th></thead>
    <tbody>
        <?php while($user = mysqli_fetch_assoc($userQuery)): ?>
        <tr>
            <td>
                <?php if($user['id'] != $user_data['id']): ?>
                    <a href="users.php?delete=<?=$user['id'];?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove-sign"></span></a>
                <?php endif; ?>
            </td>
            <td><?=$user['full_name']?></td>
            <td><?=$user['email']?></td>
            <td><?= date("M d, Y h:i A", strtotime($user['join_date'])); ?></td>
            <td><?= date("M d, Y h:i A", strtotime($user['last_login'])); ?></td>
            <td><?=$user['permissions']?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</div>
<?php } ?>

<!-- Including Social Media -->
<?php include('../Includes/social.php'); ?>
<!-- Including footer file -->
<?php include('../Includes/foot.php'); ?>
<!-- Including Details Modal -->
<?php include('../Includes/detailsModal.php'); ?>

</body>
</html>