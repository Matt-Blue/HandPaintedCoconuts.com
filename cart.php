<!DOCTYPE html>
<html  lang="en">
<head>
    
    <!--Requiring database connection-->
    <?php 
        include('top.php');
        require_once '/home4/mbluestein88/HandPaintedCoconuts.com/vendor/autoload.php'; 
        //SQL statements for adding objects from database
        $sql = "SELECT * FROM products";
        $display = $db->query($sql);

        //Starting session if necessary and checking to see if they are logged in-
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(!isset($_SESSION['SBUser'])){
            //header('Location: /home4/mbluestein88/HandPaintedCoconuts.com/Admin/login.php');
            ?>
		<script type="text/javascript">
			window.location.href = 'Admin/login.php';
		</script>
	<?php
        } 

        //setting cart variables if cart id is set
        if($cart_id != ''){
            $cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
            $result = mysqli_fetch_assoc($cartQ);
            //items in cart
            $items = json_decode($result['items'],true);
            //items in addons
            $addon_items = json_decode($result['addons'],true);
            $i = 1;
            $subtotal = 0;
            $item_count = 0;
        } 
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

<!--Making a cart that displays all added items-->
<div class="col-md-12">
    <div class="row">
        <h1 class="text-center">Your Shopping Cart</h1><hr>
        <?php if($cart_id == ''): ?>
            <div>
                <p class="text-center" id="pmedium">Your shopping cart is empty!</p>
                <br>
                <center>
                    <!--NO addons-->
                    <a href="browse.php" id="white">Browse Coconuts</a>
                </center>
            </div>
        <?php elseif($cart_id == '0'): ?>
            <div>
                <p class="text-center" id="pmedium">There was an error creating your shopping cart, please try again.</p>
                <br>
                <center>
                    <!--NO addons-->
                    <a href="browse.php" id="white">Browse Coconuts</a>
                </center>
            </div>
        <?php else: ?>
            <!--Displaying Cart-->
            <table class="table table-bordered table-condensed table-striped">
                <thead>
                    <th><p id="pmedium">#</p></th>
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
                        <td id="pbsmall"><?=$i?></td>
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
                        <td id="pbsmall"><?="$".number_format((($product['price']*$item['quantity'])+$subtotal),2);?></td>
                    </tr>
                    <?php 
                        $i++;
                        $item_count += $item['quantity'];
                        $subtotal += ($product['price'] * $item['quantity']);
                    } 
                    //make grand total equal to subtotal plus shipping with tax on top of it
                    $tax = TAXRATE * $subtotal;
                    $tax = number_format($tax,2);
                    $grand_total = $tax + $subtotal;
                    ?>
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

            <!-- Checkout Button -->
            <button type="button" class="btn btn-warning btn-lg pull-right" data-toggle="modal" data-target="#checkoutModal">
                <span class="glyphicon glyphicon-shopping-cart"></span> Checkout
            </button><br>

            <center><h3><br><u><a href="addons.php" id="white">Add a Custom Message</a></u><br></h3></center>
            <center><h3><br><u><a href="browse.php" id="white">Back to Browse</a></u><br><br></h3></center>

            <!-- Modal -->
            <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="checkoutModalLabel"><div id="black">Shipping Address</div></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <form action="checkout.php" method="post" id="payment-form">
                                    <div class="form-row">

                                        <div class="text-center">
                                            <span id="payment-errors"></span>
                                        </div>

                                        <input id="country" name="country" type="hidden" data-stripe="address_country" value="United States"></input>
                                        <input type="hidden" name="tax" value="<?=$tax;?>">
                                        <input type="hidden" name="subtotal" value="<?=$subtotal;?>">
                                        <input type="hidden" name="grand_total" value="<?=$grand_total;?>">
                                        <input type="hidden" name="cart_id" value="<?=$cart_id;?>">
                                        <input type="hidden" name="item_count" value="<?=$item_count;?>">

                                        <div style="display:block;">
                                            <div class="form-group col-md-6">
                                                <label for="full_name">Full Name: </label>
                                                <input class="form-control" id="full_name" name="full_name" type="text"></input>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="email">Email: </label>
                                                <input class="form-control" id="email" name="email" type="email"></input>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="street">Street Address: </label>
                                                <input class="form-control" id="street" name="street" type="text" data-stripe="address_line1"></input>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="street2">Street Address 2: </label>
                                                <input class="form-control" id="street2" name="street2" type="text" data-stripe="address_line2"></input>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="city">City: </label>
                                                <input class="form-control" id="city" name="city" type="text" data-stripe="address_city"></input>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="state">State: </label>
                                                <input class="form-control" id="state" name="state" type="text" data-stripe="address_state"></input>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="zip_code">Zip Code: </label>
                                                <input class="form-control" id="zip_code" name="zip_code" type="text" data-stripe="address_zip"></input>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <div id="pad"><p id="pbsmall">Note: We only deliver to addresses within the <u>United States mainland</u>.</p></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><div id="black">Close</div></button>
                            <button type="submit" class="btn btn-warning" id="next_button"><div id="black"> <p id="white">Next >></p> </div></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function check_address(){
        var data = {
            'full_name' : jQuery('#full_name').val(),
            'email' : jQuery('#email').val(),
            'street' : jQuery('#street').val(),
            'street2' : jQuery('#street2').val(),
            'city' : jQuery('#city').val(),
            'state' : jQuery('#state').val(),
            'zip_code' : jQuery('#zip_code').val(),
            'country' : jQuery('#country').val(),
            }
        jQuery.ajax({
            url : '/Admin/check_address.php',
            method : 'POST',
            data : data,
            //data below refers to the data coming back from check address
            success : function(data){
                if(data != 'passed'){
                    jQuery('#payment-errors').html(data);
                }if(data == 'passed'){
                    jQuery('#payment-errors').html("");
                    window.location.replace("checkout.php");
                }
            },
            error : function(){alert("Something went wrong!")},
        })
    }


</script>

<!-- Including Social Media -->
<!-- Including footer file -->
<?php include('/home4/mbluestein88/HandPaintedCoconuts.com/Includes/foot.php'); ?>
    
</body>
</html>