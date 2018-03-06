<?php
    require_once "../Core/easyinit.php";
    //getting post data
    $mode = ((isset($_POST['mode']))?htmlentities($_POST['mode'], ENT_QUOTES, "UTF-8"):'');
    $edit_id = ((isset($_POST['edit_id']))?htmlentities($_POST['edit_id'], ENT_QUOTES, "UTF-8"):'');
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
    $result = mysqli_fetch_assoc($cartQ);
    $items = json_decode($result['items'], true);
    $updated_items = array();
    //making it work on localhost 
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
    //Remove function based on mode
    if($mode == 'removeone'){
        foreach ($items as $item){
            if($item['id'] == $edit_id){
                $item['quantity'] = $item['quantity'] - 1;
            }
            if($item['quantity'] > 0){
                $updated_items[] = $item;
            }
        }
    }
    //add function based on mode
    if($mode == 'addone'){
        foreach ($items as $item){
            if($item['id'] == $edit_id){
                $item['quantity'] = $item['quantity'] + 1;
            }
            $updated_items[] = $item;
        }
    }
    //updates cart since it still has items in it
    if(!empty($updated_items)){
        $json_updated = json_encode($updated_items);
        $db->query("UPDATE cart SET items = '$json_updated' WHERE id = '$cart_id'");
    }
    //delete cart if the updated items is an empty string
    if(empty($updated_items)){
        $db->query("DELETE FROM cart WHERE id = '$cart_id'");
        setcookie(CART_COOKIE,'',1,"/",$domain,false);
    }
?>