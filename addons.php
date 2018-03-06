<?php session_start(); ?>
<!DOCTYPE html>
<html  lang="en">
<head>
    <?php include('top.php');
    //if button is pressed in addons table initiate delete function
    if (isset($_POST['delete'])){ 
        //get post data
        $addon_id = ((isset($_POST['addon_id']))?htmlentities($_POST['addon_id'], ENT_QUOTES, "UTF-8"):'');
            $addon_id = trim($addon_id);
        if($cart_id != ''){
                $cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
                $result = mysqli_fetch_assoc($cartQ);
                //items in addons
                $addon_items = json_decode($result['addons'],true);
                //if there is only one item in the array
                if(count($addon_items) == 1){
                    //upload blank string and refresh page
                    if ($db->query("UPDATE cart SET addons = '' WHERE id = '$cart_id'") === true) {header("Refresh:0");} else {}
                }else{
                    $j=0;
                    foreach ($addon_items as $match) {
                        if($addon_id == $match['addon_id']) {
                            unset($addon_items[$j]);
                        }
                        $j++;
                    }
                    //TODO DOES NOT WORK update addon IDs
                    $j=0;
                    foreach ($addon_items as $item) {
                        $item['addon_id'] = $j;
                        $j++;
                    }
                    //encode
                    $addons_json = json_encode($addon_items);
                    //upload and refresh page
                    if ($db->query("UPDATE cart SET addons = '$addons_json' WHERE id = '$cart_id'") === true) {header("Refresh:0");} else {}
                }
    }   }   ?>
</head>
<body> 

<!--Making space between top and background image so the navbar doesn't cover the image-->
<section class="container-fluid">  
    <center><header class="col-md-12"><p></p></header></center>
    <h1><br></h1>
</section>

<!--Populating Store Inventory-->
<section class="container-fluid">  
  <center>
    
    <!--Left Side Bar-->
    <?php include('/home4/mbluestein88/HandPaintedCoconuts.com/Includes/left.php'); ?>

    <!--Rows of objects-->
    <div class="col-md-8">
        
        <h1><i>Custom Hand Painted Message</i></h1><br>
        <h2>Your custom message straight from our brushes to your mailbox.</h2><br>
        <p id="pmedium">$9.99 / Message / Coconut</p>
        <br><br>

        <!--make sure they have a cart before you allow addons-->
        <?php
            if($cart_id != '' && $cart_id != '0'){
                $cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
                $result = mysqli_fetch_assoc($cartQ);
                //items in cart
                $items = json_decode($result['items'],true);
                //items in addons
                $addon_items = json_decode($result['addons'],true);
        ?>

        <!-- Form to add.php to add the customization to the cart -->
        <form action="add.php" method="post" id="payment-form">
            <!-- Hidden form to post data about the option-->
            <input type="hidden" name="price" value="9.99">
            <div class="form-group col-md-12">
                <label for="product_id"><p id="pmedium">Product ID (Lookup Below): </p></label>
                <input type="number" name="product_id" class="form-control">
            </div>
            <div class="form-group col-md-12">
                <label for="custom"><p id="pmedium">Custom Writing: </p></label>
                <input type="text" name="custom" class="form-control">
            </div>
            <div class="form-group col-md-12">
                <label for="placement"><p id="pmedium">Placement of Writing on Coconut: </p></label>
                <input type="text" name="placement" class="form-control">
            </div>
            <div class="form-group col-md-12">
                <label for="extra"><p id="pmedium">Extra Instructions (Optional): </p></label>
                <input type="text" name="extra" class="form-control">
            </div>

            <!--Submit-->
            <button type="submit" class="btn btn-warning"><div id="black"> <p id="white"> Add to Order </p> </div></button>
        </form>
        <br>

        <!--Displaying cart-->
        <h3><br> Your Cart <br></h3>
        <table class="table table-bordered table-condensed table-striped">
                <thead>
                    <th><p id="pmedium">ID</p></th>
                    <th><p id="pmedium">Item</p></th>
                    <th><p id="pmedium">Price</p></th>
                    <th><p id="pmedium">Quantity</p></th>
                    <th><p id="pmedium">Subtotal</p></th>
                </thead>
                <tbody>
                    <?php
                        foreach($items as $item){
                            $product_id = $item['id'];
                            $productQ = $db->query("SELECT * FROM products WHERE id = '$product_id'");
                            $product = mysqli_fetch_assoc($productQ);
                    ?>
                    <tr id="whiteback">
                        <td id="pbsmall"><?=$product_id?></td>
                        <td id="pbsmall"><?=$product['title'];?></td>
                        <td id="pbsmall"><?="$".$product['price'];?></td>
                        <td id="pbsmall">
                            <button class="btn btn-xs btn-default" onclick="update_cart('removeone', <?= $product_id ?>);">-</button>
                            <?=$item['quantity'];?>
                            <?php if($item['quantity'] < $product['available']){ ?>
                                <button class="btn btn-xs btn-default" onclick="update_cart('addone', <?= $product_id ?>);">+</button>
                            <?php }else{ ?>
                                <span class="text-danger">Max</span>
                            <?php } ?>
                        </td>
                        <td id="pbsmall"><?="$".number_format(($product['price']*$item['quantity']),2);?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        <!--Displaying Addons-->
        <?php if($addon_items != '' && $addon_items != '[]' && $addon_items != null){ 
        $j = 0;//counter for display purposes so delete selects the correct item out of the associative array ?>

        <h3><br> Your Addons <br></h3>
        <table class="table table-bordered table-condensed table-striped">
                <thead>
                    <th><p id="pmedium">ID</p></th>
                    <th><p id="pmedium">Custom Message</p></th>
                    <th><p id="pmedium">Message Placement</p></th>
                    <th><p id="pmedium">Extra Message</p></th>
                    <th><p id="pmedium">Subtotal</p></th>
                </thead>
                <tbody>
                    <?php foreach($addon_items as $addon) {?>
                    <tr id="whiteback">
                        <td id="pbsmall"><?=$addon['product_id'];?></td>
                        <td id="pbsmall"><?=$addon['custom'];?></td>
                        <td id="pbsmall"><?=$addon['placement'];?></td>
                        <td id="pbsmall"><?=$addon['extra'];?></td>
                        <td id="pbsmall"><?="$".number_format($addon['price'],2);?></td>
                        <td id="pbsmall"><form method="POST">
                            <input type="hidden" name="addon_id"  value="<?= $addon['addon_id'];?>">
                            <input type="submit" name="delete"  value="delete">
                        </form></td>
                    </tr>
                    <?php $j++; } ?>
                </tbody>
            </table>
                        <p id="pmedium">The ID on your custom message should match the ID on your cart item.</p>
        <?php }else{ ?> <p id="pmedium">There are no addons to display. </p> <?php } ?>
        <!--end of displaying addons -->

        <?php }else{ ?> <p id="pmedium">Add a coconut to your cart before adding custom messages! </p> <?php } ?>
        
        <center><h3><br><br><u><a href="cart.php" id="white">Your Cart</a></u></h3></center>
        <center><h3><br><u><a href="browse.php" id="white">Back to Browse</a></u><br><br></h3></center>
        </div>
    <!--Right Side Bar-->
    <?php include('/home4/mbluestein88/HandPaintedCoconuts.com/Includes/right.php'); ?>
  </center>
</section>

<!-- Including footer file -->
<?php include('Includes/foot.php'); ?>

</body>
</html>