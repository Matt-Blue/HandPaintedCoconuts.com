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
    $subtotal = ((isset($_POST['subtotal']))?htmlentities($_POST['subtotal'], ENT_QUOTES, "UTF-8"):'');
        $subtotal = trim($subtotal);
    $shipping = ((isset($_POST['shipping']))?htmlentities($_POST['shipping'], ENT_QUOTES, "UTF-8"):'');
        $shipping = trim($shipping);
    $tax = ((isset($_POST['tax']))?htmlentities($_POST['tax'], ENT_QUOTES, "UTF-8"):'');
        $tax = trim($tax);
    $grand_total = ((isset($_POST['grand_total']))?htmlentities($_POST['grand_total'], ENT_QUOTES, "UTF-8"):'');
        $grand_total = trim($grand_total);
    $cart_id = ((isset($_POST['cart_id']))?htmlentities($_POST['cart_id'], ENT_QUOTES, "UTF-8"):'');
        $cart_id = trim($cart_id);
    $item_count = ((isset($_POST['item_count']))?htmlentities($_POST['item_count'], ENT_QUOTES, "UTF-8"):'');
        $item_count = trim($item_count);

  $token  = $_POST['stripeToken'];
  
  $charge_amount = (int)($grand_total*100);

  $customer = \Stripe\Customer::create(array(
      'email' => $email,
      'source'  => $token
  ));
try{
    //charge card
    $charge = \Stripe\Charge::create(array(
        'customer' => $customer->id,
        'amount'   => $charge_amount,
        'currency' => 'usd',
    ));

    //list of things to put into database
    $db->query("INSERT INTO transactions
    (charge_id,cart_id,full_name,email,street,street2,city,state,zip_code,country,subtotal,shipping,tax,grand_total,item_count,paid) VALUES 
    ('$charge->id','$cart_id','$full_name','$email','$street','$street2','$city','$state','$zip_code','$country','$subtotal','$shipping','$tax','$grand_total','$item_count','1')");
    $db->query("UPDATE cart SET paid = 1 WHERE id = '$cart_id'");

    //adjust inventory
    $itemQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
    $item = mysqli_fetch_assoc($itemQ);
    //true is associative array false is object
    $items = json_decode($item['items'],true);
    foreach($items as $item){
        //set product id and amount to be bought
        $product_id = $item['id'];
        $amount_bought = $item['quantity'];
        //fetches the product from the shelves and sees how much there is left
        $productQ = $db->query("SELECT * FROM products WHERE id = '$product_id'");
        $product = mysqli_fetch_assoc($productQ);
        $in_stock = $product['available'];
        //figures out the new amount to be updated in the database
        $new = $in_stock - $amount_bought;
        //update database
        $db->query("UPDATE products SET available = '$new' WHERE id='$product_id'");
    }

    if($cart_id != ''){
        //if the cart has been paid for reset cookie
        $q = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
        $obj = mysqli_fetch_assoc($q);
        if($obj['paid'] != 0){
            $domain = ($_SERVER['HTTP_HOST'] != 'localhost')? '.'.$_SERVER['HTTP_HOST']:false;
            setcookie(CART_COOKIE,'',1,"/",$domain,false);
        }
    }
?>

<!--Making space between top and background image so the navbar doesn't cover the image-->
<section class="container-fluid">  
    <center><header class="col-md-12"><p></p></header></center>
    <h1><br></h1>
</section>

<center>
<h1>Thank You!</h1>

<!--Thank You display information-->
<table class="table table-bordered table-condensed table-striped">
    <legend><center><h4><br>You may print this page as a receipt</h4></center></legend>
    <thead>
        <th><p id="pmedium">Amount Charged</p></th>
        <th><p id="pmedium">Receipt Number</p></th>
        <th><p id="pmedium">Shipping Address</p></th>
    </thead>
    <tbody>
        <tr id="whiteback">
            <td id="pbsmall"><?="$".number_format($grand_total,2);?></td>
            <td id="pbsmall"><?=$cart_id;?></td>
            <td id="pbsmall">
                <?=$full_name;?><br>
                <?=$street;?><br>
                <?=(($street2 != '')?$street2.'<br>':'');?>
                <?=$city." ".$state.", ".$zip_code;?><br>
                <?=$country;?><br>
            </td>
        </tr>
    </tbody>
</table>

<center><a href="index.php"><p  id="pmedium"><u>Home<br></u></p></a></center><br>

<?php
    }catch(\Stripe\Error\Card $e){
    echo $e;
}
?>

<!-- Including Social Media -->
<?php include('\Includes/social.php'); ?>
<!-- Including footer file -->
<?php include('\Includes/foot.php'); ?>