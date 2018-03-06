<?php session_start(); ?>
<!DOCTYPE html>
<html  lang="en">
<head>
    
    <!--Requiring database connection-->
    <?php include('top.php');
    //if button is pressed in addons table initiate delete function
    if (isset($_POST['delete'])){ 
        //get post data
        $i = ((isset($_POST['i']))?htmlentities($_POST['i'], ENT_QUOTES, "UTF-8"):'');
            $i = trim($i);
        if($cart_id != ''){
                $cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
                $result = mysqli_fetch_assoc($cartQ);
                //items in cart
                $items = json_decode($result['items'],true);
                //items in addons
                $addon_items = json_decode($result['addons'],true);
                unset($addon_items[$i]); 
                $items_json = json_encode($addon_items);
                $sql = "UPDATE cart SET addons = '$items_json' WHERE id = '$cart_id'";
                if ($db->query($sql) === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $db->error;
                }
    }   }   ?>

    <?php
    //get post data
    $price = ((isset($_POST['price']))?htmlentities($_POST['price'], ENT_QUOTES, "UTF-8"):'');
        $price = trim($price);
    $product_id = ((isset($_POST['product_id']))?htmlentities($_POST['product_id'], ENT_QUOTES, "UTF-8"):'');
        $product_id = trim($product_id);   
    $custom = ((isset($_POST['custom']))?htmlentities($_POST['custom'], ENT_QUOTES, "UTF-8"):'');
        $custom = trim($custom);
    $placement = ((isset($_POST['placement']))?htmlentities($_POST['placement'], ENT_QUOTES, "UTF-8"):'');
        $placement = trim($placement);
    $extra = ((isset($_POST['extra']))?htmlentities($_POST['extra'], ENT_QUOTES, "UTF-8"):'');
        $extra = trim($extra);

    //making it work on localhost 
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
    
    if($cart_id != ''){
        $cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
        $result = mysqli_fetch_assoc($cartQ);
        //items in cart
        $cart_items = json_decode($result['items'],true);
        //items in addons
        $addon_items = json_decode($result['addons'],true);

        $item[] = array(
            'addon_id'      => count($addon_items),
            'price'         => $price,
            'product_id'    => $product_id,
            'custom'        => $custom,
            'placement'     => $placement,
            'extra'         => $extra,
        );

        //Going through cart to validate addon requrest
        $checkone = 0;//items are all filled out
        $checktwo = 0;//item to be altered is in cart
        $checkthree = 0;//see if an addons array already exists

        if($product_id != '' && $custom != '' && $placement != ''){
            $checkone = 1;
        }

        foreach($cart_items as $cart_item){
            //Making sure the item being altered is in the cart
            if($cart_item['id'] == $product_id){
                $checktwo = 1;
            }
        }

        //Updating cart addons if they already exist

        if($addon_items != ''){$checkthree = 1;};
        
    }else{
        //setting it regardless of if a cart is present
        //addon id is 0
        $item[] = array(
            'addon_id'      => '0',
            'price'         => $price,
            'product_id'    => $product_id,
            'custom'        => $custom,
            'placement'     => $placement,
            'extra'         => $extra,
        );
    }
            
    if($checkone == 1 && $checktwo == 1){
        if($checkthree == 1){ 
            //UPDATE CART ADDONS
            //Logic to update the existing addons array
            $specific = $item[0];//used to prevent errors in how the array is stored
            $addon_items[] = $specific;//add to array to be uploaded
            $items_json = json_encode($addon_items);
            $sql = "UPDATE cart SET addons = '$items_json' WHERE id = '$cart_id'";
            if ($db->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $db->error;
            }
        }elseif($checkthree == 0){ 
            //CREATE CART ADDONS
            //logic to create and insert addons array
            $items_json = json_encode($item);
            $sql = "UPDATE cart SET addons = '$items_json' WHERE id = '$cart_id'";
            if ($db->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $db->error;
            }

            //OPTIONAL
            //set id equal to last inserted id (prebuilt function)
            //$cart_id = $db->insert_id;
            //setting cookie with cart_id
            //setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
        }
    }else{

    }
?>
    
</head>
<body> 

<!--Making space between top and background image so the navbar doesn't cover the image-->
<section class="container-fluid">  
    <center><header class="col-md-12"><p></p></header></center>
    <h1><br></h1>
</section>

<section class="container-fluid">  
  <center>
    <!--Left Side Bar-->
    <?php include('/Includes/left.php'); ?>

    <!--Display printout information about whether or not the item as received-->
    <div class="col-md-8">
        <?php
            if($checkone == 1 && $checktwo == 1){ ?>
                <h2>The following custom message was added to your cart.</h2><br>
                <h4>Custom Message: <?= $custom ?></h4><br>
                <h4>Message Placement: <?= $placement ?></h4><br>
                <h4>Extra Information: <?= $extra ?></h4><br>
                
                <?php }else{ ?>

            <!-- if checks are not passed -->
            <center>
                <h2>Please resubmit your custom message request.</h2><br>
                <?php if($checkone != 1){ ?>
                    <h4>Please fill in all required fields (extra instructions is optional).</h4>
                <?php }if($checktwo != 1){ ?>
                    <h4>Please select a product ID that matches an item in your cart.</h4>
                <?php } ?>
            </center>
        <?php }?>

        <!--Links to other locations-->
        <center><h3><br><u><a href="addons.php" id="white">Manage Addons</a></u></h3></center>
        <center><h3><br><u><a href="cart.php" id="white">Your Cart</a></u></h3></center>
        <center><h3><br><u><a href="browse.php" id="white">Back to Browse</a></u></h3><br><br></center>

    </div>

    <!--Right Side Bar-->
    <?php include('/Includes/right.php'); ?>
  </center>
</section>

<!-- Including Social Media -->
<?php include('/Includes/social.php'); ?>
<!-- Including footer file -->
<?php include('/Includes/foot.php'); ?>

</body>
</html>