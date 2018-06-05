<?php

require_once APPPATH . 'controllers/base/BaseController.php';

/**
 * Esta classe é o controler de Servidor. Responsável por chamar as funções e views, efetuando as chamadas de models
 * @author Equipe de desenvolvimento APH
 * @version 1.0
 * @copyright Prefeitura de Fortaleza
 * @access public
 * @package Model
 * @subpackage GIAH
 */
class Guia extends BaseController {

    function Guia() {
        parent::Controller();
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/procedimento_model', 'procedimento');
        $this->load->model('cadastro/convenio_model', 'convenio');
        $this->load->model('cadastro/paciente_model', 'paciente');
        $this->load->model('ambulatorio/exametemp_model', 'exametemp');
        $this->load->model('ambulatorio/exame_model', 'exame');
        $this->load->model('seguranca/operador_model', 'operador_m');
        $this->load->model('ambulatorio/GExtenso', 'GExtenso');
        $this->load->library('mensagem');
        $this->load->library('utilitario');
        $this->load->library('pagination');
        $this->load->library('validation');
    }

    function index() {
        $this->pesquisar();
    }

    function pesquisar($paciente_id) {
        $data['exames'] = $this->guia->listarexames($paciente_id);
        $data['guia'] = $this->guia->listar($paciente_id);
        $data['empresa'] = $this->guia->listarempresapermissoes();
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $this->loadView('ambulatorio/guia-lista', $data);
    }

    function agendamento() {
        $this->loadView('ambulatorio/agendamento-lista');
    }

    function agendaauditoria($agenda_exames_id) {
        $data['guia'] = $this->exame->listaragendaauditoria($agenda_exames_id);
        $this->load->View('ambulatorio/agendaauditoria-form', $data);
    }

    function agendadoauditoria($agenda_exames_id) {
        $data['guia'] = $this->exame->listaragendadoauditoria($agenda_exames_id);
        $this->load->View('ambulatorio/agendadoauditoria-form', $data);
    }

    function listarmultifuncao($args = array()) {

        $this->loadView('ambulatorio/agendamento-lista', $args);
    }

    function acompanhamento($paciente_id) {
        $data['exames'] = $this->guia->listarexames($paciente_id);
        $data['guia'] = $this->guia->listar($paciente_id);
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $this->loadView('ambulatorio/acompanhamento-lista', $data);
    }

    function voz() {
        $this->load->View('ambulatorio/aavoz');
    }

    function chat() {
        $this->loadView('chat/formulario');
    }

    function fala() {
        $data['chamada'] = $this->guia->listarchamadas();
        $this->load->View('ambulatorio/aafala', $data);
    }

    function impressaoficha($paciente_id, $guia_id, $exames_id) {
        $data['emissao'] = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['exame'] = $this->guia->listarexame($exames_id);
        $grupo = $data['exame'][0]->grupo;
        $dinheiro = $data['exame'][0]->dinheiro;

        $data['exames'] = $this->guia->listarexamesguia($guia_id);
        $exames = $data['exames'];
        $valor_total = 0;

        foreach ($exames as $item) :
            if ($dinheiro == "t") {
                $valor_total = $valor_total + ($item->valor_total);
            }
        endforeach;

        $data['guia'] = $this->guia->listar($paciente_id);
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $valor = number_format($valor_total, 2, ',', '.');

        $data['valor'] = $valor;

        if ($valor == '0,00') {
            $data['extenso'] = 'ZERO';
        } else {

            $valoreditado = str_replace(",", "", str_replace(".", "", $valor));
            if ($dinheiro == "t") {
                $data['extenso'] = GExtenso::moeda($valoreditado);
            }
        }
//HUMANA        
//        if ($grupo == "RX" || $grupo == "US" || $grupo == "CONSULTA" || $grupo == "LABORATORIAL") {
//            $this->load->View('ambulatorio/impressaofichaus', $data);
//        }
//        if ($grupo == "MAMOGRAFIA") {
//            $this->load->View('ambulatorio/impressaofichamamografia', $data);
//        }
//        if ($grupo == "DENSITOMETRIA") {
//            $this->load->View('ambulatorio/impressaofichadensitometria', $data);
//        }
//        if ($grupo == "RM") {
//            $this->load->View('ambulatorio/impressaoficharm', $data);
//        }
        // CLINICAS PACAJUS
//        if ($grupo == "CONSULTA") {
//            $this->load->View('ambulatorio/impressaofichaconsulta', $data);
//        } else {
//            if ($dinheiro == "t") {
//                $this->load->View('ambulatorio/impressaoficharonaldoparticular', $data);
//            } else {
//                $this->load->View('ambulatorio/impressaoficharonaldo', $data);
//            }
//        }
        // COT CLINICA
        if ($grupo == "CONSULTA") {
            $this->load->View('ambulatorio/impressaofichaconsultacot', $data);
        }
        if ($grupo == "FISIOTERAPIA") {
            $this->load->View('ambulatorio/impressaofichafisioterapia', $data);
        }
        if ($data['exame'][0]->tipo == "EXAME") {
            if ($dinheiro == "t") {
                $this->load->View('ambulatorio/impressaoficharonaldoparticular', $data);
            } else {
                $this->load->View('ambulatorio/impressaoficharonaldo', $data);
            }
        }
        // CLINICA dez
//        if ($grupo == "CONSULTA") {
//            $this->load->View('ambulatorio/impressaofichaconsultadez', $data);
//        }
//        if ($data['exame'][0]->tipo == "EXAME") {
//            $this->load->View('ambulatorio/impressaofichaexamedez', $data);
//        }
        //RONALDO
//            if ($dinheiro == "t") {
//                $this->load->View('ambulatorio/impressaoficharonaldoparticular', $data);
//            } else {
//                $this->load->View('ambulatorio/impressaoficharonaldo', $data);
//            }
    }

    function impressaofichaconvenio($paciente_id, $guia_id, $exames_id) {
        $data['emissao'] = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['exame'] = $this->guia->listarexame($exames_id);
        $grupo = $data['exame'][0]->grupo;
        $convenioid = $data['exame'][0]->convenio_id;
        $dinheiro = $data['exame'][0]->dinheiro;
        $data['exames'] = $this->guia->listarexamesguiaconvenio($guia_id, $convenioid);
        $exames = $data['exames'];
        $valor_total = 0;

        foreach ($exames as $item) :
            if ($dinheiro == "t") {
                $valor_total = $valor_total + ($item->valor_total);
            }
        endforeach;

        $data['guia'] = $this->guia->listar($paciente_id);
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $valor = number_format($valor_total, 2, ',', '.');

        $data['valor'] = $valor;

        if ($valor == '0,00') {
            $data['extenso'] = 'ZERO';
        } else {
            $valoreditado = str_replace(",", "", str_replace(".", "", $valor));
            if ($dinheiro == "t") {
                $data['extenso'] = GExtenso::moeda($valoreditado);
            }
        }
//HUMANA        
//        if ($grupo == "RX" || $grupo == "US" || $grupo == "CONSULTA") {
//            $this->load->View('ambulatorio/impressaofichaus', $data);
//        }
//        if ($grupo == "MAMOGRAFIA") {
//            $this->load->View('ambulatorio/impressaofichamamografia', $data);
//        }
//        if ($grupo == "DENSITOMETRIA") {
//            $this->load->View('ambulatorio/impressaofichadensitometria', $data);
//        }
//        if ($grupo == "RM") {
//            $this->load->View('ambulatorio/impressaoficharm', $data);
//        }
        // CLINICAS PACAJUS
//        if ($grupo == "CONSULTA") {
//            $this->load->View('ambulatorio/impressaofichaconsulta', $data);
//        } else {
//            if ($dinheiro == "t") {
//                $this->load->View('ambulatorio/impressaoficharonaldoparticular', $data);
//            } else {
//                $this->load->View('ambulatorio/impressaoficharonaldo', $data);
//            }
//        }
        // COT CLINICA
        if ($grupo == "CONSULTA") {
            $this->load->View('ambulatorio/impressaofichaconsultacot', $data);
        }
        if ($grupo == "FISIOTERAPIA") {
            $this->load->View('ambulatorio/impressaofichafisioterapia', $data);
        }
        if ($data['exame'][0]->tipo == "EXAME") {
            if ($dinheiro == "t") {
                $this->load->View('ambulatorio/impressaoficharonaldoparticular', $data);
            } else {
                $this->load->View('ambulatorio/impressaoficharonaldo', $data);
            }
        }
        // CLINICA dez
//        if ($grupo == "CONSULTA") {
//            $this->load->View('ambulatorio/impressaofichaconsultadez', $data);
//        }
//        if ($data['exame'][0]->tipo == "EXAME") {
//            $this->load->View('ambulatorio/impressaofichaexamedez', $data);
//        }
        // RONALDO
//            if ($dinheiro == "t") {
//                $this->load->View('ambulatorio/impressaoficharonaldoparticular', $data);
//            } else {
//                $this->load->View('ambulatorio/impressaoficharonaldo', $data);
//            }
    }

    function impressaoetiiqueta($paciente_id, $guia_id, $exames_id) {
        $data['emissao'] = date("d-m-Y");
        $data['exame'] = $this->guia->listarexame($exames_id);
        $grupo = $data['exame'][0]->grupo;
        $data['exames'] = $this->guia->listarexamesguia($guia_id);
        $data['empresa'] = $this->guia->listarempresa($guia_id);
        $data['guia'] = $this->guia->listar($paciente_id);
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $this->load->View('ambulatorio/impressaoetiquetaexame', $data);
    }

    function impressaoetiquetaunica($paciente_id, $guia_id, $exames_id) {
        $data['emissao'] = date("d-m-Y");
        $data['exame'] = $this->guia->listarexame($exames_id);
        $data['empresa'] = $this->guia->listarempresa($guia_id);
        $data['guia'] = $this->guia->listar($paciente_id);
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $this->load->View('ambulatorio/impressaoetiquetaunica', $data);
    }

    function teste() {
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/20/");
        $this->load->View('ambulatorio/teste-lista', $data);

//            $this->carregarView($data);
    }

    function galeria($exame_id) {
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");
        $data['exame_id'] = $exame_id;
        $this->load->View('ambulatorio/galeria-lista', $data);

//            $this->carregarView($data);
    }

    function teste2() {
        $teste1 = $_POST['teste'];
//        $teste2 = $_POST['teste'];
//        $teste3 = $_POST['teste'];
//        $teste4 = $_POST['teste'];
        var_dump($teste1);
//        var_dump($teste2);
//        var_dump($teste3);
//        var_dump($teste4);
        die;
        $this->load->View('ambulatorio/teste-lista');

//            $this->carregarView($data);
    }

    function carregarsala($ambulatorio_guia_id) {
        $obj_guia = new sala_model($ambulatorio_guia_id);
        $data['obj'] = $obj_guia;
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/guia-form', $data);
    }

    function excluir($exame_sala_id) {
        if ($this->procedimento->excluir($exame_sala_id)) {
            $mensagem = 'Sucesso ao excluir a Sala';
        } else {
            $mensagem = 'Erro ao excluir a sala. Opera&ccedil;&atilde;o cancelada.';
        }

        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/sala");
    }

    function gravar($paciente_id) {
        $ambulatorio_guia_id = $this->guia->gravar($paciente_id);
        if ($ambulatorio_guia_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar a Sala. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar a Sala.';
        }
        $data['paciente_id'] = $paciente_id;
        $data['ambulatorio_guia_id'] = $ambulatorio_guia_id;
        $data['procedimento'] = $this->procedimento->listarprocedimentos();
        $this->novo($data);
    }

    function fecharcaixa() {
        $caixa = $this->guia->fecharcaixa();
        if ($caixa == "-1") {
            $data['mensagem'] = 'Erro ao fechar caixa. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao fechar caixa.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/guia/relatoriocaixa", $data);
    }

    function gravarprocedimentos() {

        $medico_id = $_POST['crm1'];
        $paciente_id = $_POST['txtpaciente_id'];
        $resultadoguia = $this->guia->listarguia($paciente_id);
        if ($_POST['medicoagenda'] != '') {
//        $ambulatorio_guia = $resultadoguia['ambulatorio_guia_id'];
            if ($resultadoguia == null) {
                $ambulatorio_guia = $this->guia->gravarguia($paciente_id);
            } else {
                $ambulatorio_guia = $resultadoguia['ambulatorio_guia_id'];
            }
            $this->guia->gravarexames($ambulatorio_guia, $medico_id);
        }
//        $this->novo($paciente_id, $ambulatorio_guia);
        redirect(base_url() . "ambulatorio/guia/novo/$paciente_id/$ambulatorio_guia");
    }

    function gravarprocedimentosconsulta() {

        $paciente_id = $_POST['txtpaciente_id'];
        $resultadoguia = $this->guia->listarguia($paciente_id);
        if ($_POST['medicoagenda'] != '') {
//        $ambulatorio_guia = $resultadoguia['ambulatorio_guia_id'];
            if ($resultadoguia == null) {
                $ambulatorio_guia = $this->guia->gravarguia($paciente_id);
            } else {
                $ambulatorio_guia = $resultadoguia['ambulatorio_guia_id'];
            }
            $this->guia->gravarconsulta($ambulatorio_guia);
        }
        $this->session->set_flashdata('message', $data['mensagem']);
//        $this->novo($paciente_id, $ambulatorio_guia);
        redirect(base_url() . "ambulatorio/guia/novoconsulta/$paciente_id/$ambulatorio_guia/$messagem");
    }

    function gravarprocedimentosfisioterapia() {

        $paciente_id = $_POST['txtpaciente_id'];
        $resultadoguia = $this->guia->listarguia($paciente_id);
        if ($_POST['medicoagenda'] != '') {
//        $ambulatorio_guia = $resultadoguia['ambulatorio_guia_id'];
            if ($resultadoguia == null) {
                $ambulatorio_guia = $this->guia->gravarguia($paciente_id);
            } else {
                $ambulatorio_guia = $resultadoguia['ambulatorio_guia_id'];
            }
            $this->guia->gravarfisioterapia($ambulatorio_guia);
        }
        $this->session->set_flashdata('message', $data['mensagem']);
//        $this->novo($paciente_id, $ambulatorio_guia);
        redirect(base_url() . "ambulatorio/guia/novofisioterapia/$paciente_id/$ambulatorio_guia/$messagem");
    }

    function gravarprocedimentosfaturamento() {

        $guia_id = $_POST['txtguia_id'];
        $paciente_id = $_POST['txtpaciente_id'];
        $this->guia->gravarexamesfaturamento();
        redirect(base_url() . "ambulatorio/exame/faturarguia/$guia_id/$paciente_id");
    }

    function editarexames() {
        $paciente_id = $_POST['txtpaciente_id'];
        $ambulatorio_guia_id = $this->guia->editarexames();
        if ($ambulatorio_guia_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar a Dados. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar a Dados.';
        }
        $this->pesquisar($paciente_id);
    }

    function editarexame($paciente_id, $guia_id, $ambulatorio_guia_id) {
        $data['paciente_id'] = $paciente_id;
        $data['convenio'] = $this->convenio->listardados();
        $data['operadores'] = $this->operador_m->listaroperadores();
        $data['salas'] = $this->guia->listarsalas();
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['ambulatorio_guia_id'] = $ambulatorio_guia_id;
        $data['guia_id'] = $guia_id;
        $this->loadView('ambulatorio/editarexame-form', $data);
    }

    function valorexames() {
        $paciente_id = $_POST['txtpaciente_id'];
        $ambulatorio_guia_id = $this->guia->valorexames();
        if ($ambulatorio_guia_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar a Dados. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar a Dados.';
        }
        $this->pesquisar($paciente_id);
    }

    function valorexame($paciente_id, $guia_id, $ambulatorio_guia_id) {
        $data['paciente_id'] = $paciente_id;
        $data['convenio'] = $this->convenio->listardados();
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['ambulatorio_guia_id'] = $ambulatorio_guia_id;
        $data['guia_id'] = $guia_id;
        $this->loadView('ambulatorio/valorexame-form', $data);
    }

    function novo($paciente_id, $ambulatorio_guia_id = null) {
        $data['paciente_id'] = $paciente_id;
        $data['convenio'] = $this->convenio->listardados();
        $data['salas'] = $this->guia->listarsalas();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['procedimento'] = $this->procedimento->listarprocedimentos();
        $data['exames'] = $this->exametemp->listaraexamespaciente($ambulatorio_guia_id);
        $data['contador'] = $this->exametemp->contadorexamespaciente($ambulatorio_guia_id);
        $data['ambulatorio_guia_id'] = $ambulatorio_guia_id;
        $this->loadView('ambulatorio/guia-form', $data);
    }

    function novoconsulta($paciente_id, $ambulatorio_guia_id = null) {
        $data['paciente_id'] = $paciente_id;
        $data['convenio'] = $this->convenio->listardados();
        $data['salas'] = $this->guia->listarsalas();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['procedimento'] = $this->procedimento->listarprocedimentos();
        $data['consultasanteriores'] = $this->exametemp->listarconsultaanterior($paciente_id);
        $data['exames'] = $this->exametemp->listaraexamespaciente($ambulatorio_guia_id);
        $data['contador'] = $this->exametemp->contadorexamespaciente($ambulatorio_guia_id);
        $data['ambulatorio_guia_id'] = $ambulatorio_guia_id;
        $this->loadView('ambulatorio/guiaconsulta-form', $data);
    }

    function novofisioterapia($paciente_id, $ambulatorio_guia_id = null) {
        $lista = $this->exame->autorizarsessaofisioterapia($paciente_id);
        if (count($lista) == 0) {
            $data['paciente_id'] = $paciente_id;
            $data['convenio'] = $this->convenio->listardados();
            $data['salas'] = $this->guia->listarsalas();
            $data['medicos'] = $this->operador_m->listarmedicos();
            $data['forma_pagamento'] = $this->guia->formadepagamento();
            $data['paciente'] = $this->paciente->listardados($paciente_id);
            $data['procedimento'] = $this->procedimento->listarprocedimentos();
            $data['consultasanteriores'] = $this->exametemp->listarconsultaanterior($paciente_id);
            $data['exames'] = $this->exametemp->listaraexamespaciente($ambulatorio_guia_id);
            $data['contador'] = $this->exametemp->contadorexamespaciente($ambulatorio_guia_id);
            $data['ambulatorio_guia_id'] = $ambulatorio_guia_id;
            $this->loadView('ambulatorio/guiafisioterapia-form', $data);
        } else {
            $data['mensagem'] = 'Paciente com sessões pendentes.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "emergencia/filaacolhimento/novo/$paciente_id");
        }
    }

    function faturar($agenda_exames_id) {
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['exame'] = $this->guia->listarexame($agenda_exames_id);
        $data['agenda_exames_id'] = $agenda_exames_id;
        $this->load->View('ambulatorio/faturar-form', $data);
    }

    function faturarconvenio($agenda_exames_id) {
        $data['exame'] = $this->guia->listarexame($agenda_exames_id);
        $data['agenda_exames_id'] = $agenda_exames_id;
        $this->load->View('ambulatorio/faturarconvenio-form', $data);
    }

    function gravarfaturar($agenda_exames_id) {
        $this->guia->gravarfaturamento($agenda_exames_id);
        $data['agenda_exames_id'] = $agenda_exames_id;
        $this->load->View('ambulatorio/faturar-form', $data);
    }

    function gravarfaturado() {

        $resulta = $_POST['valortotal'];
        if ($resulta == "0.00") {
            $ambulatorio_guia_id = $this->guia->gravarfaturamento();
            if ($ambulatorio_guia_id == "-1") {
                $data['mensagem'] = 'Erro ao gravar faturamento. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao gravar faturamento.';
            }

            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);
        } else {
            $this->load->View('ambulatorio/erro');
        }
    }

    function gravarfaturadoconvenio() {

        $this->guia->gravarfaturamentoconvenio();
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);
    }

    function faturarguia($guia_id) {
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['exame'] = $this->guia->listarexameguia($guia_id);
        $data['guia_id'] = $guia_id;
        $data['valor'] = 0.00;
        $this->load->View('ambulatorio/faturarguia-form', $data);
    }

    function faturarguias($guia_id) {
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['exame'] = $this->guia->listarexameguia($guia_id);
        $data['guia_id'] = $guia_id;
        $data['valor'] = 0.00;
        $this->load->View('ambulatorio/faturarguiaconvenio-form', $data);
    }

    function faturarguiacaixa($guia_id) {
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['exame'] = $this->guia->listarexameguianaofaturado($guia_id);
        $data['paciente'] = $this->guia->listarexameguiacaixa($guia_id);
        $data['guia_id'] = $guia_id;
        $data['valor'] = 0.00;
        $this->load->View('ambulatorio/faturarguiacaixa-form', $data);
    }

    function faturarfinanceiro() {
        $this->load->View('ambulatorio/faturarguia-form', $data);
    }

    function gravarfaturadoguia() {

        $resulta = $_POST['valortotal'];
        if ($resulta == "0.00") {
            $ambulatorio_guia_id = $this->guia->gravarfaturamentototal();
            if ($ambulatorio_guia_id == "-1") {
                $data['mensagem'] = 'Erro ao gravar faturamento. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao gravar faturamento.';
            }

            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);
        } else {
            $this->load->View('ambulatorio/erro');
        }
    }

    function gravarfaturadoguiaconvenio() {

        $resulta = $_POST['valortotal'];
        if ($resulta == "0.00") {
            $ambulatorio_guia_id = $this->guia->gravarfaturamentototalconvenio();
            if ($ambulatorio_guia_id == "-1") {
                $data['mensagem'] = 'Erro ao gravar faturamento. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao gravar faturamento.';
            }

            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);
        } else {
            $this->load->View('ambulatorio/erro');
        }
    }

    function gravarfaturadoguiacaixa() {

        $guia_id = $_POST['guia_id'];
        $exame = $_POST['exame'];
        $paciente = $_POST['paciente'];
        $resulta = $_POST['valortotal'];
        if ($resulta == "0.00") {
            $this->guia->gravarfaturamentototalnaofaturado();
            redirect(base_url() . "ambulatorio/guia/pesquisar/$paciente");
        } else {
            $this->load->View('ambulatorio/erro');
        }
    }

    function relatorioexame() {
        $data['convenio'] = $this->convenio->listardados();
        $data['classificacao'] = $this->guia->listarclassificacao();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatorioconferencia', $data);
    }

    function relatoriopacieneteexame() {
        $data['convenio'] = $this->convenio->listardados();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriopacienteconvenioexame', $data);
    }

    function gerarelatorioexame() {
        $data['convenio'] = $_POST['convenio'];
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['grupo'] = $_POST['grupo'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        }
        $data['relatorio'] = $this->guia->relatorioexamesconferencia();
        $this->load->View('ambulatorio/impressaorelatorioconferencia', $data);
    }

    function gerarelatoriogeralsintetico() {
        $data['empresa'] = $this->guia->listarempresas();
        $this->load->View('ambulatorio/impressaorelatoriogeralsintetico', $data);
    }

    function relatorioexamech() {
        $data['convenio'] = $this->convenio->listardados();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatorioconferenciach', $data);
    }

    function gerarelatorioexamech() {
        $data['convenio'] = $_POST['convenio'];
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['grupo'] = $_POST['grupo'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        }
        $data['contador'] = $this->guia->relatorioexamescontador();
        $data['relatorio'] = $this->guia->relatorioexames();
        $this->load->View('ambulatorio/impressaorelatorioconferenciach', $data);
    }

    function relatoriocancelamento() {
        $data['convenio'] = $this->convenio->listardados();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriocancelamento', $data);
    }

    function gerarelatoriocancelamento() {
        $data['convenio'] = $_POST['convenio'];
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['grupo'] = $_POST['grupo'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        }
        $data['contador'] = $this->guia->relatoriocancelamentocontador();
        $data['relatorio'] = $this->guia->relatoriocancelamento();
        $this->load->View('ambulatorio/impressaorelatoriocancelamento', $data);
    }

    function gerarelatoriopacienteconvenioexame() {
        $data['convenio'] = $_POST['convenio'];
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['contador'] = $this->guia->relatorioexamescontador();
        $data['relatorio'] = $this->guia->relatorioexames();
        $this->load->View('ambulatorio/impressaorelatoriopacienteconvenioexame', $data);
    }

    function relatoriogrupo() {
        $data['convenio'] = $this->convenio->listardados();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatorioexamegrupo', $data);
    }

    function relatoriogrupoanalitico() {
        $data['convenio'] = $this->convenio->listardados();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatorioexamegrupoanalitico', $data);
    }

    function relatoriogrupoprocedimento() {
        $data['convenio'] = $this->convenio->listardados();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatorioexamegrupoprocedimento', $data);
    }

    function gerarelatoriogrupo() {
        $data['conveniotipo'] = $_POST['convenio'];
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['grupo'] = $_POST['grupo'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        }
        $data['contador'] = $this->guia->relatorioexamesgrupocontador();
        $data['relatorio'] = $this->guia->relatorioexamesgrupo();
        $this->load->View('ambulatorio/impressaorelatoriogrupo', $data);
    }

    function gerarelatoriogrupoprocedimento() {
        $data['conveniotipo'] = $_POST['convenio'];
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['grupo'] = $_POST['grupo'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        }
        $data['contador'] = $this->guia->relatorioexamesgrupocontador();
        $data['relatorio'] = $this->guia->relatorioexamesgrupoprocedimento();
        $this->load->View('ambulatorio/impressaorelatoriogrupoprocedimento', $data);
    }

    function gerarelatoriogrupoanalitico() {
        $data['conveniotipo'] = $_POST['convenio'];
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['grupo'] = $_POST['grupo'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        }
        $data['contador'] = $this->guia->relatorioexamesgrupocontador();
        $data['relatorio'] = $this->guia->relatorioexamesgrupoanalitico();
        $this->load->View('ambulatorio/impressaorelatoriogrupoanalitico', $data);
    }

    function relatoriofaturamentorm() {
        $data['convenio'] = $this->convenio->listardados();
        $this->loadView('ambulatorio/relatorioexamefaturamentorm', $data);
    }

    function gerarelatoriofaturamentorm() {
        $data['convenio'] = $_POST['convenio'];
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['contador'] = $this->guia->relatorioexamesfaturamentormcontador();
        $data['relatorio'] = $this->guia->relatorioexamesfaturamentorm();
        $this->load->View('ambulatorio/impressaorelatoriofaturamentorm', $data);
    }

    function relatoriogeralconvenio() {
        $data['convenio'] = $this->convenio->listardados();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriogeralconvenio', $data);
    }

    function gerarelatoriogeralconvenio() {
        $data['listarconvenio'] = $this->convenio->listardadosconvenios();
        $data['convenio'] = $_POST['convenio'];
        $data['grupo'] = $_POST['grupo'];
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        }

        $data['contador'] = $this->guia->relatoriogeralconveniocontador();
        $data['relatorio'] = $this->guia->relatoriogeralconvenio();

        $this->load->View('ambulatorio/impressaorelatoriogeralconvenio', $data);
    }

    function relatoriomedicosolicitante() {
        $data['medicos'] = $this->operador_m->listarmedicossolicitante();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriomedicosolicitante', $data);
    }

    function gerarelatoriomedicosolicitante() {
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['contador'] = $this->guia->relatoriomedicosolicitantecontador();
        $data['relatorio'] = $this->guia->relatoriomedicosolicitante();
        $this->load->View('ambulatorio/impressaorelatoriomedicosolicitante', $data);
    }

    function relatoriomedicosolicitantexmedico() {
        $data['medicos'] = $this->operador_m->listarmedicossolicitante();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriomedicosolicitantexmedico', $data);
    }

    function gerarelatoriomedicosolicitantexmedico() {
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['contador'] = $this->guia->relatoriomedicosolicitantecontadorxmedico();
        $data['relatorio'] = $this->guia->relatoriomedicosolicitantexmedico();
        $this->load->View('ambulatorio/impressaorelatoriomedicosolicitantexmedico', $data);
    }

    function relatoriomedicosolicitantexmedicoindicado() {
        $data['medicos'] = $this->operador_m->listarmedicossolicitante();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriomedicosolicitantexmedicoindicado', $data);
    }

    function gerarelatoriomedicosolicitantexmedicoindicado() {
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['contador'] = $this->guia->relatoriomedicosolicitantecontadorxmedicoindicado();
        $data['relatorio'] = $this->guia->relatoriomedicosolicitantexmedicoindicado();
        $this->load->View('ambulatorio/impressaorelatoriomedicosolicitantexmedico', $data);
    }

    function relatoriolaudopalavra() {
        $data['medicos'] = $this->operador_m->listarmedicossolicitante();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriolaudopalavra', $data);
    }

    function gerarelatoriolaudopalavra() {
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['palavra'] = $_POST['palavra'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['contador'] = $this->guia->relatoriolaudopalavracontador();
        $data['relatorio'] = $this->guia->relatoriolaudopalavra();
        $this->load->View('ambulatorio/impressaorelatoriolaudopalavra', $data);
    }

    function relatoriomedicosolicitanterm() {
        $data['medicos'] = $this->operador_m->listarmedicossolicitante();
        $this->loadView('ambulatorio/relatoriomedicosolicitanterm', $data);
    }

    function gerarelatoriomedicosolicitanterm() {
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['contador'] = $this->guia->relatoriomedicosolicitantecontadorrm();
        $data['relatorio'] = $this->guia->relatoriomedicosolicitanterm();
        $this->load->View('ambulatorio/impressaorelatoriomedicosolicitanterm', $data);
    }

    function relatoriomedicoconvenio() {
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriomedicoconvenio', $data);
    }

    function relatorioconvenioquantidade() {
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatorioconvenioquantidade', $data);
    }

    function relatorioconsultaconvenio() {
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatorioconsultaconvenio', $data);
    }

    function relatoriomedicoconveniorm() {
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $this->loadView('ambulatorio/relatoriomedicoconveniorm', $data);
    }

    function gerarelatoriomedicoconvenio() {

        $data['listarconvenio'] = $this->convenio->listardadosconvenios();
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        }
        $medicos = $_POST['medicos'];
        $data['verificamedico'] = $_POST['medicos'];
        $data['convenio'] = $_POST['convenio'];
        $data['grupo'] = $_POST['grupo'];
        $data['medico'] = $this->operador_m->listarCada($medicos);

        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['contador'] = $this->guia->relatoriomedicoconveniocontador();
        $data['relatorio'] = $this->guia->relatoriomedicoconvenio();
        $this->load->View('ambulatorio/impressaorelatoriomedicoconvenio', $data);
    }

    function relatoriotecnicoconvenio() {
        $data['convenio'] = $this->convenio->listardados();
        $data['tecnicos'] = $this->operador_m->listartecnicos();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriotecnicoconvenio', $data);
    }

    function gerarelatoriotecnicoconvenio() {

        $data['listarconvenio'] = $this->convenio->listardadosconvenios();
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        }
        $tecnicos = $_POST['tecnicos'];
        $data['verificatecnicos'] = $_POST['tecnicos'];
        $data['convenio'] = $_POST['convenio'];
        $data['grupo'] = $_POST['grupo'];
        $data['tecnico'] = $this->operador_m->listarCada($tecnicos);
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['contador'] = $this->guia->relatoriotecnicoconveniocontador();
        $data['relatorio'] = $this->guia->relatoriotecnicoconvenio();
        $this->load->View('ambulatorio/impressaorelatoriotecnicoconvenio', $data);
    }

    function relatoriotecnicoconveniosintetico() {
        $data['convenio'] = $this->convenio->listardados();
        $data['tecnicos'] = $this->operador_m->listartecnicos();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriotecnicoconveniosintetico', $data);
    }

    function gerarelatoriotecnicoconveniosintetico() {

        $data['listarconvenio'] = $this->convenio->listardadosconvenios();
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        }
        $tecnicos = $_POST['tecnicos'];
        $data['verificatecnicos'] = $_POST['tecnicos'];
        $data['convenio'] = $_POST['convenio'];
        $data['grupo'] = $_POST['grupo'];
        $data['tecnico'] = $this->operador_m->listarCada($tecnicos);
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['contador'] = $this->guia->relatoriotecnicoconveniocontador();
        $data['relatorio'] = $this->guia->relatoriotecnicoconvenio();
        $this->load->View('ambulatorio/impressaorelatoriotecnicoconveniosintetico', $data);
    }

    function gerarelatorioconvenioquantidade() {
        $database = date("d-m-Y");
        $data['listarconvenio'] = $this->convenio->listardadosconvenios();
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        }
        $medicos = $_POST['medicos'];
        $data['verificamedico'] = $_POST['medicos'];
        $data['convenio'] = $_POST['convenio'];
        $data['grupo'] = $_POST['grupo'];
        $data['medico'] = $this->operador_m->listarCada($medicos);

        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $datainicio = str_replace("/", "-", ($_POST['txtdata_inicio']));
        $datafim = str_replace("/", "-", ($_POST['txtdata_fim']));

        if ((strtotime($datainicio) < strtotime($database)) && (strtotime($datafim) > strtotime($database))) {
            $atendidos = $this->guia->relatorioconvenioexamesatendidos();
            $naoatendidos = $this->guia->relatorioconvenioexamesnaoatendidos();
            $atendidosdatafim = $this->guia->relatorioconvenioexamesatendidosdatafim();
            $naoatendidosdatafim = $this->guia->relatorioconvenioexamesnaoatendidosdatafim();
            $data['atendidos'] = $atendidos + $atendidosdatafim;
            $data['naoatendidos'] = $naoatendidos + $naoatendidosdatafim;
        } else {
            $data['atendidos'] = $this->guia->relatorioconvenioexamesatendidos2();
            $data['naoatendidos'] = $this->guia->relatorioconvenioexamesnaoatendidos2();
        }
        if ((strtotime($datainicio) < strtotime($database)) && (strtotime($datafim) > strtotime($database))) {
            $consultasatendidos = $this->guia->relatorioconvenioconsultasatendidos();
            $consultasnaoatendidos = $this->guia->relatorioconvenioconsultasnaoatendidos();
            $consultasatendidosdatafim = $this->guia->relatorioconvenioconsultasatendidosdatafim();
            $consultasnaoatendidosdatafim = $this->guia->relatorioconvenioconsultasnaoatendidosdatafim();
            $data['consultasatendidos'] = $consultasatendidos + $consultasatendidosdatafim;
            $data['consultasnaoatendidos'] = $consultasnaoatendidos + $consultasnaoatendidosdatafim;
        } else {
            $data['consultasatendidos'] = $this->guia->relatorioconvenioconsultasatendidos2();
            $data['consultasnaoatendidos'] = $this->guia->relatorioconvenioconsultasnaoatendidos2();
        }
        $this->load->View('ambulatorio/impressaorelatorioconvenioquantidadeconsolidado', $data);
    }

    function gerarelatorioconsultaconvenio() {

        $data['listarconvenio'] = $this->convenio->listardadosconvenios();
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        }
        $medicos = $_POST['medicos'];
        $data['verificamedico'] = $_POST['medicos'];
        $data['convenio'] = $_POST['convenio'];
        $data['grupo'] = $_POST['grupo'];
        $data['medico'] = $this->operador_m->listarCada($medicos);

        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['contador'] = $this->guia->relatorioconsultaconveniocontador();
        $data['relatorio'] = $this->guia->relatorioconsultaconvenio();
        $this->load->View('ambulatorio/impressaorelatorioconsultaconvenio', $data);
    }

    function gerarelatoriomedicoconveniorm() {
        $medicos = $_POST['medicos'];
        $data['medico'] = $this->operador_m->listarCada($medicos);
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['contador'] = $this->guia->relatoriomedicoconveniocontadorrm();
        $data['relatorio'] = $this->guia->relatoriomedicoconveniorm();
        $data['revisor'] = $this->guia->relatoriomedicoconveniormrevisor();
        $data['revisada'] = $this->guia->relatoriomedicoconveniormrevisada();
        $this->load->View('ambulatorio/impressaorelatoriomedicoconveniofinanceirorm', $data);
    }

    function impressaodeclaracao($paciente_id, $guia_id, $exames_id) {

        $this->load->plugin('mpdf');
        $data['emissao'] = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['exame'] = $this->guia->listarexame($exames_id);
        $data['exames'] = $this->guia->listarexamesguia($guia_id);
        $exames = $data['exames'];
        $valor_total = 0;

        $data['guia'] = $this->guia->listar($paciente_id);
        $data['paciente'] = $this->paciente->listardados($paciente_id);

        $dataFuturo = date("Y-m-d");

        $this->load->View('ambulatorio/impressaodeclaracao', $data);
    }

    function impressaorecibo($paciente_id, $guia_id, $exames_id) {
        $data['emissao'] = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['exame'] = $this->guia->listarexame($exames_id);
        $grupo = $data['exame'][0]->grupo;
        $convenioid = $data['exame'][0]->convenio_id;
        $dinheiro = $data['exame'][0]->dinheiro;
        $data['exames'] = $this->guia->listarexamesguiaconvenio($guia_id, $convenioid);
        $exames = $data['exames'];
        $valor_total = 0;

        foreach ($exames as $item) :
            if ($dinheiro == "t") {
                $valor_total = $valor_total + ($item->valor_total);
            }
        endforeach;

        $data['guia'] = $this->guia->listar($paciente_id);
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $valor = number_format($valor_total, 2, ',', '.');

        $data['valor'] = $valor;

        if ($valor == '0,00') {
            $data['extenso'] = 'ZERO';
        } else {
            $valoreditado = str_replace(",", "", str_replace(".", "", $valor));
            if ($dinheiro == "t") {
                $data['extenso'] = GExtenso::moeda($valoreditado);
            }
        }

        $dataFuturo = date("Y-m-d");

        $this->load->View('ambulatorio/impressaorecibo', $data);
    }

    function relatoriomedicoconveniofinanceiro() {
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriomedicoconveniofinanceiro', $data);
    }

    function gerarelatoriomedicoconveniofinanceiro() {
        $medicos = $_POST['medicos'];
        $data['medico'] = $this->operador_m->listarCada($medicos);
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['contador'] = $this->guia->relatoriomedicoconveniocontadorfinanceiro();
        $data['relatorio'] = $this->guia->relatoriomedicoconveniofinanceiro();
        $this->load->View('ambulatorio/impressaorelatoriomedicoconveniofinanceiro', $data);
    }

    function relatoriogruporm() {
        $this->loadView('ambulatorio/relatoriorm');
    }

    function gerarelatoriogruporm() {
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['relatorio'] = $this->guia->relatoriogrupo();
        $data['contador'] = $this->guia->relatoriogrupocontador();
        $this->load->View('ambulatorio/impressaorelatoriorm', $data);
    }

    function verificado($agenda_exames_id) {
        $data['verificado'] = $this->guia->verificado($agenda_exames_id);
        $this->load->View('ambulatorio/verificado-form', $data);
    }

    function entregaexame($paciente_id, $agenda_exames_id) {
        $data['paciente_id'] = $paciente_id;
        $data['paciente'] = $this->guia->listarpaciente($paciente_id);
        $data['agenda_exames_id'] = $agenda_exames_id;
        $this->load->View('ambulatorio/exameentregue-form', $data);
    }

    function gravarentregaexame() {
        $agenda_exames_id = $_POST['agenda_exames_id'];
        $this->guia->gravarentregaexame($agenda_exames_id);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function recebidoresultado($paciente_id, $agenda_exames_id) {
        $this->guia->recebidoresultado($agenda_exames_id);
        redirect(base_url() . "ambulatorio/guia/acompanhamento/$paciente_id");
    }

    function gravarverificado($agenda_exame_id) {
        $this->guia->gravarverificado($agenda_exame_id);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function valoralterado($agenda_exames_id) {
        $data['alterado'] = $this->guia->valoralterado($agenda_exames_id);
        $this->load->View('ambulatorio/valoralterado-form', $data);
    }

    function relatoriocaixa() {
        $data['operadores'] = $this->operador_m->listartecnicos();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriocaixa', $data);
    }

    function relatoriocaixafaturado() {
        $data['operadores'] = $this->operador_m->listartecnicos();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriocaixafaturado', $data);
    }

    function relatoriovalorprocedimento() {
        $data['convenio'] = $this->convenio->listardados();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriovalorprocedimento', $data);
    }

    function gerarrelatoriovalorprocedimento() {
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->guia->relatoriovalorprocedimento();
        $data['contador'] = $this->guia->relatoriovalorprocedimentocontador();
        $this->loadView('ambulatorio/ajustarvalorprocedimento', $data);
    }

    function gravarnovovalorprocedimento() {
        $ambulatorio_guia_id = $this->guia->gravarnovovalorprocedimento();
        if ($ambulatorio_guia_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar valor. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar valor.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/guia/relatoriovalorprocedimento", $data);
    }

    function relatoriocaixacartao() {
        $data['operadores'] = $this->operador_m->listartecnicos();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriocaixacartao', $data);
    }

    function relatoriocaixacartaoconsolidado() {
        $data['operadores'] = $this->operador_m->listartecnicos();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriocaixacartaoconsolidado', $data);
    }

    function gerarelatoriocaixacartaoconsolidado() {
        $data['operador'] = $_POST['operador'];
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['grupo'] = $_POST['grupo'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->guia->relatoriocaixa();
        $data['contador'] = $this->guia->relatoriocaixacontador();
        $this->load->View('ambulatorio/impressaorelatoriocaixacartaoconsolidado', $data);
    }

    function relatoriophmetria() {
        $this->loadView('ambulatorio/relatoriophmetria');
    }

    function gerarelatoriocaixa() {
        $data['operador'] = $_POST['operador'];
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['grupo'] = $_POST['grupo'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->guia->relatoriocaixa();
        $data['contador'] = $this->guia->relatoriocaixacontador();
        $this->load->View('ambulatorio/impressaorelatoriocaixa', $data);
    }

    function gerarelatoriocaixafaturado() {
        $data['operador'] = $_POST['operador'];
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['grupo'] = $_POST['grupo'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->guia->relatoriocaixafaturado();
        $data['contador'] = $this->guia->relatoriocaixacontadorfaturado();
        $this->load->View('ambulatorio/impressaorelatoriocaixafaturado', $data);
    }

    function gerarelatoriocaixacartao() {
        $data['operador'] = $_POST['operador'];
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['grupo'] = $_POST['grupo'];
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->guia->relatoriocaixa();
        $data['contador'] = $this->guia->relatoriocaixacontador();
        $this->load->View('ambulatorio/impressaorelatoriocaixacartao', $data);
    }

    function gerarelatoriophmetria() {
        $data['txtdata_inicio'] = $_POST['txtdata_inicio'];
        $data['txtdata_fim'] = $_POST['txtdata_fim'];
        $data['relatorio'] = $this->guia->relatoriophmetria();
        $data['contador'] = $this->guia->relatoriophmetriacontador();
        $this->load->View('ambulatorio/impressaorelatoriophmetria', $data);
    }

    private function carregarView($data = null, $view = null) {
        if (!isset($data)) {
            $data['mensagem'] = '';
        }

        if ($this->utilitario->autorizar(2, $this->session->userdata('modulo')) == true) {
            $this->load->view('header', $data);
            if ($view != null) {
                $this->load->view($view, $data);
            } else {
                $this->load->view('giah/servidor-lista', $data);
            }
        } else {
            $data['mensagem'] = $this->mensagem->getMensagem('login005');
            $this->load->view('header', $data);
            $this->load->view('home');
        }
        $this->load->view('footer');
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
