<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-history"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-button">
      <a href="<?php echo base_url('payment/transaction_log_manual'); ?>" class="btn btn-primary"><i class="fas fa-hand-holding-usd"></i> <?php echo $this->lang->line('Manual Transaction Log'); ?></a> 
    </div>
    <div class="section-header-breadcrumb">
      <?php 
      if($this->session->userdata("user_type")=="Admin") 
      echo '<div class="breadcrumb-item">'.$this->lang->line("Subscription").'</div>';
      else echo '<div class="breadcrumb-item">'.$this->lang->line("Payment").'</div>';
      ?>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <?php 
    $this->load->view('admin/theme/message'); 
    if($this->session->flashdata('xendit_currency_error') != '')
    echo "<div class='alert alert-danger text-center'><i class='fas fa-check-circle'></i> ".$this->session->flashdata('xendit_currency_error')."</div>";
  ?>

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
                    <th>
                        <input class="regular-checkbox" id="datatableSelectAllRows" type="checkbox"/><label for="datatableSelectAllRows"></label>        
                    </th>
                    <th><?php echo $this->lang->line("ID"); ?></th>      
                    <th><?php echo $this->lang->line("Email"); ?></th>      
                    <th><?php echo $this->lang->line("First Name"); ?></th>      
                    <th><?php echo $this->lang->line("Last Name"); ?></th>      
                    <th><?php echo $this->lang->line("Method"); ?></th>
                    <th><?php echo $this->lang->line("Cycle Start"); ?></th>
                    <th><?php echo $this->lang->line("cycle End"); ?></th>
                    <th><?php echo $this->lang->line("Paid at"); ?></th>
                    <th><?php echo $this->lang->line("Amount")." ".$curency_icon; ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                    <th><?php echo $this->lang->line("Total"); ?></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                  </tr>
                </tfoot>
              </table>
            </div>             
          </div>

        </div>
      </div>
    </div>
    
  </div>
</section>


<?php
$drop_menu ='<a href="javascript:;" id="payment_date_range" class="btn btn-primary btn-lg float-right icon-left btn-icon"><i class="fas fa-calendar"></i> '.$this->lang->line("Choose Date").'</a><input type="hidden" id="payment_date_range_val">';
?>

<?php include("application/views/admin/payment/transaction_log_js.php"); ?>
<script src="<?php echo base_url('assets/js/system/transaction_log.js');?>"></script>
