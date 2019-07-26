<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Perfil extends REST_Controller
{
 
    public function __construct()
    {
		
        parent::__construct();
		
		$this->output->set_header('Access-Control-Allow-Headers:*');                
		$this->output->set_header('Access-Control-Allow-Origin: *');
        $this->output->set_header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        //header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
        $this->load->model('perfil_model');
    }
    

    /*
		if (isset($_SERVER['HTTP_ORIGIN'])){
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        */    

/* LOGIN  DO USUÁRIO */

public function  login_post() {
     
    $username = $this->post('username');
    $password = $this->post('password');
        
    if(!empty($username) && !empty($password)){
                
        $userData['returnType'] = 'single';
        $userData['conditions'] = array(
            'username' => $username,
            'password' => md5($password),
            'status' => 1
        );
        $resData = $this->perfil_model->apiGet($userData);
        
        if($resData){
            
            $this->response([
                'status' => TRUE,
                'message' => 'Usuário autenticado com sucesso.',
                'data' => $resData
            ], REST_Controller::HTTP_OK);
        }else{            
            //BAD_REQUEST (400) 
            $this->response([
                'status' => FALSE,
                'message' => 'Usuário ou senha invalido.'], REST_Controller::HTTP_BAD_REQUEST);
        }
    }else{        
        $this->response([
            'status' => FALSE,
            'message' => 'Por favor, fornece seu usuário e senha corretamente.'], REST_Controller::HTTP_BAD_REQUEST);
    }
}



/* CADASTRO E LISTA DE USUÁRIOS */

    public function perfil_get($id = 0) {

        $id = $id ? array('id' => $id) : '';
        $usuarios = $this->perfil_model->apiGet($id);//$id
                
        if(!empty($usuarios)){            
            $this->response($usuarios, REST_Controller::HTTP_OK);
        }else{            
            $this->response([
                'status' => FALSE,
                'message' => 'Nenhum registro foi encontrado.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }     
    
    public function perfil_post() {
        $userData = array();
        $userData = $this->post();

        if($this->perfil_model->getUserEmail($userData['email']))
        {
            $this->response([
                'status' => FALSE,
                'message' => 'Este email já está em uso, por favor tente novamente com outro email!'
            ], REST_Controller::HTTP_OK);

        }else{
            $insert = $this->perfil_model->apiInsert($userData);
 
            if($insert){                
                $this->response([
                    'status' => TRUE,
                    'message' => 'Seu registro foi criado com sucesso!'
                ], REST_Controller::HTTP_OK);
            }else{                
                $this->response("Ocorreu um erro, por favor tente novamente.", REST_Controller::HTTP_BAD_REQUEST);
            }            
        }     
    }
    
    public function perfil_put() {
        $resData = array();
        $id = $this->put('id'); 
        $resData = $this->put();
 
            $update = $this->perfil_model->apiUpdate($resData, $id);
                        
            if($update){                
                $this->response([
                    'status' => TRUE,
                    'message' => 'Registro atualizado com sucesso!'
                ], REST_Controller::HTTP_OK);
            }else{        
                    
                $this->response("Ocorreu um erro ao atualizar registro, por favor tente novamente.", REST_Controller::HTTP_BAD_REQUEST);
            }

    }
    
    public function perfil_delete($id){        
        if($id){            
            $delete = $this->perfil_model->apiDelete($id);
            
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

