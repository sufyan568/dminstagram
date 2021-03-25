<?php if(!isset($page_title)) $page_title = $this->lang->line("Demo Restriction"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?php echo $this->config->item('product_name')." | ".$page_title;?></title>
  <link rel="shortcut icon" href="<?php echo base_url();?>assets/img/favicon.png"> 
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome/css/v4-shims.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css">
</head>

<body class="bg_ffffff">
  <div class="card box_shadow_none">
      <div class="card-body">
        <div class="empty-state height_400px" data-height="400">
          <div class="empty-state-icon bg-danger">
            <i class="fas fa-times"></i>
          </div>
          <h2><?php echo $this->lang->line("Demo Restriction"); ?></h2>
          <p class="lead">
            <?php echo $this->lang->line("This feature is disabled in this demo."); ?>
          </p>
          <a class="btn btn-outline-primary mt-4" href="<?php echo base_url('dashboard'); ?>"><i class="fas fa-arrow-circle-left"></i> <?php echo $this->lang->line("Go back to home"); ?></a>
        </div>
      </div>
    </div>
</body>
</html>
