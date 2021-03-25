<script type="text/javascript">
  "use strict";
  var all_links_JS = [<?php echo '"'.implode('","', $all_links).'"' ?>]; // all urls includes database & custom urls
  var custom_links_JS= [<?php echo '"'.implode('","', array_keys($custom_links)).'"' ?>]; // only custom urls
  var custom_links_assoc_JS = <?php echo $custom_links_assoc_str?>; // custom urls associative array link -> parent  
</script>

<script src="<?php echo base_url('assets/js/system/sidebar.js');?>"></script>