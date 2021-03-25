<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-bell"></i> <?php echo $page_title; ?></h1>
    <?php if($this->session->userdata("user_type")=="Admin") 
    { ?>
      <div class="section-header-button">
       <a class="btn btn-primary"  href="<?php echo site_url('announcement/add');?>">
          <i class="fas fa-plus-circle"></i> <?php echo $this->lang->line("New Announcement"); ?>
       </a> 
      </div>
    <?php 
    } ?>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $this->lang->line("Subscription"); ?></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <?php $this->load->view('admin/theme/message'); ?>
  <?php 
  if($this->session->flashdata('mark_seen_success')!='')
  echo "<div class='alert alert-success text-center'><i class='fas fa-check-circle'></i> ".$this->session->flashdata('mark_seen_success')."</div>"; 
  ?>

  <div class="section-body">

    <div class="row">      
      <div class="col-12 col-md-7">
        <div class="input-group mb-3" id="searchbox">
          <div class="input-group-prepend">
              <select class="select2 form-control" id="seen_type">
                <option value="0"><?php echo $this->lang->line("Unseen"); ?></option>
                <option value="1"><?php echo $this->lang->line("Seen"); ?></option>
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
        <button class="btn btn-outline-primary btn-lg float-right" id="mark_seen_all"><i class="fas fa-eye-slash"></i> <?php echo $this->lang->line("Mark all unseen as seen"); ?></button>
      </div>
    </div>

    <div class="activities">
        <div id="load_data" class="width_100"></div>      
    </div> 


    <div class="text-center width_100 margin_30_0px" id="waiting">
      <i class="fas fa-spinner fa-spin blue font_size_60px"></i>
    </div>  

    <div class="card" id="nodata" style="display: none">
      <div class="card-body">
        <div class="empty-state">
          <img class="img-fluid height_200px" src="<?php echo base_url('assets/img/drawkit/drawkit-nature-man-colour.svg'); ?>" alt="image">
          <h2 class="mt-0"><?php echo $this->lang->line("We could not find any data.") ?></h2>
        </div>
      </div>
    </div>
 

    <button class="btn btn-outline-primary float-right d_none" id="load_more" data-limit="10" data-start="0"><i class="fas fa-book-reader"></i> <?php echo $this->lang->line("Load More"); ?></button>
      
  </div>
</section>


<script src="<?php echo base_url('assets/js/system/announcement.js');?>"></script>