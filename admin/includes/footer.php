</div>
<footer class="text-center" id="footer">&copy; Copyright 2019-20~~ SK's Boutique </footer>
<script>
    function updateSizes() {
        var sizeString = '';
        for(var i=1; i<=12; i++){
            if (jQuery('#size' + i).val() != ''){
                sizeString += jQuery('#size' +i).val()+ ':' + jQuery('#qty'+i).val()+ ', ';
            }
        }
       jQuery('#sizes').val(sizeString);
    }

    function get_child_options(selected){
          if(typeof selected === 'undefined'){
              var selected = '';
          }
          var parentID = jQuery('#parent').val();
          jQuery.ajax({
              url: '/boutique/admin/parsers/child_categories.php',
              type: 'POST',
              data : {parentID: parentID, selected:selected},
              success: function (data) {
                  jQuery('#child').html(data);
              },
              error: function () {
                  alert("Alt kategori seçiminde problem var.")},
              });
        }
    jQuery('select[name="parent"]').change(function () {
        get_child_options();
    });



</script>
</body>
</html>