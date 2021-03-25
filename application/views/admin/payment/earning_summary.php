<section class="section">
  <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12">
      <div class="card card-statistic-2">
        <div class="card-stats">
          <div class="card-stats-title"><i class="fas fa-eye"></i> <?php echo $this->lang->line("Summary"); ?>
          </div>
          <div class="card-stats-items">
            <div class="card-stats-item">
              <div class="card-stats-item-count"><?php echo $curency_icon.$payment_today; ?></div>
              <div class="card-stats-item-label"><?php echo $this->lang->line("Today"); ?></div>
            </div>
            <div class="card-stats-item">
              <div class="card-stats-item-count"><?php echo $curency_icon.$payment_month; ?></div>
              <div class="card-stats-item-label"><?php echo $this->lang->line(date("M")); ?></div>
            </div>
            <div class="card-stats-item">
              <div class="card-stats-item-count"><?php echo $curency_icon.$payment_year; ?></div>
              <div class="card-stats-item-label"><?php echo $this->lang->line("Year"); ?></div>
            </div>
          </div>
        </div>
        <div class="card-icon shadow-primary bg-info">
          <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4><?php echo $this->lang->line("Life Time")." ".$this->lang->line("Earning"); ?></h4>
          </div>
          <div class="card-body">
            <?php echo $curency_icon.$payment_life; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
      <div class="card card-statistic-2">
        <div class="card-chart">
          <canvas id="month-chart" height="80"></canvas>
        </div>
        <div class="card-icon shadow-primary bg-primary">
          <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4><?php echo date("M - Y")." ".$this->lang->line("Earning"); ?></h4>
          </div>
          <div class="card-body">
            <?php echo $curency_icon.$payment_month; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-12">
      <div class="card card-statistic-2">
        <div class="card-chart">
          <canvas id="year-chart" height="80"></canvas>
        </div>
        <div class="card-icon shadow-primary bg-warning">
          <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4><?php echo date("Y")." ".$this->lang->line("Earning"); ?></h4>
          </div>
          <div class="card-body">
           <?php echo $curency_icon.$payment_year; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-6">
      <div class="card min_width_420px">
        <div class="card-header">
          <h4><i class="fas fa-balance-scale"></i> <?php echo $this->lang->line("Earning Comparison")." : ".$year." ".$this->lang->line("Vs")." ".$lastyear; ?></h4>
        </div>
        <div class="card-body">
          <canvas id="comparison-chart" height="158"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h4><i class="fas fa-flag-checkered"></i> <?php echo $this->lang->line("Top Countries")." : ".$year." ".$this->lang->line("Vs")." ".$lastyear; ?></h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
              <div class="text-title mb-2"><?php echo $year;?></div>
              <ul class="list-unstyled list-unstyled-border list-unstyled-noborder mb-0">
                <?php
                $count=1;
                foreach ($this_year_top as $key => $value) 
                { ?>  
                  <li class="media">
                    <img class="img-fluid mt-1 img-shadow" src="<?php echo base_url(); ?>assets/modules/flag-icon-css/flags/4x3/<?php echo strtolower($key);?>.svg" alt="image" width="40">
                    <div class="media-body ml-3">
                      <div class="media-title"><?php echo isset($country_names[$key]) ? $this->lang->line($country_names[$key]) : "-"; ?></div>
                      <div class="text-small text-muted"><?php echo $curency_icon.$value; ?> <i class="fas fa-caret-down text-danger"></i></div>
                    </div>
                  </li>
                <?php 
                $count++;
                if($count==5) break;
                } ?>                
              </ul>
            </div>
            <div class="col-sm-6 mt-sm-0 mt-4">
              <div class="text-title mb-2"><?php echo $lastyear;?></div>
              <ul class="list-unstyled list-unstyled-border list-unstyled-noborder mb-0">
                <?php
                $count=1;
                foreach ($last_year_top as $key => $value) 
                { ?>  
                  <li class="media">
                    <img class="img-fluid mt-1 img-shadow" src="<?php echo base_url(); ?>assets/modules/flag-icon-css/flags/4x3/<?php echo strtolower($key);?>.svg" alt="image" width="40">
                    <div class="media-body ml-3">
                      <div class="media-title"><?php echo isset($country_names[$key]) ? $this->lang->line($country_names[$key]) : "-"; ?></div>
                      <div class="text-small text-muted"><?php echo $curency_icon.$value; ?> <i class="fas fa-caret-down text-danger"></i></div>
                    </div>
                  </li>
                <?php 
                $count++;
                if($count==5) break;
                } ?>                
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php 
$max1 = (!empty($this_year_earning)) ? max($this_year_earning) : 0;
$max2 = (!empty($last_year_earning)) ? max($last_year_earning) : 0;
$steps = round(max(array($max1,$max2))/7);
if($steps==0) $steps = 1;
?>


<?php include("application/views/admin/payment/earning_summary_js.php"); ?>