<?php

require_once("Home.php"); // including home controller

/**
* class config
* @category controller
*/
class Dashboard extends Home
{
    public $user_id;
    /**
    * load constructor method
    * @access public
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');
        $this->user_id=$this->session->userdata('user_id');
    
        set_time_limit(0);
        $this->important_feature();
        $this->member_validity();   
    }    


    /**
    * load index method. redirect to config
    * @access public
    * @return void
    */
    
    public function index($default_value='0')
    { 
        if($this->session->userdata('user_type') != 'Admin') $default_value='0';
        if($default_value == '0')
        {
            $user_id=$this->user_id;
            $data['other_dashboard'] = '0';
        }
        else
        {
            $user_id = $default_value;
            if($default_value == 'system')
                $data['system_dashboard'] = 'yes';
            else
            {
                $user_info = $this->basic->get_data('users',array('where'=>array('id'=>$user_id)));
                $data['user_name'] = isset($user_info[0]['name']) ? $user_info[0]['name'] : '';
                $data['user_email'] = isset($user_info[0]['email']) ? $user_info[0]['email'] : '';
                $data['system_dashboard'] = 'no';
            }
            $data['other_dashboard'] = '1';
        }
        if($this->is_demo === '1' && $data['other_dashboard'] === '1' && isset($data['system_dashboard']) && $data['system_dashboard'] === 'no')
        {            
            return $this->load->view('page/demo_restriction'); 
        }


        $current_date = date("Y-m-d");
        $last_30_day = date("Y-m-d", strtotime("$current_date - 30 days"));
        $where = array('where' => array('date_format(last_updated_at,"%Y-%m-%d") >=' => $last_30_day,"media_type"=>"instagram"));
        if($default_value != 'system') $where['where']['user_id'] = $user_id;
        $posting_data = $this->basic->get_data("facebook_rx_auto_post",$where,array("post_type","image_url","video_url","posting_status","post_url","last_updated_at","schedule_time","page_or_group_or_user_name"));

        $image_post_count = $video_post_count = $completed_post_count = $total_post_count = 0;
        $image_post_list = $video_post_list = $image_video_compare_list = $post_per_account = array();
        foreach ($posting_data as $key => $value)
        {
            $last_updated_at = date("Y-m-d",strtotime($value['last_updated_at']));
            if(!isset($image_post_list[$last_updated_at])) $image_post_list[$last_updated_at] = 0;
            if(!isset($video_post_list[$last_updated_at])) $video_post_list[$last_updated_at] = 0;
            if(!isset($post_per_account[$value["page_or_group_or_user_name"]])) $post_per_account[$value["page_or_group_or_user_name"]] = 0;

            if($value["post_type"]=="image_submit")
            {
                $image_post_count++;
                if($value["posting_status"]=='2')
                {
                    $image_post_list[$last_updated_at] = $image_post_list[$last_updated_at]+1;
                }
            }
            else
            {
                $video_post_count++;
                if($value["posting_status"]=='2')
                {
                    $video_post_list[$last_updated_at] = $video_post_list[$last_updated_at]+1;
                }
            }

            if($value["posting_status"]=='2')
            {
                $post_per_account[$value["page_or_group_or_user_name"]] = $post_per_account[$value["page_or_group_or_user_name"]]+1;
                $completed_post_count++;
            }
            
            $image_video_compare_list[$last_updated_at] = date("j M",strtotime($value['last_updated_at']));

            $total_post_count++;
        }
        arsort($post_per_account);
        $largest_values = array();
        $max_value = 1;
        $step_size = 1;
        if(!empty($image_post_list)) array_push($largest_values, max($image_post_list));
        if(!empty($video_post_list)) array_push($largest_values, max($video_post_list));
        if(!empty($largest_values)) $max_value = max($largest_values);
        if($max_value > 10) $step_size = floor($max_value/10);

        $where = array('where' => array('date_format(schedule_time,"%Y-%m-%d %H:%i:%s") >' => $current_date,"media_type"=>"instagram","schedule_type"=>"later"));
        if($default_value != 'system') $where['where']['user_id'] = $user_id;
        $posting_data_upcoming = $this->basic->get_data("facebook_rx_auto_post",$where,array("campaign_name","page_or_group_or_user_name","message","post_type","image_url","video_url","posting_status","post_url","last_updated_at","schedule_time","id"),"",7,"","schedule_time ASC");

        $where = array('where' => array("social_media_type"=>"Instagram"));
        if($default_value != 'system') $where['where']['user_id'] = $user_id;
        $auto_comment_data = $this->basic->get_data("auto_comment_reply_info",$where,array("id","campaign_name","insta_media_url","auto_reply_done_info","auto_private_reply_status","last_reply_time"),"",5,"","last_reply_time DESC");
        $auto_comment_data_formatted = array();
        $i=0;
        foreach ($auto_comment_data as $key => $value)
        {
           $temp = $value;
           $auto_reply_done_info = json_decode($temp["auto_reply_done_info"],true);
           $last_reply_data = is_array($auto_reply_done_info) ? array_pop($auto_reply_done_info) : array();
           if(isset($temp["auto_reply_done_info"])) unset($temp["auto_reply_done_info"]);
           $auto_comment_data_formatted[$i] = $temp;
           $auto_comment_data_formatted[$i]['last_reply_data'] = $last_reply_data;
           $i++;
        }

        $where = array();
        if($default_value != 'system') $where['where']['user_id'] = $user_id;
        $auto_reply_data = $this->basic->get_data("instagram_autoreply_report",$where,array("id","commenter_name","reply_time","comment_id","comment_reply_text","reply_time","post_url","post_id"),"",5,"","reply_time DESC");

        $data = array
        (
            "image_post_count"=>$image_post_count,
            "video_post_count"=>$video_post_count,
            "completed_post_count"=>$completed_post_count,
            "total_post_count"=>$total_post_count,
            "image_post_list"=>$image_post_list,
            "video_post_list"=>$video_post_list,
            "image_video_compare_list"=>$image_video_compare_list,
            "posting_data_upcoming"=>$posting_data_upcoming,
            "auto_comment_data"=>$auto_comment_data_formatted,
            "auto_reply_data"=>$auto_reply_data,
            "post_per_account"=>$post_per_account,
            "step_size"=>$step_size
        );

        $data['body'] = 'dashboard/dashboard';
        $data['page_title'] = $this->lang->line('Dashboard');
        $this->_viewcontroller($data);
    }
 
}