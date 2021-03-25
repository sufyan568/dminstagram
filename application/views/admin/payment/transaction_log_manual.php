<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-hand-holding-usd"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-button">
      <?php if ('Member' == $this->session->userdata('user_type')): ?>
      <a class="btn btn-primary" href="<?php echo base_url('payment/buy_package'); ?>"><i class="fa fa-cart-plus"></i> <?php echo $this->lang->line('Renew Package'); ?></a>
      <?php endif; ?>
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
                    <th><?php echo $this->lang->line("Name"); ?></th>      
                    <th><?php echo $this->lang->line("Email"); ?></th>      
                    <th><?php echo $this->lang->line("Additional Info"); ?></th>
                    <th><?php echo $this->lang->line("Attachment"); ?></th>
                    <th><?php echo $this->lang->line("Status"); ?></th>
                    <th><?php echo $this->lang->line("Actions"); ?></th>
                    <th><?php echo $this->lang->line("Paid At"); ?></th>
                    <th><?php echo $this->lang->line("Paid Amount"); ?></th>      
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                    <th><?php echo $this->lang->line("Total"); ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
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

<?php if ('Admin' == $this->session->userdata('user_type')): ?>
  <div class="modal fade" tabindex="-1" role="dialog" id="manual-payment-reject-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-times-circle"></i> <?php echo $this->lang->line("Manual payment rejection");?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container">            
            <div class="row">
              <!-- Additional Info -->
              <div class="col-lg-12">
                <div class="form-group">
                  <label for="paid-amount"><?php echo $this->lang->line('Describe, why do you want to reject this payment?'); ?></label>
                  &nbsp;
                  <textarea name="rejected-reason" id="rejected-reason" class="form-control"></textarea>
                  <input type="hidden" id="mp-transaction-id">
                  <input type="hidden" id="mp-action-type">
                </div>
              </div>  
            </div>

          </div><!-- ends container -->
        </div><!-- ends modal-body -->

        <!-- Modal footer -->
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" id="manual-payment-reject-submit" class="btn btn-primary"><?php echo $this->lang->line('Submit'); ?></button>      
          <button type="button" class="btn btn-secondary btn-md" data-dismiss="modal"><i class="fa fa-remove"></i> <?php echo $this->lang->line("Close"); ?></button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<div class="modal fade" tabindex="-1" role="dialog" id="manual-payment-modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-file-invoice-dollar"></i> <?php echo $this->lang->line("Manual payment");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          
          <!-- Manual payment instruction -->
          <div id="manual-payment-instructions" class="row d-none">
            <div class="col-lg-12 mb-4">
              <div class="alert alert-light alert-has-icon">
                <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                <div class="alert-body">
                  <div class="alert-title"><?php echo $this->lang->line('Manual payment instructions'); ?></div>
                  <p id="payment-instructions"></p>
                </div>
              </div>
            </div>
          </div>

          <!-- Paid amount and currency -->
          <div class="row">
            <div class="col-lg-6 mb-4">
              <div class="form-group">
                <label for="paid-amount"><i class="fa fa-money-bill-alt"></i> <?php echo $this->lang->line('Paid Amount'); ?>:</label>
                <input type="number" name="paid-amount" id="paid-amount" class="form-control" min="1">
              </div>
            </div>
            <div class="col-lg-6 mb-4">
              <div class="form-group">
                <label for="paid-currency"><i class="fa fa-coins"></i> <?php echo $this->lang->line('Currency'); ?></label>              
                <?php echo form_dropdown('paid-currency', $currency_list, [], ['id' => 'paid-currency', 'class' => 'form-control']); ?>
              </div>
            </div>
          </div>          
          
          <!-- Image upload - Dropzone -->
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label><i class="fa fa-paperclip"></i> <?php echo $this->lang->line('Attachment'); ?> <?php echo $this->lang->line('(Max 5MB)');?> </label>
                <div id="manual-payment-dropzone" class="dropzone mb-1">
                  <div class="dz-default dz-message">
                    <input class="form-control" name="uploaded-file" id="uploaded-file" type="hidden">
                    <span class="font_size_20px"><i class="fas fa-cloud-upload-alt font_size_35px c6777ef_color"></i> <?php echo $this->lang->line('Upload'); ?></span>
                  </div>
                </div>
                <span class="red">Allowed types: pdf, doc, txt, png, jpg and zip</span>
              </div>
            </div>

            <!-- Additional Info -->
            <div class="col-lg-6">
              <div class="form-group">
                <label for="paid-amount"><i class="fa fa-info-circle"></i> <?php echo $this->lang->line('Additional Info'); ?>:</label>
                &nbsp;
                <textarea name="additional-info" id="additional-info" class="form-control"></textarea>
              </div>
              <input type="hidden" id="selected-package-id">
              <input type="hidden" id="mp-resubmitted-id">
            </div>  
          </div>

        </div><!-- ends container -->
      </div><!-- ends modal-body -->

      <!-- Modal footer -->
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" id="manual-payment-submit" class="btn btn-primary"><?php echo $this->lang->line('Submit'); ?></button>      
        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fa fa-remove"></i> <?php echo $this->lang->line("Close"); ?></button>
      </div>
      <div id="mp-spinner" class="justify-content-center align-items-center d-flex"><i class="fa fa-spinner fa-spin fa-3x text-primary"></i></div><!-- spinner -->
    </div>
  </div>
</div>

<?php include("application/views/admin/payment/transaction_log_manual_js.php"); ?>
<script src="<?php echo base_url('assets/js/system/transaction_log_manual.js');?>"></script>
<?php if ('Admin' == $this->session->userdata('user_type')): ?> 
<script src="<?php echo base_url('assets/js/system/transaction_log_manual_admin.js');?>"></script>
<?php endif; ?>