<!-- footer -->
<section class="container-fluid">
    <center>
        <footer class="col-md-12">
          Hand Painted Coconuts<br>
          <div id="smaller">Made From Scratch by Matthew Bluestein</div>
          <br>
        </footer>
    </center>
</section>
    
<script>
    //ajax request to detailsModal.php to dynamically load modal
    function detailsmodal(id){
        var data = {"id" : id};
        jQuery.ajax({
            url : '/Includes/detailsModal.php',
            method : "post",
            data : data,
            success : function(data){
                //adds information to just above the closing body tag
                jQuery('body').append(data);
                //select and toggle modal
                jQuery('#modal1').modal('toggle');
            },
            error : function(){alert("Something went wrong!");},
        });
    }

    //unknown use
    function show_update(mode, edit_id){
        alert(mode);
        alert(edit_id);
    }

    //ajax request to update_cart.php
    function update_cart(mode, edit_id){
        var data = {"mode" : mode, "edit_id" : edit_id};
        jQuery.ajax({
            url : '/Admin/update_cart.php',
            method : "post",
            data : data,
            success : function(){location.reload();},
            error : function(){alert("Something went wrong!");},
        })
    }

    function add_to_cart(quantity, available){
        //resets error display
        jQuery('#modal_errors').html("");
        //get variables from the id tags 
        var quantity = jQuery('#quantity').val();
        var available = jQuery('#available').val();
        var error = '';
        //takes values of form and serializes them into parameters to pass along
        var data = jQuery('#add_product_form').serialize();
        //checks to make quantity is not 0 or null
        if(quantity=='' || quantity==0){
            error += '<p class="text-danger text-center">You must choose a quantity.</p>';
            jQuery('#modal_errors').html(error);
            return;
        }else if(quantity > available){//checks if quantity is more than available
            error += '<p class="text-danger text-center">There is/are only '+available+' item(s) available.';
            jQuery('#modal_errors').html(error);
            return;
        }else{//ajax request to add_cart.php
            jQuery.ajax({
                url : '/Admin/add_cart.php',
                method : 'post',
                data : data,
                success : function(){
                    window.location.replace("cart.php");
                },
                error : function(){alert("Something went wrong!");}
            })
        }
    }

</script>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>