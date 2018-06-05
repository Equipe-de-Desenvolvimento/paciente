<?php

class login_model extends Model {
    /* Método construtor */

    function Login_model($servidor_id = null) {
        parent::Model();
    }

    function autenticar($usuario, $senha) {
//        $this->db->select(' o.operador_id,
//                                o.perfil_id,
//                                p.nome as perfil,
//                                a.modulo_id'
//        );
//        $this->db->from('tb_operador o');
//        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
//        $this->db->join('tb_acesso a', 'a.perfil_id = o.perfil_id', 'left');
//        $this->db->where('o.usuario', $usuario);
//        $this->db->where('o.senha', md5($senha));
//        $this->db->where('o.ativo = true');
//        $this->db->where('p.ativo = true');
//        $return = $this->db->get()->result();
        
        $this->db->select(' paciente_id' );
        $this->db->from('tb_agenda_exames');
        $this->db->where('paciente_id', $usuario);
        $this->db->where('senha', md5($senha));
        $return = $this->db->get()->result();

//        $this->db->select('empresa_id,
//                            nome');
//        $this->db->from('tb_empresa');
//        $this->db->where('empresa_id', $empresa);
//        $retorno = $this->db->get()->result();


        
        if (isset($return) && count($return) > 0) {

            $p = array(
                'autenticado' => true,
                'login_paciente' => true,
                'operador_id' => $return[0]->paciente_id,
                'paciente_id' => $return[0]->paciente_id
            );
            $this->session->set_userdata($p);
            return true;
        } else {
            $this->session->sess_destroy();
            return false;
        }
    }

    function listar() {

        $this->db->select('e.empresa_id,
                            ordem_chegada,
                            promotor_medico,
                            login_paciente,
                            endereco_externo,
                            excluir_transferencia,
                            oftamologia,
                            ');
        $this->db->from('tb_empresa e');
        $this->db->where('e.empresa_id', 1);
        $this->db->join('tb_empresa_permissoes ep', 'ep.empresa_id = e.empresa_id', 'left');
        $this->db->orderby('e.empresa_id');
        $return = $this->db->get();
        return $return->result();
    }

}

?>
