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
        $questionario = $this->UsuarioModel->listarUsuarios();
    }

    public function autenticarUsuario(){
        
    }
}

?>
