<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Auth extends REST_Controller
{
 
    public function __construct()
    {
		
        parent::__construct();
		
		$this->output->set_header('Access-Control-Allow-Headers:*');                
		$this->output->set_header('Access-Control-Allow-Origin: *');
        $this->output->set_header('Access-Control-Allow-Methods: *');	
        
        $this->load->model('auth_model');
    }
    

    /*
		if (isset($_SERVER['HTTP_ORIGIN'])){
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        */    

/* LOGIN  DO USUÁRIO */

public function  index_post() {
     
    $username = $this->post('email');
    $password = $this->post('senha');
  
    if(!empty($username) && !empty($password)){
                
        $userData['returnType'] = 'single';
        $userData['conditions'] = array(
            'email' => $username,
            'senha' => md5($password),
            'is_blocked' => 0
        );
        $resData = $this->auth_model->apiGet($userData);
        
        if($resData){
            
                $sessionData = array(
                    'userID' => $resData['id'],
                   // 'email'    => 'johndoe@some-site.com',
                    'isLogged' => TRUE
            );

            // Create a token from the user data and send it as reponse
            $token = AUTHORIZATION::generateToken(['username' => $username]);
         //   $response = ['status' => $status, 'token' => $token];
            
         //   $this->session->set_userdata($sessionData);

            $this->response([
                'status' => TRUE,
                'token' => $token,
                'userID' => $resData['id']
                //'message' => 'Usuário autenticado com sucesso!'
            ], REST_Controller::HTTP_OK);
        }else{            
            //BAD_REQUEST (400) 
            $this->response([
                'status' => FALSE,
                'message' => 'Usuário ou senha invalido.'], REST_Controller::HTTP_OK);
        }
    }else{        
        $this->response([
            'status' => FALSE,
            'message' => 'Por favor, forneça seu usuário e senha corretamente.'], REST_Controller::HTTP_NOT_FOUND);
    }
}


public function  logout_post() {

    $this->session->unset_userdata('login');
    $this->session->unset_userdata('logged');            
    $this->response([
        'status' => TRUE,
        'message' => 'Logout efetuado com sucesso!',
        'data' => $resData
    ], REST_Controller::HTTP_OK);

}

public function verify_get() {

    $isLogged = $this->session->userdata('isLogged');
 
    if(isset($isLogged)){            
        $this->response(['status' => TRUE], REST_Controller::HTTP_OK);
    }else{            
        $this->response([
            'status' => FALSE,
            'message' => 'Usuário não autenticado.'
        ], REST_Controller::HTTP_OK);
    }
}     

private function verify_request()
{
    // Get all the headers
    $headers = $this->input->request_headers();

    // Extract the token
    $token = $headers['Authorization'];

    // Use try-catch
    // JWT library throws exception if the token is not valid
    try {
        // Validate the token
        // Successfull validation will return the decoded user data else returns false
        $data = AUTHORIZATION::validateToken($token);
        if ($data === false) {
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
            $this->response($response, $status);

            exit();
        } else {
            return $data;
        }
    } catch (Exception $e) {
        // Token is invalid
        // Send the unathorized access message
        $status = parent::HTTP_UNAUTHORIZED;
        $response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
        $this->response($response, $status);
    }
}
}

