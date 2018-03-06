<?php include('top.php');
    //SQL statements for adding objects from database
    $sql = "SELECT * FROM products";
    $display = $db->query($sql);
?></head>
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
    <div class="row">
    <div class="col-md-8">
        <!--While loop that goes through all objects and displays them-->
        <?php $counter = 0; //counter to make sure after three items are displayed another full row is inserted to make sure indentation is correct
        while($product = mysqli_fetch_assoc($display)): ?>
            <!--Don't display if there aren't any available-->
            <?php if($product['available'] != 0){ ?>
            <!--One object-->
            <div class="row row-eq-height">
            <div class="col-md-4">
                <h2><i><?= $product["title"]; ?></i></h2>
                <img src=<?= $product['front']; ?> alt="<?= $product["title"]; ?>" class="img-thumb" id="product">
                <br><br>
                <!-- Trigger the modal with a button -->
                <button type="button" class="btn btn-success btn-lg" onclick="detailsmodal(<?= $product['id']; ?>)"> Details </button>
                <br>
            </div>
            <?php
                $counter += 1;
                if($counter == 3){
                    echo('<center><div class="col-md-12"><p><br> <br></p></div></center>');
                    $counter = 0;
                }
            }
            ?>
        <?php endwhile; ?>
    </div>
    </div>
    </div>
    </div>

    <!--Right Side Bar-->
    <?php include('/home4/mbluestein88/HandPaintedCoconuts.com/Includes/right.php'); ?>
  </center>
</section>

<!-- Including Social Media -->
<!-- Including footer file -->
<?php include('/home4/mbluestein88/HandPaintedCoconuts.com/Includes/foot.php'); ?>
<!-- Including Details Modal -->
<?php include('/home4/mbluestein88/HandPaintedCoconuts.com/Includes/detailsModal.php'); ?>

</body>
</html>