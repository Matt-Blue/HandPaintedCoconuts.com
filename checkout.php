<?php
    include('top.php');
    require '/vendor/autoload.php';
    
    //get the rest of the post data
    $full_name = ((isset($_POST['full_name']))?htmlentities($_POST['full_name'], ENT_QUOTES, "UTF-8"):'');
        $full_name = trim($full_name);
    $email = ((isset($_POST['email']))?htmlentities($_POST['email'], ENT_QUOTES, "UTF-8"):'');
         $email = trim($email);
    $street = ((isset($_POST['street']))?htmlentities($_POST['street'], ENT_QUOTES, "UTF-8"):'');
        $street = trim($street);
    $street2 = ((isset($_POST['street2']))?htmlentities($_POST['street2'], ENT_QUOTES, "UTF-8"):'');
        $street2 = trim($street2);
    $city = ((isset($_POST['city']))?htmlentities($_POST['city'], ENT_QUOTES, "UTF-8"):'');
        $city = trim($city);
    $state = ((isset($_POST['state']))?htmlentities($_POST['state'], ENT_QUOTES, "UTF-8"):'');
        $state = trim($state);
    $zip_code = ((isset($_POST['zip_code']))?htmlentities($_POST['zip_code'], ENT_QUOTES, "UTF-8"):'');
        $zip_code = trim($zip_code);
    $country = ((isset($_POST['country']))?htmlentities($_POST['country'], ENT_QUOTES, "UTF-8"):'');
        $country = trim($country);
    $tax = ((isset($_POST['tax']))?htmlentities($_POST['tax'], ENT_QUOTES, "UTF-8"):'');
        $tax = trim($tax);
    $subtotal = ((isset($_POST['subtotal']))?htmlentities($_POST['subtotal'], ENT_QUOTES, "UTF-8"):'');
        $subtotal = trim($subtotal);
    $grand_total = ((isset($_POST['grand_total']))?htmlentities($_POST['grand_total'], ENT_QUOTES, "UTF-8"):'');
        $grand_total = trim($grand_total);
    $cart_id = ((isset($_POST['cart_id']))?htmlentities($_POST['cart_id'], ENT_QUOTES, "UTF-8"):'');
        $cart_id = trim($cart_id);
    $item_count = ((isset($_POST['item_count']))?htmlentities($_POST['item_count'], ENT_QUOTES, "UTF-8"):'');
        $item_count = trim($item_count);
    $tax = 0;
    $shipping=0;

//error spotting
$errors = array();
$required = array(
    'full_name' => 'Full Name',
    'email'     => 'Email',
    'street'    => 'Street Address',
    'city'      => 'City',
    'state'     => 'State',
    'zip_code'  => 'Zip Code',
    'country'   => 'Country',
);
//check for all required fields
foreach($required as $f => $d){
    if(empty($_POST[$f]) || $_POST[$f]==''){
        $errors[] = $d.' is required.';
    }
}
//check for valid email
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors[] = 'Please enter a valid email.';
}

if(!empty($errors)){
    //making space for navbar
    echo('
            <section class="container-fluid">  
                <center><header class="col-md-12"><p></p></header></center>
                <h1><br></h1>
            </section>
        ');

    foreach($errors as $error){
        echo('<center><p id="pmedium">'.$error .'</p></center><br>');
    }

    echo('
        <center><a href="cart.php"><p id="pmedium"><u>Back</u></p></a></center>
    ');
}else{
    try{
        $to_address = \EasyPost\Address::create(
            array(
                "name"    => $full_name,
                "street1" => $street,
                "street2" => $street2,
                "city"    => $city,
                "state"   => $state,
                "zip"     => $zip_code
            )
        );
        $from_address = \EasyPost\Address::create(
            array(
                "company" => "Hand Painted Coconuts",
                "street1" => "4808 SW 24th PLace",
                "city"    => "Cape Coral",
                "state"   => "FL",
                "zip"     => "33914"
            )
        );
        $parcel = \EasyPost\Parcel::create(
            array(
                "predefined_package" => "MediumFlatRateBox",
                "weight" => 20
            )
        );
        $shipment = \EasyPost\Shipment::create(
            array(
                "to_address"   => $to_address,
                "from_address" => $from_address,
                "parcel"       => $parcel
            )
        );
        try{
            $ship = $shipment->lowest_rate();
            $shipping = $ship["list_rate"];
            //multiply cost per shipment times the amount of items being bought
            $shipping = $shipping * $item_count;
        }catch (\EasyPost\Error $e) {
            echo $e->ecode;
        }
    }catch (\EasyPost\Error $e) {
        echo $e->ecode;
    }

    //setting cart variables if cart id is set
    if($cart_id != ''){
        $cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
        $result = mysqli_fetch_assoc($cartQ);
        $items = json_decode($result['items'],true);
    } 

    //making the grand total into cents for processing
    $tax = TAXRATE * ($subtotal + $shipping);
    $tax = number_format($tax,2);
    $grand_total = $subtotal + $shipping + $tax;
    $charge_amount = (int)($grand_total * 100);

    //Inserting logic so that if the shipping address is incorrect then it tells the customer to go back and redo the shipping address
    if($shipping != 0){
?>

<!--Making space between top and background image so the navbar doesn't cover the image-->
<section class="container-fluid">  
    <center><header class="col-md-12"><p></p></header></center>
    <h1><br></h1>
</section>

<center>
<h3>Your order will be shipped to the address below. <br>
    Please confirm your shipping information before proceeding.<br></h3><br>
<p>_____________________________________________________________________________________________________________________________________________</p>
<p id="pmedium">
    <?=$full_name;?><br>
    <?=$street;?><br>
    <?=(($street2 != '')?$street2.'<br>':'');?>
    <?=$city." ".$state.", ".$zip_code;?><br>
    <?=$country;?><br></p>
<p>_____________________________________________________________________________________________________________________________________________</p>
</center>

<!--Adding second table to display subtotal, shipping, tax and grand total-->
<table class="table table-bordered table-condensed table-striped">
    <legend><center><h4><br>Totals</h4></center></legend>
    <thead>
        <th><p id="pmedium">Total Items</p></th>
        <th><p id="pmedium">Subtotal</p></th>
        <th><p id="pmedium">Shipping</p></th>
        <th><p id="pmedium">Tax</p></th>
        <th><p id="pmedium">Grand Total</p></th>
    </thead>
    <tbody>
        <tr id="whiteback">
            <td id="pbsmall"><?=$item_count;?></td>
            <td id="pbsmall"><?="$".number_format($subtotal,2);?></td>
            <td id="pbsmall"><?="$".number_format($shipping,2);?></td>            
            <td id="pbsmall"><?="$".number_format($tax,2);?></td>
            <td id="pbsmall"><?="$".number_format($grand_total,2);?></td>
        </tr>
    </tbody>
</table>

<center><br>
<!--Button to pay after all the metadata is set-->
<form action="charge.php" method="post">
    <input type="hidden" name="full_name" value="<?=$full_name;?>">
    <input type="hidden" name="email" value="<?=$email;?>">
    <input type="hidden" name="street" value="<?=$street;?>">
    <input type="hidden" name="street2" value="<?=$street2;?>">
    <input type="hidden" name="city" value="<?=$city;?>">
    <input type="hidden" name="state" value="<?=$state;?>">
    <input type="hidden" name="zip_code" value="<?=$zip_code;?>">
    <input type="hidden" name="country" value="<?=$country;?>">
    <input type="hidden" name="subtotal" value="<?=$subtotal;?>">
    <input type="hidden" name="shipping" value="<?=$shipping;?>">
    <input type="hidden" name="tax" value="<?=$tax;?>">
    <input type="hidden" name="grand_total" value="<?=$grand_total;?>">
    <input type="hidden" name="cart_id" value="<?=$cart_id;?>">
    <input type="hidden" name="item_count" value="<?=$item_count;?>">
    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key="<?php echo $stripe['publishable_key']; ?>"
          data-description="Checkout"
          data-amount="<?=$charge_amount?>"
          data-locale="auto">
    </script>
</form>

<a href="cart.php"><p id="pmedium"><u>Back</u><br></p></a><br>
</center>

<!--What happens if the address does not work through easypost-->
<?php }else{ ?>
    <!--Making space between top and background image so the navbar doesn't cover the image-->
    <section class="container-fluid">  
        <center><header class="col-md-12"><p></p></header></center>
        <h1><br></h1>
    </section>

    <center>
    <h3>The address you have submitted is invalid. <br>
        Please go back and review your address.<br></h3><br>
    <p>_____________________________________________________________________________________________________________________________________________</p>
    <p id="pmedium">
        <?=$full_name;?><br>
        <?=$street;?><br>
        <?=(($street2 != '')?$street2.'<br>':'');?>
        <?=$city." ".$state.", ".$zip_code;?><br>
        <?=$country;?><br></p>
    <p>_____________________________________________________________________________________________________________________________________________</p>

    <a href="cart.php"><p id="pmedium"><u>Back</u><br></p></a><br>
    </center>

<?php }} ?>

<!-- Including Social Media -->
<?php include('\Includes/social.php'); ?>
<!-- Including footer file -->
<?php include('\Includes/foot.php'); ?>