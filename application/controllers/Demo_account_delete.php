<?php 
require_once("Home.php"); // loading home controller

class Demo_account_delete extends Home
{
	public function __construct()
	{
	    parent::__construct();	    
	    $this->load->library("fb_rx_login");       
	}

    public function delete_accounts_imported_before_threedays($secret_code='42TDcCVuRsJQgPXf6q')
    {
    	if($secret_code != '42TDcCVuRsJQgPXf6q') exit;
    	$current_date = date("Y-m-d");
    	$last_seven_day = date("Y-m-d", strtotime("$current_date - 3 days"));
    	$admin_user_info = $this->basic->get_data('users',array('where'=>array('user_type'=>'Admin','email'=>'admin@xerochat.com')));

    	if(!empty($admin_user_info))
    	{
    		$admin_user_id = $admin_user_info[0]['id'];    		
	    	$fb_user_infos = $this->basic->get_data('facebook_rx_fb_user_info',array('where'=>array('add_date <'=>$last_seven_day,'user_id !='=>$admin_user_id)),array('id'));
	    	foreach($fb_user_infos as $value)
	    	{
	    	  $fb_page_infos = $this->basic->get_data('facebook_rx_fb_page_info',array('where'=>array('facebook_rx_fb_user_info_id'=>$value['id'])),array('id'));
	    	  foreach($fb_page_infos as $value2)
	    	    $this->demo_delete_data_basedon_page($value2['id']);

	    	  $this->demo_delete_data_basedon_account($value['id'],'1');
	    	}
    	}
    }

    private function demo_delete_data_basedon_account($fb_user_id=0,$app_delete=0)
    {
      $this->db->trans_start();
      $table_names = $this->demo_table_names_array_foraccount();
      foreach($table_names as $value)
      {
        if($this->db->table_exists($value['table_name']))
          $this->basic->delete_data($value['table_name'],array("{$value['column_name']}"=>$fb_user_id));
      }
      $this->db->trans_complete();                

      if ($this->db->trans_status() === FALSE) 
      {   
          $response['status'] = 0;
          $response['message'] = $this->lang->line('Something went wrong, please try again.');           
      }
      else
      {
          if($app_delete!='1')
          {
            // delete data to useges log table
            $this->_delete_usage_log($module_id=65,$request=1);
            $this->session->sess_destroy();            
          }
          $response['status'] = 1;
          $response['message'] = $this->lang->line("Your account and all of it's corresponding pages, groups and campaigns have been deleted successfully. Now you'll be redirected to the login page.");       
      }
      return $response;
    }

    private function demo_delete_data_basedon_page($table_id=0)
    {
      if($table_id == 0)
      {
        return json_encode(array('success'=>0,'message'=>$this->lang->line("Page is not found for this user. Something is wrong.")));
        exit();
      }

      $page_information = $this->basic->get_data('facebook_rx_fb_page_info',array('where'=>array('id'=>$table_id)));
      
      $table_names = $this->demo_table_names_array();
      foreach($table_names as $value)
      {
        if(isset($value['has_dependent_table']) && $value['has_dependent_table'] == 'yes')
        {
          if(isset($value['is_facebook_page_id']) && $value['is_facebook_page_id'] == 'yes')
            $table_id = $page_information[0]['page_id'];   

          $table_ids_array = array();   
          if($this->db->table_exists($value['table_name']))     
            $table_ids_info = $this->basic->get_data($value['table_name'],array('where'=>array("{$value['column_name']}"=>$table_id)),'id');
          else continue;

          foreach($table_ids_info as $info)
            array_push($table_ids_array, $info['id']);

          if($this->db->table_exists($value['table_name']))
            $this->basic->delete_data($value['table_name'],array("{$value['column_name']}"=>$table_id));

          $dependent_table_names = explode(',', $value['dependent_tables']);
          $dependent_table_column = explode(',', $value['dependent_table_column']);
          if(!empty($table_ids_array) && !empty($dependent_table_names))
          {            
            for($i=0;$i<count($dependent_table_names);$i++)
            {
              $this->db->where_in($dependent_table_column[$i], $table_ids_array);
              if($this->db->table_exists($dependent_table_names[$i]))
                $this->db->delete($dependent_table_names[$i]);
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

    }

    private function demo_table_names_array()
    {
      $tables = array (
                    0 => 
                    array (
                      'table_name' => 'auto_comment_reply_info',
                      'column_name' => 'page_info_table_id',
                      'module_id' => ''
                    ),                    
                    1 => 
                    array (
                      'table_name' => 'facebook_rx_auto_post',
                      'column_name' => 'page_group_user_id',
                      'module_id' => ''
                    ),                                    
                    2 => 
                    array (
                      'table_name' => 'facebook_rx_fb_page_info',
                      'column_name' => 'id',
                      'persistent_getstarted_check' => 'yes',
                      'module_id' => ''
                    ),
                    3 => 
                    array (
                      'table_name' => 'instagram_reply_autoreply',
                      'column_name' => 'page_info_table_id',
                      'module_id' => '',
                      'has_dependent_table' => 'yes',
                      'dependent_tables' => 'instagram_autoreply_report',
                      'dependent_table_column' =>'autoreply_table_id'
                    ),
                  );
      return $tables;
    }

    public function demo_table_names_array_foraccount()
    {
        $tables = array(
                        1 => 
                        array (
                          'table_name' => 'facebook_rx_fb_group_info',
                          'column_name' => 'facebook_rx_fb_user_info_id',
                          'module_id' => ''
                        ),
                        2 => 
                        array (
                          'table_name' => 'facebook_rx_fb_user_info',
                          'column_name' => 'id',
                          'module_id' => ''
                        )
                );
        return $tables;
    }


    



}