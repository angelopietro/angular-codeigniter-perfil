<?php

class Auth_model extends CI_Model
{

  public $table = 'tb_usuarios';


  function __construct()
  {
    parent::__construct();
  }
  
 
  public function apiGet($params = array()){
      try {   
       //var_dump($params);
        $this->db->select('*');
        $this->db->from($this->table);
  //fetch data by conditions
  if(@array_key_exists("conditions",$params)){
    foreach($params['conditions'] as $key => $value){
        $this->db->where($key,$value);
    }
}

if(@array_key_exists("id",$params)){
    $id = $params['id'];
    $this->db->where('id',$params['id']);
    $query = $this->db->get();
    $result = $query->row_array();
    //print $this->db->last_query();
}else{
    //set start and limit
    if(@array_key_exists("start",$params) && @array_key_exists("limit",$params)){
        $this->db->limit($params['limit'],$params['start']);
    }elseif(!@array_key_exists("start",$params) && @array_key_exists("limit",$params)){
        $this->db->limit($params['limit']);
    }
    
    if(@array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
        $result = $this->db->count_all_results();    
        
    }elseif(@array_key_exists("returnType",$params) && $params['returnType'] == 'single'){
        $query = $this->db->get();
        $result = ($query->num_rows() > 0) ? $query->row_array() : false;
        
    }else{
        $query = $this->db->get();
       
        $result = ($query->num_rows() > 0) ? $query->result_array() : false;
    }

    return $result;
}




      if(!empty($id)){         
          $query = $this->db->select("*")->order_by('id', 'desc')->get_where($this->table,  array('id' => $id));            
          return $query->row_array();          
                
      }else{
          $query = $this->db->order_by('id', 'desc')->get($this->table);          
          return $query->result_array();
          
      }
    } catch (PDOException $e) {
      throw $e;
    }      
  }
}
