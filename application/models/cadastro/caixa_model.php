<?php

class caixa_model extends Model {

    function caixa_model($ambulatorio_caixa_id = null) {
        parent::Model();
        if (isset($ambulatorio_caixa_id)) {
            $this->instanciar($ambulatorio_caixa_id);
        }
    }

    function listarentrada($args = array()) {
        $this->db->select('valor,
                            entradas_id,
                            observacao,
                            fe.descricao as conta,
                            data,
                            fcd.razao_social,
                            tipo');
        $this->db->from('tb_entradas e');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = e.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = e.nome', 'left');
        $this->db->where('e.ativo', 'true');
        if (isset($args['empresa']) && strlen($args['empresa']) > 0) {
            $this->db->where('e.nome', $args['empresa']);
        }
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('tipo', $args['nome']);
        }
        if (isset($args['conta']) && strlen($args['conta']) > 0) {
            $this->db->where('e.conta', $args['conta']);
        }
        if (isset($args['datainicio']) && strlen($args['datainicio']) > 0) {
            $this->db->where('e.data >=', $args['datainicio']);
        }
        if (isset($args['obs']) && strlen($args['obs']) != '') {
            $this->db->where('e.observacao ilike', "%" . $args['obs'] . "%");
        }
        if (isset($args['datafim']) && strlen($args['datafim']) > 0) {
            $this->db->where('e.data <=', $args['datafim']);
        }
        return $this->db;
    }

    function listarsaida($args = array()) {
        $this->db->select('s.valor,
                            s.saidas_id,
                            s.observacao,
                            s.data,
                            fcd.razao_social,
                            fe.descricao as conta,
                            s.tipo');
        $this->db->from('tb_saidas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->where('s.ativo', 'true');
        if (isset($args['empresa']) && strlen($args['empresa']) > 0) {
            $this->db->where('s.nome', $args['empresa']);
        }
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('tipo', $args['nome']);
        }
        if (isset($args['conta']) && strlen($args['conta']) > 0) {
            $this->db->where('s.conta', $args['conta']);
        }
        if (isset($args['datainicio']) && strlen($args['datainicio']) > 0) {
            $this->db->where('s.data >=', $args['datainicio']);
        }
        if (isset($args['datafim']) && strlen($args['datafim']) > 0) {
            $this->db->where('s.data <=', $args['datafim']);
        }
        if (isset($args['obs']) && strlen($args['obs']) != '') {
            $this->db->where('s.observacao ilike', "%" . $args['obs'] . "%");
        }
        return $this->db;
    }

    function relatoriosaida() {
        $this->db->select('s.valor,
                            s.saidas_id,
                            s.observacao,
                            s.data,
                            fcd.razao_social,
                            fe.descricao as conta,
                            s.tipo');
        $this->db->from('tb_saidas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('tipo', $_POST['tipo']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', $_POST['txtdata_inicio']);
        $this->db->where('s.data <=', $_POST['txtdata_fim']);
        $this->db->orderby('s.data');
        $this->db->orderby('fcd.razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriosaidagrupo() {
        $this->db->select('s.valor,
                            s.saidas_id,
                            s.observacao,
                            s.data,
                            fcd.razao_social,
                            fe.descricao as conta,
                            s.tipo');
        $this->db->from('tb_saidas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('tipo', $_POST['tipo']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', $_POST['txtdata_inicio']);
        $this->db->where('s.data <=', $_POST['txtdata_fim']);
        $this->db->orderby('s.tipo');
        $this->db->orderby('fcd.razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriosaidacontador() {
        $this->db->select('s.valor');
        $this->db->from('tb_saidas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('tipo', $_POST['tipo']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', $_POST['txtdata_inicio']);
        $this->db->where('s.data <=', $_POST['txtdata_fim']);
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioentradagrupo() {
        $this->db->select('s.valor,
                            s.entradas_id,
                            s.observacao,
                            s.data,
                            fcd.razao_social,
                            fe.descricao as conta,
                            s.tipo');
        $this->db->from('tb_entradas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('tipo', $_POST['tipo']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', $_POST['txtdata_inicio']);
        $this->db->where('s.data <=', $_POST['txtdata_fim']);
        $this->db->orderby('s.conta');
        $this->db->orderby('fcd.razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioentredacontador() {
        $this->db->select('s.valor');
        $this->db->from('tb_entradas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('tipo', $_POST['tipo']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', $_POST['txtdata_inicio']);
        $this->db->where('s.data <=', $_POST['txtdata_fim']);
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioentrada() {
        $this->db->select('s.valor,
                            s.entradas_id,
                            s.observacao,
                            s.data,
                            fcd.razao_social,
                            fe.descricao as conta,
                            s.tipo');
        $this->db->from('tb_entradas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('tipo', $_POST['tipo']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', $_POST['txtdata_inicio']);
        $this->db->where('s.data <=', $_POST['txtdata_fim']);
        $this->db->orderby('s.conta');
        $this->db->orderby('fcd.razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioentreda() {
        $this->db->select('s.valor');
        $this->db->from('tb_entradas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('tipo', $_POST['tipo']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', $_POST['txtdata_inicio']);
        $this->db->where('s.data <=', $_POST['txtdata_fim']);
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarsomaconta($forma_entradas_saida_id) {
        $this->db->select('sum(valor) as total');
        $this->db->from('tb_saldo');
        $this->db->where('ativo', 'true');
        $this->db->where('conta', $forma_entradas_saida_id);
        $return = $this->db->get();
        return $return->result();
    }

    function statusparcelas() {
        $this->db->select('caixa_parcelas_id,
                            paga,
                            data,
                            caixa_id,
                            valor_parcela');
        $this->db->from('tb_caixa_parcelas');
        $this->db->where('paga', 'false');
        $this->db->orderby('data');
        $return = $this->db->get();
        return $return->result();
    }

    function listarcredordevedor() {
        $this->db->select('financeiro_credor_devedor_id,
                            razao_social');
        $this->db->from('tb_financeiro_credor_devedor');
        $this->db->where('ativo', 'true');
        $this->db->orderby('razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function buscarcredordevedor($financeiro_credor_devedor_id) {
        $this->db->select('financeiro_credor_devedor_id,
                            razao_social');
        $this->db->from('tb_financeiro_credor_devedor');
        $this->db->where('financeiro_credor_devedor_id', "$financeiro_credor_devedor_id");
        $this->db->where('ativo', 'true');
        $return = $this->db->get();
        return $return->result();
    }

    function gravarentrada() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
            $inicio = $_POST['inicio'];
            $dia = substr($inicio, 0, 2);
            $mes = substr($inicio, 3, 2);
            $ano = substr($inicio, 6, 4);
            $datainicio = $ano . '-' . $mes . '-' . $dia;
            $this->db->set('data', $datainicio);
            $this->db->set('tipo', $_POST['tipo']);
            $this->db->set('nome', $_POST['devedor']);
            $this->db->set('conta', $_POST['conta']);
            $this->db->set('observacao', $_POST['Observacao']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_entradas');
            $entradas_id = $this->db->insert_id();
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
            $this->db->set('entrada_id', $entradas_id);
            $this->db->set('conta', $_POST['conta']);
            $this->db->set('nome', $_POST['devedor']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_saldo');




            return $entradas_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarsaida() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
            $inicio = $_POST['inicio'];
            $dia = substr($inicio, 0, 2);
            $mes = substr($inicio, 3, 2);
            $ano = substr($inicio, 6, 4);
            $datainicio = $ano . '-' . $mes . '-' . $dia;
            $this->db->set('data', $datainicio);
            $this->db->set('tipo', $_POST['tipo']);
            $this->db->set('conta', $_POST['conta']);
            $this->db->set('nome', $_POST['devedor']);
            $this->db->set('observacao', $_POST['Observacao']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_saidas');
            $saida_id = $this->db->insert_id();
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                $valor = str_replace(",", ".", str_replace(".", "", $_POST['valor']));
            $this->db->set('valor', -$valor);
            $this->db->set('conta', $_POST['conta']);
            $this->db->set('nome', $_POST['devedor']);
            $this->db->set('saida_id', $saida_id);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_saldo');



            return $entradas_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function saldo() {

        $this->db->select('sum(valor)');
        $this->db->from('tb_saldo');
        $this->db->where('ativo', 'true');
        $return = $this->db->get();
        return $return->result();
    }

    function empresa() {

        $this->db->select('financeiro_credor_devedor_id,
            razao_social');
        $this->db->from('tb_financeiro_credor_devedor');
        $this->db->orderby('razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function excluirentrada($entrada) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('entrada_id', $entrada);
        $this->db->update('tb_saldo');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('entradas_id', $entrada);
        $this->db->update('tb_entradas');
    }

    function excluirsaida($saida) {


        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('saida_id', $saida);
        $this->db->update('tb_saldo');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('saidas_id', $saida);
        $this->db->update('tb_saidas');
    }

}

?>
