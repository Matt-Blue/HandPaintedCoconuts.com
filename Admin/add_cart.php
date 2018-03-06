<?php
    require_once '../Core/easyinit.php';
    include '../Includes/foot.php';
    
    setcookie("location","top of add to cart");

    //getting variables from post
    $product_id = ((isset($_POST['product_id']))?htmlentities($_POST['product_id'], ENT_QUOTES, "UTF-8"):'');
    $available = ((isset($_POST['available']))?htmlentities($_POST['available'], ENT_QUOTES, "UTF-8"):'');
    $quantity = ((isset($_POST['quantity']))?htmlentities($_POST['quantity'], ENT_QUOTES, "UTF-8"):'');

    //item array to add to associative array for upload to database
    $item = array();
    $item[] = array(
        'id'        => $product_id,
        'quantity'  => $quantity,
    );

    //making it work on localhost 
    //$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;

    //getting the product for its variables
    $query = $db->query("SELECT * FROM products WHERE id = '$product_id'");
    $product = mysqli_fetch_assoc($query);
    echo($product['title']." was added to your cart.");

    //check to see if cart cookie exists
    if($cart_id != '' && $cart_id != '0'){
        //cart query
        $cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
        $cart = mysqli_fetch_assoc($cartQ);
        //getting the items already inside the cart
        $previous_items = json_decode($cart['items'],true);

        $item_match = 0;
        $new_items = array();

        //looping through existing items so that it will add onto the quantity instead of addins a separate but equal item
        foreach($previous_items as $pitem){
            if($item[0]['id'] == $pitem['id']){
                $pitem['quantity'] = $pitem['quantity'] + $item[0]['quantity'];
                if($pitem['quantity'] > $available){
                    $pitem['quantity'] = $available;
                }
                $item_match = 1;
            }
            $new_items[] = $pitem;
        }
        //if there was no item in the cart that matched the item being added
        if($item_match != 1){
            $new_items = array_merge($item, $previous_items);
        }

        //encode the result for upload to database
        $items_json = json_encode($new_items);
        //set expire time
        $cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
        //insert into database
        $db->query("UPDATE cart SET items = '$items_json', expire_date = '$cart_expire' WHERE id = '$cart_id'");
        //expire cookie
        //setcookie(CART_COOKIE,'',1);
        unset($_COOKIE["CART_COOKIE"]);
        //recreate cookie
        setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE);
    }else{
        //encode items for upload to database
        $items_json = json_encode($item);
        //set expire time
        $cart_expire = date("Y-m-d H:i:s", strtotime("+30 days"));
        //make new cart in database
        $db->query("INSERT INTO `cart` (`id`, `items`, `addons`, `expire_date`, `paid`) VALUES (NULL, '$items_json', '', '$cart_expire', '0');");
        //old insert function $sql = "INSERT INTO cart (items,addons,expire_date) VALUES ('$items_json','','$cart_expire')";
        //set id equal to last ID inserted into the database (prebuilt function)
        $cart_id = $db->insert_id;
        //setting cookie with cart_id
        setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE);
    }
?>