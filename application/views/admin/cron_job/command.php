<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-tasks"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $this->lang->line("System"); ?></div>      
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <?php $this->load->view('admin/theme/message'); ?>

  <div class="section-body">
  	<div class="row">
      <div class="col-12">			
		<div class="card">
          <div class="card-header">
            <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Membership Expiration Alert & Delete Junk Data");?> <code><?php echo $this->lang->line("Once/Day"); ?></code></h4>
          </div>
          <div class="card-body">
            <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".site_url("cron_job/membership_alert_delete_junk_data")." >/dev/null 2>&1"; ?></span></code></pre>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h4>
              <i class="fas fa-circle"></i> 
              <?php echo $this->lang->line("Auto Comment");?>              
              <code><?php echo $this->lang->line("Once/3 Minutes"); ?></code></h4>
          </div>
          <div class="card-body">
            <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".site_url("cron_job/auto_comment_on_post")." >/dev/null 2>&1"; ?></span></code></pre>
          </div>
        </div>



        <div class="card">
          <div class="card-header">
            <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Instagram Posting");?> <code><?php echo $this->lang->line("Once/2 Minutes"); ?></code></h4>
          </div>
          <div class="card-body">
            <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".site_url("cron_job/publish_post")." >/dev/null 2>&1"; ?></span></code></pre>
          </div>
        </div>
	  </div>
  </div>
</section>