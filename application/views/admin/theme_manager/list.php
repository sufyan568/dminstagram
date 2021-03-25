<?php $is_demo=$this->is_demo;?>
<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-plug"></i> <?php echo $page_title; ?></h1>    
    <div class="section-header-button">
      <a class="btn btn-primary" href="<?php echo base_url('themes/upload');?>"><i class="fas fa-cloud-upload-alt"></i> <?php echo $this->lang->line('Install Theme');?></a>
    </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $this->lang->line("System"); ?></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <?php $this->load->view('admin/theme/message'); ?>
  <?php if($this->session->flashdata('theme_upload_success')!="") echo "<div class='alert alert-success text-center'><i class='fa fa-check'></i> ".$this->session->flashdata('theme_upload_success')."</div>";?>

   <div class="section-body">
      <?php 
      if(!empty($theme_list))
      {       
        $i=0;
        echo "<div class='row'>";
        foreach($theme_list as $value)
        {
          $i++;
          ?>
          <div class="col-12 col-sm-6 col-md-4">
            <?php 
              $asset_path=$value['thumb']; 
              $base64file = xit_theme_thumbs($asset_path);
              if($base64file=="") $thumb = base_url('assets/img/example-image.jpg');
              else $thumb = $base64file;

            ?>

            <div class="card">
              <div class="card-header">
                <h4>
                  <?php 
                    if($value['folder_name'] == $this->config->item('current_theme')) 
                      echo "<i class='fas fa-check-circle blue' title='".$this->lang->line('active')."'></i> "; 
                    echo $value['theme_name'];
                  ?>
                </h4>
              </div>
              <div class="card-body">
                <div class="chocolat-parent">
                  <a href="<?php echo $thumb; ?>" class="chocolat-image" title="<?php echo $value['theme_name'];?>">
                    <div data-crop-image="275">
                      <img alt="image" src="<?php echo $thumb; ?>" class="img-fluid">
                    </div>
                  </a>
                </div>
                <div class="mb-2  mt-4 text-muted"><?php echo $value['description']; ?></div>
              </div>
              <div class="card-footer text-center">
                <?php if($value['folder_name'] != $this->config->item('current_theme')): ?>
                  <a title="<?php echo $this->lang->line("activate"); ?>" class="btn btn-outline-primary activate_action" data-i='<?php echo $i; ?>' href="" data-unique-name="<?php echo $value['folder_name'];?>"><i class="fa fa-check"></i> <?php echo $this->lang->line('activate');?></a>

                <?php else: ?>
                  <a title="<?php echo $this->lang->line("deactivate"); ?>" class="<?php if($this->is_demo=='1' || count($theme_list)<=1) echo 'disabled'; ?> btn btn-outline-dark deactivate_action" href="" data-i='<?php echo $i; ?>' data-unique-name="<?php echo $value['folder_name'];?>"><i class="fa fa-ban"></i> <?php echo $this->lang->line('deactivate');?></a>
                <?php endif; ?>
                <?php if($value['folder_name'] != 'default'): ?>
                <a title="<?php echo $this->lang->line("delete"); ?>" class="<?php if($this->is_demo=='1') echo 'disabled'; ?> btn btn-outline-danger delete_action" href="" data-i='<?php echo $i; ?>' data-unique-name="<?php echo $value['folder_name'];?>"><i class="fa fa-trash"></i> <?php echo $this->lang->line('delete');?></a>
                <?php endif; ?>
              </div>
            </div>
            
          </div>     

          <?php 
        }
        echo "</div>";
      }
      else
      { ?>
        <div class="card">
            <div class="card-header">
              <h4><i class="fas fa-question"></i> <?php echo $this->lang->line("No Theme uploaded"); ?></h4>
            </div>
            <div class="card-body">
              <div class="empty-state height_400px" data-height="400">
                <div class="empty-state-icon">
                  <i class="fas fa-question"></i>
                </div>
                <h2><?php echo $this->lang->line("System could not find any Theme."); ?></h2>
                <p class="lead">
                  <?php echo $this->lang->line("No Theme found. Your Theme will display here once uploaded."); ?>
                  
                </p>
                <a class="btn btn-primary" href="<?php echo base_url('themes/upload');?>"><i class="fas fa-cloud-upload-alt"></i> <?php echo $this->lang->line('Upload Theme');?></a>
              </div>
            </div>
          </div>

        <?php
      }
      ?>   
   </div>
</section>



<script src="<?php echo base_url('assets/js/system/theme_manager.js');?>"></script>