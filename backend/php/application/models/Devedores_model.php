<?php

class Devedores_model extends CI_Model
{

  public $table = 'tb_devedores';


  function __construct()
  {
    parent::__construct();
  }
  
 
  public function apiGet($id = "", $tipo){
      try {   

      if(!empty($id)){        


        switch($tipo){
          case 'debitos':
          $query = $this->db->order_by('id', 'desc')->get_where($this->table, array('id_usuario' => $id));
 
          return $query->result_array();
          break;

          default:
          $query = $this->db->select("id, id_usuario, titulo, FORMAT(valor_devido,2, 'pt-BR') AS valor_devido, date_format(data_debito, '%d/%m/%Y') AS data_debito")->order_by('id', 'desc')->get_where($this->table,  array('id' => $id));
            
          return $query->row_array();
          break;
        }
                
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
     
     $data['data_debito'] = date("Y-m-d",  strtotime(str_replace('/', '-', $data['data_debito'])));

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

          $data['data_debito'] = date("Y-m-d",  strtotime(str_replace('/', '-', $data['data_debito'])));

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


}
