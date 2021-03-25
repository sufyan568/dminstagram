<input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $this->session->userdata('csrf_token_session'); ?>">
<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-users"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-button">
     <a class="btn btn-primary"  href="<?php echo site_url('admin/add_user');?>">
        <i class="fas fa-plus-circle"></i> <?php echo $this->lang->line("New User"); ?>
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
                    <th class="dataTable_checkbox">
                        <input class="regular-checkbox" id="datatableSelectAllRows" type="checkbox"/><label for="datatableSelectAllRows"></label>        
                    </th>
                    <th><?php echo $this->lang->line("ID"); ?></th>      
                    <th><?php echo $this->lang->line("Avatar"); ?></th>      
                    <th><?php echo $this->lang->line("Name"); ?></th>      
                    <th><?php echo $this->lang->line("Email"); ?></th>
                    <th><?php echo $this->lang->line("Package"); ?></th>
                    <th><?php echo $this->lang->line("Status"); ?></th>
                    <th><?php echo $this->lang->line("Type"); ?></th>
                    <th><?php echo $this->lang->line("Expiry"); ?></th>
                    <th class="min_width_150px"><?php echo $this->lang->line("Actions"); ?></th>
                    <th><?php echo $this->lang->line("Registered"); ?></th>
                    <th><?php echo $this->lang->line("Last Login"); ?></th>
                    <th><?php echo $this->lang->line("Last IP"); ?></th>
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

<?php
$drop_menu = '<div class="btn-group dropleft float-right"><button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  '.$this->lang->line("Options").'  </button>  <div class="dropdown-menu dropleft"> <a class="dropdown-item has-icon send_email_ui pointer"><i class="fas fa-paper-plane"></i> '.$this->lang->line("Send Email").'</a> <a class="dropdown-item has-icon" href="'.base_url('admin/login_log').'"><i class="fas fa-history"></i> '.$this->lang->line("Login Log").'</a>';
// if($this->session->userdata('license_type') == 'double')
//   $drop_menu .= '<a target="_BLANK" class="dropdown-item has-icon" href="'.base_url('dashboard/index/system').'"><i class="fas fa-tachometer-alt"></i> '.$this->lang->line("System Dashboard").'</a><a target="_BLANK" class="dropdown-item has-icon" href="'.base_url('admin/activity_log').'"><i class="fas fa-history"></i> '.$this->lang->line("User Activity Log").'</a>';
$drop_menu .= '</div> </div>';
?> 



<?php include("application/views/admin/user/user_list_js.php"); ?>



<div class="modal fade" tabindex="-1" role="dialog" id="change_password" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-key"></i> <?php echo $this->lang->line("Change Password");?> (<span id="putname"></span>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">  
              <form class="form-horizontal" action="<?php echo site_url().'admin/change_user_password_action';?>" method="POST">
                <div id="wait"></div>
                <input id="putid" value="" class="form-control" type="hidden">           
                <div class="form-group">
                  <label for="password"><?php echo $this->lang->line("New Password"); ?> *  </label>                  
                  <input id="password" class="form-control password" type="password">             
                  <div class="invalid-feedback"><?php echo $this->lang->line("You have to type new password twice"); ?></div>
                </div>

                <div class="form-group">
                    <label for="confirm_password"><?php echo $this->lang->line("Confirm New Password"); ?> * </label>                  
                    <input id="confirm_password"  class="form-control password" type="password">             
                   <div class="invalid-feedback"><?php echo $this->lang->line("Passwords does not match"); ?></div>
                </div>
              </form>            
            </div>


            <div class="modal-footer bg-whitesmoke br">
              <button type="button" id="save_change_password_button" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> <?php echo $this->lang->line("Save"); ?></button>
              <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line("Close"); ?></button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="modal_send_sms_email" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-paper-plane"></i> <?php echo $this->lang->line("Send Email");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div id="modalBody" class="modal-body">        
        <div id="show_message" class="text-center"></div>

        <div class="form-group">
          <label for="subject"><?php echo $this->lang->line("Subject"); ?> *</label><br/>
          <input type="text" id="subject" class="form-control"/>
          <div class="invalid-feedback"><?php echo $this->lang->line("Subject is required"); ?></div>
        </div>

        <div class="form-group">
          <label for="message"><?php echo $this->lang->line("Message"); ?> *</label><br/>
          <textarea name="message" class="summernote form-control height_300px" id="message"></textarea>
          <div class="invalid-feedback"><?php echo $this->lang->line("Message is required"); ?></div>
        </div>
     
      </div>

      <div class="modal-footer">
           <button id="send_sms_email" class="btn-lg btn btn-primary" > <i class="fas fa-paper-plane"></i>  <?php echo $this->lang->line("Send"); ?></button>
            <button type="button" class="btn-lg btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line("Close"); ?></button>
      </div>
    </div>
  </div>
</div>