<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Devedores extends REST_Controller
{
	
    public function __construct()
    {
		
        parent::__construct();
		
		$this->output->set_header('Access-Control-Allow-Headers:*');                
		$this->output->set_header('Access-Control-Allow-Origin: *');
        $this->output->set_header('Access-Control-Allow-Methods: *');	
        
        $this->load->model('devedores_model');
	}

    public function index_get($id = 0, $tipo = false) {

        $debitos = $this->devedores_model->apiGet($id, $tipo);
                
        if(!empty($debitos)){            
            $this->response($debitos, REST_Controller::HTTP_OK);
        }else{            
            $this->response([
                'status' => FALSE,
                'message' => 'Nenhum registro foi encontrado.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
     
    
    public function index_post() {
        $userData = array();
        $userData = $this->post();

            $insert = $this->devedores_model->apiInsert($userData);
 
            if($insert){                
                $this->response([
                    'status' => TRUE,
                    'message' => 'Registro adicionado com sucesso!'
                ], REST_Controller::HTTP_OK);
            }else{                
                $this->response("Ocorreu um erro, por favor tente novamente.", REST_Controller::HTTP_BAD_REQUEST);
            }
 
    }
    
    public function index_put() {
        $resData = array();
        $id = $this->put('id'); 
        $resData = $this->put();
 
            $update = $this->devedores_model->apiUpdate($resData, $id);
                        
            if($update){                
                $this->response([
                    'status' => TRUE,
                    'message' => 'Registro atualizado com sucesso!'
                ], REST_Controller::HTTP_OK);
            }else{        
                    
                $this->response("Ocorreu um erro ao atualizar registro, por favor tente novamente.", REST_Controller::HTTP_BAD_REQUEST);
            }

    }
    
    public function index_delete($id){        
        if($id){            
            $delete = $this->devedores_model->apiDelete($id);
            
            if($delete){                
                $this->response([
                    'status' => TRUE,
                    'message' => 'Registro removido com sucesso!.'
                ], REST_Controller::HTTP_OK);
            }else{                
                $this->response("Ocorreu um erro, por favor tente novamente.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }else{            
            $this->response([
                'status' => FALSE,
                'message' => 'Não foi possível eliminar este registro.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }  
}

