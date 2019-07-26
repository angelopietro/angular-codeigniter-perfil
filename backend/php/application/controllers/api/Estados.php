<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Estados extends REST_Controller
{
 
    public function __construct()
    {
		
        parent::__construct();
		
		$this->output->set_header('Access-Control-Allow-Headers:*');                
		$this->output->set_header('Access-Control-Allow-Origin: *');
        $this->output->set_header('Access-Control-Allow-Methods: *');	
        
        $this->load->model('estados_model');
    }    
 
/*  LISTA DE ESTADOS */

    public function index_get($id = 0) {

        $id = $id ? array('id' => $id) : '';
        $estados = $this->estados_model->apiGetEstados($id);//$id
                
        if(!empty($estados)){            
            $this->response($estados, REST_Controller::HTTP_OK);
        }else{            
            $this->response([
                'status' => FALSE,
                'message' => 'Nenhum registro foi encontrado.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }         

    /*  LISTA DE CIDADES */

    public function cidades_get($id = 0) {
 
        $id = $id ? array('estado' => $id) : '';
        $cidades = $this->estados_model->apiGetCidades($id);//$id
                
        if(!empty($cidades)){            
            $this->response($cidades, REST_Controller::HTTP_OK);
        }else{            
            $this->response([
                'status' => FALSE,
                'message' => 'Nenhum registro foi encontrado.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }     
         

}

