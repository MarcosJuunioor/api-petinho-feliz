<?php
require_once BASEPATH."core/Model.php";
class UsuarioModel extends CI_Model {
    
    public function __construct(){
        $this->load->database();
    }

    public function cadastrarUsuario($dadosUsuario)
	{    
        //Dados do usuário
        $email = $dadosUsuario->email;
        $senha = $dadosUsuario->senha;
        //doador
        $contato = $dadosUsuario->contato;
        $tipoContato = $dadosUsuario->tipoContato;
        $disponibilidadeDias = $dadosUsuario->disponibilidadeDias;
        $disponibilidadeHoras = $dadosUsuario->disponibilidadeHoras;
        $queryDoadorFilho = "";

        
        $this->db->trans_start(); 
        //Inserção do doador
        $this->db->query("
            INSERT INTO doador 
            (contato, tipo_contato, disponibilidade_dia, disponibilidade_hora) values
            ('$contato', '$tipoContato', '$disponibilidadeDias', '$disponibilidadeHoras')
        ");
        $idDoador = $this->db->insert_id();
        //Inserção do usuário
        $this->db->query("
            INSERT INTO usuario 
            (email, senha, fk_doador) values
            ('$email', '$senha', $idDoador)
        ");


        if($dadosUsuario->tipoDoador == "cuidador"){
            //caso seja cuidador
            $nome = $dadosUsuario->nome;
            $cpf = $dadosUsuario->cpf;
            $genero = $dadosUsuario->genero;
            $queryDoadorFilho = "        
                INSERT INTO cuidador 
                (nome, cpf, genero, fk_doador) values
                ('$nome', '$cpf', '$genero', $idDoador)";

        }else{
            //caso seja estabelecimento
            $empresa = $dadosUsuario->empresa;
            $cnpj = $dadosUsuario->cnpj;
            $queryDoadorFilho = "        
                INSERT INTO estabelecimento 
                (empresa, cnpj, fk_doador) values
                ('$nome', '$cpf', $idDoador)";

        }

        //Inserção do doador filho
        $this->db->query($queryDoadorFilho);

        $this->db->trans_complete();

        if($this->db->trans_status() === TRUE){
            $this->db->trans_commit();
            return true;
        }else{
            $this->db->trans_rollback();
            return false;
        }
    }

    public function deletarUsuario()
	{    
        $this->db->trans_start(); 
        $query_empresa = $this->db->query();
        $this->db->trans_complete();
        
        if($this->db->trans_status() === TRUE){
            $this->db->trans_commit();
            return true;
        }else{
            $this->db->trans_rollback();
            return false;
        }
    }

    public function atualizarUsuario()
	{    
        $this->db->trans_start(); 
        $query_empresa = $this->db->query();
        $this->db->trans_complete();
        
        if($this->db->trans_status() === TRUE){
            $this->db->trans_commit();
            return true;
        }else{
            $this->db->trans_rollback();
            return false;
        }
    }

    public function listarUsuarios()
	{    
        $usuarios = $this->db->get("nome da tabela...");
        return $usuarios->result();
    }


}

?>