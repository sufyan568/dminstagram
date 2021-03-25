<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-shopping-bag"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-button">
     <a class="btn btn-primary"  href="<?php echo site_url('payment/add_package');?>">
        <i class="fas fa-plus-circle"></i> <?php echo $this->lang->line("New Package"); ?>
     </a> 
    </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $this->lang->line("Subscription"); ?></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <?php $this->load->view('admin/theme/message'); ?>

  <div class="section-body">

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body data-card">
            <div class="table-responsive2">
              <table class="table table-bordered" id="mytable">
                <thead>
                  <tr>
                    <th>#</th>      
                    <th><?php echo $this->lang->line("Package ID"); ?></th>      
                    <th><?php echo $this->lang->line("Package Name"); ?></th>
                    <th><?php echo $this->lang->line("Price"); ?> - <?php echo isset($payment_config[0]['currency']) ? $payment_config[0]['currency'] : 'USD'; ?></th>
                    <th><?php echo $this->lang->line("Validity"); ?> - <?php echo $this->lang->line("days"); ?></th>
                    <th><?php echo $this->lang->line("Default Package"); ?></th>
                    <th class="min_width_150px"><?php echo $this->lang->line("Actions"); ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>             
          </div>

        </div>
      </div>
    </div>
    
  </div>
</section>


<?php $csrf_token=$this->session->userdata('csrf_token_session'); ?>
<script>
	"use strict";
    var csrf_token="<?php echo $csrf_token; ?>";
 </script>
<script src="<?php echo base_url('assets/js/system/package_manager.js');?>"></script>

