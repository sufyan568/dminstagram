<?php

require_once("Home.php"); // loading home controller

class calendar extends Home
{    

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');
        if($this->session->userdata("facebook_rx_fb_user_info")==0)
        redirect('social_accounts/index','refresh');      
       
    }



    public function index()
    {
      $this->full_calendar();
    }

    public function full_calendar()
    {
        $data['calendar_data'] = array();  
        $select = array("facebook_rx_auto_post.id","facebook_rx_auto_post.post_type","facebook_rx_auto_post.schedule_time","facebook_rx_auto_post.time_zone","facebook_rx_auto_post.posting_status","facebook_rx_auto_post.campaign_name","facebook_rx_auto_post.page_or_group_or_user_name","facebook_rx_auto_post.last_updated_at","image_url","video_url");
        $table2 = $this->basic->get_data('facebook_rx_auto_post',array('where'=>array('user_id'=>$this->user_id,"media_type"=>"instagram")),$select=$select);       


        $data['info'] = $table2;

        foreach ($data['info'] as $key => $value)
        {
            
            if($value['post_type']=="image_submit" && !empty($value['image_url']))
            $data['calendar_data'][$key]['imageurl'] = $value['image_url'];
            if($value['post_type']=="video_submit" && !empty($value['video_url']))
            $data['calendar_data'][$key]['imageurl'] = $value['video_url'];
                  
            if($value['schedule_time']!='0000-00-00 00:00:00') $data['calendar_data'][$key]['start'] = $value['schedule_time']; 
            else $data['calendar_data'][$key]['start'] = $value['last_updated_at'];
                 

            $posting_status = $value['posting_status'];
        

            $c_type = '';
            $edit_url = site_url('instagram_poster/image_video_edit_auto_post/'.$value['id']);
            $data['calendar_data'][$key]['url']=$edit_url;

            if(isset($value['post_type']) && $value['post_type']== "image_submit") $c_type = $this->lang->line('image');
            else $c_type = $this->lang->line('video');
            
            if( $posting_status == '2'){

                $data['calendar_data'][$key]['title'] = $c_type." ".$this->lang->line("completed");
                $data['calendar_data'][$key]['color'] = "#4CAF50";
                
            } 
              
            else if( $posting_status == '1') 
            {
                $data['calendar_data'][$key]['title'] = $c_type." ".$this->lang->line("processing");
                $data['calendar_data'][$key]['color'] = "#ffc107";
               
            }
            
            else if( $posting_status == '3') 
            {
                $data['calendar_data'][$key]['title'] = $c_type." ".$this->lang->line("stopped");
                $data['calendar_data'][$key]['color'] = "#dc3545";
            }

            else 
            {
                $data['calendar_data'][$key]['title'] = $c_type." ".$this->lang->line("pending");
                $data['calendar_data'][$key]['color'] = "#007bff";
            }
             
        }
        $data['body'] = "calendar/full_calendar";
        $data['page_title'] = $this->lang->line("Post Calendar");
        $this->_viewcontroller($data);
    }
}
