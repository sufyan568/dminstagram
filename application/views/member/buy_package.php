<link rel="stylesheet" href="<?php echo base_url('assets/css/system/buy_package.css');?>">

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-cart-plus"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-button">
      <a href="<?php echo base_url('payment/transaction_log'); ?>" class="btn btn-primary"><i class="fas fa-history"></i> <?php echo $this->lang->line("Transaction Log"); ?></a>
    </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $this->lang->line("Payment"); ?></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></a></div>
    </div>
  </div>

  <div class="section-body">
    
    <div class="row">
      <?php 
      foreach($payment_package as $pack)
      {?>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="pricing <?php if($pack['highlight']=='1') echo 'pricing-highlight';?>">
            <div class="pricing-title">
              <?php echo $pack["package_name"]; ?>
            </div>
            <div class="pricing-padding">
              <div class="pricing-price">
                <div><?php echo $curency_icon; ?></sup><?php echo $pack["price"]?></div>
                <div><?php echo $pack["validity"]?> <?php echo $this->lang->line("days"); ?></div>
              </div>
              <div class="pricing-details nicescroll height_180px">
                <?php 
                $module_ids=$pack["module_ids"];
                $monthly_limit=json_decode($pack["monthly_limit"],true);
                $module_names_array=$this->basic->execute_query('SELECT module_name,id FROM modules WHERE FIND_IN_SET(id,"'.$module_ids.'") > 0  ORDER BY module_name ASC');

                foreach ($module_names_array as $row)
                {                              
                    $limit=0;
                    $limit=$monthly_limit[$row["id"]];
                    if($limit=="0") $limit2=$this->lang->line("unlimited");
                    else $limit2=$limit;
                    $limit2=" : ".$limit2;
                    echo '
                    <div class="pricing-item">
                      <div class="pricing-item-icon_x bg-light_x"><i class="fas fa-check"></i></div>
                      <div class="pricing-item-label">&nbsp;'.$this->lang->line($row["module_name"]).$limit2.'</div>
                    </div>';
                } ?>
                                
              </div>
            </div>
            <div class="pricing-cta">
              <a href="" class="choose_package" data-id="<?php echo $pack['id'];?>"><?php echo $this->lang->line("Select Package"); ?> <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
      <?php 
      } ?>
    </div>
  </div>
</section>

<div class="modal fade" tabindex="-1" role="dialog" id="payment_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-cart-plus"></i> <?php echo $this->lang->line("Payment Options");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center w-100 mt-4 mb-4 ml-0 mr-0" id="waiting"><i class="fas fa-spinner fa-spin blue font_size_40px"></i></div>
        <div id="button_place"></div>
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
      <div class="modal-footer bg-whitesmoke br">
        <?php if ('yes' == $manual_payment): ?>
          <button type="button" id="manual-payment-button" class="btn btn-outline-warning btn-lg"><?php echo $this->lang->line('Manual Payment'); ?></button>      
        <?php endif; ?>
        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fa fa-remove"></i> <?php echo $this->lang->line("Close"); ?></button>
      </div>
    </div>
  </div>
</div>

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
                <input type="hidden" id="selected-package-id">
              </div>
            </div>
            <div class="col-lg-6 mb-4">
              <div class="form-group">
                <label for="paid-currency"><i class="fa fa-coins"></i> <?php echo $this->lang->line('Currency'); ?></label>              
                <?php echo form_dropdown('paid-currency', $currency_list, $currency, ['id' => 'paid-currency', 'class' => 'form-control select2','style'=>'width:100%;']); ?>
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

<script src="<?php echo base_url('assets/js/system/buy_package.js');?>"></script>