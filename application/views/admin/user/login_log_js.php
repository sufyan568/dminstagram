<script>
    "use strict";    
    var drop_menu = '<?php echo $drop_menu;?>';
      setTimeout(function(){ 
        $("#mytable_filter").append(drop_menu); 
    }, 2000);  
</script>
<script src="<?php echo base_url('assets/js/system/user_manager_login_log.js');?>"></script>