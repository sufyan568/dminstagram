<?php

require_once("Home.php"); // loading home controller

class Social_accounts extends Home
{ 
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');   
        
        if($this->session->userdata('user_type') != 'Admin' && !in_array(65,$this->module_access))
        redirect('home/login_page', 'location'); 

        if($this->session->userdata("facebook_rx_fb_user_info")==0 && $this->config->item("backup_mode")==1 && $this->uri->segment(2)!="app_delete_action")
        redirect('social_apps/index','refresh');

        $this->important_feature();
        $this->member_validity();
        
        $this->load->library("fb_rx_login");       
    }


    public function index()
    {
      $this->account_import();
    }
  
    public function account_import()
    {
        $this->is_group_posting_exist=$this->group_posting_exist();
        $data['body'] = 'facebook_rx/account_import';
        $data['page_title'] = $this->lang->line('Facebook Account Import');

        $redirect_url = base_url()."social_accounts/manual_renew_account";
        $fb_login_button = $this->fb_rx_login->login_for_user_access_token($redirect_url);
        $data['fb_login_button'] = $fb_login_button;

        $where['where'] = array('user_id'=>$this->user_id);
        $existing_accounts = $this->basic->get_data('facebook_rx_fb_user_info',$where);

        $show_import_account_box = 1;
        $data['show_import_account_box'] = 1;
        if(!empty($existing_accounts))
        {
            $i=0;
            foreach($existing_accounts as $value)
            {
                $existing_account_info[$i]['need_to_delete'] = $value['need_to_delete'];
                if($value['need_to_delete'] == '1')
                {
                   $show_import_account_box = 0; 
                   $data['show_import_account_box'] = $show_import_account_box;
                }

                $existing_account_info[$i]['fb_id'] = $value['fb_id'];
                $existing_account_info[$i]['userinfo_table_id'] = $value['id'];
                $existing_account_info[$i]['name'] = $value['name'];
                $existing_account_info[$i]['email'] = $value['email'];
                $existing_account_info[$i]['user_access_token'] = $value['access_token'];

                $valid_or_invalid = $this->fb_rx_login->access_token_validity_check_for_user($value['access_token']);
                if($valid_or_invalid)
                {
                    $existing_account_info[$i]['validity'] = 'yes';
                }
                else{
                    $existing_account_info[$i]['validity'] = 'no';
                }


                $where = array();
                $where['where'] = array('facebook_rx_fb_user_info_id'=>$value['id']);
                $page_count = $this->basic->get_data('facebook_rx_fb_page_info',$where,'','','','','has_instagram DESC');
                $existing_account_info[$i]['page_list'] = $page_count;
                if(!empty($page_count))
                {
                    $existing_account_info[$i]['total_pages'] = count($page_count);                    
                }
                else
                    $existing_account_info[$i]['total_pages'] = 0;


                $group_count = $this->basic->get_data('facebook_rx_fb_group_info',$where);
                $existing_account_info[$i]['group_list'] = $group_count;
                if(!empty($group_count))
                {
                    $existing_account_info[$i]['total_groups'] = count($group_count);                    
                }
                else
                    $existing_account_info[$i]['total_groups'] = 0;
                
                $i++;
            }

            $data['existing_accounts'] = $existing_account_info;
        }
        else
            $data['existing_accounts'] = '0';


        $this->_viewcontroller($data);
    }



    public function group_delete_action()
    {
        if($this->is_demo == '1')
        {
            if($this->session->userdata('user_type') == "Admin")
            {
                $response['status'] = 0;
                $response['message'] = "You can not delete anything from admin account!!";
                echo json_encode($response);
                exit();
            }
        }


        $table_id = $this->input->post("group_table_id");
        $data = array('deleted' => '1');
        $this->basic->delete_data('facebook_rx_fb_group_info',array('id'=>$table_id,'user_id'=>$this->user_id));
        echo json_encode(array('status'=>1,'message'=>$this->lang->line('Group has been deleted successfully.')));
    }


    public function page_delete_action()
    {
        $this->ajax_check();
        $response = array();
        if($this->is_demo == '1')
        {
            if($this->session->userdata('user_type') == "Admin")
            {
                $response['status'] = 0;
                $response['message'] = "You can not delete anything from admin account!!";
                echo json_encode($response);
                exit();
            }
        }

        $table_id = $this->input->post("page_table_id",true);
        $response = $this->delete_data_basedon_page($table_id);
        echo $response;

    }

    public function account_delete_action()
    {
        $response = array();
        if($this->is_demo == '1')
        {
            if($this->session->userdata('user_type') == "Admin")
            {
                $response['status'] = 0;
                $response['message'] = "You can't delete anything from admin account!!";
                echo json_encode($response);
                exit();
            }
        }
        
        $facebook_rx_fb_user_info_id = $this->input->post("user_table_id");

        $account_information = $this->basic->get_data('facebook_rx_fb_user_info',array('where'=>array('id'=>$facebook_rx_fb_user_info_id,'user_id'=>$this->user_id)));
        if(empty($account_information)){
            echo json_encode(array('success'=>0,'message'=>$this->lang->line("Account is not found for this user. Something is wrong.")));
            exit();
        }


        $page_list = $this->basic->get_data('facebook_rx_fb_page_info',array('where'=>array('facebook_rx_fb_user_info_id'=>$facebook_rx_fb_user_info_id)),array('id','page_id'));

        foreach($page_list as $value)
        {
        	$this->delete_data_basedon_page($value['id']);
        }

        $response = $this->delete_data_basedon_account($facebook_rx_fb_user_info_id);
        
        echo json_encode($response);
        
    }



    public function app_delete_action()
    {
     if($this->is_demo == '1')
      {
          if($this->session->userdata('user_type') == "Admin")
          {
              $response['status'] = 0;
              $response['message'] = "You can not delete anything from admin account!!";
              echo json_encode($response);
              exit();
          }
      }

      $this->ajax_check();
      $this->csrf_token_check();
      $app_table_id = $this->input->post('app_table_id',true);
      $app_info = $this->basic->get_data('facebook_rx_config',array('where'=>array('id'=>$app_table_id,'user_id'=>$this->user_id)));
      if(empty($app_info))
      {
        $response['status'] = 0;
        $response['message'] = $this->lang->line('We could not find any APP with this ID for this account.');  
        echo json_encode($response);
        exit;
      }

      $fb_user_infos = $this->basic->get_data('facebook_rx_fb_user_info',array('where'=>array('facebook_rx_config_id'=>$app_table_id)),array('id'));
      foreach($fb_user_infos as $value)
      {
        $fb_page_infos = $this->basic->get_data('facebook_rx_fb_page_info',array('where'=>array('facebook_rx_fb_user_info_id'=>$value['id'])),array('id'));
        foreach($fb_page_infos as $value2)
          $this->delete_data_basedon_page($value2['id'],'1');

        $this->delete_data_basedon_account($value['id'],'1');
      }

      $this->basic->delete_data('facebook_rx_config',array('id'=>$app_table_id,'user_id'=>$this->user_id));
      $this->session->sess_destroy(); 
      $response['status'] = 1;
      $response['message'] = $this->lang->line("APP and all the data corresponding to this APP has been deleted successfully. Now you'll be redirected to the login page.");  
      echo json_encode($response);
    }



    public function enable_disable_webhook()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(200,$this->module_access))
        exit();
        if(!$_POST) exit();

        $response = array();
        if($this->is_demo == '1')
        {
            if($this->session->userdata('user_type') == "Admin")
            {
                $response['status'] = 0;
                $response['message'] = "This function is disabled from admin account in this demo!!";
                echo json_encode($response);
                exit();
            }
        }

        $user_id = $this->user_id;
        $page_id=$this->input->post('page_id');
        $restart=$this->input->post('restart');
        $enable_disable=$this->input->post('enable_disable');
        $page_data=$this->basic->get_data("facebook_rx_fb_page_info",array("where"=>array("id"=>$page_id,"user_id"=>$this->user_id)));

        if(empty($page_data)){

            echo json_encode(array('success'=>0,'message'=>$this->lang->line("Page is not found for this user. Something is wrong.")));
            exit();
        }


        $fb_page_id=isset($page_data[0]["page_id"]) ? $page_data[0]["page_id"] : "";
        $page_access_token=isset($page_data[0]["page_access_token"]) ? $page_data[0]["page_access_token"] : "";
        $persistent_enabled=isset($page_data[0]["persistent_enabled"]) ? $page_data[0]["persistent_enabled"] : "0";
        $fb_user_id = $page_data[0]["facebook_rx_fb_user_info_id"];
        $fb_user_info = $this->basic->get_data('facebook_rx_fb_user_info',array('where'=>array('id'=>$fb_user_id)));
        $this->fb_rx_login->app_initialize($fb_user_info[0]['facebook_rx_config_id']); 
        if($enable_disable=='enable')
        {
            $already_enabled = $this->basic->get_data('facebook_rx_fb_page_info',array('where'=>array('page_id'=>$fb_page_id,'bot_enabled !='=>'0')));
            if(!empty($already_enabled))
            {                
                if($already_enabled[0]['user_id'] != $this->user_id || $already_enabled[0]['facebook_rx_fb_user_info_id'] != $fb_user_id )
                {
                    $facebook_user_info = $this->basic->get_data('facebook_rx_fb_user_info',array('where'=>array('id'=>$already_enabled[0]['facebook_rx_fb_user_info_id'])));
                    $facebook_user_name = isset($facebook_user_info[0]['name']) ? $facebook_user_info[0]['name'] : '';
                    $system_user_info = $this->basic->get_data('users',array('where'=>array('id'=>$already_enabled[0]['user_id'])));
                    $system_email = isset($system_user_info[0]['email']) ? $system_user_info[0]['email'] : '';
                    $response_message = $this->lang->line("This page is already enabled by other user.").'<br/>';
                    $response_message .= $this->lang->line('Enabled from').':<br/>';
                    $response_message .= $this->lang->line('Email').': '.$system_email.'<br/>';
                    $response_message .= $this->lang->line('FB account name').': '.$facebook_user_name;
                    echo json_encode(array('success'=>0,'message'=>$response_message));
                    exit();
                }
            }
            //************************************************//
            if($restart != '1')
            {                
                $status=$this->_check_usage($module_id=200,$request=1);
                if($status=="2") 
                {
                    echo json_encode(array('success'=>0,'message'=>$this->lang->line("Module limit is over.")));
                    exit();
                }
                else if($status=="3") 
                {
                    echo json_encode(array('success'=>0,'message'=>$this->lang->line("Module limit is over.")));
                    exit();
                }
            }
            //************************************************//

            $output=$this->fb_rx_login->enable_bot($fb_page_id,$page_access_token);
            if(!isset($output['error'])) $output['error'] = '';

            if($output['error'] == '')
            {
                $this->basic->update_data("facebook_rx_fb_page_info",array("id"=>$page_id),array("bot_enabled"=>"1"));                
                if($restart != '1')  $this->_insert_usage_log($module_id=200,$request=1);
                $response['status'] = 1; 
                $response['message'] = $this->lang->line('Bot Connection has been enabled successfully.');              
            }
            else
            {
                $response['status'] = 0; 
                $response['message'] = $output['error'];
            }
        } 
        else
        {
            $updateData=array("bot_enabled"=>"2");            
            $output=$this->fb_rx_login->disable_bot($fb_page_id,$page_access_token);
            if(!isset($output['error'])) $output['error'] = '';
            if($output['error'] == '')
            {
                $this->basic->update_data("facebook_rx_fb_page_info",array("id"=>$page_id),$updateData);
                $response['status'] = 1; 
                $response['message'] = $this->lang->line('Bot Connection has been disabled successfully.');
            }
            else
            {
                $response['status'] = 0; 
                $response['message'] = $output['error'];
            }
        } 
        echo json_encode($response);
    }

    public function enable_disable_insta_autoreply()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(207,$this->module_access))
        exit();
        $this->ajax_check();

        $response = array();
        if($this->is_demo == '1')
        {
            if($this->session->userdata('user_type') == "Admin")
            {
                $response['status'] = 0;
                $response['message'] = "This function is disabled from admin account in this demo!!";
                echo json_encode($response);
                exit();
            }
        }

        $user_id = $this->user_id;
        $table_id=$this->input->post('table_id');
        $restart=$this->input->post('restart');
        $enable_disable=$this->input->post('enable_disable');
        $page_data=$this->basic->get_data("instagram_reply_page_info",array("where"=>array("id"=>$table_id,"user_id"=>$this->user_id)));

        if(empty($page_data)){

            echo json_encode(array('success'=>0,'message'=>$this->lang->line("Page is not found for this user. Something is wrong.")));
            exit();
        }


        $fb_page_id=isset($page_data[0]["page_id"]) ? $page_data[0]["page_id"] : "";
        $instagram_business_account_id = isset($page_data[0]["instagram_business_account_id"]) ? $page_data[0]["instagram_business_account_id"] : "";
        $page_access_token=isset($page_data[0]["page_access_token"]) ? $page_data[0]["page_access_token"] : "";
        $fb_user_id = $page_data[0]["facebook_rx_fb_user_info_id"];
        $fb_user_info = $this->basic->get_data('facebook_rx_fb_user_info',array('where'=>array('id'=>$fb_user_id)));
        $this->fb_rx_login->app_initialize($fb_user_info[0]['facebook_rx_config_id']); 
        if($enable_disable=='enable')
        {
            $already_enabled = $this->basic->get_data('instagram_reply_page_info',array('where'=>array('page_id'=>$fb_page_id,'instagram_business_account_id'=>$instagram_business_account_id,'bot_enabled !='=>'0')));
            if(!empty($already_enabled))
            {                
                if($already_enabled[0]['user_id'] != $this->user_id || $already_enabled[0]['facebook_rx_fb_user_info_id'] != $fb_user_id )
                {
                    $facebook_user_info = $this->basic->get_data('facebook_rx_fb_user_info',array('where'=>array('id'=>$already_enabled[0]['facebook_rx_fb_user_info_id'])));
                    $facebook_user_name = isset($facebook_user_info[0]['name']) ? $facebook_user_info[0]['name'] : '';
                    $system_user_info = $this->basic->get_data('users',array('where'=>array('id'=>$already_enabled[0]['user_id'])));
                    $system_email = isset($system_user_info[0]['email']) ? $system_user_info[0]['email'] : '';
                    $response_message = $this->lang->line("This account is already enabled for auto-reply by other user.").'<br/>';
                    $response_message .= $this->lang->line('Enabled from').':<br/>';
                    $response_message .= $this->lang->line('Email').': '.$system_email.'<br/>';
                    $response_message .= $this->lang->line('FB account name').': '.$facebook_user_name;
                    echo json_encode(array('success'=>0,'message'=>$response_message));
                    exit();
                }
            }
            //************************************************//
            if($restart != '1')
            {                
                $status=$this->_check_usage($module_id=207,$request=1);
                if($status=="2") 
                {
                    echo json_encode(array('success'=>0,'message'=>$this->lang->line("Module limit is over.")));
                    exit();
                }
                else if($status=="3") 
                {
                    echo json_encode(array('success'=>0,'message'=>$this->lang->line("Module limit is over.")));
                    exit();
                }
            }
            //************************************************//

            $output=$this->fb_rx_login->enable_bot($fb_page_id,$page_access_token);
            if(!isset($output['error'])) $output['error'] = '';

            if($output['error'] == '')
            {
                $this->basic->update_data("instagram_reply_page_info",array("id"=>$table_id,'user_id'=>$this->user_id),array("bot_enabled"=>"1"));
                if($restart != '1')                    
                    $this->_insert_usage_log($module_id=207,$request=1);
                $response['status'] = 1; 
                $response['message'] = $this->lang->line('Auto Reply has been enabled successfully.');              
            }
            else
            {
                $response['status'] = 0; 
                $response['message'] = $output['error'];
            }
        } 
        else
        {
            $updateData=array("bot_enabled"=>"2");
            $output=$this->fb_rx_login->disable_bot($fb_page_id,$page_access_token);

            if(!isset($output['error'])) $output['error'] = '';
            if($output['error'] == '')
            {
                $this->basic->update_data("instagram_reply_page_info",array("id"=>$table_id,'user_id'=>$this->user_id),$updateData);
                $response['status'] = 1; 
                $response['message'] = $this->lang->line('Auto Reply has been disabled successfully.');
            }
            else
            {
                $response['status'] = 0; 
                $response['message'] = $output['error'];
            }
        } 
        echo json_encode($response);
    }



    public function delete_full_bot()
    {
        $this->ajax_check();
        if($this->session->userdata('user_type') != 'Admin' && !in_array(200,$this->module_access)) exit();

        $response = array();
        if($this->is_demo == '1')
        {
            if($this->session->userdata('user_type') == "Admin")
            {
                $response['status'] = 0;
                $response['message'] = "This function is disabled from admin account in this demo!!";
                echo json_encode($response);
                exit();
            }
        }

        $user_id = $this->user_id;
        $page_id=$this->input->post('page_id');
        $already_disabled=$this->input->post('already_disabled');       

        $page_data=$this->basic->get_data("facebook_rx_fb_page_info",array("where"=>array("id"=>$page_id,"user_id"=>$this->user_id)));

        if(empty($page_data)){
            echo json_encode(array('success'=>0,'message'=>$this->lang->line("Page is not found for this user. Something is wrong.")));
            exit();
        }

        $fb_page_id=isset($page_data[0]["page_id"]) ? $page_data[0]["page_id"] : "";
        $page_access_token=isset($page_data[0]["page_access_token"]) ? $page_data[0]["page_access_token"] : "";
        $persistent_enabled=isset($page_data[0]["persistent_enabled"]) ? $page_data[0]["persistent_enabled"] : "0";
        $ice_breaker_status=isset($page_data[0]["ice_breaker_status"]) ? $page_data[0]["ice_breaker_status"] : "0";
        $fb_user_id = $page_data[0]["facebook_rx_fb_user_info_id"];
        $fb_user_info = $this->basic->get_data('facebook_rx_fb_user_info',array('where'=>array('id'=>$fb_user_id)));
        $this->fb_rx_login->app_initialize($fb_user_info[0]['facebook_rx_config_id']);

        $updateData=array("bot_enabled"=>"0");
        if($already_disabled == 'no')
        {   
            $response=$this->fb_rx_login->disable_bot($fb_page_id,$page_access_token);
        }
        $this->basic->update_data("facebook_rx_fb_page_info",array("id"=>$page_id),$updateData);
        $this->_delete_usage_log($module_id=200,$request=1);

        $this->delete_bot_data($page_id,$fb_page_id);

        $response['status'] = 1;
        $response['message'] = $this->lang->line("Bot Connection and all of the settings and campaigns of this page has been deleted successfully.");

        echo json_encode($response);

    }


    private function delete_bot_data($page_id,$fb_page_id)
    {

        $table_id = $page_id;
        $page_information = $this->basic->get_data('facebook_rx_fb_page_info',array('where'=>array('id'=>$table_id,'user_id'=>$this->user_id)));

        $table_names=$this->table_names_array();

        foreach($table_names as $value)
        {
          if(isset($value['has_dependent_table']) && $value['has_dependent_table'] == 'yes')
          {
            $table_ids_array = array();   
            if($this->db->table_exists($value['table_name']))
            {
              if(isset($value['is_facebook_page_id']) && $value['is_facebook_page_id'] == 'yes')
              {
                $facebook_page_id = $page_information[0]['page_id']; 
                $table_ids_info = $this->basic->get_data($value['table_name'],array('where'=>array("{$value['column_name']}"=>$facebook_page_id)),'id');
              }
              else
                $table_ids_info = $this->basic->get_data($value['table_name'],array('where'=>array("{$value['column_name']}"=>$table_id)),'id');

            }    
            else continue;

            foreach($table_ids_info as $info)
              array_push($table_ids_array, $info['id']);

            if($this->db->table_exists($value['table_name']))
            {
              if(isset($value['is_facebook_page_id']) && $value['is_facebook_page_id'] == 'yes')
                $this->basic->delete_data($value['table_name'],array("{$value['column_name']}"=>$facebook_page_id));
              else
                $this->basic->delete_data($value['table_name'],array("{$value['column_name']}"=>$table_id));
            }

            $dependent_table_names = explode(',', $value['dependent_tables']);
            $dependent_table_column = explode(',', $value['dependent_table_column']);
            if(!empty($table_ids_array) && !empty($dependent_table_names))
            {            
              for($i=0;$i<count($dependent_table_names);$i++)
              {
                if($this->db->table_exists($dependent_table_names[$i]))
                {
                  $this->db->where_in($dependent_table_column[$i], $table_ids_array);
                  $this->db->delete($dependent_table_names[$i]);
                }
              }
            }
          }
          else if(isset($value['comma_separated']) && $value['comma_separated'] == 'yes')
          {
            $str = "FIND_IN_SET('".$table_id."', ".$value['column_name'].") !=";
            $where = array($str=>0);
            if($this->db->table_exists($value['table_name']))
              $this->basic->delete_data($value['table_name'],$where);
          }
          else
          {
            if($this->db->table_exists($value['table_name']))
              $this->basic->delete_data($value['table_name'],array("{$value['column_name']}"=>$table_id));
          }
        }

        return true;
    } 



    public function manual_renew_account()
    {
        $this->is_group_posting_exist=$this->group_posting_exist();
        $id = $this->session->userdata('fb_rx_login_database_id');
        $redirect_url = base_url()."social_accounts/manual_renew_account";

        $user_info = array();
        $user_info = $this->fb_rx_login->login_callback_without_email($redirect_url);   
                
        if( isset($user_info['status']) && $user_info['status'] == '0')
        {
            $data['error'] = 1;
            $data['message'] = $this->lang->line("something went wrong in profile access")." : ".$user_info['message'];
            $data['body'] = "facebook_rx/user_login";
            $this->_viewcontroller($data);
        } 
        else 
        {
            $access_token=$user_info['access_token_set'];

            //checking permission given by the users            
            $permission = $this->fb_rx_login->debug_access_token($access_token);

            $given_permission = array();
            if(isset($permission['data']['scopes']))
            {
                $permission_checking = array();
                $needed_permission = array('instagram_content_publish','instagram_manage_comments','instagram_basic');
                $given_permission = $permission['data']['scopes'];
                $permission_checking = array_intersect($needed_permission,$given_permission);
                if(empty($permission_checking))
                {
                    $documentation_link = base_url('documentation/#!/sm_import_account');
                    $text = $this->lang->line("All needed permissions are not approved for your app")." [".implode(',', $needed_permission)."]";
                    $this->session->set_userdata('limit_cross', $text);
                    redirect('social_accounts/index','location');                
                    exit();
                }
            }
            
            if(isset($access_token))
            {
                $data = array(
                    'user_id' => $this->user_id,
                    'facebook_rx_config_id' => $id,
                    'access_token' => $access_token,
                    'name' => $user_info['name'],
                    'fb_id' => $user_info['id'],
                    'add_date' => date('Y-m-d'),
                    'deleted' => '0'
                    );

                $where=array();
                $where['where'] = array('user_id'=>$this->user_id,'fb_id'=>$user_info['id']);
                $exist_or_not = array();
                $exist_or_not = $this->basic->get_data('facebook_rx_fb_user_info',$where,$select='',$join='',$limit='',$start=NULL,$order_by='',$group_by='',$num_rows=0,$csv='',$delete_overwrite=1);

                if(empty($exist_or_not))
                {
                    //************************************************//
                    $status=$this->_check_usage($module_id=65,$request=1);
                    if($status=="2") 
                    {
                        $this->session->set_userdata('limit_cross', $this->lang->line("Module limit is over."));
                        redirect('social_accounts/index','location');                
                        exit();
                    }
                    else if($status=="3") 
                    {
                        $this->session->set_userdata('limit_cross', $this->lang->line("Module limit is over."));
                        redirect('social_accounts/index','location');                
                        exit();
                    }
                    //************************************************//
                    $this->basic->insert_data('facebook_rx_fb_user_info',$data);
                    $facebook_table_id = $this->db->insert_id();

                    //insert data to useges log table
                    $this->_insert_usage_log($module_id=65,$request=1);
                }
                else
                {
                    $facebook_table_id = $exist_or_not[0]['id'];
                    $where = array('user_id'=>$this->user_id,'id'=>$facebook_table_id);
                    $this->basic->update_data('facebook_rx_fb_user_info',$where,$data);
                }

                $this->session->set_userdata("facebook_rx_fb_user_info",$facebook_table_id);  

                $page_list = array();
                $page_list = $this->fb_rx_login->get_page_list($access_token);

                if(isset($page_list['error']) && $page_list['error'] == '1')
                {
                    $data['error'] = 1;
                    $data['message'] = $this->lang->line("Something went wrong in page access")." : ".$page_list['message'];
                    $data['body'] = "facebook_rx/user_login";
                    return $this->_viewcontroller($data);                    
                }

                if(!empty($page_list))
                {
                    foreach($page_list as $page)
                    {
                        $user_id = $this->user_id;
                        $page_id = $page['id'];
                        $page_cover = '';
                        if(isset($page['cover']['source'])) $page_cover = $page['cover']['source'];
                        $page_profile = '';
                        if(isset($page['picture']['url'])) $page_profile = $page['picture']['url'];
                        $page_name = '';
                        if(isset($page['name'])) $page_name = $page['name'];
                        $page_access_token = '';
                        if(isset($page['access_token'])) $page_access_token = $page['access_token'];
                        $page_email = '';
                        if(isset($page['emails'][0])) $page_email = $page['emails'][0];
                        $page_username = '';
                        if(isset($page['username'])) $page_username = $page['username'];

                        $data = array(
                            'user_id' => $user_id,
                            'facebook_rx_fb_user_info_id' => $facebook_table_id,
                            'page_id' => $page_id,
                            'page_cover' => $page_cover,
                            'page_profile' => $page_profile,
                            'page_name' => $page_name,
                            'username' => $page_username,
                            'page_access_token' => $page_access_token,
                            'page_email' => $page_email,
                            'add_date' => date('Y-m-d'),
                            'deleted' => '0'
                            );

                        // instagram section
                        $instagram_account_exist_or_not = '';
                        if($this->config->item('instagram_reply_enable_disable') == '1')
	                      //  $instagram_account_exist_or_not = $this->fb_rx_login->instagram_account_check_by_id($page['id'], $page['access_token']);
                            $instagram_account_exist_or_not = $this->fb_rx_login->instagram_account_check_by_id($page['id'], $access_token);
                        
                        if ($instagram_account_exist_or_not != "") {
                         //  $instagram_account_info = $this->fb_rx_login->instagram_account_info($instagram_account_exist_or_not, $access_token); 
                            $instagram_account_info = $this->fb_rx_login->instagram_account_info($instagram_account_exist_or_not, $access_token); 

                            $data['has_instagram'] = '1';
                            $data['instagram_business_account_id'] = $instagram_account_exist_or_not; 
                            $data['insta_username'] = isset($instagram_account_info['username']) ? $instagram_account_info['username'] : "";
                            $data['insta_followers_count'] = isset($instagram_account_info['followers_count']) ? $instagram_account_info['followers_count'] : "";
                            $data['insta_media_count'] = isset($instagram_account_info['media_count']) ? $instagram_account_info['media_count'] : "";
                            $data['insta_website'] = isset($instagram_account_info['website']) ? $instagram_account_info['website'] : "";
                            $data['insta_biography'] = isset($instagram_account_info['biography']) ? $instagram_account_info['biography'] : "";
                        }
                        // end of instagram section

                        $where=array();
                        $where['where'] = array('facebook_rx_fb_user_info_id'=>$facebook_table_id,'page_id'=>$page['id']);
                        $exist_or_not = array();
                        $exist_or_not = $this->basic->get_data('facebook_rx_fb_page_info',$where,$select='',$join='',$limit='',$start=NULL,$order_by='',$group_by='',$num_rows=0,$csv='',$delete_overwrite=1);

                        if(empty($exist_or_not))
                        {
                            $this->basic->insert_data('facebook_rx_fb_page_info',$data);
                        }
                        else
                        {
                            $where = array('facebook_rx_fb_user_info_id'=>$facebook_table_id,'page_id'=>$page['id']);
                            $this->basic->update_data('facebook_rx_fb_page_info',$where,$data);
                        }


                    }
                }

                $group_list = array();
                if($this->config->item('facebook_poster_group_enable_disable') == '1' && $this->is_group_posting_exist)
                    $group_list = $this->fb_rx_login->get_group_list($access_token);


                if(!empty($group_list))
                {
                    foreach($group_list as $group)
                    {
                        $user_id = $this->user_id;
                        $group_access_token = $access_token; // group uses user access token
                        $group_id = $group['id'];
                        $group_cover = '';
                        if(isset($group['cover']['source'])) $group_cover = $group['cover']['source'];
                        $group_profile = '';
                        if(isset($group['picture']['url'])) $group_profile = $group['picture']['url'];
                        $group_name = '';
                        if(isset($group['name'])) $group_name = $group['name'];

                        $data = array(
                            'user_id' => $user_id,
                            'facebook_rx_fb_user_info_id' => $facebook_table_id,
                            'group_id' => $group_id,
                            'group_cover' => $group_cover,
                            'group_profile' => $group_profile,
                            'group_name' => $group_name,
                            'group_access_token' => $group_access_token,
                            'add_date' => date('Y-m-d'),
                            'deleted' => '0'
                            );

                        $where=array();
                        $where['where'] = array('facebook_rx_fb_user_info_id'=>$facebook_table_id,'group_id'=>$group['id']);
                        $exist_or_not = array();
                        $exist_or_not = $this->basic->get_data('facebook_rx_fb_group_info',$where,$select='',$join='',$limit='',$start=NULL,$order_by='',$group_by='',$num_rows=0,$csv='',$delete_overwrite=1);

                        if(empty($exist_or_not))
                        {
                            $this->basic->insert_data('facebook_rx_fb_group_info',$data);
                        }
                        else
                        {
                            $where = array('facebook_rx_fb_user_info_id'=>$facebook_table_id,'group_id'=>$group['id']);
                            $this->basic->update_data('facebook_rx_fb_group_info',$where,$data);
                        }
                    }
                }

                $this->session->set_userdata('success_message', 'success');
                redirect('social_accounts/index','location');                
                exit();
            }
            else
            {
                $data['error'] = 1;
                $data['message'] = "'".$this->lang->line("something went wrong,please")."' <a href='".base_url("social_accounts/account_import")."'>'".$this->lang->line("try again")."'</a>";
                $data['body'] = "facebook_rx/user_login";
                $this->_viewcontroller($data);
            }
        }
    }

    public function fb_rx_account_switch()
    {
        $this->ajax_check();
        $id=$this->input->post("id");
        
        $this->session->set_userdata("facebook_rx_fb_user_info",$id); 

        $get_user_data = $this->basic->get_data("facebook_rx_fb_user_info",array("where"=>array("id"=>$id,"user_id"=>$this->user_id)));
        $config_id = isset($get_user_data[0]["facebook_rx_config_id"]) ? $get_user_data[0]["facebook_rx_config_id"] : 0;
        $this->session->set_userdata("fb_rx_login_database_id",$config_id);

        $this->session->unset_userdata("bot_list_get_page_details_page_table_id");
        $this->session->unset_userdata("sync_subscribers_get_page_details_page_table_id");
        $this->session->unset_userdata("get_page_details_page_table_id");
    }

    public function enableDisableWebHook()
    {
        if (!isset($_POST)) exit;

        $page_id = $this->input->post('page_id');
        $page_table_id = $page_id;
        $enable_or_disable = $this->input->post('enable_or_disable');

        if ($enable_or_disable == "disabled")
            $webhook_enabled = '1';
        else
            $webhook_enabled = '0';

        $page_info = $this->basic->get_data('facebook_rx_fb_page_info',array('where'=>array('id'=>$page_id)),array('page_access_token','page_id'));
        $page_access_token = $page_info[0]['page_access_token'];
        $page_id = $page_info[0]['page_id'];

        $this->load->library('fb_rx_login');
        $this->fb_rx_login->app_initialize($this->session->userdata('fb_rx_login_database_id'));

        if($webhook_enabled == '1')
            $response = $this->fb_rx_login->enable_webhook($page_id,$page_access_token);
        else
            $response = $this->fb_rx_login->disable_webhook($page_id,$page_access_token);

        if($response['error'] != '')
        {
            echo json_encode($response);
            exit;
        }

        if($response['error'] == '' && $page_table_id != '-1')
        {
            $this->basic->update_data('facebook_rx_fb_page_info', array('id' => $page_table_id), array('webhook_enabled' => $webhook_enabled));
            $response['error'] = '';
            echo json_encode($response);
        }
    }

}