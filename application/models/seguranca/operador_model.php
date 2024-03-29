<?php

require_once APPPATH . 'models/base/BaseModel.php';

class Operador_model extends BaseModel {

    var $_operador_id = null;
    var $_usuario = null;
    var $_senha = null;
    var $_perfil_id = null;
    var $_ativo = null;
    var $_nome = null;
    var $_cns = null;
    var $_conselho = null;
    var $_email = null;
    var $_nascimento = null;
    var $_cpf = null;
    var $_sexo = null;
    var $_celular = null;
    var $_telefone = null;
    var $_tipoLogradouro = null;
    var $_numero = null;
    var $_bairro = null;
    var $_complemento = null;
    var $_cidade = null;
    var $_cep = null;
    var $_cidade_nome = null;
    var $_cbo_nome = null;
    var $_cbo_ocupacao_id = null;
    var $_logradouro = null;

    function Operador_model($operador_id = null) {
        parent::Model();
        if (isset($operador_id)) {
            $this->instanciar($operador_id);
        }
    }

    function totalRegistros($parametro) {
        $this->db->select('operador_id');
        $this->db->from('tb_operador');
        if ($parametro != null && $parametro != -1) {

            $this->db->where('usuario ilike', $parametro . "%");
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function listar($args = array()) {
        $this->db->from('tb_operador')
                ->join('tb_perfil', 'tb_perfil.perfil_id = tb_operador.perfil_id', 'left')
                ->select('"tb_operador".*, tb_perfil.nome as nomeperfil');

        if ($args) {
            if (isset($args['nome']) && strlen($args['nome']) > 0) {
                // $this->db->like('tb_operador.nome', $args['nome'], 'left');
                $this->db->where('tb_operador.nome ilike', "%" . $args['nome'] . "%");
                $this->db->orwhere('tb_operador.usuario ilike', "%" . $args['nome'] . "%");
            }
        }
        return $this->db;
    }

    function listarCada($operador_id) {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.perfil_id,
                               p.nome,
                               o.nome as operador
                               ');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->where('o.operador_id', $operador_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listaroperadores() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id', 'left');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarmedicos() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->where('consulta', 'true');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }
    
        function listarespecialidade() {
        $this->db->select('distinct(co.cbo_ocupacao_id),
                               co.descricao');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->join('tb_cbo_ocupacao co', 'co.cbo_ocupacao_id = o.cbo_ocupacao_id');
        $this->db->where('consulta', 'true');
        $this->db->where('o.ativo', 'true');
        $return = $this->db->get();
        return $return->result();
    }
    
        function listarautocompletemedicos($parametro) {
        $this->db->select(' co.cbo_ocupacao_id,
                            o.operador_id,                           
                            o.nome as medico');
        $this->db->from('tb_operador o');
        $this->db->join('tb_cbo_ocupacao co', 'co.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
        $this->db->where('o.cbo_ocupacao_id', $parametro);
        $this->db->where('o.consulta', 'true');
        $this->db->where('o.ativo', 'true');
        $this->db->orderby("o.nome");
        $return = $this->db->get();
        return $return->result();
    }
    
    

    function listarmedicossolicitante() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.perfil_id');
        $this->db->from('tb_operador o');
        $this->db->where('medico', 'true');
        $this->db->where('o.ativo', 'true');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listartecnicos() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->where('consulta', 'false');
        $this->db->where('medico', 'false');
        $return = $this->db->get();
        return $return->result();
    }

    function listarcbo($parametro = null) {
        $this->db->select('cbo_grupo_id,
                            descricao
                            ');
        if ($parametro != null) {
            $this->db->where('descricao ilike', $parametro . "%");
        }
        $return = $this->db->get('tb_cbo');
        return $return->result();
    }

    function listarPerfil() {
        $this->db->select('perfil_id,
                               nome,
                               ');
        $this->db->from('tb_perfil');
        $this->db->where('ativo', 't');

        $return = $this->db->get();
        return $return->result();
    }

    function gravar() {
        try {

            /* inicia o mapeamento no banco */
            $this->db->set('nome', $_POST['nome']);
            $this->db->set('sexo', $_POST['sexo']);
            if ($_POST['nascimento'] != '')
                $this->db->set('nascimento', substr($_POST['nascimento'], 6, 4) . '-' . substr($_POST['nascimento'], 3, 2) . '-' . substr($_POST['nascimento'], 0, 2));
            else
                $this->db->set('nascimento', null);
            $this->db->set('conselho', $_POST['conselho']);
            if ($_POST['cpf'] != '') {
                $this->db->set('cpf', str_replace("-", "", str_replace(".", "", $_POST['cpf'])));
            } else {
                $this->db->set('cpf', null);
            }

            if ($_POST['tipo_logradouro'] != "") {
                $this->db->set('tipo_logradouro', $_POST['tipo_logradouro']);
            }
            $this->db->set('logradouro', $_POST['endereco']);
            $this->db->set('numero', $_POST['numero']);
            $this->db->set('bairro', $_POST['bairro']);
            $this->db->set('complemento', $_POST['complemento']);
            if ($_POST['municipio_id'] != null)
                $this->db->set('municipio_id', $_POST['municipio_id']);
            else
                $this->db->set('municipio_id', null);
            $this->db->set('cep', $_POST['cep']);
            $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['celular']))));
            $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))));
            $this->db->set('email', $_POST['email']);
            if ($_POST['txtconsulta'] != null) {
                $this->db->set('consulta', $_POST['txtconsulta']);
                $this->db->set('medico', 't');
            }
            if ($_POST['txtcboID'] != "") {
                $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
            }
            $this->db->set('usuario', $_POST['txtUsuario']);
            $this->db->set('senha', md5($_POST['txtSenha']));
            if ($_POST['txtPerfil'] != "") {
                $this->db->set('perfil_id', $_POST['txtPerfil']);
            }
            $this->db->set('ativo', 't');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['operador_id'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_operador');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") { // erro de banco
                    return false;
                } else
                if ($_POST['txtcboID'] != "") {
                    $operador_id = $this->db->insert_id();
                    $this->db->set('operador_id', $operador_id);
                    $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
                    $this->db->insert('tb_operador_cbo');
                }
            } else { // update
                $operador_id = $_POST['operador_id'];
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('operador_id', $operador_id);
                $this->db->update('tb_operador');
                if ($_POST['txtcboID'] != "") {
                    $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
                    $this->db->where('operador_id', $operador_id);
                    $this->db->update('tb_operador_cbo');
                }
            }
            $erro = $this->db->_error_message();
            if (trim($erro) != "") { // erro de banco
                return false;
            } else {
                return true;
            }
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravarrecepcao() {
        try {

            /* inicia o mapeamento no banco */
            $this->db->set('nome', $_POST['nome']);
            $this->db->set('sexo', $_POST['sexo']);
            $this->db->set('conselho', $_POST['conselho']);
            $this->db->set('ativo', 't');
            if ($_POST['txtcboID'] != "") {
                $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
            }
            $this->db->set('medico', 't');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['operador_id'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_operador');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") { // erro de banco
                    return false;
                } else
                if ($_POST['txtcboID'] != "") {
                    $operador_id = $this->db->insert_id();
                    $this->db->set('operador_id', $operador_id);
                    $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
                    $this->db->insert('tb_operador_cbo');
                }
            } else { // update
                $operador_id = $_POST['operador_id'];
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('operador_id', $operador_id);
                $this->db->update('tb_operador');
                if ($_POST['txtcboID'] != "") {
                    $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
                    $this->db->where('operador_id', $operador_id);
                    $this->db->update('tb_operador_cbo');
                }
            }
            $erro = $this->db->_error_message();
            if (trim($erro) != "") { // erro de banco
                return false;
            } else {
                return true;
            }
        } catch (Exception $exc) {
            return false;
        }
    }

    function listaoperadorautocomplete($parametro = null) {
        $this->db->select('operador_id,
                            nome,
                            conselho,
                            cpf');
        $this->db->from('tb_operador');
        $this->db->where('ativo', 'true');
        if ($parametro != null) {
            $this->db->where('nome ilike', "%" . $parametro . "%");
            $this->db->orwhere('conselho ilike', "%" . $parametro . "%");
            $this->db->orwhere('cpf ilike', "%" . $parametro . "%");
        }
        $return = $this->db->get();
        return $return->result();
    }

    function listacboprofissionaisautocomplete($parametro = null) {
        $this->db->select('cbo_ocupacao_id,
                            descricao');
        $this->db->from('tb_cbo_ocupacao');
        if ($parametro != null) {
            $this->db->where('descricao ilike', "%" . $parametro . "%");
        }

        $return = $this->db->get();
        return $return->result();
    }

    function gravarNovaSenha() {
        try {

            $novasenha = md5($_POST['txtNovaSenha']);
            $operador_id = $_POST['txtOperadorID'];
            /* inicia o mapeamento no banco */
//                $this->db->set('senha', md5($_POST['txtNovaSenha']));
//                $this->db->update('tb_operador');
//                $this->db->where('operador_id', $_POST['txtOperadorID']);
            $sql = ("UPDATE ponto.tb_operador SET senha = '$novasenha' WHERE operador_id= '$operador_id'");

            $this->db->query($sql);
            $erro = $this->db->_error_message();

            if (trim($erro) != "") { // erro de banco
                return false;
            } else {
                return true;
            }
        } catch (Exception $exc) {
            return false;
        }
    }

    function excluirOperador($operador_id) {


        $this->db->set('ativo', 'f');
        $this->db->where('operador_id', $operador_id);
        $this->db->update('tb_operador');
    }

    function instanciar($operador_id) {
        if ($operador_id != 0) {
            $this->db->select('o.operador_id,
                                o.usuario,
                                o.senha,
                                o.perfil_id,
                                o.ativo,
                                o.nome,
                                o.cns,
                                o.conselho,
                                o.email,
                                o.nascimento,
                                o.cpf,
                                o.sexo,
                                o.celular,
                                o.telefone,
                                o.tipo_logradouro,
                                o.logradouro,
                                o.numero,
                                o.bairro,
                                o.consulta,
                                o.complemento,
                                o.municipio_id,
                                o.cep,
                                o.cbo_ocupacao_id,
                                m.nome as cidade_nome,
                                c.descricao as cbo_nome');
            $this->db->from('tb_operador o');
            $this->db->join('tb_municipio m', 'm.municipio_id = o.municipio_id', 'left');
            $this->db->join('tb_cbo_ocupacao c', 'c.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
            $this->db->where("o.operador_id", $operador_id);
            $query = $this->db->get();
            $return = $query->result();

            $this->_operador_id = $return[0]->operador_id;
            $this->_usuario = $return[0]->usuario;
            $this->_senha = $return[0]->senha;
            $this->_perfil_id = $return[0]->perfil_id;
            $this->_ativo = $return[0]->ativo;
            $this->_nome = $return[0]->nome;
            $this->_cns = $return[0]->cns;
            $this->_conselho = $return[0]->conselho;
            $this->_email = $return[0]->email;
            $this->_nascimento = $return[0]->nascimento;
            $this->_cpf = $return[0]->cpf;
            $this->_sexo = $return[0]->sexo;
            $this->_celular = $return[0]->celular;
            $this->_telefone = $return[0]->telefone;
            $this->_tipoLogradouro = $return[0]->tipo_logradouro;
            $this->_logradouro = $return[0]->logradouro;
            $this->_numero = $return[0]->numero;
            $this->_bairro = $return[0]->bairro;
            $this->_complemento = $return[0]->complemento;
            $this->_cidade = $return[0]->municipio_id;
            $this->_cep = $return[0]->cep;
            $this->_consulta = $return[0]->consulta;
            $this->_cidade_nome = $return[0]->cidade_nome;
            $this->_cbo_nome = $return[0]->cbo_nome;
            $this->_cbo_ocupacao_id = $return[0]->cbo_ocupacao_id;
        }
    }


    function medicocabecalhorodape($operador_id) {
        $this->db->select('o.nome,
                            o.operador_id,
                            o.rodape,
                            o.cabecalho,
                            o.cabecalho2,
                            o.rodape2,
                            c.descricao as ocupacao,
                            o.conselho,
                            o.curriculo
                            ');
        $this->db->from('tb_operador o');
        $this->db->join('tb_cbo_ocupacao c', 'c.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
        $this->db->where('o.operador_id', $operador_id);
        $return = $this->db->get();
        return $return->result();
    }

    function operadoratualsistema($operador_id){
        $this->db->select('');
        $this->db->from('tb_operador');
        $this->db->where('operador_id', $operador_id);
        return $this->db->get()->result();
    }



}