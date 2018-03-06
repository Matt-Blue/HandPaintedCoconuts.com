<!--Login function-->
<?php
    function is_logged_in(){
        if(isset($_SESSION['SBUser']) && $_SESSION['SBUser']>0){
            return true;
        }else{return false;}
    }

    function login_error_redirect(){
        $_SESSION['error_flash'] = 'You must be logged in to access that page.';
        header('Location: login.php');
    }

    function has_permission($permission = 'admin'){
        $permissions = explode(',', $user_data['permissions']);
        if(in_array($permission, $permissions, true)){
            return true;
        }
        else{return false;}
    }
?>