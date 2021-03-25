<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-ticket-alt"></i> <?php echo $page_title; ?></h1>  
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $this->lang->line("Support Desk"); ?></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <?php $this->load->view('admin/theme/message'); ?>

  <div class="section-body">

    <div class="row">      
      <div class="col-12 col-md-7">
        <div class="input-group mb-3" id="searchbox">
          <div class="input-group-prepend">
              <select class="select2 form-control" id="ticket_status">
                <option value="1"><?php echo $this->lang->line("Open"); ?></option>
                <option value="3"><?php echo $this->lang->line("Resolved"); ?></option>
                <option value="2"><?php echo $this->lang->line("Closed"); ?></option>
                <?php if($this->session->userdata("user_type")=="Admin") { ?>
                <option value="hidden"><?php echo $this->lang->line("Hidden"); ?></option>
                <?php } ?>
                <option value=""><?php echo $this->lang->line("Everything"); ?></option>
              </select>
            </div>
          <input type="text" class="form-control" id="search" autofocus placeholder="<?php echo $this->lang->line('Search...'); ?>" aria-label="" aria-describedby="basic-addon2">
          <div class="input-group-append">
            <button class="btn btn-primary" id="search_submit" type="button"><i class="fas fa-search"></i> <?php echo $this->lang->line('Search'); ?></button>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-5">

        <?php if($this->session->userdata("user_type")=="Admin") 
        { ?>
          <a class="btn btn-outline-primary btn-lg float-right" href="<?php echo base_url('simplesupport/support_category_manager'); ?>"><i class="fas fa-layer-group"></i> <?php echo $this->lang->line("Manage Category"); ?></a>
        <?php 
        } 
        else 
        { ?>
           <a class="btn btn-outline-primary btn-lg float-right"  href="<?php echo site_url('simplesupport/open_ticket');?>">
              <i class="fas fa-plus-circle"></i> <?php echo $this->lang->line("New Ticket"); ?>
           </a> 
        <?php 
        } ?>


        
      </div>
    </div>

    <div class="activities">
        <div id="load_data" class="width_100"></div>      
    </div> 


    <div class="text-center waiting_spinner_edit_video_css" id="waiting">
      <i class="fas fa-spinner fa-spin blue font_size_60px"></i>
    </div>  

    <div class="card d_none" id="nodata">
      <div class="card-body">
        <div class="empty-state">
          <img class="img-fluid height_300px" src="<?php echo base_url('assets/img/drawkit/drawkit-nature-man-colour.svg'); ?>" alt="image">
          <h2 class="mt-0"><?php echo $this->lang->line("We could not find any data.") ?></h2>
        </div>
      </div>
    </div>
 

    <button class="btn btn-outline-primary float-right d_none" id="load_more" data-limit="10" data-start="0"><i class="fas fa-book-reader"></i> <?php echo $this->lang->line("Load More"); ?></button>
      
  </div>
</section>


<script src="<?php echo base_url('assets/js/system/simplesupport_list.js');?>"></script>