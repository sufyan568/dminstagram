<?php
require_once("Home.php");

class Cron_job extends Home
{
    public function __construct()
    {
        parent::__construct();
        $this->upload_path = realpath( APPPATH . '../upload');        
    }


    public function api_member_validity($user_id='')
    {
        if($user_id!='') {
            $where['where'] = array('id'=>$user_id);
            $user_expire_date = $this->basic->get_data('users',$where,$select=array('expired_date'));
            $expire_date = strtotime($user_expire_date[0]['expired_date']);
            $current_date = strtotime(date("Y-m-d"));
            $package_data=$this->basic->get_data("users",$where=array("where"=>array("users.id"=>$user_id)),$select="package.price as price, users.user_type",$join=array('package'=>"users.package_id=package.id,left"));

            if(is_array($package_data) && array_key_exists(0, $package_data) && $package_data[0]['user_type'] == 'Admin' )
                return true;

            $price = '';
            if(is_array($package_data) && array_key_exists(0, $package_data))
            $price=$package_data[0]["price"];
            if($price=="Trial") $price=1;

            
            if ($expire_date < $current_date && ($price>0 && $price!=""))
            return false;
            else return true;           

        }
    }

    protected function get_fb_rx_config($fb_user_id=0)
    {
        if($fb_user_id==0) return 0;

        $getdata= $this->basic->get_data("facebook_rx_fb_user_info",array("where"=>array("id"=>$fb_user_id)),array("facebook_rx_config_id"));
        $return_val = isset($getdata[0]["facebook_rx_config_id"]) ? $getdata[0]["facebook_rx_config_id"] : 0;

        return $return_val; 
       
    }


    public function index()
    {
       $this->get_api();
    }


    public function get_api()
    {
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');

        if($this->session->userdata('user_type') != 'Admin')
        redirect('home/login_page', 'location');

        $this->member_validity();

        $data['body'] = "admin/cron_job/command";
        $data['page_title'] = $this->lang->line("Cron Job");
        $this->_viewcontroller($data);
    }

 


    public function api_key_check($api_key="")
    {

        return TRUE;
        if($this->input->is_cli_request()) return TRUE;

        $user_id="";
        if($api_key!="")
        {
            $explde_api_key=explode('-',$api_key);
            $user_id="";
            if(array_key_exists(0, $explde_api_key))
            $user_id=$explde_api_key[0];
        }

        if($api_key=="")
        {        
            echo "API Key is required.";    
            exit();
        }

        if(!$this->basic->is_exist("native_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
           echo "API Key does not match with any user.";
           exit();
        }

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0","user_type"=>"Admin")))
        {
            echo "API Key does not match with any authentic user.";
            exit();
        }              
       

    }      

    
    public function auto_comment_on_post_orginal($api_key = '')
    {

        //api key need to be checked
        // $this->api_key_check($api_key);

        //load library for commenting
        $this->load->library('fb_rx_login');

        //fetch data from database
        if($this->is_demo == '1')
        $where['where'] = array('auto_comment_reply_info.auto_private_reply_status' => '0', 'auto_comment_reply_info.user_id !='=>1);
        else
        $where['where'] = array('auto_comment_reply_info.auto_private_reply_status' => '0');

        $join = array('auto_comment_reply_tb'=>"auto_comment_reply_info.auto_comment_template_id=auto_comment_reply_tb.id,left");
        $select = array('auto_comment_reply_info.*','auto_comment_reply_tb.auto_reply_comment_text');
        $limit = 10;
        $order_by = 'auto_comment_reply_info.last_updated_at asc';
        $auto_comment_reply_info = $this->basic->get_data('auto_comment_reply_info', $where, $select, $join, $limit, "", $order_by);

        if(count($auto_comment_reply_info) == 0) 
            return; 

        //update campaign status and create page access token's array
        $page_info_table_list = array();
        $campaign_post_id_info = array();
        $campaign_post_info = array();
        foreach ($auto_comment_reply_info as $single_comment_reply_info) {
            
            $this->basic->update_data('auto_comment_reply_info', array("id" => $single_comment_reply_info['id']), array("auto_private_reply_status" => '1'));

            array_push($page_info_table_list, $single_comment_reply_info['page_info_table_id']);
            $campaign_post_id_info[$single_comment_reply_info['id']] = $single_comment_reply_info['page_info_table_id'];
        }
        
        $page_info_table_list = array_unique($page_info_table_list);


        //page's info array
        $where = array("where_in" => array("facebook_rx_fb_page_info.id" => $page_info_table_list) );
        $join = array('facebook_rx_fb_user_info'=>"facebook_rx_fb_page_info.facebook_rx_fb_user_info_id=facebook_rx_fb_user_info.id,left");
        $select = array("facebook_rx_fb_page_info.*", "facebook_rx_fb_user_info.facebook_rx_config_id","facebook_rx_fb_user_info.access_token");
        $page_info_list = $this->basic->get_data('facebook_rx_fb_page_info',$where, $select, $join);


        //associate page info and other info with campaign id
        foreach ($campaign_post_id_info as $key_id => $page_info_id) {
            
            foreach ($page_info_list as $single_page_info) {
                
                if($page_info_id == $single_page_info['id']){

                    $campaign_post_info[$key_id]['facebook_rx_fb_user_info_id'] = $single_page_info['facebook_rx_fb_user_info_id'];
                    $campaign_post_info[$key_id]['page_access_token'] = $single_page_info['page_access_token'];
                    $campaign_post_info[$key_id]['facebook_rx_config_id'] = $single_page_info['facebook_rx_config_id'];
                    $campaign_post_info[$key_id]['user_access_token'] = $single_page_info['access_token'];

                }
            }
    
        }

        foreach ($auto_comment_reply_info as $single_comment_reply_info) {

            //check if template exists
            if($single_comment_reply_info['auto_reply_comment_text'] == ""){

                $this->basic->update_data("auto_comment_reply_info",array("id"=>$single_comment_reply_info['id']),array("auto_private_reply_status"=>"2", "error_message" => "Template is missing."));
                continue;
            }
            
            $time_zone = $single_comment_reply_info['time_zone'];
            if($time_zone != '')
              date_default_timezone_set($time_zone);

            $current_time = date("Y-m-d H:i:s");
            $current_value = strtotime($current_time);

            //check comment schedule type
            $comment_schedule_type = $single_comment_reply_info['schedule_type'];

            if($comment_schedule_type == "onetime"){

                //check time
                $schedule_time = $single_comment_reply_info['schedule_time'];
                $compare_value = strtotime($schedule_time);
                if($current_value >= $compare_value){

                    //post comment
                    $this->fb_rx_login->app_initialize($campaign_post_info[$single_comment_reply_info['id']]['facebook_rx_config_id']);

                    $temp_message = $single_comment_reply_info['auto_reply_comment_text'];
                    $temp_message = json_decode($temp_message,true);
                    $message = $temp_message[0];
                    $post_id = $single_comment_reply_info['post_id'];
                    $access_token = $campaign_post_info[$single_comment_reply_info['id']]['page_access_token'];
                    $user_access_token_insta= $campaign_post_info[$single_comment_reply_info['id']]['user_access_token'];

                    try 
                    {

                      if($single_comment_reply_info['social_media_type']=="Facebook")
                        $response=$this->fb_rx_login->auto_comment($message,$post_id,$access_token);
                      else
                        $response=$this->fb_rx_login->instagram_direct_auto_comment($message,$post_id,$user_access_token_insta);

                      $commentid=isset($response['id'])?$response['id']:"";  

                      $id = $commentid;
                      $comment_text = $message;
                      $comment_time = $current_time;
                      $schedule_type = $comment_schedule_type;
                      $reply_status = "success";

                      $report_data = array();
                      $report_data['id'] = $id;
                      $report_data['comment_text'] = $comment_text;
                      $report_data['comment_time'] = $comment_time;
                      $report_data['schedule_type'] = $schedule_type;
                      $report_data['reply_status'] = $reply_status;

                      $auto_reply_done_info = array();
                      if($single_comment_reply_info['auto_reply_done_info'] != "")
                        $auto_reply_done_info = json_decode($single_comment_reply_info['auto_reply_done_info'],true);
                      array_push($auto_reply_done_info, $report_data);

                      $report = json_encode($auto_reply_done_info);

       
                      $this->basic->update_data("auto_comment_reply_info",array("id"=>$single_comment_reply_info['id']),array("auto_private_reply_status"=>"2","last_reply_time"=>$current_time,"last_updated_at"=>$current_time, "auto_reply_done_info" => $report, "auto_comment_count" => 1));
                    } 
                    catch (Exception $e) 
                    {
                      $error_msg = $e->getMessage();

                      $id = "";
                      $comment_text = $message;
                      $comment_time = $current_time;
                      $schedule_type = $comment_schedule_type;
                      $reply_status = "failed (".$error_msg.")";

                      $report_data = array();
                      $report_data['id'] = $id;
                      $report_data['comment_text'] = $comment_text;
                      $report_data['comment_time'] = $comment_time;
                      $report_data['schedule_type'] = $schedule_type;
                      $report_data['reply_status'] = $reply_status;

                      $auto_reply_done_info = array();
                      if($single_comment_reply_info['auto_reply_done_info'] != "")
                        $auto_reply_done_info = json_decode($single_comment_reply_info['auto_reply_done_info'],true);
                      array_push($auto_reply_done_info, $report_data);

                      $report = json_encode($auto_reply_done_info);


                      $this->basic->update_data("auto_comment_reply_info",array("id"=>$single_comment_reply_info['id']),array("auto_private_reply_status"=>"2","last_reply_time"=>$current_time,"last_updated_at"=>$current_time,"error_message"=>$error_msg, "auto_reply_done_info" => $report));
                    }
                    
                }
                else{

                    //update status
                    $this->basic->update_data("auto_comment_reply_info",array("id"=>$single_comment_reply_info['id']),array("auto_private_reply_status"=>"0"));
                }
            }
            else if($comment_schedule_type == "periodic"){

                //check time
                $campaign_start_time = $single_comment_reply_info['campaign_start_time'];
                $campaign_end_time = $single_comment_reply_info['campaign_end_time'];

                $compare_start = strtotime($campaign_start_time);
                $compare_end = strtotime($campaign_end_time);


                if($current_value >= $compare_start && $current_value <= $compare_end){

                    $comment_start_time = $single_comment_reply_info['comment_start_time'];
                    $comment_end_time = $single_comment_reply_info['comment_end_time'];

                    $comment_start = strtotime($comment_start_time);
                    $comment_end = strtotime($comment_end_time);

                    $current_date_time = date("H:i:s");
                    $current_date_time_value = strtotime($current_date_time);

                    if($current_date_time_value >= $comment_start && $current_date_time_value <= $comment_end){

                        //check time again
                        $periodic_time = $single_comment_reply_info['periodic_time'];

                        $last_reply_time = $single_comment_reply_info['last_reply_time'];
                        $last_reply_time_value = strtotime($last_reply_time);

                        $temp = ($last_reply_time_value + ($periodic_time * 60) );
                        
                        if($last_reply_time_value == "" || ($temp <= $current_value) ){

                            //post comment
                            $this->fb_rx_login->app_initialize($campaign_post_info[$single_comment_reply_info['id']]['facebook_rx_config_id']);

                            $auto_comment_type = $single_comment_reply_info['auto_comment_type'];
                            $temp_message = $single_comment_reply_info['auto_reply_comment_text'];
                            $temp_message = json_decode($temp_message,true);

                            if($auto_comment_type == "random"){
                                $rand_index = rand(0,(count($temp_message)-1));
                                $message = $temp_message[$rand_index];
                            }
                            else{

                                $periodic_serial_reply_count = $single_comment_reply_info['periodic_serial_reply_count'];
                                if($periodic_serial_reply_count >= count($temp_message))
                                    $periodic_serial_reply_count = 0;

                                $message = $temp_message[$periodic_serial_reply_count];
                                $periodic_serial_reply_count++;
                                
                            }
                            $post_id = $single_comment_reply_info['post_id'];
                            $access_token = $campaign_post_info[$single_comment_reply_info['id']]['page_access_token'];
                            $user_access_token_insta= $campaign_post_info[$single_comment_reply_info['id']]['user_access_token'];

                            try 
                            {

                              if($single_comment_reply_info['social_media_type']=="Facebook")
                                $response=$this->fb_rx_login->auto_comment($message,$post_id,$access_token);
                              else
                                 $response=$this->fb_rx_login->instagram_direct_auto_comment($message,$post_id,$user_access_token_insta);

                              $commentid=isset($response['id'])?$response['id']:"";        

                              $auto_comment_count = $single_comment_reply_info['auto_comment_count']; 
                              $auto_comment_count++;

                              $id = $commentid;
                              $comment_text = $message;
                              $comment_time = $current_time;
                              $schedule_type = $comment_schedule_type;
                              $reply_status = "success";

                              $report_data = array();
                              $report_data['id'] = $id;
                              $report_data['comment_text'] = $comment_text;
                              $report_data['comment_time'] = $comment_time;
                              $report_data['schedule_type'] = $schedule_type;
                              $report_data['reply_status'] = $reply_status;

                              $auto_reply_done_info = array();
                              if($single_comment_reply_info['auto_reply_done_info'] != "")
                                $auto_reply_done_info = json_decode($single_comment_reply_info['auto_reply_done_info'],true);
                              array_push($auto_reply_done_info, $report_data);

                              $report = json_encode($auto_reply_done_info);

                              $this->basic->update_data("auto_comment_reply_info",array("id"=>$single_comment_reply_info['id']),array("auto_private_reply_status"=>"0","last_reply_time"=>$current_time,"last_updated_at"=>$current_time, "auto_comment_count" => $auto_comment_count, "auto_reply_done_info" => $report));

                              //update comment count if necessary
                              if($auto_comment_type == "serially"){

                                $periodic_serial_reply_count = $single_comment_reply_info['periodic_serial_reply_count'];
                                if($periodic_serial_reply_count >= count($temp_message))
                                    $periodic_serial_reply_count = 0;

                                $periodic_serial_reply_count++;

                                $this->basic->update_data("auto_comment_reply_info",array("id"=>$single_comment_reply_info['id']),array("periodic_serial_reply_count"=>$periodic_serial_reply_count));
                              }
                            } 
                            catch (Exception $e) 
                            {
                              $error_msg = $e->getMessage();


                              $id = "";
                              $comment_text = $message;
                              $comment_time = $current_time;
                              $schedule_type = $comment_schedule_type;
                              $reply_status = "failed (".$error_msg.")";

                              $report_data = array();
                              $report_data['id'] = $id;
                              $report_data['comment_text'] = $comment_text;
                              $report_data['comment_time'] = $comment_time;
                              $report_data['schedule_type'] = $schedule_type;
                              $report_data['reply_status'] = $reply_status;

                              $auto_reply_done_info = array();
                              if($single_comment_reply_info['auto_reply_done_info'] != "")
                                $auto_reply_done_info = json_decode($single_comment_reply_info['auto_reply_done_info'],true);
                              array_push($auto_reply_done_info, $report_data);

                              $report = json_encode($auto_reply_done_info);


                              $this->basic->update_data("auto_comment_reply_info",array("id"=>$single_comment_reply_info['id']),array("auto_private_reply_status"=>"0","last_reply_time"=>$current_time,"last_updated_at"=>$current_time,"error_message"=>$error_msg, "auto_reply_done_info" => $report));
                            }
                            //update campaign status
                        }
                        else{

                            //update campaign status
                            $this->basic->update_data("auto_comment_reply_info",array("id"=>$single_comment_reply_info['id']),array("auto_private_reply_status"=>"0"));
                        }
                    }
                    else{

                        //update campaign status
                        $this->basic->update_data("auto_comment_reply_info",array("id"=>$single_comment_reply_info['id']),array("auto_private_reply_status"=>"0"));
                    }
                }
                else if($current_value > $compare_end){
                    
                    //update campaign status
                    $this->basic->update_data("auto_comment_reply_info",array("id"=>$single_comment_reply_info['id']),array("auto_private_reply_status"=>"2"));
                }
            }
            
        }
    }
    // =====================SUBSCRIBER & AUTO REPLY/COMMENT===================
     
 

    // ===========FACEBOOK POSTER RELATED FUNCTIONS=============
    public function text_image_link_video_post($api_key="") //publish_post
    {
        if($this->is_demo == '1')
        $where['where']=array("posting_status"=>"0", "facebook_rx_auto_post.user_id !="=>1);
        else
        $where['where']=array("posting_status"=>"0");
        /***   Taking fist 200 post for auto post ***/
        $post_info= $this->basic->get_data("facebook_rx_auto_post",$where,$select='',$join='',$limit=25, $start=0, $order_by='schedule_time ASC');

        $database = array();

        $campaign_id_array=array();

        foreach($post_info as $info)
        {
            $time_zone= $info['time_zone'];
            $schedule_time= $info['schedule_time'];

            if($time_zone) date_default_timezone_set($time_zone);
            $now_time = date("Y-m-d H:i:s");

            if(strtotime($now_time) < strtotime($schedule_time)) continue;

            $campaign_id_array[] = $info['id'];
        }

        if(empty($campaign_id_array)) exit();

        $this->db->where_in("id",$campaign_id_array);
        $this->db->update("facebook_rx_auto_post",array("posting_status"=>"1"));

        $config_id_database = array();
        foreach($post_info as $info)
        {
            $campaign_id= $info['id'];

            if(!in_array($campaign_id, $campaign_id_array)) continue;

            $media_type = $info['media_type'];

            $post_type= $info['post_type'];
            $page_group_user_id= $info["page_group_user_id"];
            $page_or_group_or_user= $info["page_or_group_or_user"];
            $user_id= $info['user_id'];
            $message =$info['message'];
            $link =$info['link'];
            $link_preview_image =$info['link_preview_image'];
            $link_caption =$info['link_caption'];
            $link_description =$info['link_description'];
            $image_url =$info['image_url'];
            $video_title =$info['video_title'];
            $video_url =$info['video_url'];
            $video_thumb_url =$info['video_thumb_url'];
            $link =$info['link'];

            $time_zone= $info['time_zone'];
            $schedule_time= $info['schedule_time'];

            // setting fb confid id for library call
            $fb_rx_fb_user_info_id= $info['facebook_rx_fb_user_info_id'];

            if($media_type == 'instagram')
            {
                $user_infos = $this->basic->get_data("facebook_rx_fb_user_info",array("where"=>array("user_id"=>$user_id,"id"=>$fb_rx_fb_user_info_id)));
                $user_access_token = isset($user_infos[0]['access_token']) ? $user_infos[0]['access_token'] : '';
                $page_info = $this->basic->get_data("facebook_rx_fb_page_info",array("where"=>array("user_id"=>$user_id,"id"=>$page_group_user_id)),['instagram_business_account_id']);
                $instagram_business_account_id = isset($page_info[0]['instagram_business_account_id']) ? $page_info[0]['instagram_business_account_id'] : '';
            }
            else
            {
                $user_access_token = '';
                $instagram_business_account_id = '';
            }

            if(!isset($config_id_database[$fb_rx_fb_user_info_id]))
            {
                $config_id_database[$fb_rx_fb_user_info_id] = $this->get_fb_rx_config($fb_rx_fb_user_info_id);
            }
            $this->session->set_userdata("fb_rx_login_database_id", $config_id_database[$fb_rx_fb_user_info_id]);
            $this->load->library("fb_rx_login");
            // setting fb confid id for library call


            if($page_or_group_or_user=="page")
            {
                $table_name = "facebook_rx_fb_page_info";
                $fb_id_field =  "page_id";
                $access_token_field =  "page_access_token";
            }
            else if($page_or_group_or_user=="user")
            {
                $table_name = "facebook_rx_fb_user_info";
                $fb_id_field =  "fb_id";
                $access_token_field =  "access_token";
            }
            else
            {
                $table_name = "facebook_rx_fb_group_info";
                $fb_id_field =  "group_id";
                $access_token_field =  "group_access_token";

            }

            if(!isset($database[$page_or_group_or_user][$page_group_user_id])) // if not exists in database
            {
                $access_data = $this->basic->get_data($table_name,array("where"=>array("id"=>$page_group_user_id)));

                $use_access_token = isset($access_data["0"][$access_token_field]) ? $access_data["0"][$access_token_field] : "";
                $use_fb_id = isset($access_data["0"][$fb_id_field]) ? $access_data["0"][$fb_id_field] : "";

                //inserting new data in database
                $database[$page_or_group_or_user][$page_group_user_id] = array("use_access_token"=>$use_access_token,"use_fb_id"=>$use_fb_id);
            }

            $use_access_token = isset($database[$page_or_group_or_user][$page_group_user_id]["use_access_token"]) ? $database[$page_or_group_or_user][$page_group_user_id]["use_access_token"] : "";
            $use_fb_id = isset($database[$page_or_group_or_user][$page_group_user_id]["use_fb_id"]) ? $database[$page_or_group_or_user][$page_group_user_id]["use_fb_id"] : "";

            $response =array();
            $error_msg ="";
            if($post_type=="text_submit")
            {
                try
                {
                    $response = $this->fb_rx_login->feed_post($message,"","","","","",$use_access_token,$use_fb_id);
                }
                catch(Exception $e)
                {
                    $error_msg = $e->getMessage();
                }
            }

            else if($post_type=="link_submit")
            {
                try
                {
                    $response = $this->fb_rx_login->feed_post($message,$link,"","","","",$use_access_token,$use_fb_id);
                }
                catch(Exception $e)
                {
                    $error_msg = $e->getMessage();
                }
            }

            else if($post_type=="image_submit")
            {
                $image_list = explode(',', $image_url);

                if($media_type == 'instagram')
                {
                    // $image_list[0] = 'https://betterstudio.com/wp-content/uploads/2019/05/4-5-instagram-819x1024.jpg';
                    $response = $this->fb_rx_login->instagram_create_post($instagram_business_account_id,$type="IMAGE",$image_list[0],$message,$user_access_token);
                    if(isset($response['status']) && $response['status']=="error"){
                        $error_msg = $response['message'];
                    }
                }
                else
                {
                    if(count($image_list) == 1)
                    {                    
                        try
                        {
                            $response = $this->fb_rx_login->photo_post($message,$image_list[0],"",$use_access_token,$use_fb_id);
                        }
                        catch(Exception $e)
                        {
                            $error_msg = $e->getMessage();
                        }
                    }
                    else
                    {
                        $multi_image_post_response_array = array();
                        $attach_media_array = array();
                        foreach ($image_list as $key => $value) {
                            try
                            {
                                $response = $this->fb_rx_login->photo_post_for_multipost($message,$value,"",$use_access_token,$use_fb_id);
                                $attach_media_array['media_fbid'] = $response['id'];
                                $multi_image_post_response_array[] = $attach_media_array;
                            }
                            catch(Exception $e)
                            {
                                $error_msg = $e->getMessage();
                            }
                        }


                        try
                        {
                            $response = $this->fb_rx_login->multi_photo_post($message,$multi_image_post_response_array,"",$use_access_token,$use_fb_id);
                        }
                        catch(Exception $e)
                        {
                            $error_msg = $e->getMessage();
                        }
                    }
                }

            }

            else
            {
                if($media_type == 'instagram')
                {
                    // $video_url = 'https://xerochat.in/upload/video/new_intro.mp4';
                    $response = $this->fb_rx_login->instagram_create_post($instagram_business_account_id,$type="VIDEO",$video_url,$message,$user_access_token);
                    if(isset($response['status']) && $response['status']=="error"){
                        $error_msg = $response['message'];
                    }
                }
                else
                {
                    try
                    {
                        $response = $this->fb_rx_login->post_video($message,$video_title,$video_url,"",$video_thumb_url,"",$use_access_token,$use_fb_id);
                    }
                    catch(Exception $e)
                    {
                        $error_msg = $e->getMessage();
                    }
                }
            }

            if($post_type=="image_submit")
            {
                if($media_type == 'instagram')
                    $object_id = isset($response['id']) ? $response['id'] : '';
                else
                {
                    if(count($image_list) > 1)
                    $object_id=isset($response["id"]) ? $response["id"] : "";
                    else
                    $object_id=isset($response["post_id"]) ? $response["post_id"] : "";
                }
                
            }
            else $object_id=isset($response["id"]) ? $response["id"] : "";

            $temp_data=array();
            try
            {
                if($media_type == 'instagram')
                    $temp_data=$this->fb_rx_login->instagram_get_post_info_by_id($object_id,$user_access_token);
                else
                    $temp_data=$this->fb_rx_login->get_post_permalink($object_id,$use_access_token);
            }
            catch(Exception $e)
            {
                $error_msg1 = $e->getMessage();
            }

            if($media_type == 'instagram')
                $post_url= isset($temp_data["permalink"]) ? $temp_data["permalink"] : "";
            else
                $post_url= isset($temp_data["permalink_url"]) ? $temp_data["permalink_url"] : "";


            if($object_id=="" && $error_msg==""){
                $error_msg=json_encode($response); // added later by Konok to catch up the error in unknown situation 
            }

            $update_data = array("posting_status"=>'2',"full_complete"=>'1',"post_id"=>$object_id,"post_url"=>$post_url,"error_mesage"=>$error_msg,"last_updated_at"=>date("Y-m-d H:i:s"));

            $this->basic->update_data("facebook_rx_auto_post",array("id"=>$campaign_id),$update_data);



            if($info['ultrapost_auto_reply_table_id'] != 0)
            {

                //************************************************//
                $status=$this->_check_usage($module_id=204,$request=1);
                if($status!="2" && $status!="3") 
                {

                    $auto_reply_table_info = $this->basic->get_data('ultrapost_auto_reply',['where'=>['id' => $info['ultrapost_auto_reply_table_id'] ]]);

                    $facebook_page_info = $this->basic->get_data('facebook_rx_fb_page_info',['where' => ['id' => $info['page_group_user_id']]]);

                    $auto_reply_table_data = [];

                    foreach ($auto_reply_table_info as $single_auto_reply_table_info) {

                        foreach ($single_auto_reply_table_info as $auto_key => $auto_value) {
                            
                            if($auto_key == 'id')
                                continue;

                            if($auto_key == 'page_ids')
                                continue;

                            if($auto_key == 'ultrapost_campaign_name')
                                $auto_reply_table_data['auto_reply_campaign_name'] = $auto_value;
                            else
                                $auto_reply_table_data[$auto_key] = $auto_value;
                        }
                    }



                    $auto_reply_table_data['facebook_rx_fb_user_info_id'] = $fb_rx_fb_user_info_id;
                    $auto_reply_table_data['page_info_table_id'] = $facebook_page_info[0]['id'];
                    $auto_reply_table_data['page_name'] = $facebook_page_info[0]['page_name'];

                    if($post_type=="video_submit")
                        $auto_reply_table_data['post_id'] = $facebook_page_info[0]['page_id'].'_'.$object_id;
                    else
                        $auto_reply_table_data['post_id'] = $object_id;

                    $auto_reply_table_data['post_created_at'] = date("Y-m-d h:i:s");
                    $auto_reply_table_data['post_description'] = $message;
                    $auto_reply_table_data['auto_private_reply_status'] = '0';

                    $auto_reply_table_data['auto_private_reply_count'] = 0;
                    $auto_reply_table_data['last_updated_at'] = date("Y-m-d h:i:s");
                    $auto_reply_table_data['last_reply_time'] = '';
                    $auto_reply_table_data['error_message'] = '';
                    $auto_reply_table_data['hidden_comment_count'] = 0;
                    $auto_reply_table_data['deleted_comment_count'] = 0;
                    $auto_reply_table_data['auto_comment_reply_count'] = 0;

                    $this->basic->insert_data('facebook_ex_autoreply', $auto_reply_table_data);

                 
                    $this->_insert_usage_log($module_id=204,$request=1);                        
                 }
                //************************************************//
            }


            sleep(rand ( 1 , 6 ));

        }
    }

    // =====================OTHER FUNCTIONS===================
    public function membership_alert($api_key="") //membership_alert_delete_junk_data
    {
        // $this->api_key_check($api_key);    

        $free_package_info = $this->basic->get_data('package',['where'=>['price'=>'0','validity'=>'0','is_default'=>'1']]);
        $free_package_id = isset($free_package_info[0]['id']) ? $free_package_info[0]['id'] : 0;

        $current_date = date("Y-m-d");
        $tenth_day_before_expire = date("Y-m-d", strtotime("$current_date + 10 days"));
        $one_day_before_expire = date("Y-m-d", strtotime("$current_date + 1 days"));
        $one_day_after_expire = date("Y-m-d", strtotime("$current_date - 1 days"));


        //send notification to members before 10 days of expire date
        $where = array();
        $where['where'] = array(
            'user_type !=' => 'Admin',
            'expired_date' => $tenth_day_before_expire,
            'package_id !=' => $free_package_id
            );
        $info = array();
        $value = array();
        $info = $this->basic->get_data('users',$where,$select='');
        $from = $this->config->item('institute_email');
        $mask = $this->config->item('product_name');

        // getting email template info
        $email_template_info = $this->basic->get_data("email_template_management",array('where'=>array('template_type'=>'membership_expiration_10_days_before')),array('subject','message'));

        if(isset($email_template_info[0]) && $email_template_info[0]['subject'] !='' && $email_template_info[0]['message'] !='') {

            $subject = $email_template_info[0]['subject'];
            foreach ($info as $value) 
            {
                if(!$this->api_member_validity($value['id'])) continue;
                $url = base_url();

                $message = str_replace(array('#USERNAME#','#APP_URL#','#APP_NAME#'),array($value['name'],$url,$mask),$email_template_info[0]['message']);

                $to = $value['email'];
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }


        } else {

            $subject = "Payment Notification";
            foreach ($info as $value) 
            {
                if(!$this->api_member_validity($value['id'])) continue;
                $message = "Dear {$value['name']},<br/> your account will expire after 10 days, Please pay your fees.<br/><br/>Thank you,<br/><a href='".base_url()."'>{$mask}</a> team";
                $to = $value['email'];
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }

        }

        //send notificatio to members before 1 day of expire date
        $where = array();
        $where['where'] = array(
            'user_type !=' => 'Admin',
            'expired_date' => $one_day_before_expire,
            'package_id !=' => $free_package_id
            );
        $info = array();
        $value = array();
        $info = $this->basic->get_data('users',$where,$select='');
        $from = $this->config->item('institute_email');
        $mask = $this->config->item('product_name');

        // getting email template info
        $email_template_info_01 = $this->basic->get_data("email_template_management",array('where'=>array('template_type'=>'membership_expiration_1_day_before')),array('subject','message'));

        if(isset($email_template_info_01[0]) && $email_template_info_01[0]['subject'] != '' && $email_template_info_01[0]['message'] != '') {

            $subject = $email_template_info_01[0]['subject'];
            foreach ($info as $value) {
                if(!$this->api_member_validity($value['id'])) continue;
                $url = base_url();
                $message = str_replace(array('#USERNAME#','#APP_URL#','#APP_NAME#'),array($value['name'],$url,$mask),$email_template_info_01[0]['message']);

                $to = $value['email'];
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }

        }
        else {

            $subject = "Payment Notification";
            foreach ($info as $value) {
                if(!$this->api_member_validity($value['id'])) continue;
                $message = "Dear {$value['name']},<br/> your account will expire tomorrow, Please pay your fees.<br/><br/>Thank you,<br/><a href='".base_url()."'>{$mask}</a> team";
                $to = $value['email'];
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }

        }
        

        //send notificatio to members after 1 day of expire date
        $where = array();
        $where['where'] = array(
            'user_type !=' => 'Admin',
            'expired_date' => $one_day_after_expire,
            'package_id !=' => $free_package_id
            );
        $info = array();
        $value = array();
        $info = $this->basic->get_data('users',$where,$select='');
        $from = $this->config->item('institute_email');
        $mask = $this->config->item('product_name');

        $email_template_info_02 = $this->basic->get_data("email_template_management",array('where'=>array('template_type'=>'membership_expiration_1_day_after')),array('subject','message'));

        if(isset($email_template_info_02[0]) && $email_template_info_02[0]['subject'] != '' && $email_template_info_02[0]['message'] != '') {

            $subject = $email_template_info_02[0]['subject'];

            foreach ($info as $value) {
                if(!$this->api_member_validity($value['id'])) continue;
                $url = base_url();
                $message = str_replace(array('#USERNAME#','#APP_URL#','#APP_NAME#'),array($value['name'],$url,$mask),$email_template_info_02[0]['message']);
                $to = $value['email'];
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }

        } else {

            $subject = "Payment Notification";
            foreach ($info as $value) {
                if(!$this->api_member_validity($value['id'])) continue;
                $message = "Dear {$value['name']},<br/> your account has been expired, Please pay your fees for continuity.<br/><br/>Thank you,<br/><a href='".base_url()."'>{$mask}</a> team";
                $to = $value['email'];
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }
        }        

    }
    public function delete_junk_data($api_key="") //membership_alert_delete_junk_data
    {
        // $this->api_key_check($api_key);

        $delete_junk_data_after_how_many_days = $this->config->item("delete_junk_data_after_how_many_days");
        if($delete_junk_data_after_how_many_days=="") $delete_junk_data_after_how_many_days = 30;

        $cur_time=date('Y-m-d H:i:s');
        $last_time=date("Y-m-d H:i:s",strtotime($cur_time." -".$delete_junk_data_after_how_many_days." day"));
       
       /****Clean Cache Directory , keep all files of last 24 hours******/
       $all_cache_file=$this->delete_cache('application/cache');

       //Delete error log file in root
       unlink("error_log");

    }
    protected function delete_cache($myDir) //delete_junk_data
    {

        $cur_time=date('Y-m-d H:i:s');
        $yesterday=date("Y-m-d H:i:s",strtotime($cur_time." -2 day"));
        $yesterday=strtotime($yesterday);


        $dirTree = array();
        $di = new RecursiveDirectoryIterator($myDir,RecursiveDirectoryIterator::SKIP_DOTS);
        
        foreach (new RecursiveIteratorIterator($di) as $filename) {
        
        $dir = str_replace($myDir, '', dirname($filename));
        
        $org_dir=str_replace("\\", "/", $dir);
        
        
        if($org_dir)
        $file_path = $org_dir. "/". basename($filename);
        else
        $file_path = basename($filename);

        $path_explode = explode(".",$file_path);
        $extension= array_pop($path_explode);

        if($file_path!='.htaccess' && $file_path!='index.html'){

             $full_file_path=$myDir."/".$file_path;

             $file_creation_time=filemtime($full_file_path);
             $file_creation_time=date('Y-m-d H:i:s',$file_creation_time); //convert unix time to system time zone 
             $file_creation_time=strtotime($file_creation_time);


             if($file_creation_time<$yesterday){
                $dirTree[] = trim($file_path,"/");
                unlink($full_file_path);

             }
                
        }

        
        }
        
        return $dirTree;
            
    }
    // =====================OTHER FUNCTIONS===================


    // 5 mins
    public function auto_comment_on_post($api_key='')
    {        
        $link=base_url().'cron_job/auto_comment_on_post_orginal/'.$api_key;
        $this->call_curl_internal_cronjob($link);  
    }

    //1 day
    public function membership_alert_delete_junk_data($api_key="")
    {

    	$link=base_url().'cron_job/membership_alert/'.$api_key;
    	$this->call_curl_internal_cronjob($link);

        $link=base_url().'cron_job/expired_users_disable_bot/'.$api_key;
        $this->call_curl_internal_cronjob($link);

    	$link=base_url().'cron_job/delete_junk_data/'.$api_key;
    	$this->call_curl_internal_cronjob($link);
    }

    // 5 min
    public function publish_post($api_key="")
    {   	
    	
    	$link=base_url().'cron_job/text_image_link_video_post/'.$api_key;
    	$this->call_curl_internal_cronjob($link);
    }

    protected function call_curl_internal_cronjob($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 6); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        echo $reply_response=curl_exec($ch); 
    }


    public function expired_users_disable_bot($api_key='')
    {
        // $this->api_key_check($api_key);
        $current_date = date("Y-m-d H:i:s",strtotime("-2 day"));
        $free_package_info = $this->basic->get_data('package',['where'=>['price'=>'0','validity'=>'0','is_default'=>'1']]);
        $free_package_id = isset($free_package_info[0]['id']) ? $free_package_info[0]['id'] : 0;
        $user_info = $this->basic->get_data('users',array('where'=>array('user_type !='=>'Admin','deleted'=>'0','expired_date <='=>$current_date,'bot_status'=>'1','package_id !='=>$free_package_id)),array('id'));

        foreach($user_info as $value)
        {
            $fb_page_infos = $this->basic->get_data('facebook_rx_fb_page_info',array('where'=>array('user_id'=>$value['id'])),array('id'));
            foreach($fb_page_infos as $value2)
            {
                $this->disable_bot_basedon_pages($value2['id']);
            }
            $this->basic->update_data('users',array('id'=>$value['id']),array('bot_status'=>'0'));
        }
    }

    private function disable_bot_basedon_pages($table_id=0)
    {
        $page_information = $this->basic->get_data('facebook_rx_fb_page_info',array('where'=>array('id'=>$table_id)));

        if(!empty($page_information))
        {
            $fb_page_id=isset($page_information[0]["page_id"]) ? $page_information[0]["page_id"] : "";
            $page_access_token=isset($page_information[0]["page_access_token"]) ? $page_information[0]["page_access_token"] : "";
            $persistent_enabled=isset($page_information[0]["persistent_enabled"]) ? $page_information[0]["persistent_enabled"] : "0";
            $bot_enabled=isset($page_information[0]["bot_enabled"]) ? $page_information[0]["bot_enabled"] : "0";
            $started_button_enabled=isset($page_information[0]["started_button_enabled"]) ? $page_information[0]["started_button_enabled"] : "0";
            $ice_breaker_status=isset($page_information[0]["ice_breaker_status"]) ? $page_information[0]["ice_breaker_status"] : "0";
            $fb_user_id = $page_information[0]["facebook_rx_fb_user_info_id"];
            $fb_user_info = $this->basic->get_data('facebook_rx_fb_user_info',array('where'=>array('id'=>$fb_user_id)));
            $this->load->library('Fb_rx_login');
            $this->fb_rx_login->app_initialize($fb_user_info[0]['facebook_rx_config_id']);           
            if($bot_enabled == '1') 
            {
            	$this->fb_rx_login->disable_bot($fb_page_id,$page_access_token);
	            $this->basic->update_data('facebook_rx_fb_page_info',array('id'=>$table_id),array('bot_enabled'=>'2'));            	
            }
        }
    }



}