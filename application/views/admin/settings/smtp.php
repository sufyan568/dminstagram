<link rel="stylesheet" href="<?php echo base_url('assets/css/system/smtp_settings.css');?>">
<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-envelope"></i> <?php echo $page_title; ?></h1>
    <?php if($test_btn == 1) { ?>
      <div class="section-header-button">
          <a class="btn btn-primary send_test_mail" href="">
              <i class="fas fa-paper-plane"></i> <?php echo $this->lang->line("Send Test Email"); ?>
          </a> 
      </div>
    <?php } ?>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $this->lang->line("System"); ?></div>
      <div class="breadcrumb-item active"><a href="<?php echo base_url('admin/settings'); ?>"><?php echo $this->lang->line("Settings"); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <?php $this->load->view('admin/theme/message'); ?>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
          <form action="<?php echo base_url("admin/smtp_settings_action"); ?>" method="POST">
            
          <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $this->session->userdata('csrf_token_session'); ?>">

          <div class="card">
            <div class="card-body">              
                <div class="form-group">
                    <label for=""><i class="fa fa-at"></i> <?php echo $this->lang->line("Sender Email Address");?> </label>
                    <input name="email_address" value="<?php echo isset($xvalue['email_address']) ? $xvalue['email_address'] :""; ?>"  class="form-control" type="email">              
                    <span class="red"><?php echo form_error('email_address'); ?></span>
                </div>

                <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for=""><i class="fa fa-server"></i>  <?php echo $this->lang->line("SMTP Host");?></label>
                      <input name="smtp_host" value="<?php echo isset($xvalue['smtp_host']) ? $xvalue['smtp_host'] :""; ?>" class="form-control" type="text">  
                      <span class="red"><?php echo form_error('smtp_host'); ?></span>
                    </div>
                  </div>

                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for=""><i class="fas fa-plug"></i>  <?php echo $this->lang->line("SMTP Port");?></label>
                      <input name="smtp_port" value="<?php echo isset($xvalue['smtp_port']) ? $xvalue['smtp_port'] :""; ?>" class="form-control" type="text">  
                      <span class="red"><?php echo form_error('smtp_port'); ?></span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for=""><i class="fas fa-user-circle"></i>  <?php echo $this->lang->line("SMTP User");?></label>
                      <input name="smtp_user" value="<?php echo isset($xvalue['smtp_user']) ? $xvalue['smtp_user'] :""; ?>" class="form-control" type="text">  
                      <span class="red"><?php echo form_error('smtp_user'); ?></span>
                    </div>
                  </div>

                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for=""><i class="fas fa-key"></i>  <?php echo $this->lang->line("SMTP Password");?></label>
                      <input name="smtp_password" value="<?php echo isset($xvalue['smtp_password']) ? $xvalue['smtp_password'] :""; ?>" class="form-control" type="text">  
                      <span class="red"><?php echo form_error('smtp_password'); ?></span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="smtp_type" ><i class="fa fa-shield-alt"></i> <?php echo $this->lang->line('Connection Type');?>?</label>
                    <?php 
                    $smtp_type =isset($xvalue['smtp_type'])?$xvalue['smtp_type']:"";
                    if($smtp_type == '') $smtp_type='Default';
                    ?>
                    <div class="custom-switches-stacked mt-2">
                      <div class="row">   
                        <div class="col-4 col-md-2">
                          <label class="custom-switch">
                            <input type="radio" name="smtp_type" value="Default" class="custom-switch-input" <?php if($smtp_type=='Default') echo 'checked'; ?>>
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description"><?php echo $this->lang->line('Default'); ?></span>
                          </label>
                        </div>
                        <div class="col-4 col-md-2">
                          <label class="custom-switch">
                            <input type="radio" name="smtp_type" value="tls" class="custom-switch-input" <?php if($smtp_type=='tls') echo 'checked'; ?>>
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">TLS</span>
                          </label>
                        </div>
                        <div class="col-4 col-md-2">
                          <label class="custom-switch">
                            <input type="radio" name="smtp_type" value="ssl" class="custom-switch-input" <?php if($smtp_type=='ssl') echo 'checked'; ?>>
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">SSL</span>
                          </label>
                        </div>
                      </div>                                  
                    </div>
                    <span class="red"><?php echo form_error('smtp_type'); ?></span>
                </div> 
            </div>

            <div class="card-footer bg-whitesmoke">
              <button class="btn btn-primary btn-lg" id="save-btn" type="submit"><i class="fas fa-save"></i> <?php echo $this->lang->line("Save");?></button>
              <button class="btn btn-secondary btn-lg float-right" onclick='goBack("admin/settings")' type="button"><i class="fa fa-remove"></i>  <?php echo $this->lang->line("Cancel");?></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<script src="<?php echo base_url('assets/js/system/smtp_settings.js');?>"></script>

<div class="modal fade" id="modal_send_test_email" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title blue"><i class="fas fa-paper-plane"></i> <?php echo $this->lang->line("Send Test Email");?></h5>
        <button type="button" onclick="javascript:window.location.reload()" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>

      <div id="modalBody" class="modal-body">        
        <div id="show_message" class="text-center"></div>

        <div class="row">
          <div class="col-12 col-md-6">
            <div class="form-group">
              <label for="recipient_email"><i class="fas fa-at"></i> <?php echo $this->lang->line("Recipient Email"); ?></label>
              <input type="text" id="recipient_email" class="form-control"/>
              <div class="invalid-feedback"><?php echo $this->lang->line("Email is required"); ?></div>
            </div>

            
          </div>
          <div class="col-12 col-md-6">
            <div class="form-group">
              <label for="subject"><i class="far fa-lightbulb"></i> <?php echo $this->lang->line("Subject"); ?></label>
              <input type="text" id="subject" class="form-control"/>
              <div class="invalid-feedback"><?php echo $this->lang->line("Subject is required"); ?></div>
            </div>
          </div>

          <div class="col-12">
            <div class="form-group">
              <label for="message"><i class="fas fa-envelope"></i> <?php echo $this->lang->line("Message"); ?></label>
              <textarea name="message" class="summernote form-control height_300px" id="message"></textarea>
              <div class="invalid-feedback"><?php echo $this->lang->line("Message is required"); ?></div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer bg-whitesmoke">
        <button id="send_test_email" class="btn-lg btn btn-primary" > <i class="fas fa-paper-plane"></i>  <?php echo $this->lang->line("Send"); ?></button>
        <button type="button" onclick="javascript:window.location.reload()" class="btn-lg btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line("Close"); ?></button>
      </div>
    </div>
  </div>
</div>