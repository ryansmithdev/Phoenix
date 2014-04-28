<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $.get("http://localhost2/index.php",function(data){
      document.write(data);
    });
  });
</script>