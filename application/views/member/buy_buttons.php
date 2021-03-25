<link rel="stylesheet" href="<?php echo base_url('assets/css/system/buy_buttons.css');?>">


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-cart-plus"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-button">
      <a href="<?php echo base_url('payment/transaction_log'); ?>" class="btn btn-primary"><i class="fas fa-history"></i> <?php echo $this->lang->line("Transaction Log"); ?></a>
    </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="<?php echo base_url('payment/buy_package'); ?>"><?php echo $this->lang->line("Payment"); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></a></div>
    </div>
  </div>

  <div class="section-body">

    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-cart-plus"></i> <?php echo $this->lang->line("Payment Options");?></h4>
        </div>
        <div class="card-body">
            <div id="payment_options"><?php echo $buttons_html; ?></div>
            <br>
            <?php 
            if ($last_payment_method != '')
            { 
              
              $payment_type = ($has_reccuring == 'true') ? $this->lang->line('Recurring') : $this->lang->line('Manual');

              echo '<br><div class="alert alert-light alert-has-icon">
                      <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                      <div class="alert-body">
                        <div class="alert-title">'.$this->lang->line("Last Payment").'</div>
                        '.$this->lang->line("Last Payment").' : '.$last_payment_method.' ('.$payment_type.')
                      </div>
                    </div>';
            }?>
        </div>
        <div class="card-footer">
            <?php if ('yes' == $manual_payment): ?>
              <button type="button" id="manual-payment-button" class="btn btn-outline-warning btn-lg"><?php echo $this->lang->line('Manual Payment'); ?></button>      
            <?php endif; ?>
        </div>
    </div>

  </div>
</section>



<?php if ('yes' == $manual_payment): ?>
<div class="modal fade" role="dialog" id="manual-payment-modal" data-backdrop="static" data-keyboard="false">
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

          <?php if (isset($manual_payment_instruction) && ! empty($manual_payment_instruction)): ?>
          <div class="row">
            <div class="col-lg-12 mb-4">
              <!-- Manual payment instruction -->
              <h6  class="display-6"><i class="far fa-lightbulb"></i> <?php echo $this->lang->line('Manual payment instructions'); ?></h6>
                  <?php echo $manual_payment_instruction; ?>
            </div>
          </div>
          <?php endif; ?>

          <!-- Paid amount and currency -->
          <div class="row">
            <div class="col-lg-6 mb-4">
              <div class="form-group">
                <label for="paid-amount"><i class="fa fa-money-bill-alt"></i> <?php echo $this->lang->line('Paid Amount'); ?>:</label>
                <input type="number" name="paid-amount" id="paid-amount" class="form-control" min="1">
                <input type="hidden" id="selected-package-id" value="<?php echo $package_id; ?>">
              </div>
            </div>
            <div class="col-lg-6 mb-4">
              <div class="form-group">
                <label for="paid-currency"><i class="fa fa-coins"></i> <?php echo $this->lang->line('Currency'); ?></label>              
                <?php echo form_dropdown('paid-currency', $currency_list, $currency, ['id' => 'paid-currency', 'class' => 'form-control select2 w-100','style'=>'width:100%;']); ?>
              </div>
            </div>
          </div>          
          
          <div class="row">
            <!-- Image upload - Dropzone -->
            <div class="col-lg-6">
              <div class="form-group">
                <label><i class="fa fa-paperclip"></i> <?php echo $this->lang->line('Attachment'); ?> <?php echo $this->lang->line('(Max 5MB)');?> </label>
                <div id="manual-payment-dropzone" class="dropzone mb-1">
                  <div class="dz-default dz-message">
                    <input class="form-control" name="uploaded-file" id="uploaded-file" type="hidden">
                    <span class="font_size_20px"><i class="fas fa-cloud-upload-alt c6777ef_color font_size_35px"></i> <?php echo $this->lang->line('Upload'); ?></span>
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
            </div>  
          </div>

        </div><!-- ends container -->
      </div><!-- ends modal-body -->

      <!-- Modal footer -->
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" id="manual-payment-submit" class="btn btn-primary"><?php echo $this->lang->line('Submit'); ?></button>      
        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fa fa-remove"></i> <?php echo $this->lang->line("Close"); ?></button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

 <script src="<?php echo base_url('assets/js/system/buy_buttons.js');?>"></script>