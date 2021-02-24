<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."models/UsuarioModel.php";


class UsuarioControl extends CI_Controller {

    public function __construct(){
        parent::__construct();        
        $this->load->model('UsuarioModel'); 
    }

    /*  Exemplo de JSON:
        {    
        "email":"marcos@gmail.com",
        "senha":"123456",
        "contato":"81998139083",
        "tipoContato":"whats",
        "disponibilidadeDias":"Fim de semana",
        "disponibilidadeHoras":"de 07:00 a 12:00",
        "tipoDoador":"cuidador",
        "empresa":"Petinho Feliz",
        "cnpj":"1234567891011",
        "nome":"marcos",
        "cpf":"01230637243",
        "genero":"masculino"}
     */
    public function cadastrarUsuario()
	{    
        //o método abaixo retorna um objeto
        $dadosUsuario = json_decode(file_get_contents('php://input'));
        $dadosUsuario->senha = md5($dadosUsuario->senha);
                 
        $result = $this->UsuarioModel->cadastrarUsuario($dadosUsuario);

        header("Access-Control-Allow-Origin: *");
        header('Content-type: application/json');
		echo json_encode(array("resultado"=>$result), JSON_UNESCAPED_UNICODE);
    }

    public function deletarUsuario()
	{    
        $this->UsuarioModel->deletarUsuario();
    }

    public function atualizarUsuario()
	{    
        $this->UsuarioModel->atualizarUsuario();
    }

    public function listarUsuarios()
	{    
        session_start();
        header("Access-Control-Allow-Origin: *");
        header('Content-type: application/json');
        if($_GET["token"] == $_SESSION["tokenAdmin"]){
            $usuarios = $this->UsuarioModel->listarUsuarios();
            echo json_encode(array("resultado"=>$usuarios), JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(array("resultado"=>"sem permissão."), JSON_UNESCAPED_UNICODE);
        }
      
    }

    public function autenticarUsuario(){
        session_start();
        $dadosUsuario = json_decode(file_get_contents('php://input'));
        $dadosUsuario->senha = md5($dadosUsuario->senha);
        $result = $this->UsuarioModel->consultarUsuarioPorEmailESenha($dadosUsuario->email, $dadosUsuario->senha);
       
        header("Access-Control-Allow-Origin: *");
        header('Content-type: application/json');
        if($result){
            //gera token
            $token = uniqid();
            $token = md5($token);
            if($dadosUsuario->email == "adminpetinhofeliz@gmail.com" && $dadosUsuario->senha == "13e554ff3ec02a3de6fca76e15299881"){
                $_SESSION["tokenAdmin"] = $token;
            }else{
                $_SESSION["tokenDoador"]  = $token;
            }
            echo json_encode(array("token"=>$token), JSON_UNESCAPED_UNICODE);
        }else{
            
            echo json_encode(array("token"=>$result), JSON_UNESCAPED_UNICODE);
        }
    }

    public function logout(){
        //unset($_SESSION["newsession"]);
    }
}

?>
