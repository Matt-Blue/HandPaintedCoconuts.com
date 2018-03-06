<?php
  error_reporting(0);
  require_once '../Core/easyinit.php';
  setcookie("location","top of details modal");
  //getting ID
  $id = $_POST['id'];
  $id = (int)$id;
  //Creating SQL statement
  $sql = "SELECT * FROM products WHERE id = '$id'";
  //Executing query
  $result = $db->query($sql);
  //getting product
  $product = mysqli_fetch_assoc($result);
?>

<!--Start of buffer for sending modal-->
<?php ob_start(); ?>

<!-- Modal -->
<div id="modal1" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <!--Modal Header-->
      <div class="modal-header">
        <button type="button" class="close" onclick="closeModal()">&times;</button>
        <i><h4 class="modal-title" id="black"> <?= $product['title']; ?> </h4></i>
      </div>

      <!--Modal Image-->
      <div class="col-md-6">
        <img src=<?= $product['front']; ?> alt="product img" class="img-thumb">
        <img src=<?= $product['back']; ?> alt="<?= $product["title"]; ?>" class="img-thumb">
      </div>

      <!--Modal Body-->
      <div class="col-md-6">
        <div class="modal-body">
          <span id="modal_errors" class="bg-danger"></span>
          <p id="black">
            <?= $product['description']; ?>
          </p>
          <br>
          <p id="black">Cost: $<?= $product['price']; ?></p>
          <p id="black"> Quantity Available: <?= $product['available']; ?></p>
        </div>

        <form action="add_cart.php" method="POST" id="add_product_form"> 
          <input type="hidden" name="product_id" value="<?=$id?>">
          <input type="hidden" name="available" id="available" value=<?= $product['available']; ?>>
          <input type="hidden" name="quantity" id="quantity" type="number" value="1">
        </form>
      </div>

      <!--Modal Footer-->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="closeModal()">Close</button>
        <button class="btn btn-warning" 
          <?php if(!isset($_SESSION['SBUser'])){ ?>
            onClick="document.location.href='/Admin/login.php'"
          <?php }else{ ?>
          onclick="add_to_cart();return false;"
          <?php } ?>
        ><span class="glyphicon glyphicon-shopping-cart"></span>Add to Cart</button>
      </div>

    </div>  

  </div>
</div>
<!--Script to close modal-->
<script>
  function closeModal(){
    jQuery('#modal1').modal('hide');
    setTimeout(function() {
      jQuery('#modal1').remove();
      jQuery('.modal-backdrop').remove();
    }, 5);
    location.reload();
  }
</script>

<!-- end of buffer for sending modal -->
<?php echo ob_get_clean(); ?>