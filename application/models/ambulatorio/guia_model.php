<?php

class guia_model extends Model {

    var $_ambulatorio_guia_id = null;
    var $_nome = null;

    function guia_model($ambulatorio_guia_id = null) {
        parent::Model();
        if (isset($ambulatorio_guia_id)) {
            $this->instanciar($ambulatorio_guia_id);
        }
    }

    function listarpaciente($paciente_id) {

        $this->db->select('nome,
                            telefone');
        $this->db->from('tb_paciente');
        $this->db->where("paciente_id", $paciente_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listar($paciente_id) {

        $this->db->select('ag.ambulatorio_guia_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            p.nome as paciente');
        $this->db->from('tb_ambulatorio_guia ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->where("ag.paciente_id", $paciente_id);
        $this->db->orderby('ag.ambulatorio_guia_id', 'desc');
        $return = $this->db->get();
        return $return->result();
    }

    function listarexames($paciente_id) {

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.faturado,
                            ae.agenda_exames_nome_id,
                            ae.ativo,
                            ae.situacao,
                            e.exames_id,
                            pc.convenio_id,
                            c.nome as convenio,
                            ae.guia_id,
                            e.situacao as situacaoexame,
                            al.situacao as situacaolaudo,
                            ae.paciente_id,
                            c.dinheiro,
                            ae.recebido,
                            ae.data_recebido,
                            ae.entregue,
                            ae.data_entregue,
                            p.nome as paciente,
                            p.indicacao,
                            p.nascimento,
                            ae.entregue_telefone,
                            o.nome as operadorrecebido,
                            ae.procedimento_tuss_id,
                            al.exame_id,
                            al.ambulatorio_laudo_id,
                            al.situacao,
                            pt.nome as procedimento');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id= pc.convenio_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.operador_recebido', 'left');
        $this->db->where('ae.confirmado', 't');
        $this->db->where('ae.tipo', 'EXAME');
        $this->db->where("ae.paciente_id", $paciente_id);
        $this->db->orderby('ae.guia_id');
        $this->db->orderby('ae.agenda_exames_id');
        $return = $this->db->get();
        return $return->result();
    }

    function listarchamadas() {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ac.ambulatorio_chamada_id,
                            ac.descricao,
                            p.nome as paciente,
                            es.nome_chamada as sala,
                            es.nome as nome_sala');
        $this->db->from('tb_ambulatorio_chamada ac');
        $this->db->join('tb_paciente p', 'p.paciente_id = ac.paciente_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = ac.sala_id', 'left');
        $this->db->where('ac.empresa_id', $empresa_id);
        $this->db->where("ac.ativo", 'true');
        $this->db->limit(1);
        $query = $this->db->get();
        $return = $query->result();

        $ambulatorio_chamada_id = $return[0]->ambulatorio_chamada_id;

        $this->db->set('ativo', 'f');
        $this->db->where('ambulatorio_chamada_id', $ambulatorio_chamada_id);
        $this->db->update('tb_ambulatorio_chamada');


        return $return;
    }

    function relatorioexamesconferencia() {

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            al.ambulatorio_laudo_id as laudo,
                            ae.situacao,
                            c.nome as convenio,
                            ae.guia_id,
                            pc.valortotal,
                            ae.quantidade,
                            ae.valor_total,
                            ae.autorizacao,
                            pc.qtdech,
                            pc.valorch,
                            ae.paciente_id,
                            o.nome as medico,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            pt.nome as exame,
                            pt.grupo,
                            pt.descricao as procedimento,
                            pt.codigo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_tuss tu', 'tu.tuss_id = pt.tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->where('ae.cancelada', 'false');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "" && $_POST['convenio'] != "-1") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['convenio'] == "-1") {
            $this->db->where("c.dinheiro", "t");
        }
        if ($_POST['tipo'] != "0" && $_POST['tipo'] != "") {
            $this->db->where("tu.classificacao", $_POST['tipo']);
        }
        if ($_POST['tipo'] == "") {
            $this->db->where("tu.classificacao !=", "2");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->orderby('c.convenio_id');
        if ($_POST['classificacao'] == "0") {
            $this->db->orderby('ae.guia_id');
            $this->db->orderby('ae.data');
            $this->db->orderby('p.nome');
        } else {
            $this->db->orderby('p.nome');
            $this->db->orderby('ae.guia_id');
            $this->db->orderby('ae.data');
        }
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioexames() {

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            al.ambulatorio_laudo_id as laudo,
                            ae.situacao,
                            c.nome as convenio,
                            ae.guia_id,
                            pc.valortotal,
                            ae.quantidade,
                            ae.valor_total,
                            ae.autorizacao,
                            pc.qtdech,
                            pc.valorch,
                            ae.paciente_id,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            pt.nome as exame,
                            pt.grupo,
                            o.nome as medico,
                            pt.descricao as procedimento,
                            pt.codigo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->where('ae.cancelada', 'false');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "" && $_POST['convenio'] != "-1") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['convenio'] == "-1") {
            $this->db->where("c.dinheiro", "t");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->orderby('c.convenio_id');
        $this->db->orderby('ae.guia_id');
        $this->db->orderby('ae.data');
        $this->db->orderby('p.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioexamescontador() {

        $this->db->select('ae.agenda_exames_id');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('ae.cancelada', 'false');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "" && $_POST['convenio'] != "-1") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['convenio'] == "-1") {
            $this->db->where("c.dinheiro", "t");
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriocancelamento() {

        $this->db->select('ac.agenda_exames_id,
                            ac.data_cadastro as data,
                            ac.operador_cadastro,
                            c.nome as convenio,
                            ac.paciente_id,
                            ae.data_autorizacao,
                            ac.observacao_cancelamento,
                            p.nome as paciente,
                            ac.procedimento_tuss_id,
                            pt.nome as exame,
                            pt.grupo,
                            ca.descricao,
                            pt.descricao as procedimento,
                            pt.codigo');
        $this->db->from('tb_ambulatorio_atendimentos_cancelamento ac');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = ac.agenda_exames_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ac.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ac.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_ambulatorio_cancelamento ca', 'ca.ambulatorio_cancelamento_id = ac.ambulatorio_cancelamento_id', 'left');
        $this->db->where("ac.data_cadastro >=", $_POST['txtdata_inicio'] . ' 00:00:00');
        $this->db->where("ac.data_cadastro <=", $_POST['txtdata_fim'] . ' 23:59:59');
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "" && $_POST['convenio'] != "-1") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['convenio'] == "-1") {
            $this->db->where("c.dinheiro", "t");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ac.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->orderby('c.convenio_id');
        $this->db->orderby('ac.data_cadastro');
        $this->db->orderby('p.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriocancelamentocontador() {

        $this->db->select('ac.agenda_exames_id');
        $this->db->from('tb_ambulatorio_atendimentos_cancelamento ac');
        $this->db->join('tb_paciente p', 'p.paciente_id = ac.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ac.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where("ac.data_cadastro >=", $_POST['txtdata_inicio'] . ' 00:00:00');
        $this->db->where("ac.data_cadastro <=", $_POST['txtdata_fim'] . ' 23:59:59');
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "" && $_POST['convenio'] != "-1") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['convenio'] == "-1") {
            $this->db->where("c.dinheiro", "t");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ac.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriovalorprocedimento() {

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.valor,
                            ae.situacao,
                            c.nome as convenio,
                            ae.guia_id,
                            pc.valortotal,
                            ae.quantidade,
                            ae.valor_total,
                            ae.autorizacao,
                            ae.paciente_id,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            pt.nome as exame,
                            pt.descricao as procedimento,
                            pt.codigo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('ae.empresa_id', $_POST['empresa']);
        $this->db->where("ae.procedimento_tuss_id", $_POST['procedimento1']);
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->orderby('ae.data');
        $this->db->orderby('p.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriovalorprocedimentocontador() {

        $this->db->select('ae.agenda_exames_id');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('ae.empresa_id', $_POST['empresa']);
        $this->db->where("ae.procedimento_tuss_id", $_POST['procedimento1']);
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioexamesgrupo() {

        $this->db->select('pt.grupo,
            c.nome as convenio,
            sum(ae.quantidade) as quantidade,
            sum(ae.valor_total)as valor');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "" && $_POST['convenio'] != "-1") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['convenio'] == "-1") {
            $this->db->where("c.dinheiro", "t");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('pt.grupo');
        $this->db->groupby('c.nome');
        $this->db->orderby('c.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioexamesgrupoprocedimento() {

        $this->db->select('pt.nome,
            c.nome as convenio,
            sum(ae.quantidade) as quantidade,
            sum(ae.valor_total)as valor');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "" && $_POST['convenio'] != "-1") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['convenio'] == "-1") {
            $this->db->where("c.dinheiro", "t");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('pt.procedimento_tuss_id');
        $this->db->groupby('pt.nome');
        $this->db->groupby('c.nome');
        $this->db->orderby('c.nome');
        $this->db->orderby('pt.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioexamesgrupoanalitico() {

        $this->db->select('pt.grupo,
            c.nome as convenio,
            ae.quantidade,
	    p.nome,
	    pt.nome as procedimento,
            ae.valor_total as valor');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "" && $_POST['convenio'] != "-1") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['convenio'] == "-1") {
            $this->db->where("c.dinheiro", "t");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->orderby('pc.convenio_id');
        $this->db->orderby('pt.grupo');
        $this->db->orderby('ae.data');
        $this->db->orderby('c.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioexamesgrupocontador() {

        $this->db->select('pt.grupo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "" && $_POST['convenio'] != "-1") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['convenio'] == "-1") {
            $this->db->where("c.dinheiro", "t");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] != "0") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('pt.grupo');
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioexamesfaturamentorm() {

        $this->db->select('pt.grupo,
            c.nome as convenio,
            sum(ae.quantidade) as quantidade,
            sum(ae.valor_total)as valor');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['convenio'] != "0") {
            $this->db->where('pc.convenio_id', $_POST['convenio']);
        }
        $this->db->where('pt.grupo', 'RM');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('pt.grupo');
        $this->db->groupby('c.nome');
        $this->db->orderby('c.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioexamesfaturamentormcontador() {

        $this->db->select('pt.grupo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['convenio'] != "0") {
            $this->db->where('pc.convenio_id', $_POST['convenio']);
        }
        $this->db->where('pt.grupo', 'RM');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('pt.grupo');
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriogeralconvenio() {

        $this->db->select('c.nome as convenio,
            sum(ae.quantidade) as quantidade,
            sum(ae.valor_total)as valor');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "" && $_POST['convenio'] != "-1") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['convenio'] == "-1") {
            $this->db->where("c.dinheiro", "t");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('c.nome');
        $this->db->orderby('c.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriogeralconveniocontador() {

        $this->db->select('c.nome');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "" && $_POST['convenio'] != "-1") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['convenio'] == "-1") {
            $this->db->where("c.dinheiro", "t");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('c.nome');
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriomedicosolicitante() {

        $this->db->select('o.nome as medico,
            sum(ae.quantidade) as quantidade,
            sum(ae.valor_total)as valor');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('o.operador_id', $_POST['medicos']);
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('o.nome');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriomedicosolicitantecontador() {

        $this->db->select('o.nome as medico,
            sum(ae.quantidade) as quantidade,
            sum(ae.valor_total)as valor');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('o.operador_id', $_POST['medicos']);
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('o.nome');
        $this->db->orderby('o.nome');
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriomedicosolicitantexmedico() {

        $this->db->select('o.nome as medicosolicitante,
            os.nome as medico,
            pt.nome as procedimento,
            p.nome as paciente,
            ae.valor_total as valor,
            al.situacao');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_operador os', 'os.operador_id = al.medico_parecer1', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('o.operador_id', $_POST['medicos']);
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriomedicosolicitantecontadorxmedico() {

        $this->db->select('o.nome as medicosolicitante');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('o.operador_id', $_POST['medicos']);
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('o.nome');
        $this->db->orderby('o.nome');
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriomedicosolicitantexmedicoindicado() {

        $this->db->select('o.nome as medicosolicitante,
            os.nome as medico,
            pt.nome as procedimento,
            p.nome as paciente,
            ae.valor_total as valor,
            al.situacao');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_operador os', 'os.operador_id = al.medico_parecer1', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        $this->db->where('e.indicado', 'true');
        if ($_POST['medicos'] != "0") {
            $this->db->where('o.operador_id', $_POST['medicos']);
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriomedicosolicitantecontadorxmedicoindicado() {

        $this->db->select('o.nome as medicosolicitante');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        $this->db->where('e.indicado', 'true');
        if ($_POST['medicos'] != "0") {
            $this->db->where('o.operador_id', $_POST['medicos']);
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('o.nome');
        $this->db->orderby('o.nome');
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriolaudopalavra() {

        $this->db->select('os.nome as medico,
            pt.nome as procedimento,
            p.nome as paciente,
            p.telefone,
            tl.descricao as tipologradouro,
            p.logradouro,
            p.numero,
            p.complemento,
            p.bairro,
            p.nascimento,
            p.sexo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_tipo_logradouro tl', 'tl.tipo_logradouro_id = p.tipo_logradouro', 'left');
        $this->db->join('tb_operador os', 'os.operador_id = al.medico_parecer1', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('al.texto ilike', "%" . $_POST['palavra'] . "%");
        if ($_POST['medicos'] != "0") {
            $this->db->where('o.operador_id', $_POST['medicos']);
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->orderby('p.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriolaudopalavracontador() {

        $this->db->select('o.nome as medicosolicitante');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('al.texto ilike', "%" . $_POST['palavra'] . "%");
        if ($_POST['medicos'] != "0") {
            $this->db->where('o.operador_id', $_POST['medicos']);
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('o.nome');
        $this->db->orderby('o.nome');
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriomedicosolicitanterm() {

        $this->db->select('o.nome as medico,
            sum(ae.quantidade) as quantidade,
            sum(ae.valor_total)as valor');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        $this->db->where('pt.grupo', 'RM');
        if ($_POST['medicos'] != "0") {
            $this->db->where('o.operador_id', $_POST['medicos']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('o.nome');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriomedicosolicitantecontadorrm() {

        $this->db->select('o.nome as medico,
            sum(ae.quantidade) as quantidade,
            sum(ae.valor_total)as valor');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        $this->db->where('pt.grupo', 'RM');
        if ($_POST['medicos'] != "0") {
            $this->db->where('o.operador_id', $_POST['medicos']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('o.nome');
        $this->db->orderby('o.nome');
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriomedicoconvenio() {

        $this->db->select('ae.quantidade,
            p.nome as paciente,
            pt.nome as procedimento,
            ae.autorizacao,
            ae.data,
            pt.grupo,
            al.situacao as situacaolaudo,
            o.nome as revisor,
            c.nome as convenio');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer2', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('al.medico_parecer1', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data_realizacao >=", $_POST['txtdata_inicio'] . " 00:00:01");
        $this->db->where("ae.data_realizacao <=", $_POST['txtdata_fim'] . " 23:59:59");
        $this->db->orderby('pc.convenio_id');
        $this->db->orderby('ae.data');
        $this->db->orderby('p.nome');


        $return = $this->db->get();
        return $return->result();
    }

    function relatoriomedicoconveniocontador() {

        $this->db->select('ae.data,
            c.nome as convenio');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('al.medico_parecer1', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data_realizacao >=", $_POST['txtdata_inicio'] . " 00:00:01");
        $this->db->where("ae.data_realizacao <=", $_POST['txtdata_fim'] . " 23:59:59");
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriotecnicoconvenio() {

        $this->db->select('ae.quantidade,
            p.nome as paciente,
            pt.nome as procedimento,
            ae.data,
            pt.grupo,
            o.nome as tecnico,
            c.nome as convenio');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = e.tecnico_realizador', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['tecnicos'] != "0") {
            $this->db->where('e.tecnico_realizador', $_POST['tecnicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->orderby('o.nome');
        $this->db->orderby('ae.data');
        $this->db->orderby('p.nome');


        $return = $this->db->get();
        return $return->result();
    }

    function relatoriotecnicoconveniocontador() {

        $this->db->select('ae.data,
            c.nome as convenio');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['tecnicos'] != "0") {
            $this->db->where('e.tecnico_realizador', $_POST['tecnicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriotecnicoconveniosintetico() {

        $this->db->select('ae.quantidade,
            p.nome as paciente,
            pt.nome as procedimento,
            ae.data,
            pt.grupo,
            o.nome as tecnico,
            c.nome as convenio');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = e.tecnico_realizador', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['tecnicos'] != "0") {
            $this->db->where('e.tecnico_realizador', $_POST['tecnicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->orderby('e.tecnico_realizador');
        $this->db->orderby('ae.data');
        $this->db->orderby('p.nome');


        $return = $this->db->get();
        return $return->result();
    }

    function relatoriotecnicoconveniocontadorsintetico() {

        $this->db->select('ae.data,
            c.nome as convenio');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['tecnicos'] != "0") {
            $this->db->where('e.tecnico_realizador', $_POST['tecnicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioconvenioexamesatendidos() {
        $data = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');

        // EXAMES ATENDIDOS

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo l', 'l.exame_id = e.exames_id', 'left');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where('ae.data <=', $data);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.situacao', 'OK');
        $this->db->where('ae.confirmado', 't');
        $this->db->where('ae.tipo', 'EXAME');
        $this->db->where('ae.cancelada', 'false');
//        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('medico_parecer1', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioconvenioexamesnaoatendidos() {
        $data = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');

        // EXAMES ATENDIDOS
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where('ae.data <=', $data);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.situacao', 'OK');
        $this->db->where('ae.confirmado', 'f');
        $this->db->where('ae.tipo', 'EXAME');
        $this->db->where('ae.cancelada', 'false');
//        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('ae.medico_agenda', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("p.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioconvenioexamesatendidosdatafim() {
        $data = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');

        // EXAMES ATENDIDOS

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo l', 'l.exame_id = e.exames_id', 'left');
        $this->db->where("ae.data >", $data);
        $this->db->where('ae.data <=', $_POST['txtdata_fim']);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.situacao', 'OK');
        $this->db->where('ae.confirmado', 't');
        $this->db->where('ae.tipo', 'EXAME');
        $this->db->where('ae.cancelada', 'false');
//        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('medico_parecer1', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioconvenioexamesnaoatendidosdatafim() {
        $data = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');

        // EXAMES ATENDIDOS
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->where("ae.data >", $data);
        $this->db->where('ae.data <=', $_POST['txtdata_fim']);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.situacao', 'OK');
        $this->db->where('ae.confirmado', 'f');
        $this->db->where('ae.tipo', 'EXAME');
        $this->db->where('ae.cancelada', 'false');
//        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('ae.medico_agenda', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("p.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioconvenioexamesatendidos2() {
        $data = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');

        // EXAMES ATENDIDOS

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo l', 'l.exame_id = e.exames_id', 'left');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where('ae.data <=', $_POST['txtdata_fim']);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.situacao', 'OK');
        $this->db->where('ae.confirmado', 't');
        $this->db->where('ae.tipo', 'EXAME');
        $this->db->where('ae.cancelada', 'false');
//        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('medico_parecer1', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioconvenioexamesnaoatendidos2() {
        $data = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');

        // EXAMES ATENDIDOS
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where('ae.data <=', $_POST['txtdata_fim']);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.situacao', 'OK');
        $this->db->where('ae.confirmado', 'f');
        $this->db->where('ae.tipo', 'EXAME');
        $this->db->where('ae.cancelada', 'false');
//        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('ae.medico_agenda', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("p.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioconvenioconsultasatendidos() {
        $data = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');

        // EXAMES ATENDIDOS

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo l', 'l.exame_id = e.exames_id', 'left');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where('ae.data <=', $data);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.situacao', 'OK');
        $this->db->where('pt.nome not ilike', '%RETORNO%');
        $this->db->where('ae.confirmado', 't');
        $this->db->where('ae.tipo', 'CONSULTA');
        $this->db->where('ae.cancelada', 'false');
//        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('medico_parecer1', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioconvenioconsultasnaoatendidos() {
        $data = date("d/m/Y");
        $empresa_id = $this->session->userdata('empresa_id');
        // EXAMES ATENDIDOS
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where('ae.data <=', $data);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.situacao', 'OK');
        $this->db->where('ae.confirmado', 'f');
        $this->db->where('ae.tipo', 'CONSULTA');
        $this->db->where('ae.cancelada', 'false');
//        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('ae.medico_consulta_id', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("p.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioconvenioconsultasatendidosdatafim() {
        $data = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');

        // EXAMES ATENDIDOS

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo l', 'l.exame_id = e.exames_id', 'left');
        $this->db->where("ae.data >", $data);
        $this->db->where('ae.data <=', $_POST['txtdata_fim']);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.situacao', 'OK');
        $this->db->where('pt.nome not ilike', '%RETORNO%');
        $this->db->where('ae.confirmado', 't');
        $this->db->where('ae.tipo', 'CONSULTA');
        $this->db->where('ae.cancelada', 'false');
//        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('medico_parecer1', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioconvenioconsultasnaoatendidosdatafim() {
        $data = date("d/m/Y");
        $empresa_id = $this->session->userdata('empresa_id');

        // EXAMES ATENDIDOS
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->where("ae.data >", $data);
        $this->db->where('ae.data <=', $_POST['txtdata_fim']);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.situacao', 'OK');
        $this->db->where('ae.confirmado', 'f');
        $this->db->where('ae.tipo', 'CONSULTA');
        $this->db->where('ae.cancelada', 'false');
//        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('ae.medico_consulta_id', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("p.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioconvenioconsultasatendidos2() {
        $data = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');

        // EXAMES ATENDIDOS

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo l', 'l.exame_id = e.exames_id', 'left');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where('ae.data <=', $_POST['txtdata_fim']);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.situacao', 'OK');
        $this->db->where('ae.confirmado', 't');
        $this->db->where('pt.nome not ilike', '%RETORNO%');
        $this->db->where('ae.tipo', 'CONSULTA');
        $this->db->where('ae.cancelada', 'false');
//        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('medico_parecer1', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioconvenioconsultasnaoatendidos2() {
        $data = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');

        // EXAMES ATENDIDOS
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where('ae.data <=', $_POST['txtdata_fim']);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.situacao', 'OK');
        $this->db->where('ae.confirmado', 'f');
        $this->db->where('ae.tipo', 'CONSULTA');
        $this->db->where('ae.cancelada', 'false');
//        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('ae.medico_consulta_id', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("p.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioconsultaconvenio() {

        $this->db->select('ae.quantidade,
            p.nome as paciente,
            pt.nome as procedimento,
            ae.autorizacao,
            ae.data,
            pt.grupo,
            al.situacao as situacaolaudo,
            o.nome as revisor,
            c.nome as convenio');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer2', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('al.medico_parecer1', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->orderby('pc.convenio_id');
        $this->db->orderby('ae.data');
        $this->db->orderby('ae.paciente_id');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioconsultaconveniocontador() {

        $this->db->select('ae.data,
            c.nome as convenio');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['medicos'] != "0") {
            $this->db->where('al.medico_parecer1', $_POST['medicos']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriomedicoconveniorm() {

        $this->db->select('ae.quantidade,
            p.nome as paciente,
            pt.nome as procedimento,
            ae.autorizacao,
            ae.data,
            al.situacao as situacaolaudo,
            o.nome as revisor,
            c.nome as convenio');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer2', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        $this->db->where('al.medico_parecer1', $_POST['medicos']);
        if ($_POST['convenio'] != "0") {
            $this->db->where('pc.convenio_id', $_POST['convenio']);
        }
        $this->db->where('pt.grupo', 'RM');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->orderby('pc.convenio_id');
        $this->db->orderby('ae.data');
        $this->db->orderby('p.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriomedicoconveniocontadorrm() {

        $this->db->select('ae.data,
            c.nome as convenio');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        $this->db->where('al.medico_parecer1', $_POST['medicos']);
        if ($_POST['convenio'] != "0") {
            $this->db->where('pc.convenio_id', $_POST['convenio']);
        }
        $this->db->where('pt.grupo', 'RM');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $return = $this->db->count_all_results();
        return $return;
    }

    function percentualmedico($procedimentopercentual, $medicopercentual) {

        $this->db->select('valor');
        $this->db->from('tb_procedimento_percentual_medico');
        $this->db->where('procedimento_tuss_id', $procedimentopercentual);
        $this->db->where('medico', $medicopercentual);
        $this->db->where('ativo', 'true');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriomedicoconveniofinanceiro() {

        $this->db->select('ae.quantidade,
            p.nome as paciente,
            pt.nome as procedimento,
            ae.autorizacao,
            ae.data,
            ae.valor_total,
            pc.procedimento_tuss_id,
            al.medico_parecer1,
            pt.perc_medico,
            al.situacao as situacaolaudo,
            tu.classificacao,
            o.nome as revisor,
            c.nome as convenio');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_tuss tu', 'tu.tuss_id = pt.tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer2', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
//        $this->db->where('al.situacao', 'FINALIZADO');
        $this->db->where('al.medico_parecer1', $_POST['medicos']);
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->orderby('pc.convenio_id');
        $this->db->orderby('ae.data');
        $this->db->orderby('ae.paciente_id');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriomedicoconveniocontadorfinanceiro() {

        $this->db->select('ae.data,
            c.nome as convenio');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
//        $this->db->where('al.situacao', 'FINALIZADO');
        $this->db->where('al.medico_parecer1', $_POST['medicos']);
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriogrupo() {

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            al.ambulatorio_laudo_id as laudo,
                            ae.situacao,
                            c.nome as convenio,
                            ae.guia_id,
                            pc.valortotal,
                            ae.quantidade,
                            ae.valor_total,
                            ae.autorizacao,
                            f.nome as forma_pagamento,
                            ae.paciente_id,
                            ae.operador_editar,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            pt.nome as exame,
                            pt.descricao as procedimento,
                            pt.codigo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_forma_pagamento f', 'f.forma_pagamento_id = ae.forma_pagamento', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        $this->db->where("pt.grupo", $_POST['grupo']);
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->orderby('pc.convenio_id');
        $this->db->orderby('ae.data');
        $this->db->orderby('p.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriogrupocontador() {

        $this->db->select('ae.agenda_exames_id');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        $this->db->where("pt.grupo", $_POST['grupo']);
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriocaixa() {

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.guia_id,
                            ae.inicio,
                            ae.fim,
                            ae.financeiro,
                            ae.faturado,
                            ae.ativo,
                            ae.verificado,
                            al.ambulatorio_laudo_id as laudo,
                            ae.situacao,
                            pt.grupo,
                            c.nome as convenio,
                            ae.guia_id,
                            pc.valortotal,
                            ae.quantidade,
                            ae.valor_total,
                            ae.valor1,
                            ae.forma_pagamento2,
                            ae.valor2,
                            ae.forma_pagamento3,
                            ae.valor3,
                            ae.forma_pagamento4,
                            ae.valor4,
                            ae.autorizacao,
                            ae.operador_autorizacao,
                            ae.paciente_id,
                            ae.operador_editar,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            pt.nome as exame,
                            o.nome,
                            e.exames_id,
                            op.nome as nomefaturamento,
                            f.nome as forma_pagamento,
                            f2.nome as forma_pagamento_2,
                            f3.nome as forma_pagamento_3,
                            f4.nome as forma_pagamento_4,
                            pt.descricao as procedimento,
                            pt.codigo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_forma_pagamento f', 'f.forma_pagamento_id = ae.forma_pagamento', 'left');
        $this->db->join('tb_forma_pagamento f2', 'f2.forma_pagamento_id = ae.forma_pagamento2', 'left');
        $this->db->join('tb_forma_pagamento f3', 'f3.forma_pagamento_id = ae.forma_pagamento3', 'left');
        $this->db->join('tb_forma_pagamento f4', 'f4.forma_pagamento_id = ae.forma_pagamento4', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.operador_autorizacao', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_faturamento', 'left');
        $this->db->where('ae.cancelada', 'false');
        $this->db->where('ae.operador_autorizacao >', 0);
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        if ($_POST['operador'] != "0") {
            $this->db->where('ae.operador_autorizacao', $_POST['operador']);
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        $this->db->where('c.dinheiro', "t");
        $this->db->orderby('ae.operador_autorizacao');
        $this->db->orderby('pc.convenio_id');
        $this->db->orderby('ae.data');
        $this->db->orderby('p.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriocaixafaturado() {

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.guia_id,
                            ae.inicio,
                            ae.fim,
                            ae.financeiro,
                            ae.faturado,
                            ae.ativo,
                            ae.verificado,
                            al.ambulatorio_laudo_id as laudo,
                            ae.situacao,
                            pt.grupo,
                            c.nome as convenio,
                            ae.guia_id,
                            pc.valortotal,
                            ae.quantidade,
                            ae.valor_total,
                            ae.valor1,
                            ae.forma_pagamento2,
                            ae.valor2,
                            ae.forma_pagamento3,
                            ae.valor3,
                            ae.forma_pagamento4,
                            ae.valor4,
                            ae.autorizacao,
                            ae.operador_autorizacao,
                            ae.paciente_id,
                            ae.operador_editar,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            pt.nome as exame,
                            o.nome,
                            e.exames_id,
                            op.nome as nomefaturamento,
                            f.nome as forma_pagamento,
                            f2.nome as forma_pagamento_2,
                            f3.nome as forma_pagamento_3,
                            f4.nome as forma_pagamento_4,
                            pt.descricao as procedimento,
                            pt.codigo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_forma_pagamento f', 'f.forma_pagamento_id = ae.forma_pagamento', 'left');
        $this->db->join('tb_forma_pagamento f2', 'f2.forma_pagamento_id = ae.forma_pagamento2', 'left');
        $this->db->join('tb_forma_pagamento f3', 'f3.forma_pagamento_id = ae.forma_pagamento3', 'left');
        $this->db->join('tb_forma_pagamento f4', 'f4.forma_pagamento_id = ae.forma_pagamento4', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.operador_autorizacao', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_faturamento', 'left');
        $this->db->where('ae.cancelada', 'false');
        $this->db->where('ae.operador_autorizacao >', 0);
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        if ($_POST['operador'] != "0") {
            $this->db->where('ae.operador_faturamento', $_POST['operador']);
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        $this->db->where('c.dinheiro', "t");
        $this->db->orderby('ae.operador_faturamento');
        $this->db->orderby('pc.convenio_id');
        $this->db->orderby('ae.data');
        $this->db->orderby('p.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function valoralterado($agenda_exames_id) {

        $this->db->select('ae.agenda_exames_id,
                            pt.codigo,
                            c.nome as convenio,
                            pt.nome as procedimento,
                            ae.editarvalor_total,
                            ae.editarforma_pagamento,
                            o.nome,
                            op.nome as usuario_antigo,
                            ae.editarquantidade,
                            f.nome as forma');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.editarprocedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_forma_pagamento f', 'f.forma_pagamento_id = ae.editarforma_pagamento', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.operador_editar', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_faturamentoantigo', 'left');
        $this->db->where("ae.agenda_exames_id", $agenda_exames_id);
        $return = $this->db->get();
        return $return->result();
    }

    function verificado($agenda_exames_id) {

        $this->db->select('ae.agenda_exames_id,
                            ae.valor_total,
                            f.nome as forma');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_forma_pagamento f', 'f.forma_pagamento_id = ae.forma_pagamento', 'left');
        $this->db->where("ae.agenda_exames_id", $agenda_exames_id);
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriocaixacontador() {

        $this->db->select('ae.agenda_exames_id');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('ae.cancelada', 'false');
        $this->db->where('ae.operador_autorizacao >', 0);
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        if ($_POST['operador'] != "0") {
            $this->db->where('ae.operador_autorizacao', $_POST['operador']);
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        $this->db->where('c.dinheiro', "t");
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriocaixacontadorfaturado() {

        $this->db->select('ae.agenda_exames_id');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('ae.cancelada', 'false');
        $this->db->where('ae.operador_autorizacao >', 0);
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        if ($_POST['operador'] != "0") {
            $this->db->where('ae.operador_faturamento', $_POST['operador']);
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        $this->db->where('c.dinheiro', "t");
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriophmetria() {

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            al.ambulatorio_laudo_id as laudo,
                            ae.situacao,
                            c.nome as convenio,
                            ae.guia_id,
                            pc.valortotal,
                            ae.quantidade,
                            ae.valor_total,
                            ae.autorizacao,
                            ae.paciente_id,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            pt.nome as exame,
                            f.nome as forma_pagamento,
                            pt.descricao as procedimento,
                            o.nome as medicosolicitante,
                            pt.codigo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_forma_pagamento f', 'f.forma_pagamento_id = ae.forma_pagamento', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->where('pt.grupo', 'RX');
        $this->db->where('pc.convenio_id', '38');
        $this->db->orderby('o.nome');
        $this->db->orderby('pc.convenio_id');
        $this->db->orderby('ae.data');
        $this->db->orderby('p.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriophmetriacontador() {

        $this->db->select('ae.agenda_exames_id');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->where('pt.grupo', 'RX');
        $this->db->where('pc.convenio_id', '38');
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriomedicoconveniormrevisor() {

        $this->db->select('o.nome as revisor,
            count(o.nome) as quantidade');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer2', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        $this->db->where('al.medico_parecer1', $_POST['medicos']);
        if ($_POST['convenio'] != "0") {
            $this->db->where('pc.convenio_id', $_POST['convenio']);
        }
        $this->db->where('pt.grupo', 'RM');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('o.nome');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriomedicoconveniormrevisada() {

        $this->db->select('o.nome as revisor,
            count(o.nome) as quantidade');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        $this->db->where('al.medico_parecer2', $_POST['medicos']);
        if ($_POST['convenio'] != "0") {
            $this->db->where('pc.convenio_id', $_POST['convenio']);
        }
        $this->db->where('pt.grupo', 'RM');
        $this->db->where("ae.data >=", $_POST['txtdata_inicio']);
        $this->db->where("ae.data <=", $_POST['txtdata_fim']);
        $this->db->groupby('o.nome');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarexamesguia($guia_id) {

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.operador_autorizacao,
                            op.nome as operador,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.situacao,
                            ae.valor_total,
                            es.nome as agenda,
                            ae.guia_id,
                            ae.paciente_id,
                            ae.quantidade,
                            ae.data_atualizacao,
                            ae.data_autorizacao,
                            p.nome as paciente,
                            ae.forma_pagamento,
                            ae.forma_pagamento2,
                            ae.forma_pagamento3,
                            ae.forma_pagamento4,
                            p.sexo,
                            es.nome as sala,
                            c.nome as convenio,
                            ae.autorizacao,
                            fp.nome as formadepagamento,
                            fp2.nome as formadepagamento2,
                            fp3.nome as formadepagamento3,
                            fp4.nome as formadepagamento4,
                            o.nome as medicosolicitante,
                            ae.procedimento_tuss_id,
                            pt.grupo,
                            pc.convenio_id,
                            c.dinheiro,
                            pt.nome as procedimento');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_forma_pagamento fp', 'fp.forma_pagamento_id =ae.forma_pagamento', 'left');
        $this->db->join('tb_forma_pagamento fp2', 'fp2.forma_pagamento_id =ae.forma_pagamento2', 'left');
        $this->db->join('tb_forma_pagamento fp3', 'fp3.forma_pagamento_id =ae.forma_pagamento3', 'left');
        $this->db->join('tb_forma_pagamento fp4', 'fp4.forma_pagamento_id =ae.forma_pagamento4', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_autorizacao', 'left');
        $this->db->where("ae.guia_id", $guia_id);
        $this->db->where("ae.cancelada", "f");
        $this->db->orderby('ae.guia_id');
        $return = $this->db->get();
        return $return->result();
    }

    function listarexamesguiaconvenio($guia_id, $convenioid) {

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.operador_autorizacao,
                            op.nome as operador,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.situacao,
                            ae.valor_total,
                            es.nome as agenda,
                            ae.guia_id,
                            ae.paciente_id,
                            ae.quantidade,
                            ae.data_atualizacao,
                            ae.data_autorizacao,
                            p.nome as paciente,
                            ae.forma_pagamento,
                            ae.forma_pagamento2,
                            ae.forma_pagamento3,
                            ae.forma_pagamento4,
                            p.sexo,
                            es.nome as sala,
                            c.nome as convenio,
                            ae.autorizacao,
                            fp.nome as formadepagamento,
                            fp2.nome as formadepagamento2,
                            fp3.nome as formadepagamento3,
                            fp4.nome as formadepagamento4,
                            o.nome as medicosolicitante,
                            ae.procedimento_tuss_id,
                            pt.grupo,
                            pc.convenio_id,
                            pt.nome as procedimento');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_forma_pagamento fp', 'fp.forma_pagamento_id =ae.forma_pagamento', 'left');
        $this->db->join('tb_forma_pagamento fp2', 'fp2.forma_pagamento_id =ae.forma_pagamento2', 'left');
        $this->db->join('tb_forma_pagamento fp3', 'fp3.forma_pagamento_id =ae.forma_pagamento3', 'left');
        $this->db->join('tb_forma_pagamento fp4', 'fp4.forma_pagamento_id =ae.forma_pagamento4', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_autorizacao', 'left');
        $this->db->where("ae.guia_id", $guia_id);
        $this->db->where("c.convenio_id", $convenioid);
        $this->db->where("ae.cancelada", "f");
        $this->db->orderby('ae.guia_id');
        $return = $this->db->get();
        return $return->result();
    }

    function listarexame($exames_id) {

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            p.indicacao,
                            es.nome as agenda,
                            ae.fim,
                            ae.valor_total,
                            ae.ativo,
                            ae.situacao,
                            ae.guia_id,
                            ae.tipo,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.data_entrega,
                            p.nome as paciente,
                            p.sexo,
                            pc.convenio_id,
                            c.nome as convenio,
                            ae.autorizacao,
                            ae.valor1,
                            ae.valor2,
                            ae.valor3,
                            ae.valor4,
                            ae.forma_pagamento,
                            ae.forma_pagamento2,
                            ae.forma_pagamento3,
                            ae.forma_pagamento4,
                            ae.desconto,
                            ae.data_autorizacao,
                            ae.agrupador_fisioterapia,
                            ae.numero_sessao,
                            ae.qtde_sessao,
                            ae.texto,
                            o.nome as medicosolicitante,
                            op.nome as atendente,
                            opm.nome as medico,
                            ex.exames_id,
                            fp.nome as formadepagamento,
                            ae.procedimento_tuss_id,
                            pt.grupo,
                            ep.logradouro,
                            ep.razao_social,
                            ep.cnpj,
                            ep.numero,
                            ep.telefone,
                            ep.celular,
                            ep.bairro,
                            ep.razao_social,
                            es.nome as sala,
                            ae.cid,
                            cid.no_cid,
                            c.convenio_id,
                            ag.data_cadastro as data_guia,
                            p.nascimento,
                            c.dinheiro,
                            ae.diabetes,
                            ae.hipertensao,
                            cbo.descricao as profissaos,
                            pt.perc_medico,
                            m.nome as municipio,
                            pt.nome as procedimento');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_cbo_ocupacao cbo', 'cbo.cbo_ocupacao_id = p.profissao', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames ex', 'ex.agenda_exames_id =ae.agenda_exames_id', 'left');
        $this->db->join('tb_forma_pagamento fp', 'fp.forma_pagamento_id =ae.forma_pagamento', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_autorizacao', 'left');
        $this->db->join('tb_operador opm', 'opm.operador_id = ae.medico_agenda', 'left');
        $this->db->join('tb_empresa ep', 'ep.empresa_id = ae.empresa_id', 'left');
        $this->db->join('tb_ambulatorio_guia ag', 'ag.ambulatorio_guia_id = ae.guia_id', 'left');
        $this->db->join('tb_municipio m', 'm.municipio_id = ep.municipio_id', 'left');
        $this->db->join('tb_cid cid', 'cid.co_cid = ae.cid', 'left');
        $this->db->where("ae.agenda_exames_id", $exames_id);
        $this->db->where("ae.cancelada", "f");
        $return = $this->db->get();
        return $return->result();
    }

    function listarexameguia($guia_id) {

        $this->db->select('sum(valor_total) as total');
        $this->db->from('tb_agenda_exames');
        $this->db->where("guia_id", $guia_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarexameguianaofaturado($guia_id) {

        $this->db->select('sum(valor_total) as total');
        $this->db->from('tb_agenda_exames');
        $this->db->where("guia_id", $guia_id);
        $this->db->where("faturado", 'f');
        $return = $this->db->get();
        return $return->result();
    }

    function listarexameguiacaixa($guia_id) {

        $this->db->select('paciente_id,
                            agenda_exames_id');
        $this->db->from('tb_agenda_exames');
        $this->db->where("guia_id", $guia_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listar2($args = array()) {
        $this->db->select('ag.ambulatorio_guia_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            p.nome as paciente');
        $this->db->from('tb_ambulatorio_guia ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames e', 'e.guia_id = ag.ambulatorio_guia_id', 'left');
        $this->db->orderby('ag.data_cadastro');
        $this->db->where("ag.paciente_id", $args['paciente']);
        $return = $this->db->get();
        return $return->result();
    }

    function listarsalas() {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('exame_sala_id,
                            nome, tipo');
        $this->db->from('tb_exame_sala');
        $this->db->where('empresa_id', $empresa_id);
        $this->db->orderby('nome');
        $return = $this->db->get();
        return $return->result();
    }

    function formadepagamento() {
        $this->db->select('forma_pagamento_id,
                            nome');
        $this->db->from('tb_forma_pagamento');
        $this->db->orderby('nome');
        $return = $this->db->get();
        return $return->result();
    }

    function verificamedico($crm) {
        $this->db->select();
        $this->db->from('tb_operador');
        $this->db->where('conselho', $crm);
        $this->db->where('medico', 'true');
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarmedico($crm) {
        $this->db->select('operador_id');
        $this->db->from('tb_operador');
        $this->db->where('conselho', $crm);
        $this->db->where('medico', 'true');
        $return = $this->db->get();
        return $return->row_array();
    }

    function listarmedicos($parametro = null) {
        $this->db->select('operador_id,
                            nome,
                            conselho');
        $this->db->from('tb_operador');
        $this->db->where('ativo', 't');
        $this->db->where('medico', 'true');
        if ($parametro != null) {
            $this->db->where('nome ilike', "%" . $parametro . "%");
            $this->db->orwhere('conselho ilike', "%" . $parametro . "%");
        }
        $return = $this->db->get();
        return $return->result();
    }

    function listarguia($paciente_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ambulatorio_guia_id');
        $this->db->from('tb_ambulatorio_guia');
        $this->db->where('empresa_id', $empresa_id);
        $this->db->where('paciente_id', $paciente_id);
        $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->row_array();
    }

    function excluir($ambulatorio_guia_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('ambulatorio_guia_id', $ambulatorio_guia_id);
        $this->db->update('tb_ambulatorio_guia');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function gravarnovovalorprocedimento() {
        $procedimento = $_POST['procedimento'];
        $data_inicio = $_POST['txtdata_inicio'];
        $data_fim = $_POST['txtdata_fim'];
        $valor = str_replace(",", ".", $_POST['valor']);
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $sql = "UPDATE ponto.tb_agenda_exames 
SET data_atualizacao = '$horario', 
operador_atualizacao = $operador_id, 
valor = $valor, 
valor_total = quantidade * $valor 
WHERE procedimento_tuss_id = $procedimento 
AND data >= '$data_inicio' 
AND data <= '$data_fim'";
        $this->db->query($sql);
        return 0;
    }

    function consultargeralparticular($mes) {

        $sql = "SELECT sum (valor_total) as total
   FROM ponto.tb_agenda_exames ae
   left join ponto.tb_exames as e on e.agenda_exames_id = ae.agenda_exames_id
   left join ponto.tb_procedimento_convenio as pc on pc.procedimento_convenio_id = ae.procedimento_tuss_id
   left join ponto.tb_procedimento_tuss as pt on pt.procedimento_tuss_id = pc.procedimento_tuss_id
   left join ponto.tb_convenio as c on c.convenio_id = pc.convenio_id
   WHERE EXTRACT('Month' From data) = $mes
   and Extract (Year from data) = 2015
   and c.dinheiro = true
   and e.cancelada = false
   and e.situacao = 'FINALIZADO'";
        $return = $this->db->query($sql)->result();
        return $return;
    }

    function consultargeralparticularfaturado($mes) {

        $sql = "SELECT sum (valor_total) as total
   FROM ponto.tb_agenda_exames ae
   left join ponto.tb_exames as e on e.agenda_exames_id = ae.agenda_exames_id
   left join ponto.tb_procedimento_convenio as pc on pc.procedimento_convenio_id = ae.procedimento_tuss_id
   left join ponto.tb_procedimento_tuss as pt on pt.procedimento_tuss_id = pc.procedimento_tuss_id
   left join ponto.tb_convenio as c on c.convenio_id = pc.convenio_id
   WHERE EXTRACT('Month' From data) = $mes
   and Extract (Year from data) = 2015
   and c.dinheiro = true
   and ae.faturado = true
   and e.cancelada = false
   and e.situacao = 'FINALIZADO'";
        $return = $this->db->query($sql)->result();
        return $return;
    }

    function consultargeralparticularnaofaturado($mes) {

        $sql = "SELECT sum (valor_total) as total
   FROM ponto.tb_agenda_exames ae
   left join ponto.tb_exames as e on e.agenda_exames_id = ae.agenda_exames_id
   left join ponto.tb_procedimento_convenio as pc on pc.procedimento_convenio_id = ae.procedimento_tuss_id
   left join ponto.tb_procedimento_tuss as pt on pt.procedimento_tuss_id = pc.procedimento_tuss_id
   left join ponto.tb_convenio as c on c.convenio_id = pc.convenio_id
   WHERE EXTRACT('Month' From data) = $mes
   and Extract (Year from data) = 2015
   and c.dinheiro = true
   and ae.faturado = false
   and e.cancelada = false
   and e.situacao = 'FINALIZADO'";
        $return = $this->db->query($sql)->result();
        return $return;
    }

    function consultargeralconveniofaturado($mes) {
        $sql = "SELECT sum (valor_total) as total
   FROM ponto.tb_agenda_exames ae
   left join ponto.tb_exames as e on e.agenda_exames_id = ae.agenda_exames_id
   left join ponto.tb_procedimento_convenio as pc on pc.procedimento_convenio_id = ae.procedimento_tuss_id
   left join ponto.tb_procedimento_tuss as pt on pt.procedimento_tuss_id = pc.procedimento_tuss_id
   left join ponto.tb_convenio as c on c.convenio_id = pc.convenio_id
   WHERE EXTRACT('Month' From data) = $mes
   and Extract (Year from data) = 2015
   and c.dinheiro = false
   and ae.faturado = true
   and e.cancelada = false
   and e.situacao = 'FINALIZADO'";
        $return = $this->db->query($sql)->result();
        return $return;
    }

    function consultargeralconvenionaofaturado($mes) {
        $sql = "SELECT sum (valor_total) as total
   FROM ponto.tb_agenda_exames ae
   left join ponto.tb_exames as e on e.agenda_exames_id = ae.agenda_exames_id
   left join ponto.tb_procedimento_convenio as pc on pc.procedimento_convenio_id = ae.procedimento_tuss_id
   left join ponto.tb_procedimento_tuss as pt on pt.procedimento_tuss_id = pc.procedimento_tuss_id
   left join ponto.tb_convenio as c on c.convenio_id = pc.convenio_id
   WHERE EXTRACT('Month' From data) = $mes
   and Extract (Year from data) = 2015
   and c.dinheiro = false
   and ae.faturado = false
   and e.cancelada = false
   and e.situacao = 'FINALIZADO'";
        $return = $this->db->query($sql)->result();
        return $return;
    }

    function consultargeralconvenio($mes) {
        $sql = "SELECT sum (valor_total) as total
   FROM ponto.tb_agenda_exames ae
   left join ponto.tb_exames as e on e.agenda_exames_id = ae.agenda_exames_id
   left join ponto.tb_procedimento_convenio as pc on pc.procedimento_convenio_id = ae.procedimento_tuss_id
   left join ponto.tb_procedimento_tuss as pt on pt.procedimento_tuss_id = pc.procedimento_tuss_id
   left join ponto.tb_convenio as c on c.convenio_id = pc.convenio_id
   WHERE EXTRACT('Month' From data) = $mes
   and Extract (Year from data) = 2015
   and c.dinheiro = false
   and e.cancelada = false
   and e.situacao = 'FINALIZADO'";
        $return = $this->db->query($sql)->result();
        return $return;
    }

    function consultargeralsintetico($mes) {
        $sql = "SELECT sum (valor_total) as total
   FROM ponto.tb_agenda_exames ae
   left join ponto.tb_exames as e on e.agenda_exames_id = ae.agenda_exames_id
   left join ponto.tb_procedimento_convenio as pc on pc.procedimento_convenio_id = ae.procedimento_tuss_id
   left join ponto.tb_procedimento_tuss as pt on pt.procedimento_tuss_id = pc.procedimento_tuss_id
   left join ponto.tb_convenio as c on c.convenio_id = pc.convenio_id
   WHERE EXTRACT('Month' From data) = $mes
   and Extract (Year from data) = 2015
   and e.cancelada = false
   and e.situacao = 'FINALIZADO'
                and (c.dinheiro = false
                or c.dinheiro = true)";
        $return = $this->db->query($sql)->result();
        return $return;
    }

    function gravar($paciente_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('tipo', 'EXAME');
            $this->db->set('paciente_id', $paciente_id);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_ambulatorio_guia');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                $ambulatorio_guia_id = $this->db->insert_id();


            return $ambulatorio_guia_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarverificado($agenda_exame_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('verificado', 't');
            $this->db->set('data_verificado', $horario);
            $this->db->set('operador_verificado', $operador_id);
            $this->db->where('agenda_exames_id', $agenda_exame_id);
            $this->db->update('tb_agenda_exames');

            return $agenda_exame_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function recebidoresultado($agenda_exame_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('recebido', 't');
            $this->db->set('data_recebido', $horario);
            $this->db->set('operador_recebido', $operador_id);
            $this->db->where('agenda_exames_id', $agenda_exame_id);
            $this->db->update('tb_agenda_exames');

            return $agenda_exame_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarentregaexame($agenda_exame_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('entregue', $_POST['txtentregue']);
            $this->db->set('entregue_telefone', $_POST['telefone']);
            $this->db->set('entregue_observacao', $_POST['observacaocancelamento']);
            $this->db->set('data_entregue', $horario);
            $this->db->set('operador_entregue', $operador_id);
            $this->db->where('agenda_exames_id', $agenda_exame_id);
            $this->db->update('tb_agenda_exames');

            return $agenda_exame_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarfaturamento() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if ($_POST['formapamento1'] != '') {
                $this->db->set('forma_pagamento', $_POST['formapamento1']);
                $this->db->set('valor1', str_replace(",", ".", $_POST['valor1']));
            }
            if ($_POST['formapamento2'] != '') {
                $this->db->set('forma_pagamento2', $_POST['formapamento2']);
                $this->db->set('valor2', str_replace(",", ".", $_POST['valor2']));
            }
            if ($_POST['formapamento3'] != '') {
                $this->db->set('forma_pagamento3', $_POST['formapamento3']);
                $this->db->set('valor3', str_replace(",", ".", $_POST['valor3']));
            }
            if ($_POST['formapamento4'] != '') {
                $this->db->set('forma_pagamento4', $_POST['formapamento4']);
                $this->db->set('valor4', str_replace(",", ".", $_POST['valor4']));
            }
            $this->db->set('valor_total', $_POST['novovalortotal']);
            $this->db->set('data_faturamento', $horario);
            $this->db->set('operador_faturamento', $operador_id);
            $this->db->set('faturado', 't');
            $this->db->where('agenda_exames_id', $_POST['agenda_exames_id']);
            $this->db->update('tb_agenda_exames');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarfaturamentoconvenio() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('valor1', $_POST['valorafaturar']);

            $this->db->set('data_faturamento', $horario);
            $this->db->set('operador_faturamento', $operador_id);
            $this->db->set('faturado', 't');
            $this->db->where('agenda_exames_id', $_POST['agenda_exames_id']);
            $this->db->update('tb_agenda_exames');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarfaturamentototal() {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $guia = $_POST['guia_id'];

            $this->db->select('agenda_exames_id, valor_total');
            $this->db->from('tb_agenda_exames');
            $this->db->where("guia_id", $guia);
            $this->db->where("faturado", 'f');
            $this->db->where('confirmado', 'true');
            $query = $this->db->get();
            $returno = $query->result();

            $forma1 = $_POST['formapamento1'];
            $forma2 = $_POST['formapamento2'];
            $forma3 = $_POST['formapamento3'];
            $forma4 = $_POST['formapamento4'];
            $valor1 = $_POST['valor1'];
            $valor2 = $_POST['valor2'];
            $valor3 = $_POST['valor3'];
            $valor4 = $_POST['valor4'];
            $juros = $_POST['juros'];
            $id_juros = $returno[0]->agenda_exames_id;
            $valortotal_juros = $returno[0]->valor_total + $juros;


            foreach ($returno as $value) {
                $i = 0;
                if ($valor1 > 0 && $valor1 >= $value->valor_total) {
                    $valor1 = $valor1 - $value->valor_total;
                    $this->db->set('forma_pagamento', $_POST['formapamento1']);
                    $this->db->set('valor1', str_replace(",", ".", $value->valor_total));
                    $this->db->set('data_faturamento', $horario);
                    $this->db->set('operador_faturamento', $operador_id);
                    $this->db->set('faturado', 't');
                    $this->db->where('agenda_exames_id', $value->agenda_exames_id);
                    $this->db->update('tb_agenda_exames');
                    $i = 1;
                } elseif ($i != 1 && $valor2 > 0 && $valor1 < $value->valor_total && $valor2 >= ($value->valor_total - $valor1)) {
                    $valor2 = $valor2 - ($value->valor_total - $valor1);
                    $restovalor2 = $value->valor_total - $valor1;
                    if ($valor1 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento1']);
                        $this->db->set('valor1', str_replace(",", ".", $valor1));
                        $this->db->set('forma_pagamento2', $_POST['formapamento2']);
                        $this->db->set('valor2', str_replace(",", ".", $restovalor2));
                    }
                    if ($valor1 == 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento2']);
                        $this->db->set('valor1', str_replace(",", ".", $restovalor2));
                    }
                    $this->db->set('data_faturamento', $horario);
                    $this->db->set('operador_faturamento', $operador_id);
                    $this->db->set('faturado', 't');
                    $this->db->where('agenda_exames_id', $value->agenda_exames_id);
                    $this->db->update('tb_agenda_exames');
                    $valor1 = 0;
                    $i = 2;
                } elseif ($i != 1 && $i != 2 && $valor3 > 0 && $valor2 < $value->valor_total && $valor3 >= ($value->valor_total - ($valor1 + $valor2))) {
                    $valor3 = $valor3 - ($value->valor_total - ($valor2 + $valor1));
                    $restovalor3 = $value->valor_total - ($valor2 + $valor1);
                    if ($valor1 > 0 && $valor2 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento1']);
                        $this->db->set('valor1', str_replace(",", ".", $valor1));
                        $this->db->set('forma_pagamento2', $_POST['formapamento2']);
                        $this->db->set('valor2', str_replace(",", ".", $valor2));
                        $this->db->set('forma_pagamento3', $_POST['formapamento3']);
                        $this->db->set('valor3', str_replace(",", ".", $restovalor3));
                    }
                    if ($valor1 == 0 && $valor2 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento2']);
                        $this->db->set('valor1', str_replace(",", ".", $valor2));
                        $this->db->set('forma_pagamento2', $_POST['formapamento3']);
                        $this->db->set('valor2', str_replace(",", ".", $restovalor3));
                    }
                    if ($valor2 == 0 && $valor1 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento1']);
                        $this->db->set('valor1', str_replace(",", ".", $valor1));
                        $this->db->set('forma_pagamento2', $_POST['formapamento3']);
                        $this->db->set('valor2', str_replace(",", ".", $restovalor3));
                    }
                    if ($valor2 == 0 && $valor1 == 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento3']);
                        $this->db->set('valor1', str_replace(",", ".", $restovalor3));
                    }
                    $this->db->set('data_faturamento', $horario);
                    $this->db->set('operador_faturamento', $operador_id);
                    $this->db->set('faturado', 't');
                    $this->db->where('agenda_exames_id', $value->agenda_exames_id);
                    $this->db->update('tb_agenda_exames');
                    $valor2 = 0;
                    $valor1 = 0;
                    $i = 3;
                } elseif ($i != 1 && $i != 2 && $i != 3 && $valor2 < ($value->valor_total - $valor1) && $valor3 < ($value->valor_total - ($valor1 + $valor2)) && $valor4 >= ($value->valor_total - ($valor1 + $valor2 + $valor3))) {
                    $valor4 = $valor4 - ($value->valor_total - ($valor3 + $valor2 + $valor1));
                    $restovalor4 = $value->valor_total - ($valor3 + $valor2 + $valor1);
                    if ($valor1 > 0 && $valor2 > 0 && $valor3 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento1']);
                        $this->db->set('valor1', str_replace(",", ".", $valor1));
                        $this->db->set('forma_pagamento2', $_POST['formapamento2']);
                        $this->db->set('valor2', str_replace(",", ".", $valor2));
                        $this->db->set('forma_pagamento3', $_POST['formapamento3']);
                        $this->db->set('valor3', str_replace(",", ".", $valor3));
                        $this->db->set('forma_pagamento4', $_POST['formapamento4']);
                        $this->db->set('valor4', str_replace(",", ".", $restovalor4));
                    }
                    if ($valor1 == 0 && $valor2 > 0 && $valor3 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento2']);
                        $this->db->set('valor1', str_replace(",", ".", $valor2));
                        $this->db->set('forma_pagamento2', $_POST['formapamento3']);
                        $this->db->set('valor2', str_replace(",", ".", $valor3));
                        $this->db->set('forma_pagamento3', $_POST['formapamento4']);
                        $this->db->set('valor3', str_replace(",", ".", $restovalor4));
                    }
                    if ($valor2 == 0 && $valor1 > 0 && $valor3 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento1']);
                        $this->db->set('valor1', str_replace(",", ".", $valor1));
                        $this->db->set('forma_pagamento2', $_POST['formapamento3']);
                        $this->db->set('valor2', str_replace(",", ".", $valor3));
                        $this->db->set('forma_pagamento3', $_POST['formapamento4']);
                        $this->db->set('valor3', str_replace(",", ".", $restovalor4));
                    }
                    if ($valor2 > 0 && $valor1 > 0 && $valor3 == 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento1']);
                        $this->db->set('valor1', str_replace(",", ".", $valor1));
                        $this->db->set('forma_pagamento2', $_POST['formapamento2']);
                        $this->db->set('valor2', str_replace(",", ".", $valor2));
                        $this->db->set('forma_pagamento3', $_POST['formapamento4']);
                        $this->db->set('valor3', str_replace(",", ".", $restovalor4));
                    }
                    if ($valor2 == 0 && $valor1 == 0 && $valor3 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento3']);
                        $this->db->set('valor1', str_replace(",", ".", $valor3));
                        $this->db->set('forma_pagamento2', $_POST['formapamento4']);
                        $this->db->set('valor2', str_replace(",", ".", $restovalor4));
                    }
                    if ($valor2 == 0 && $valor1 > 0 && $valor3 == 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento1']);
                        $this->db->set('valor1', str_replace(",", ".", $valor1));
                        $this->db->set('forma_pagamento2', $_POST['formapamento4']);
                        $this->db->set('valor2', str_replace(",", ".", $restovalor4));
                    }
                    if ($valor2 > 0 && $valor1 == 0 && $valor3 == 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento2']);
                        $this->db->set('valor1', str_replace(",", ".", $valor2));
                        $this->db->set('forma_pagamento2', $_POST['formapamento4']);
                        $this->db->set('valor2', str_replace(",", ".", $restovalor4));
                    }
                    if ($valor2 == 0 && $valor1 == 0 && $valor3 == 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento4']);
                        $this->db->set('valor1', str_replace(",", ".", $restovalor4));
                    }
                    $this->db->set('data_faturamento', $horario);
                    $this->db->set('operador_faturamento', $operador_id);
                    $this->db->set('faturado', 't');
                    $this->db->where('agenda_exames_id', $value->agenda_exames_id);
                    $this->db->update('tb_agenda_exames');
                    $valor2 = 0;
                    $valor1 = 0;
                    $valor3 = 0;
                    $i = 4;
                }
                if ($juros > 0) {
                    if ($_POST['formapamento1'] == 3) {
                        $formajuros = 3;
                    }
                    if ($_POST['formapamento1'] == 4) {
                        $formajuros = 4;
                    }
                    if ($_POST['formapamento1'] == 5) {
                        $formajuros = 5;
                    }
                    if ($_POST['formapamento1'] == 6) {
                        $formajuros = 6;
                    }
                    if ($_POST['formapamento2'] == 3) {
                        $formajuros = 3;
                    }
                    if ($_POST['formapamento2'] == 4) {
                        $formajuros = 4;
                    }
                    if ($_POST['formapamento2'] == 5) {
                        $formajuros = 5;
                    }
                    if ($_POST['formapamento2'] == 6) {
                        $formajuros = 6;
                    }

                    $this->db->set('forma_pagamento4', $formajuros);
                    $this->db->set('valor_total', $valortotal_juros);
                    $this->db->set('valor4', $juros);
                    $this->db->where('agenda_exames_id', $id_juros);
                    $this->db->update('tb_agenda_exames');
                }
                /* inicia o mapeamento no banco */
            }
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarfaturamentototalnaofaturado() {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $guia = $_POST['guia_id'];

            $this->db->select('agenda_exames_id, valor_total');
            $this->db->from('tb_agenda_exames');
            $this->db->where("guia_id", $guia);
            $this->db->where("faturado", 'f');
            $this->db->where('confirmado', 'true');
            $query = $this->db->get();
            $returno = $query->result();

            $forma1 = $_POST['formapamento1'];
            $forma2 = $_POST['formapamento2'];
            $forma3 = $_POST['formapamento3'];
            $forma4 = $_POST['formapamento4'];
            $valor1 = $_POST['valor1'];
            $valor2 = $_POST['valor2'];
            $valor3 = $_POST['valor3'];
            $valor4 = $_POST['valor4'];
            $juros = $_POST['juros'];
            $id_juros = $returno[0]->agenda_exames_id;
            $valortotal_juros = $returno[0]->valor_total + $juros;


            foreach ($returno as $value) {
                $i = 0;
                if ($valor1 > 0 && $valor1 >= $value->valor_total) {
                    $valor1 = $valor1 - $value->valor_total;
                    $this->db->set('forma_pagamento', $_POST['formapamento1']);
                    $this->db->set('valor1', str_replace(",", ".", $value->valor_total));
                    $this->db->set('data_faturamento', $horario);
                    $this->db->set('operador_faturamento', $operador_id);
                    $this->db->set('faturado', 't');
                    $this->db->where('agenda_exames_id', $value->agenda_exames_id);
                    $this->db->update('tb_agenda_exames');
                    $i = 1;
                } elseif ($i != 1 && $valor2 > 0 && $valor1 < $value->valor_total && $valor2 >= ($value->valor_total - $valor1)) {
                    $valor2 = $valor2 - ($value->valor_total - $valor1);
                    $restovalor2 = $value->valor_total - $valor1;
                    if ($valor1 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento1']);
                        $this->db->set('valor1', str_replace(",", ".", $valor1));
                        $this->db->set('forma_pagamento2', $_POST['formapamento2']);
                        $this->db->set('valor2', str_replace(",", ".", $restovalor2));
                    }
                    if ($valor1 == 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento2']);
                        $this->db->set('valor1', str_replace(",", ".", $restovalor2));
                    }
                    $this->db->set('data_faturamento', $horario);
                    $this->db->set('operador_faturamento', $operador_id);
                    $this->db->set('faturado', 't');
                    $this->db->where('agenda_exames_id', $value->agenda_exames_id);
                    $this->db->update('tb_agenda_exames');
                    $valor1 = 0;
                    $i = 2;
                } elseif ($i != 1 && $i != 2 && $valor3 > 0 && $valor2 < $value->valor_total && $valor3 >= ($value->valor_total - ($valor1 + $valor2))) {
                    $valor3 = $valor3 - ($value->valor_total - ($valor2 + $valor1));
                    $restovalor3 = $value->valor_total - ($valor2 + $valor1);
                    if ($valor1 > 0 && $valor2 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento1']);
                        $this->db->set('valor1', str_replace(",", ".", $valor1));
                        $this->db->set('forma_pagamento2', $_POST['formapamento2']);
                        $this->db->set('valor2', str_replace(",", ".", $valor2));
                        $this->db->set('forma_pagamento3', $_POST['formapamento3']);
                        $this->db->set('valor3', str_replace(",", ".", $restovalor3));
                    }
                    if ($valor1 == 0 && $valor2 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento2']);
                        $this->db->set('valor1', str_replace(",", ".", $valor2));
                        $this->db->set('forma_pagamento2', $_POST['formapamento3']);
                        $this->db->set('valor2', str_replace(",", ".", $restovalor3));
                    }
                    if ($valor2 == 0 && $valor1 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento1']);
                        $this->db->set('valor1', str_replace(",", ".", $valor1));
                        $this->db->set('forma_pagamento2', $_POST['formapamento3']);
                        $this->db->set('valor2', str_replace(",", ".", $restovalor3));
                    }
                    if ($valor2 == 0 && $valor1 == 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento3']);
                        $this->db->set('valor1', str_replace(",", ".", $restovalor3));
                    }
                    $this->db->set('data_faturamento', $horario);
                    $this->db->set('operador_faturamento', $operador_id);
                    $this->db->set('faturado', 't');
                    $this->db->where('agenda_exames_id', $value->agenda_exames_id);
                    $this->db->update('tb_agenda_exames');
                    $valor2 = 0;
                    $valor1 = 0;
                    $i = 3;
                } elseif ($i != 1 && $i != 2 && $i != 3 && $valor2 < ($value->valor_total - $valor1) && $valor3 < ($value->valor_total - ($valor1 + $valor2)) && $valor4 >= ($value->valor_total - ($valor1 + $valor2 + $valor3))) {
                    $valor4 = $valor4 - ($value->valor_total - ($valor3 + $valor2 + $valor1));
                    $restovalor4 = $value->valor_total - ($valor3 + $valor2 + $valor1);
                    if ($valor1 > 0 && $valor2 > 0 && $valor3 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento1']);
                        $this->db->set('valor1', str_replace(",", ".", $valor1));
                        $this->db->set('forma_pagamento2', $_POST['formapamento2']);
                        $this->db->set('valor2', str_replace(",", ".", $valor2));
                        $this->db->set('forma_pagamento3', $_POST['formapamento3']);
                        $this->db->set('valor3', str_replace(",", ".", $valor3));
                        $this->db->set('forma_pagamento4', $_POST['formapamento4']);
                        $this->db->set('valor4', str_replace(",", ".", $restovalor4));
                    }
                    if ($valor1 == 0 && $valor2 > 0 && $valor3 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento2']);
                        $this->db->set('valor1', str_replace(",", ".", $valor2));
                        $this->db->set('forma_pagamento2', $_POST['formapamento3']);
                        $this->db->set('valor2', str_replace(",", ".", $valor3));
                        $this->db->set('forma_pagamento3', $_POST['formapamento4']);
                        $this->db->set('valor3', str_replace(",", ".", $restovalor4));
                    }
                    if ($valor2 == 0 && $valor1 > 0 && $valor3 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento1']);
                        $this->db->set('valor1', str_replace(",", ".", $valor1));
                        $this->db->set('forma_pagamento2', $_POST['formapamento3']);
                        $this->db->set('valor2', str_replace(",", ".", $valor3));
                        $this->db->set('forma_pagamento3', $_POST['formapamento4']);
                        $this->db->set('valor3', str_replace(",", ".", $restovalor4));
                    }
                    if ($valor2 > 0 && $valor1 > 0 && $valor3 == 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento1']);
                        $this->db->set('valor1', str_replace(",", ".", $valor1));
                        $this->db->set('forma_pagamento2', $_POST['formapamento2']);
                        $this->db->set('valor2', str_replace(",", ".", $valor2));
                        $this->db->set('forma_pagamento3', $_POST['formapamento4']);
                        $this->db->set('valor3', str_replace(",", ".", $restovalor4));
                    }
                    if ($valor2 == 0 && $valor1 == 0 && $valor3 > 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento3']);
                        $this->db->set('valor1', str_replace(",", ".", $valor3));
                        $this->db->set('forma_pagamento2', $_POST['formapamento4']);
                        $this->db->set('valor2', str_replace(",", ".", $restovalor4));
                    }
                    if ($valor2 == 0 && $valor1 > 0 && $valor3 == 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento1']);
                        $this->db->set('valor1', str_replace(",", ".", $valor1));
                        $this->db->set('forma_pagamento2', $_POST['formapamento4']);
                        $this->db->set('valor2', str_replace(",", ".", $restovalor4));
                    }
                    if ($valor2 > 0 && $valor1 == 0 && $valor3 == 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento2']);
                        $this->db->set('valor1', str_replace(",", ".", $valor2));
                        $this->db->set('forma_pagamento2', $_POST['formapamento4']);
                        $this->db->set('valor2', str_replace(",", ".", $restovalor4));
                    }
                    if ($valor2 == 0 && $valor1 == 0 && $valor3 == 0) {
                        $this->db->set('forma_pagamento', $_POST['formapamento4']);
                        $this->db->set('valor1', str_replace(",", ".", $restovalor4));
                    }
                    $this->db->set('data_faturamento', $horario);
                    $this->db->set('operador_faturamento', $operador_id);
                    $this->db->set('faturado', 't');
                    $this->db->where('agenda_exames_id', $value->agenda_exames_id);
                    $this->db->update('tb_agenda_exames');
                    $valor2 = 0;
                    $valor1 = 0;
                    $valor3 = 0;
                    $i = 4;
                } elseif ($valor1 == 0 && $valor1 >= $value->valor_total) {
                    $valor1 = $valor1 - $value->valor_total;
                    $this->db->set('forma_pagamento', $_POST['formapamento1']);
                    $this->db->set('valor1', str_replace(",", ".", $value->valor_total));
                    $this->db->set('data_faturamento', $horario);
                    $this->db->set('operador_faturamento', $operador_id);
                    $this->db->set('faturado', 't');
                    $this->db->where('agenda_exames_id', $value->agenda_exames_id);
                    $this->db->update('tb_agenda_exames');
                    $i = 1;
                }
                if ($juros > 0) {
                    if ($_POST['formapamento1'] == 3) {
                        $formajuros = 3;
                    }
                    if ($_POST['formapamento1'] == 4) {
                        $formajuros = 4;
                    }
                    if ($_POST['formapamento1'] == 5) {
                        $formajuros = 5;
                    }
                    if ($_POST['formapamento1'] == 6) {
                        $formajuros = 6;
                    }
                    if ($_POST['formapamento2'] == 3) {
                        $formajuros = 3;
                    }
                    if ($_POST['formapamento2'] == 4) {
                        $formajuros = 4;
                    }
                    if ($_POST['formapamento2'] == 5) {
                        $formajuros = 5;
                    }
                    if ($_POST['formapamento2'] == 6) {
                        $formajuros = 6;
                    }

                    $this->db->set('forma_pagamento4', $formajuros);
                    $this->db->set('valor_total', $valortotal_juros);
                    $this->db->set('valor4', $juros);
                    $this->db->where('agenda_exames_id', $id_juros);
                    $this->db->update('tb_agenda_exames');
                }
                /* inicia o mapeamento no banco */
            }
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarfaturamentototalconvenio() {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $guia = $_POST['guia_id'];

            $this->db->select('agenda_exames_id, valor_total');
            $this->db->from('tb_agenda_exames');
            $this->db->where("guia_id", $guia);
            $query = $this->db->get();
            $returno = $query->result();

            foreach ($returno as $value) {
                $this->db->set('forma_pagamento', $_POST['formapamento1']);
                $this->db->set('valor1', str_replace(",", ".", $value->valor_total));
                $this->db->set('data_faturamento', $horario);
                $this->db->set('operador_faturamento', $operador_id);
                $this->db->set('faturado', 't');
                $this->db->where('agenda_exames_id', $value->agenda_exames_id);
                $this->db->update('tb_agenda_exames');
            }
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function fecharcaixa() {
//        try {
        /* inicia o mapeamento no banco */
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $data = date("Y-m-d");
        $data30 = date('Y-m-d', strtotime("+30 days", strtotime($data)));
        $data4 = date('Y-m-d', strtotime("+4 days", strtotime($data)));
        $data2 = date('Y-m-d', strtotime("+2 days", strtotime($data)));
        $data_inicio = $_POST['data1'];
        $data_fim = $_POST['data2'];
        if ($_POST['grupo'] == 0) {

            $sql = "UPDATE ponto.tb_agenda_exames
SET operador_financeiro = $operador_id, data_financeiro= '$horario', financeiro = 't'
where agenda_exames_id in (SELECT ae.agenda_exames_id
FROM ponto.tb_agenda_exames ae 
LEFT JOIN ponto.tb_procedimento_convenio pc ON pc.procedimento_convenio_id = ae.procedimento_tuss_id 
LEFT JOIN ponto.tb_procedimento_tuss pt ON pt.procedimento_tuss_id = pc.procedimento_tuss_id 
LEFT JOIN ponto.tb_exames e ON e.agenda_exames_id = ae.agenda_exames_id 
LEFT JOIN ponto.tb_ambulatorio_laudo al ON al.exame_id = e.exames_id 
LEFT JOIN ponto.tb_convenio c ON c.convenio_id = pc.convenio_id 
WHERE e.cancelada = 'false' 
AND ae.data >= '$data_inicio' 
AND ae.data <= '$data_fim' 
AND c.dinheiro = true 
ORDER BY ae.agenda_exames_id)";
            $this->db->query($sql);
        }

        if ($_POST['grupo'] == 1) {

            $sql = "UPDATE ponto.tb_agenda_exames
SET operador_financeiro = $operador_id, data_financeiro= '$horario', financeiro = 't'
where agenda_exames_id in (SELECT ae.agenda_exames_id
FROM ponto.tb_agenda_exames ae 
LEFT JOIN ponto.tb_procedimento_convenio pc ON pc.procedimento_convenio_id = ae.procedimento_tuss_id 
LEFT JOIN ponto.tb_procedimento_tuss pt ON pt.procedimento_tuss_id = pc.procedimento_tuss_id 
LEFT JOIN ponto.tb_exames e ON e.agenda_exames_id = ae.agenda_exames_id 
LEFT JOIN ponto.tb_ambulatorio_laudo al ON al.exame_id = e.exames_id 
LEFT JOIN ponto.tb_convenio c ON c.convenio_id = pc.convenio_id 
WHERE e.cancelada = 'false' 
AND ae.data >= '$data_inicio' 
AND ae.data <= '$data_fim' 
AND pt.grupo != 'RM'
AND c.dinheiro = true  
ORDER BY ae.agenda_exames_id)";
            $this->db->query($sql);
        }

        if ($_POST['grupo'] == "RM") {

            $sql = "UPDATE ponto.tb_agenda_exames
SET operador_financeiro = $operador_id, data_financeiro= '$horario',financeiro = 't'
where agenda_exames_id in (SELECT ae.agenda_exames_id
FROM ponto.tb_agenda_exames ae 
LEFT JOIN ponto.tb_procedimento_convenio pc ON pc.procedimento_convenio_id = ae.procedimento_tuss_id 
LEFT JOIN ponto.tb_procedimento_tuss pt ON pt.procedimento_tuss_id = pc.procedimento_tuss_id 
LEFT JOIN ponto.tb_exames e ON e.agenda_exames_id = ae.agenda_exames_id 
LEFT JOIN ponto.tb_ambulatorio_laudo al ON al.exame_id = e.exames_id 
LEFT JOIN ponto.tb_convenio c ON c.convenio_id = pc.convenio_id 
WHERE e.cancelada = 'false' 
AND ae.data >= '$data_inicio' 
AND ae.data <= '$data_fim' 
AND pt.grupo = 'RM'
AND c.dinheiro = true  
ORDER BY ae.agenda_exames_id)";
            $this->db->query($sql);
        }


        if ($_POST['dinheiro'] != '0,00') {

            $this->db->set('data', $data);
            $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['dinheiro'])));
            $this->db->set('tipo', 'CAIXA DINHEIRO');
            $this->db->set('nome', 14);
            $this->db->set('conta', 1);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_entradas');
            $entradas_id = $this->db->insert_id();

            $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['dinheiro'])));
            $this->db->set('entrada_id', $entradas_id);
            $this->db->set('conta', 1);
            $this->db->set('nome', 14);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_saldo');
        }

        if ($_POST['cheque'] != '0,00') {

            $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['cheque'])));
            $this->db->set('devedor', 14);
            $this->db->set('data', $data4);
            $this->db->set('tipo', 'CAIXA CHEQUE');
            $this->db->set('conta', 1);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_financeiro_contasreceber');
        }

        if ($_POST['cartaovisa'] != '0,00') {
            $cartaovisa = str_replace(",", ".", str_replace(".", "", $_POST['cartaovisa']));
//            $cartaovisa = $cartaovisa * 0.965;
            $cartaovisa = $cartaovisa * 1;
            $this->db->set('valor', $cartaovisa);
            $this->db->set('devedor', 14);
            $this->db->set('data', $data30);
            $this->db->set('tipo', 'CAIXA CARTAO VISA');
            $this->db->set('conta', 6);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_financeiro_contasreceber');
        }

        if ($_POST['cartaomaster'] != '0,00') {
            $cartaomaster = str_replace(",", ".", str_replace(".", "", $_POST['cartaomaster']));
//            $cartaomaster = $cartaomaster * 0.965;
            $cartaomaster = $cartaomaster * 1;
            $this->db->set('valor', $cartaomaster);
            $this->db->set('devedor', 14);
            $this->db->set('data', $data30);
            $this->db->set('tipo', 'CAIXA CARTAO MASTER');
            $this->db->set('conta', 6);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_financeiro_contasreceber');
        }

        if ($_POST['cartaohiper'] != '0,00') {
            $cartaohiper = str_replace(",", ".", str_replace(".", "", $_POST['cartaohiper']));
//            $cartaohiper = $cartaohiper * 0.965;
            $cartaohiper = $cartaohiper * 1;
            $this->db->set('valor', $cartaomaster);
            $this->db->set('devedor', 14);
            $this->db->set('data', $data30);
            $this->db->set('tipo', 'CAIXA CARTAO HIPER');
            $this->db->set('conta', 6);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_financeiro_contasreceber');
        }

        if ($_POST['outros'] != '0,00') {
            $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['outros'])));
            $this->db->set('devedor', 14);
            $this->db->set('data', $data2);
            $this->db->set('tipo', 'CAIXA OUTROS');
            $this->db->set('conta', 1);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_financeiro_contasreceber');
        }
    }

    function listardados($convenio) {
        $this->db->select('nome,
                            credor_devedor_id,
                            conta_id');
        $this->db->from('tb_convenio');
        $this->db->where("ativo", 't');
        $this->db->where("convenio_id", $convenio);
        $return = $this->db->get();
        return $return->result();
    }

    function listarempresas() {

        $this->db->select('empresa_id,
            razao_social,
            nome');
        $this->db->from('tb_empresa');
        $this->db->orderby('empresa_id');
        $return = $this->db->get();
        return $return->result();
    }

    function listarexamesguialaboratorio($guia_id) {

        $this->db->select('ae.agenda_exames_id,
                            pt.procedimento_tuss_id,
                            ae.json_integracao_lab,
                            ae.data,
                            ae.mensagem_integracao_lab,
                            ae.operador_autorizacao,
                            op.nome as operador,
                            ae.guia_id,
                            ae.paciente_id,
                            ae.data_autorizacao,
                            p.nome as paciente,
                            c.nome as convenio,
                            p.sexo,
                            p.nascimento,
                            p.cpf,
                            p.rg,
                            o.nome as medicosolicitante,
                            o.uf_profissional,
                            sig.nome as sigla_conselho,
                            o.conselho as crm_solicitante,
                            ae.indicacao as promotor2,
                            pt.grupo,
                            pc.convenio_id,
                            pt.codigo,
                            ope.conselho,
                            m.estado,
                            m.codigo_ibge,
                            ag.tipo ');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_paciente_indicacao pi', 'ae.indicacao = pi.paciente_indicacao_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_guia ge', 'ge.ambulatorio_guia_id = ae.guia_id', 'left');
        $this->db->join('tb_ambulatorio_laudo l', 'l.exame_id = e.exames_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_ambulatorio_grupo ag', 'pt.grupo = ag.nome', 'left');
        $this->db->join('tb_empresa ep', 'ep.empresa_id = ae.empresa_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_sigla sig', 'sig.sigla_id = o.sigla_id', 'left');
        $this->db->join('tb_municipio m', 'm.municipio_id = o.municipio_id', 'left');
        $this->db->join('tb_operador ope', 'ope.operador_id = ae.medico_consulta_id', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_autorizacao', 'left');
        $this->db->where("ae.guia_id", $guia_id);
        $this->db->where("pt.grupo", 'LABORATORIAL');
        //$this->db->where("ae.mensagem_integracao_lab !=", 'IMPORTADO');
        $this->db->where("ae.cancelada", "f");
        $this->db->orderby('ae.agenda_exames_id');
        $return = $this->db->get();
//        var_dump($return->result()); die;
        return $return->result();
    }

    function listarexameguiaprocedimentosmodelo2($guia_id) {

        $this->db->select('sum((ae.valor * ae.quantidade)) as valor_total, 
                           array_agg(ae.agenda_exames_id) as array_exames,
                           array_agg(ae.valor * ae.quantidade) as array_valores,
                           paciente_id
    
                        ');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        // $this->db->where('faturado', 'f');
        $this->db->where('confirmado', 't');
        $this->db->where('c.dinheiro', 't');
        $this->db->where("guia_id", $guia_id);
        $this->db->groupby("guia_id, paciente_id");
        $return = $this->db->get();
        return $return->result();
    }

    function agendaExamesFormasPagamentoGuiaTotalLab($guia_id) {
        //        var_dum
                // Dentro do select tem um pequeno select interno que faz a contagem pra saber quanto ainda resta a pagar
                // Desse procedimento
                $this->db->select('
                                    aef.guia_id,
                                    sum(aef.valor_total) as valor_total,
                                    array_agg(aef.valor_total),
                                    (sum(aef.valor_total) - (select sum(valor_bruto) + sum(desconto)  as valorTotPag from ponto.tb_agenda_exames_faturar aef2
                                    where aef2.guia_id = aef.guia_id and ativo = true)) as valor_restante,
                                    sum(aef.valor) as valor,
                                    sum(valor_bruto) + sum(desconto) as valor_total_pago,
                                    
                                    sum(aef.desconto) as desconto', false);
                $this->db->from('tb_agenda_exames_faturar aef');
                $this->db->join('tb_forma_pagamento fp', 'fp.forma_pagamento_id = aef.forma_pagamento_id', 'left');
                $this->db->where('aef.guia_id', $guia_id);
                $this->db->where('aef.ativo', 't');
                $this->db->groupby('
                                    aef.guia_id,
                                    
                                    ');
                // $this->db->orderby('');
                $return = $this->db->get();
                $retorno = $return->result();
                return $retorno;
            }

    function listarempresapermissoes($empresa_id = null) {
        if ($empresa_id == null) {
            $empresa_id = $this->session->userdata('empresa_id');
        }

        $this->db->select('ep.*, e.*');
        $this->db->from('tb_empresa e');
//        $this->db->where('e.empresa_id', 1);
        $this->db->join('tb_empresa_permissoes ep', 'ep.empresa_id = e.empresa_id', 'left');
        $this->db->orderby('e.empresa_id');
        $return = $this->db->get();
        return $return->result();
    }

    function listarclassificacao() {

        $this->db->select('tuss_classificacao_id,
            nome');
        $this->db->from('tb_tuss_classificacao');
        $this->db->orderby('nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarempresa($empresa_id = null) {
        if ($empresa_id == null) {
            $empresa_id = $this->session->userdata('empresa_id');
        }

        $this->db->select('e.empresa_id,
                            e.razao_social,
                            e.logradouro,
                            e.numero,
                            e.nome,
                            e.telefone,
                            e.email,
                            e.cnes,
                            e.horario_sab,
                            e.horario_seg_sex,
                            e.producaomedicadinheiro,
                            e.impressao_declaracao,
                            e.impressao_orcamento,
                            e.impressao_internacao,
                            e.data_contaspagar,
                            e.medico_laudodigitador,
                            e.impressao_laudo,
                            e.chamar_consulta,
                            e.impressao_recibo,
                            e.cabecalho_config,
                            e.rodape_config,
                            e.laudo_config,
                            e.recibo_config,
                            e.ficha_config,
                            e.declaracao_config,
                            e.atestado_config,
                            e.celular,
                            e.bairro,
                            e.endereco_integracao_lab,
                            e.endereco_externo_base,
                            e.identificador_lis,
                            e.origem_lis,
                            
                            m.nome as municipio,
                            m.estado,
                            e.impressao_tipo, 
                            e.site_empresa,
                            e.cnpj,
                            e.internacao');
        $this->db->from('tb_empresa e');
        $this->db->join('tb_municipio m', 'm.municipio_id = e.municipio_id', 'left');
        // $this->db->where('e.empresa_id', $empresa_id);

        $this->db->orderby('e.empresa_id');
        $return = $this->db->get();
        return $return->result();
    
    }

    function listarconfiguracaoimpressao($empresa_id = null) {
        if($empresa_id == null){
            $empresa_id = $this->session->userdata('empresa_id');
        }
        // var_dump($empresa_id); die;
        $data = date("Y-m-d");
        $this->db->select('ei.empresa_impressao_cabecalho_id,ei.cabecalho,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_cabecalho ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        // $this->db->where('ei.empresa_id', $empresa_id);
//      $this->db->where('paciente_id', $paciente_id);
//      $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->result();
    }

    function medicocabecalhorodape($operador_id) {
        $this->db->select('o.nome,
                            o.operador_id,
                            o.rodape,
                            o.cabecalho,
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

    function listarconfiguracaoimpressaolaudo($empresa_id = null) {
        $data = date("Y-m-d");
        if($empresa_id == null){
            $empresa_id = $this->session->userdata('empresa_id');
        }
       
        $this->db->select('ei.empresa_impressao_laudo_id,ei.cabecalho,ei.texto,ei.adicional_cabecalho,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_laudo ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        // $this->db->where('ei.empresa_id', $empresa_id);
        $this->db->where('ei.ativo', 't');
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->result();
    }

    function gravarguia($paciente_id) {
        $horario = date("Y-m-d H:i:s");
        $data = date("Y-m-d");
        $operador_id = $this->session->userdata('operador_id');
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->set('empresa_id', $empresa_id);
        $this->db->set('tipo', 'EXAME');
        $this->db->set('data_criacao', $data);
        $this->db->set('convenio_id', $_POST['convenio1']);
        $this->db->set('paciente_id', $paciente_id);
        $this->db->set('data_cadastro', $horario);
        $this->db->set('operador_cadastro', $operador_id);
        $this->db->insert('tb_ambulatorio_guia');
        $ambulatorio_guia_id = $this->db->insert_id();
        return $ambulatorio_guia_id;
    }

    function gravarmedico($crm) {
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('data_cadastro', $horario);
        $this->db->set('operador_cadastro', $operador_id);
        $this->db->set('conselho', $crm);
        $this->db->set('nome', $_POST['medico1']);
        $this->db->set('medico', 't');
        $this->db->insert('tb_operador');
        $medico_id = $this->db->insert_id();
        return $medico_id;
    }

    function gravarexames($ambulatorio_guia_id, $medico_id) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->select('dinheiro');
            $this->db->from('tb_convenio');
            $this->db->where("convenio_id", $_POST['convenio1']);
            $query = $this->db->get();
            $return = $query->result();
            $dinheiro = $return[0]->dinheiro;

            $hora = date("H:i:s");
            $data = date("Y-m-d");
            $this->db->set('procedimento_tuss_id', $_POST['procedimento1']);
            if ($_POST['medicoagenda'] != "") {
                $this->db->set('medico_agenda', $_POST['medicoagenda']);
                $this->db->set('medico_consulta_id', $_POST['medicoagenda']);
            }
            $this->db->set('valor', $_POST['valor1']);
            $valortotal = $_POST['valor1'] * $_POST['qtde1'];
            $this->db->set('valor_total', $valortotal);
            $this->db->set('quantidade', $_POST['qtde1']);
            $this->db->set('autorizacao', $_POST['autorizacao1']);
//            $this->db->set('observacoes', $_POST['observacao']);
            if ($_POST['ordenador'] != "") {
                $this->db->set('ordenador', $_POST['ordenador']);
                ;
            }
            if ($_POST['data'] != "") {
                $this->db->set('data_entrega', $_POST['data']);
            }
            $this->db->set('agenda_exames_nome_id', $_POST['sala1']);
            $this->db->set('inicio', $hora);
            $this->db->set('fim', $hora);
            if ($_POST['formapamento'] != 0 && $dinheiro == "t") {
                $this->db->set('faturado', 't');
                $this->db->set('valor1', $valortotal);
                $this->db->set('operador_faturamento', $operador_id);
                $this->db->set('data_faturamento', $horario);
                $this->db->set('forma_pagamento', $_POST['formapamento']);
            }
            $empresa_id = $this->session->userdata('empresa_id');
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('confirmado', 't');
            $this->db->set('tipo', 'EXAME');
            $this->db->set('ativo', 'f');
            $this->db->set('situacao', 'OK');
            $this->db->set('guia_id', $ambulatorio_guia_id);


            $this->db->set('paciente_id', $_POST['txtpaciente_id']);
            $this->db->set('medico_solicitante', $medico_id);

            $this->db->set('data', $data);
            $this->db->set('data_autorizacao', $horario);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->set('operador_autorizacao', $operador_id);
            $this->db->insert('tb_agenda_exames');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") { // erro de banco
                return -1;
            } else {
                $agenda_exames_id = $this->db->insert_id();
            }

            return $agenda_exames_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarconsulta($ambulatorio_guia_id) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->select('dinheiro');
            $this->db->from('tb_convenio');
            $this->db->where("convenio_id", $_POST['convenio1']);
            $query = $this->db->get();
            $return = $query->result();
            $dinheiro = $return[0]->dinheiro;

            $hora = date("H:i:s");
            $data = date("Y-m-d");
            $this->db->set('procedimento_tuss_id', $_POST['procedimento1']);
            if ($_POST['medicoagenda'] != "") {
                $this->db->set('medico_consulta_id', $_POST['medicoagenda']);
                $this->db->set('medico_agenda', $_POST['medicoagenda']);
            }
            $this->db->set('valor', $_POST['valor1']);
            $valortotal = $_POST['valor1'] * $_POST['qtde1'];
            $this->db->set('valor_total', $valortotal);
            $this->db->set('quantidade', $_POST['qtde1']);
            $this->db->set('autorizacao', $_POST['autorizacao1']);
//            $this->db->set('observacoes', $_POST['observacao']);
            if ($_POST['ordenador'] != "") {
                $this->db->set('ordenador', $_POST['ordenador']);
                ;
            }
            $this->db->set('agenda_exames_nome_id', $_POST['sala1']);
            $this->db->set('inicio', $hora);
            $this->db->set('fim', $hora);
            if ($_POST['formapamento'] != 0 && $dinheiro == "t") {
                $this->db->set('faturado', 't');
                $this->db->set('valor1', $valortotal);
                $this->db->set('operador_faturamento', $operador_id);
                $this->db->set('data_faturamento', $horario);
                $this->db->set('forma_pagamento', $_POST['formapamento']);
            }
            $empresa_id = $this->session->userdata('empresa_id');
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('confirmado', 't');
            $this->db->set('tipo', 'CONSULTA');
            $this->db->set('ativo', 'f');
            $this->db->set('situacao', 'OK');
            $this->db->set('guia_id', $ambulatorio_guia_id);

            $this->db->set('paciente_id', $_POST['txtpaciente_id']);

            $this->db->set('data', $data);
            $this->db->set('data_autorizacao', $horario);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->set('operador_autorizacao', $operador_id);
            $this->db->insert('tb_agenda_exames');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") { // erro de banco
                return -1;
            } else {
                $agenda_exames_id = $this->db->insert_id();
            }

            return $agenda_exames_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarfisioterapia($ambulatorio_guia_id) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->select('dinheiro');
            $this->db->from('tb_convenio');
            $this->db->where("convenio_id", $_POST['convenio1']);
            $query = $this->db->get();
            $return = $query->result();
            $dinheiro = $return[0]->dinheiro;

            $hora = date("H:i:s");
            $data = date("Y-m-d");
            $qtde = $_POST['qtde'];
            for ($index = 1; $index <= $qtde; $index++) {

                $this->db->set('procedimento_tuss_id', $_POST['procedimento1']);
                if ($_POST['medicoagenda'] != "") {
                    $this->db->set('medico_consulta_id', $_POST['medicoagenda']);
                    $this->db->set('medico_agenda', $_POST['medicoagenda']);
                }
                $this->db->set('convenio_id', $_POST['convenio1']);
                $this->db->set('quantidade', '1');
                if ($dinheiro == "t") {
                    if ($index == 1) {
                        $this->db->set('valor', $_POST['valor1']);
                        $this->db->set('valor_total', $_POST['valor1']);
                        $this->db->set('confirmado', 't');
                    } else {
                        $this->db->set('valor', 0);
                        $this->db->set('valor_total', 0);
                        $this->db->set('confirmado', 'f');
                    }
                } else {
                    if ($index == 1) {
                        $this->db->set('valor', $_POST['valor1']);
                        $this->db->set('valor_total', $_POST['valor1']);
                        $this->db->set('confirmado', 't');
                    } else {
                        $this->db->set('valor', $_POST['valor1']);
                        $this->db->set('valor_total', $_POST['valor1']);
                        $this->db->set('confirmado', 'f');
                    }
                }
                $this->db->set('autorizacao', $_POST['autorizacao1']);
                if ($_POST['ordenador'] != "") {
                    $this->db->set('ordenador', $_POST['ordenador']);
                    ;
                }
                $this->db->set('agenda_exames_nome_id', $_POST['sala1']);
                $this->db->set('inicio', $hora);
                $this->db->set('fim', $hora);
                if ($_POST['formapamento'] != 0 && $dinheiro == "t") {
                    $this->db->set('faturado', 't');
                    $this->db->set('valor1', $valortotal);
                    $this->db->set('operador_faturamento', $operador_id);
                    $this->db->set('data_faturamento', $horario);
                    $this->db->set('forma_pagamento', $_POST['formapamento']);
                }
                $empresa_id = $this->session->userdata('empresa_id');
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('quantidade', '1');
                $this->db->set('tipo', 'FISIOTERAPIA');
                $this->db->set('ativo', 'f');
                $this->db->set('situacao', 'OK');
                $this->db->set('guia_id', $ambulatorio_guia_id);
                $this->db->set('agrupador_fisioterapia', $ambulatorio_guia_id);
                $this->db->set('numero_sessao', $index);
                $this->db->set('qtde_sessao', $qtde);
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                $this->db->set('data', $data);
                $this->db->set('data_autorizacao', $horario);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->set('operador_autorizacao', $operador_id);
                $this->db->insert('tb_agenda_exames');
            }
            return $agenda_exames_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarexamesfaturamento() {
        try {

            $hora = date("H:i:s");
            $data = date("Y-m-d");
            $this->db->set('procedimento_tuss_id', $_POST['procedimento1']);
            $this->db->set('valor', $_POST['valor1']);
            $valortotal = $_POST['valor1'] * $_POST['qtde1'];
            $this->db->set('valor1', $valortotal);
            $this->db->set('valor_total', $valortotal);
            $this->db->set('quantidade', $_POST['qtde1']);
            $this->db->set('autorizacao', $_POST['autorizacao1']);
            $empresa_id = $this->session->userdata('empresa_id');
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('confirmado', 't');
            $this->db->set('tipo', $_POST['tipo']);
            $this->db->set('ativo', 'f');
            $this->db->set('realizada', 't');
            $this->db->set('faturado', 't');
            $this->db->set('situacao', 'OK');
            $this->db->set('guia_id', $_POST['txtguia_id']);
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('paciente_id', $_POST['txtpaciente_id']);
            $this->db->set('data', $_POST['txtdata']);
            $this->db->set('data_autorizacao', $horario);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->set('data_realizacao', $horario);
            $this->db->set('operador_realizacao', $operador_id);
            $this->db->set('data_faturamento', $horario);
            $this->db->set('operador_faturamento', $operador_id);
            $this->db->set('operador_autorizacao', $operador_id);
            $this->db->insert('tb_agenda_exames');
            $agenda_exames_id = $this->db->insert_id();

            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('paciente_id', $_POST['txtpaciente_id']);
            $this->db->set('procedimento_tuss_id', $_POST['procedimento1']);
            $this->db->set('guia_id', $_POST['txtguia_id']);
            $this->db->set('agenda_exames_id', $agenda_exames_id);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_exames');
            $exames_id = $this->db->insert_id();

            if ($_POST['laudo'] == "on") {
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data', $_POST['txtdata']);
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                $this->db->set('procedimento_tuss_id', $_POST['procedimento1']);
                $this->db->set('exame_id', $exames_id);
                $this->db->set('guia_id', $_POST['txtguia_id']);
                $this->db->set('tipo', $_POST['tipo']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);

                $this->db->insert('tb_ambulatorio_laudo');
            }
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function editarexames() {
        try {

            $this->db->set('autorizacao', $_POST['autorizacao1']);
            $this->db->set('agenda_exames_nome_id', $_POST['sala1']);
            $this->db->set('guia_id', $_POST['guia_id']);
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('tipo', 'EXAME');
            $this->db->set('paciente_id', $_POST['txtpaciente_id']);
            $this->db->set('medico_solicitante', $_POST['medico']);
            $this->db->set('data_editar', $horario);
            $this->db->set('operador_editar', $operador_id);
            $this->db->where('agenda_exames_id', $_POST['agenda_exames_id']);
            $this->db->update('tb_agenda_exames');

            $this->db->set('sala_id', $_POST['sala1']);
            $this->db->where('agenda_exames_id', $_POST['agenda_exames_id']);
            $this->db->update('tb_exames');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") { // erro de banco
                return -1;
            }

            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function valorexames() {
        try {
            $exame_id = "";

            $this->db->select('dinheiro');
            $this->db->from('tb_convenio');
            $this->db->where("convenio_id", $_POST['convenio1']);
            $query = $this->db->get();
            $return = $query->result();
            $dinheiro = $return[0]->dinheiro;

            $agenda_exames_id = $_POST['agenda_exames_id'];
            $this->db->select('exames_id');
            $this->db->from('tb_exames');
            $this->db->where("agenda_exames_id", $agenda_exames_id);
            $retorno = $this->db->count_all_results();

            if ($retorno > 0) {
                $agenda_exames_id = $_POST['agenda_exames_id'];
                $this->db->select('exames_id');
                $this->db->from('tb_exames');
                $this->db->where("agenda_exames_id", $agenda_exames_id);
                $query = $this->db->get();
                $return = $query->result();
                $exame_id = $return[0]->exames_id;
            }

            $dadosantigos = $this->listarvalor($agenda_exames_id);
            $this->db->set('editarforma_pagamento', $dadosantigos[0]->forma_pagamento);
            $this->db->set('editarquantidade', $dadosantigos[0]->quantidade);
            $this->db->set('editarprocedimento_tuss_id', $dadosantigos[0]->procedimento_tuss_id);
            $this->db->set('editarvalor_total', $dadosantigos[0]->valor_total);
            $this->db->set('operador_faturamentoantigo', $dadosantigos[0]->operador_faturamento);
            $this->db->set('procedimento_tuss_id', $_POST['procedimento1']);
            $this->db->set('valor', $_POST['valor1']);
            $valortotal = $_POST['valor1'] * $_POST['qtde1'];
            $this->db->set('valor_total', $valortotal);
            $this->db->set('quantidade', $_POST['qtde1']);
            $this->db->set('autorizacao', $_POST['autorizacao1']);
            $this->db->set('guia_id', $_POST['guia_id']);
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if ($_POST['formapamento'] != 0 && $dinheiro == "t") {
                $this->db->set('faturado', 't');
                $this->db->set('forma_pagamento', $_POST['formapamento']);
                $this->db->set('valor1', $valortotal);
                $this->db->set('forma_pagamento2', NULL);
                $this->db->set('valor2', 0);
                $this->db->set('forma_pagamento3', NULL);
                $this->db->set('valor3', 0);
                $this->db->set('forma_pagamento4', NULL);
                $this->db->set('valor4', 0);
                $this->db->set('operador_faturamento', $operador_id);
                $this->db->set('data_faturamento', $horario);
            } elseif ($_POST['formapamento'] == 0 && $dinheiro == "t") {
                $this->db->set('faturado', 'f');
                $this->db->set('forma_pagamento', NULL);
                $this->db->set('valor1', 0);
                $this->db->set('forma_pagamento2', NULL);
                $this->db->set('valor2', 0);
                $this->db->set('forma_pagamento3', NULL);
                $this->db->set('valor3', 0);
                $this->db->set('forma_pagamento4', NULL);
                $this->db->set('valor4', 0);
            }
            $this->db->set('data_editar', $horario);
            $this->db->set('operador_editar', $operador_id);
            $this->db->where('agenda_exames_id', $_POST['agenda_exames_id']);
            $this->db->update('tb_agenda_exames');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") { // erro de banco
                return -1;
            }

            if ($exame_id != "") {
                $this->db->set('procedimento_tuss_id', $_POST['procedimento1']);
                $this->db->where('exames_id', $exame_id);
                $this->db->update('tb_exames');

                $this->db->set('procedimento_tuss_id', $_POST['procedimento1']);
                $this->db->where('exame_id', $exame_id);
                $this->db->update('tb_ambulatorio_laudo');
            }



            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function listarvalor($agenda_exames_id) {
        $this->db->select('forma_pagamento,
                            quantidade,
                            operador_faturamento,
                            procedimento_tuss_id,
                            valor_total');
        $this->db->from('tb_agenda_exames');
        $this->db->where('agenda_exames_id', $agenda_exames_id);
        $return = $this->db->get();
        return $return->result();
    }

    private function instanciar($exame_sala_id) {

        if ($exame_sala_id != 0) {
            $this->db->select('exame_sala_id, nome');
            $this->db->from('tb_ambulatorio_guia');
            $this->db->where("exame_sala_id", $exame_sala_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_exame_sala_id = $exame_sala_id;
            $this->_nome = $return[0]->nome;
        } else {
            $this->_exame_sala_id = null;
        }
    }

}

?>
