</div>
<br>
<br>
<br>
<footer class="text-center" id="footer">&copy; Copyright 2019-20~~ SK's Boutique </footer>

<!--Details Button -->


<script>


    function detailsmodal(id) {
       var data = {"id": id};
       jQuery.ajax({
           url: '/boutique/includes/detailsmodal.php',
           method:"post",
           data: data,
           success: function (data) {
               jQuery('body').append(data);
               jQuery('#details-modal').modal('toggle');
           },
           error:function () {
               alert("Something went wrong!");
           }

       });
    }
    
    function add_to_cart(){
    jQuery('#modal_errors').html("");
    var size = jQuery('#size').val();
    var quantity = jQuery('#quantity').val();
    var available = jQuery('#available').val();
    var error = '';
    var data = jQuery('#add_product_form').serialize();
    if (size == ''|| quantity == '' || quantity == 0 ) {
        error += '<p class="text-danger text-center ">Beden ve adet seçmelisiniz. </p>';
        jQuery('#modal_errors').html(error);
        return;

    }else if(quantity > available){
        error += '<p class="text-danger text-center">Sadece '+available+' adet seçebilirsiniz !</p>'
        jQuery('#modal_errors').html(error);
        return;
    }else{
        jQuery.ajax({
            url: '/boutique/admin/parsers/add_cart.php',
            method: 'post',
            data : data,
            success: function () {
                location.reload();
            },
            error : function () {
                alert("Birşeyler ters gidiyiii");
            }
        });
    }


    }
</script>
</body>
</html>