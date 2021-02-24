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
        $tipoUsuario = $dadosUsuario->tipoUsuario;
        
        $this->db->trans_start(); 

        //Inserção do usuário
        $this->db->query("
            INSERT INTO usuario 
            (email, senha, tipo_usuario) values
            ('$email', '$senha', '$tipoUsuario')
        ");
        $idUsuario = $this->db->insert_id();
        
        if($tipoUsuario == "cuidador" || $tipoUsuario == "estabelecimento"){
            //doador
            $contato = $dadosUsuario->contato;
            $tipoContato = $dadosUsuario->tipoContato;
            $disponibilidadeDias = $dadosUsuario->disponibilidadeDias;
            $disponibilidadeHoras = $dadosUsuario->disponibilidadeHoras;
            $queryDoadorFilho = "";
            //Inserção do doador
            $this->db->query("
                INSERT INTO doador 
                (contato, tipo_contato, disponibilidade_dia, disponibilidade_hora, fk_usuario) values
                ('$contato', '$tipoContato', '$disponibilidadeDias', '$disponibilidadeHoras', $idUsuario)
            ");
            $idDoador = $this->db->insert_id();

            if($tipoUsuario == "cuidador"){
                //caso seja cuidador
                $nome = $dadosUsuario->nome;
                $cpf = $dadosUsuario->cpf;
                $genero = $dadosUsuario->genero;
                $queryDoadorFilho = "        
                    INSERT INTO cuidador 
                    (nome, cpf, genero, fk_doador) values
                    ('$nome', '$cpf', '$genero', $idDoador)";

            }else if($tipoUsuario == "estabelecimento"){
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
        }
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
        $this->db->trans_start(); 
        $usuarios = $this->db->query("
            SELECT * FROM usuario u 
            JOIN doador d
            ON d.fk_usuario = u.id_usuario
            LEFT JOIN cuidador c
            ON c.fk_doador = d.id_doador 
            LEFT JOIN estabelecimento e
            ON e.fk_doador = d.id_doador 
        ");
        $this->db->trans_complete();
        
        if($this->db->trans_status() === TRUE){
            $this->db->trans_commit();
            return $usuarios->result();
        }else{
            $this->db->trans_rollback();
            return false;
        }
    }

    public function consultarUsuarioPorEmailESenha($email, $senha){
        $this->db->trans_start(); 
        $usuario = $this->db->query("select * from usuario where email = '$email' and senha = '$senha'");
        $this->db->trans_complete();
        
        if($this->db->trans_status() === TRUE && $usuario->result()){
            $this->db->trans_commit();
            return $usuario;
        }else{
            $this->db->trans_rollback();
            return false;
        }

    }


}

?>