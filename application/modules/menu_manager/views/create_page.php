<section class="section section_custom">
    <div class="section-header">
        <h1><i class="fas fa-plus-circle"></i> <?php echo $page_title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?php echo base_url("menu_manager/index"); ?>"><?php echo $this->lang->line("Menu Manager"); ?></a></div>
            <div class="breadcrumb-item"><a href="<?php echo base_url("menu_manager/get_page_lists"); ?>"><?php echo $this->lang->line("Page Manager"); ?></a></div>
            <div class="breadcrumb-item"><?php echo $page_title; ?></div> 
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <form action="#" id="create_custom_page" method="POST" enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('Page Name'); ?></label>
                                        <input type="text" class="form-control" id="page_name" name="page_name" value="<?php echo set_value("page_name") ?>">
                                        <div class="invalid-feedback"><?php echo $this->lang->line("Page Name is Required"); ?></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('Page Description'); ?></label>
                                        <textarea type="text" class="form-control" id="page_description" name="page_description" placeholder="<?php echo $this->lang->line("Type your page description here..."); ?>"><?php echo set_value("page_description") ?></textarea>
                                        <div class="invalid-feedback"><?php echo $this->lang->line("Page Description is required"); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-whitesmoke">
                            <button class="btn btn-lg btn-primary" id="create_page" type="button"><i class="fas fa-paper-plane"></i> <?php echo $this->lang->line("Create Page") ?> </button>
                            <a class="btn btn-lg btn-light float-right" onclick='goBack("menu_manager/get_page_lists",0)'><i class="fas fa-times"></i> <?php echo $this->lang->line("Cancel") ?> </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url('assets/js/system/menu_manager_add_edit.js');?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/css/system/menu_manager.css');?>">