<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Noticias extends REST_Controller
{
 
    public function __construct()
    {
		
        parent::__construct();
		
		$this->output->set_header('Access-Control-Allow-Headers:*');                
		$this->output->set_header('Access-Control-Allow-Origin: *');
        $this->output->set_header('Access-Control-Allow-Methods: *');	
        
        $this->load->model('noticias_model');
    }
    
    public function noticia_get($slug = 0) {

        $slug       = $slug ? array('url_slug' => $slug) : ''; 
 
        $noticias = $this->noticias_model->apiGet($slug);//$id
                
        if(!empty($noticias)){            
            $this->response($noticias, REST_Controller::HTTP_OK);
        }else{            
            $this->response([
                'status' => FALSE,
                'message' => 'Nenhum registro foi encontrado.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }         

    
    public function noticiaByType_get($quantidade = 0, $tipo = 0) {
 
        if($tipo) { $tipo ? $dadosArray = array('type' => $tipo, 'limit' => $quantidade) : ''; }
        $noticias = $this->noticias_model->apiGet($dadosArray);//$id
                
        if(!empty($noticias)){            
            $this->response($noticias, REST_Controller::HTTP_OK);
        }else{            
            $this->response([
                'status' => FALSE,
                'message' => 'Nenhum registro foi encontrado.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }         

    public function noticiaImagens_get($idNoticia = 0, $arquivo_tipo = 'imagem' , $modulo = 'noticia', $is_gallery) {
 
        if($idNoticia) {
            $idNoticia ? $dadosArray = array('id_referencia' => $idNoticia, 'arquivo_tipo' => $arquivo_tipo, 'modulo' => $modulo, 'is_gallery' => $is_gallery) : ''; 
        }
        $noticias = $this->noticias_model->fkMedias($dadosArray);//$id
 

        //fkMedias($id, $arquivo_tipo, $modulo, $is_gallery)        
        if(!empty($noticias)){            
            $this->response($noticias, REST_Controller::HTTP_OK);
        }else{            
            $this->response([
                'status' => FALSE,
                'message' => 'Nenhum registro foi encontrado.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }         


}
