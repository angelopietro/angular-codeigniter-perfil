<?php

class Usuarios_model extends CI_Model
{

  public $table = 'tb_usuarios';


  function __construct()
  {
    parent::__construct();
  }
  
 
  public function apiGet($params = array()){
      try {   

        $this->db->select('*');
        $this->db->from($this->table);
  //fetch data by conditions
  if(@array_key_exists("conditions",$params)){
    foreach($params['conditions'] as $key => $value){
        $this->db->where($key,$value);
    }
}

if(@array_key_exists("id",$params)){
    $this->db->where('id',$params['id']);
    $query = $this->db->get();
    $result = $query->row_array();
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
 
  public function apiInsert($data = array()) {
      if(!array_key_exists('created_at', $data)){
          $data['created_at'] = date("Y-m-d H:i:s");
      }
      if(!array_key_exists('updated_at', $data)){
          $data['updated_at'] = date("Y-m-d H:i:s");
      }
     
    // $data['data_debito'] = date("Y-m-d",  strtotime(str_replace('/', '-', $data['data_debito'])));
      $data['password'] = md5($data['password']);

      $insert = $this->db->insert($this->table, $data);
      if($insert){
          return $this->db->insert_id();
      }else{
          return false;
      }
  }
 
  public function apiUpdate($data, $id) {
      if(!empty($data) && !empty($id)){
          if(!array_key_exists('updated_at', $data)){
              $data['updated_at'] = date("Y-m-d H:i:s");
          }

         // $data['data_debito'] = date("Y-m-d",  strtotime(str_replace('/', '-', $data['data_debito'])));
          $data['password'] = md5($data['password']);

          $update = $this->db->update($this->table, $data, array('id'=>$id));
          return $update ? true : false;
      }else{
          return false;
      }
  }

  public function apiDelete($id){
      $delete = $this->db->delete($this->table,array('id'=>$id));
      return $delete ? true : false;
  }

/*
  public function apiAuth($params = array()){
    try {   

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
*/
}
