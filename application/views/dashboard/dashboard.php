<section class="section">
  <div class="section-header pb-0 d-block">
    <div class="w-100 mb-4">      
    <h1 class=""><i class="fas fa-fire"></i> <?php echo $this->lang->line("Dashboard"); ?></h1>
    <div class="breadcrumb-item d-inline float-right d-none d-md-inline"><?php echo $this->lang->line("30 days"); ?></div>     
    </div>
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1 dee2e6_border">
          <div class="card-icon bg-primary">
            <i class="fas fa-images"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4><?php echo $this->lang->line("Total Post"); ?></h4>
            </div>
            <div class="card-body">
              <?php echo $total_post_count; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1 dee2e6_border">
          <div class="card-icon bg-success">
            <i class="fas fa-paper-plane"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4><?php echo $this->lang->line("Completed Post"); ?></h4>
            </div>
            <div class="card-body">
              <?php echo $completed_post_count; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1 dee2e6_border">
          <div class="card-icon bg-warning">
            <i class="fas fa-image"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4><?php echo $this->lang->line("Image Post"); ?></h4>
            </div>
            <div class="card-body">
              <?php echo $image_post_count; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1 dee2e6_border">
          <div class="card-icon bg-danger">
            <i class="fas fa-video"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4><?php echo $this->lang->line("Video Post"); ?></h4>
            </div>
            <div class="card-body">
              <?php echo $video_post_count; ?>
            </div>
          </div>
        </div>
      </div>
    </div>    
  </div>
  
  <div class="row">
    <div class="col-lg-8 col-md-7 col-12 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4><i class="fas fa-adjust"></i> <?php echo $this->lang->line('Image-Video Post Comparison'); ?> (<?php echo $this->lang->line("30 days");?>)</h4>
          
        </div>
        <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
         <canvas id="image_vs_video_post" height="134"></canvas>
        </div>
      </div>
    </div>    
    <div class="col-lg-4 col-md-5 col-12 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4><i class="fas fa-paper-plane"></i> <?php echo $this->lang->line("Post Summary") ?> (<?php echo $this->lang->line("30 days") ?>)</h4>
        </div>
        <div class="card-body">
          <?php
          $width = 90;      
          foreach ($post_per_account as $key => $value): 
            if($value==0) $width = 0;
            else $width-=10;
            if($width<0) $width = 0;
            ?>
            <div class="mb-4">
              <div class="text-small float-right font-weight-bold text-muted"><?php echo $value;?></div>
              <div class="font-weight-bold mb-1"><a class="text-dark" href="https://instagram.com/<?php echo $key;?>" target="_BLANK"><i class="fab fa-instagram"></i> <?php echo $key;?></a></div>
              <div class="progress" data-height="5">
                <div class="progress-bar" role="progressbar" data-width="<?php echo $width; ?>%" aria-valuenow="<?php echo $width; ?>" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            <?php
        endforeach;
        ?>
        <div class="text-right pt-1 pb-1 mb-2">
            <a href="<?php echo base_url('social_accounts');?>" class="btn btn-outline-primary btn-sm">
              <i class="fas fa-eye"></i> <?php echo $this->lang->line('Import Account'); ?>
            </a>
        </div>
        </div>
        
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-4 col-md-4 col-12 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4><i class="fas fa-calendar-alt"></i> <?php echo $this->lang->line('Upcoming Posts'); ?></h4>
        </div>
        <div class="card-body">
          <ul class="list-unstyled list-unstyled-border">
            <?php 
            foreach($posting_data_upcoming as $key => $value) 
            { ?>
              <li class="media mb-2 pb-2">
                <?php 
                if($value["post_type"]=="image_submit")
                echo '<img class="mr-3 rounded-circle" height="50" width="50" src="'.$value["image_url"].'">';
                else echo '<video class="mr-3 rounded-circle" height="50" width="50" src="'.$value["video_url"].'"></video>';
                $edit_url = base_url('instagram_poster/image_video_edit_auto_post'.'/'.$value['id']);
                ?>
                <div class="media-body">
                  <div class="float-right text-primary text-small"><?php echo date("j M y",strtotime($value["schedule_time"]));?></div>
                  <div class="media-title"><a href="<?php echo $edit_url;?>"><?php echo $value["page_or_group_or_user_name"];?></a></div>
                  <span class="text-small text-muted"><?php echo $value["campaign_name"];?></span>
                </div>
              </li>

            <?php
            }
            ?>
          </ul>
          <?php if(count($posting_data_upcoming)>0): ?>
          <div class="text-right pt-1 pb-1">
            <a href="<?php echo base_url('instagram_poster');?>" class="btn btn-outline-primary btn-sm">
              <i class="fas fa-eye"></i> <?php echo $this->lang->line('View all'); ?>
            </a>
          </div>
         <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-4 col-12 col-sm-12">
      <div class="card card-hero">
          <div class="card-header">
            <div class="card-icon">
              <i class="fas fa-reply"></i>
            </div>
            <div class="card-description"><?php echo $this->lang->line("Last Auto Comment Replies") ?></div>
          </div>
          <div class="card-body p-0">
            <div class="tickets-list">
              <?php foreach ($auto_reply_data as $key => $value)
              {
                echo '
                <a href="'.$value["post_url"].'" target="_BLANK" class="ticket-item">
                  <div class="ticket-title">
                    <h4>'.$value["post_id"].'</h4>
                  </div>
                  <div class="ticket-info">
                    <div>'.$value["comment_reply_text"].'</div>
                    <div class="bullet"></div>
                    <div class="text-primary"><small>'.date('j M y H:i:s',strtotime($value["reply_time"])).'</small></div>
                  </div>
                </a>';
              }
              if(count($auto_reply_data)>0): ?>
              <a href="<?php echo base_url('instagram_reply/instagram_autoreply_report/post');?>" class="ticket-item ticket-more">
               <i class="fas fa-eye"></i> <?php echo $this->lang->line('View all'); ?>
              </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
    </div>  

    <div class="col-lg-4 col-md-4 col-12 col-sm-12">
      <div class="card card-hero">
          <div class="card-header">
            <div class="card-icon">
              <i class="fas fa-comment-dots"></i>
            </div>
            <div class="card-description"><?php echo $this->lang->line("Last Auto Comments") ?></div>
          </div>
          <div class="card-body p-0">
            <div class="tickets-list">
              <?php foreach ($auto_comment_data as $key => $value)
              {
                if(!isset($value['last_reply_data']) || empty($value['last_reply_data'])) continue;

                $temp = isset($value['last_reply_data']['comment_text'])?$value['last_reply_data']['comment_text']:'';
                $temp2 = isset($value['last_reply_data']['comment_time'])?date('j M y H:i:s',strtotime($value['last_reply_data']['comment_time'])):'';
                echo '
                <a href="'.$value["insta_media_url"].'" target="_BLANK" class="ticket-item">
                  <div class="ticket-title">
                    <h4>'.$value["campaign_name"].'</h4>
                  </div>
                  <div class="ticket-info">
                    <div>'.$temp.'</div>
                    <div class="bullet"></div>
                    <div class="text-primary"><small>'.$temp2.'</small></div>
                  </div>
                </a>';
              }
              if(count($auto_comment_data)>0): ?>
              <a href="<?php echo base_url('comment_automation/all_auto_comment_report/0/0/1');?>" class="ticket-item ticket-more">
               <i class="fas fa-eye"></i> <?php echo $this->lang->line('View all'); ?>
              </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
    </div>    

  </div>
</section>
<script src="<?php echo base_url('assets/js/system/dashboard.js');?>"></script>


