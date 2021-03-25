<link rel="stylesheet" href="<?php echo base_url('assets/css/system/menu_manager.css');?>">
<section class="section section_custom">
    <div class="section-header">
        <h1><i class="fas fa-pager"></i> <?php echo $page_title; ?></h1>
        <div class="section-header-button">
            <a class="btn btn-primary" href="<?php echo base_url('menu_manager/create_page'); ?>">
                <i class="fas fa-plus-circle"></i> <?php echo $this->lang->line("New Page"); ?>
            </a> 
        </div>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?php echo base_url("menu_manager/index"); ?>"><?php echo $this->lang->line("Menu Manager"); ?></a></div>
            <div class="breadcrumb-item"><?php echo $page_title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body data-card">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="input-group float-left" id="searchbox">
                                    <input type="text" class="form-control" id="searching_page" name="searching_page" autofocus placeholder="<?php echo $this->lang->line('Search...'); ?>" aria-label="" aria-describedby="basic-addon2">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                              <a href="javascript:;" id="page_date_range" class="btn btn-primary btn-lg icon-left float-right btn-icon"><i class="fas fa-calendar"></i> <?php echo $this->lang->line("Choose Date");?></a><input type="hidden" id="page_date_range_val">
                              <a href="#" class="btn btn-danger btn-lg float-right mr-2 delete_selected_page" data-toggle="tooltip" title="<?php echo $this->lang->line("Delete Selected"); ?>"><i class="fas fa-trash-alt"></i> <?php echo $this->lang->line("Delete"); ?></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive2">
                                    <table class="table table-bordered" id="mytable_custom_page_lists">
                                        <thead>
                                            <tr>
                                                <th>#</th> 
                                                <th>
                                                    <input class="regular-checkbox" id="datatableSelectAllRows" type="checkbox"/>
                                                    <label for="datatableSelectAllRows"></label>        
                                                </th>      
                                                <th><?php echo $this->lang->line("ID"); ?></th>      
                                                <th><?php echo $this->lang->line("Page Name"); ?></th>
                                                <th><?php echo $this->lang->line("Slug"); ?></th>
                                                <th><?php echo $this->lang->line("URL"); ?></th>
                                                <th><?php echo $this->lang->line("Created"); ?></th>
                                                <th><?php echo $this->lang->line('Actions'); ?></th>
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
        </div>
    </div>
</section>


<script src="<?php echo base_url('assets/js/system/menu_manager_list.js');?>"></script>